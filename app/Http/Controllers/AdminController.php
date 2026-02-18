<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Ensure the current user is an admin.
     */
    private function authorize()
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }
    }

    // ─── USER MANAGEMENT ───

    /**
     * List all users (excluding current admin).
     */
    public function users(Request $request)
    {
        $this->authorize();

        $query = User::where('role', 'user');

        // Search filter
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                  ->orWhere('email', 'ilike', "%{$search}%")
                  ->orWhere('mobile', 'ilike', "%{$search}%");
            });
        }

        // Status filter
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $users = $query->withCount('documents')->latest()->paginate(15);

        return view('admin.users', [
            'user'  => Auth::user(),
            'users' => $users,
            'filters' => [
                'search' => $search ?? '',
                'status' => $status ?? '',
            ],
        ]);
    }

    /**
     * Update user (status, role).
     */
    public function updateUser(Request $request, $id)
    {
        $this->authorize();

        $target = User::findOrFail($id);

        // Prevent editing self
        if ($target->id === Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Cannot modify your own account.'], 403);
        }

        $request->validate([
            'status' => 'sometimes|in:active,pending,suspended',
            'name'   => 'sometimes|string|max:255',
            'email'  => 'sometimes|email|max:255|unique:users,email,' . $id,
            'mobile' => 'sometimes|nullable|string|max:20',
        ]);

        if ($request->has('status')) {
            $target->status = $request->status;
            if ($request->status === 'active' && !$target->activated_at) {
                $target->activated_at = now();
            }
        }

        if ($request->has('name'))   $target->name   = $request->name;
        if ($request->has('email'))  $target->email  = $request->email;
        if ($request->has('mobile')) $target->mobile = $request->mobile ?: null;

        $target->save();

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully.',
            'user'    => $target,
        ]);
    }

    /**
     * Delete a user account.
     */
    public function deleteUser($id)
    {
        $this->authorize();

        $target = User::findOrFail($id);

        if ($target->id === Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Cannot delete your own account.'], 403);
        }

        if ($target->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Cannot delete admin accounts.'], 403);
        }

        $target->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully.',
        ]);
    }

    // ─── DOCUMENT MANAGEMENT ───

    /**
     * List all documents.
     */
    public function documents(Request $request)
    {
        $this->authorize();

        $query = Document::with('user');

        // Search filter
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('tracking_number', 'ilike', "%{$search}%")
                  ->orWhere('subject', 'ilike', "%{$search}%")
                  ->orWhere('sender_name', 'ilike', "%{$search}%");
            });
        }

        // Status filter
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $documents = $query->latest()->paginate(15);

        $stats = [
            'total'     => Document::count(),
            'received'  => Document::where('status', 'received')->count(),
            'forwarded' => Document::where('status', 'forwarded')->count(),
            'completed' => Document::where('status', 'completed')->count(),
        ];

        return view('admin.documents', [
            'user'      => Auth::user(),
            'documents' => $documents,
            'stats'     => $stats,
            'filters'   => [
                'search' => $search ?? '',
                'status' => $status ?? '',
            ],
        ]);
    }

    /**
     * Update document status.
     */
    public function updateDocument(Request $request, $id)
    {
        $this->authorize();

        $document = Document::findOrFail($id);

        $request->validate([
            'status' => 'required|in:received,forwarded,completed',
        ]);

        $document->status = $request->status;
        $document->save();

        return response()->json([
            'success'  => true,
            'message'  => 'Document status updated.',
            'document' => $document,
        ]);
    }

    /**
     * Delete a document.
     */
    public function deleteDocument($id)
    {
        $this->authorize();

        $document = Document::findOrFail($id);
        $document->delete();

        return response()->json([
            'success' => true,
            'message' => 'Document deleted.',
        ]);
    }
}
