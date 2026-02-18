<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $recentDocs = Document::with('user')->latest()->take(10)->get();

            return view('admin.index', [
                'user' => $user,
                'stats' => [
                    'total_users'      => User::where('role', 'user')->count(),
                    'total_documents'  => Document::count(),
                    'pending_docs'     => Document::where('status', 'received')->count(),
                    'completed_docs'   => Document::where('status', 'completed')->count(),
                ],
                'recentDocs' => $recentDocs,
            ]);
        }

        // Regular user: fetch their own documents
        $myDocs = $user->documents()->latest()->get();

        return view('dashboard.index', [
            'user'  => $user,
            'stats' => [
                'total'     => $myDocs->count(),
                'pending'   => $myDocs->where('status', 'received')->count(),
                'completed' => $myDocs->where('status', 'completed')->count(),
            ],
            'recentDocs' => $myDocs->take(5),
        ]);
    }
}
