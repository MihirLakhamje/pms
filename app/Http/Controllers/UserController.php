<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }
    
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email,'. $user->id],
            'role' => ['required', 'string','in:admin,manager,employee'],
        ]);
        try {
            $user->update([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'role' => $request->input('role'),
            ]);
            return redirect()->route('users.index')
                ->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to update user: ' . $e->getMessage());
            return redirect()->route('users.index')
                ->with('error', 'Failed to update user.');
        }
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email'],
            'role' => ['required', 'string','in:admin,manager,employee'],
            'password' => ['required','string','min:6'],
        ]);
        try {
            $validated['password'] = bcrypt($validated['password']);
            User::create($validated);
            return redirect()->route('users.index')
                ->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to create user: ' . $e->getMessage());
            return redirect()->route('users.index')
                ->with('error', 'Failed to create user.');
        }
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();
            return redirect()->route('users.index')
                ->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to delete user: ' . $e->getMessage());
            return redirect()->route('users.index')
                ->with('error', 'Failed to delete user.');
        }
    }
}
