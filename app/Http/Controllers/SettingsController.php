<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SettingsController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('settings.index', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id
        ]);

        $user->update($request->only(['first_name', 'last_name', 'email']));

        return back()->with('success', 'Profile updated successfully.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed'
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password changed successfully.');
    }

    // ADMIN: View all staff accounts
public function manageUsers()
{
    if (auth()->user()->role !== 'admin') {
        return redirect()->route('dashboard')->with('error', 'Unauthorized access. Admin only.');
    }

    $staff = User::where('role', 'staff')->get();

    return view('settings.users', compact('staff'));
}

// ADMIN: Activate / Deactivate a staff account
public function toggleActive($id)
{
    if (auth()->user()->role !== 'admin') {
        return response()->json(['error' => 'Unauthorized access.'], 403);
    }

    $user = User::findOrFail($id);
    $user->update(['is_active' => !$user->is_active]);

    return response()->json([
        'success' => true,
        'is_active' => $user->is_active
    ]);
}
}