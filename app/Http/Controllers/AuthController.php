<?php

namespace App\Http\Controllers;

use App\Mail\ActivationMail;
use App\Models\User;
use App\Services\ActivationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function __construct(
        private ActivationService $activationService
    ) {}

    /**
     * Register a new user (pending status, no real password).
     * Sends activation email with a secure one-time token.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'mobile' => 'nullable|string|max:20',
            'account_type' => 'nullable|string|in:individual,representative',
        ]);

        try {
            // Create the user in a transaction — email is sent outside so a
            // mail failure never prevents account creation.
            $user = DB::transaction(function () use ($request) {
                return User::create([
                    'name'         => $request->name,
                    'email'        => $request->email,
                    'mobile'       => $request->mobile,
                    'account_type' => $request->account_type ?? 'individual',
                    'password'     => Hash::make(Str::random(64)), // placeholder — never usable
                    'status'       => 'pending',
                ]);
            });

            // Generate activation token (outside transaction — safe to retry)
            $rawToken = $this->activationService->createToken($user);

            // Send activation email; log but never crash on mail failure
            try {
                Mail::to($user->email)->send(new ActivationMail($user, $rawToken));
            } catch (\Exception $mailEx) {
                \Log::warning('Activation email could not be sent to ' . $user->email . ': ' . $mailEx->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Account created. Check your email to set your password and activate your account.',
            ]);
        } catch (\Exception $e) {
            \Log::error('Registration failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Registration failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the password setup page (from email link).
     */
    public function showActivationForm(string $token)
    {
        $activationToken = $this->activationService->findValidToken($token);

        if (!$activationToken) {
            // Check if user is already activated
            $hash = hash('sha256', $token);
            $expiredToken = \App\Models\ActivationToken::where('token_hash', $hash)->first();

            if ($expiredToken && $expiredToken->user && $expiredToken->user->isActive()) {
                return view('auth.activation-status', [
                    'status'  => 'already_active',
                    'message' => 'Your account is already activated. You can log in.',
                ]);
            }

            return view('auth.activation-status', [
                'status'  => 'invalid',
                'message' => 'This activation link is invalid or has expired.',
                'email'   => $expiredToken?->user?->email,
            ]);
        }

        return view('auth.set-password', [
            'token' => $token,
            'email' => $activationToken->user->email,
            'name'  => $activationToken->user->name,
        ]);
    }

    /**
     * Process password setup and activate the account.
     */
    public function activate(Request $request)
    {
        $request->validate([
            'token'    => 'required|string',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[a-z]/',      // lowercase
                'regex:/[A-Z]/',      // uppercase
                'regex:/[0-9]/',      // digit
                'regex:/[!@#$%^&*]/', // special char
            ],
        ], [
            'password.regex' => 'Password must contain uppercase, lowercase, number, and special character (!@#$%^&*).',
        ]);

        $activationToken = $this->activationService->findValidToken($request->token);

        if (!$activationToken) {
            return response()->json([
                'success' => false,
                'message' => 'This activation link is invalid or has expired.',
            ], 422);
        }

        $user = $this->activationService->activateUser(
            $activationToken,
            $request->password,
            $request->ip()
        );

        return response()->json([
            'success' => true,
            'message' => 'Your account has been activated! You can now log in.',
        ]);
    }

    /**
     * Resend activation email for a pending user.
     */
    public function resendActivation(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No account found with that email address.',
            ], 404);
        }

        if ($user->isActive()) {
            return response()->json([
                'success' => false,
                'message' => 'This account is already activated. You can log in.',
            ], 400);
        }

        if (!$this->activationService->canResend($user)) {
            return response()->json([
                'success' => false,
                'message' => 'Too many requests. Please wait before requesting another activation email.',
            ], 429);
        }

        $rawToken = $this->activationService->createToken($user);
        Mail::to($user->email)->send(new ActivationMail($user, $rawToken));

        return response()->json([
            'success' => true,
            'message' => 'A new activation email has been sent. Check your inbox.',
        ]);
    }

    /**
     * Check if an email exists in the system.
     */
    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['exists' => false]);
        }

        // If user exists but is still pending, tell them to check email
        if ($user->isPending()) {
            return response()->json([
                'exists'  => true,
                'pending' => true,
                'message' => 'Your account is pending activation. Check your email to set your password.',
            ]);
        }

        return response()->json(['exists' => true, 'pending' => false]);
    }

    /**
     * Log in with email + password (only active accounts).
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Check if user exists and is pending
        $user = User::where('email', $request->email)->first();

        if ($user && $user->isPending()) {
            return response()->json([
                'success' => false,
                'pending' => true,
                'message' => 'Your account is not yet activated. Check your email to set your password.',
            ], 403);
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'user'    => Auth::user(),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'The provided credentials do not match our records.',
        ], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['success' => true]);
    }
}
