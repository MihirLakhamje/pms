<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AccountController extends Controller
{
    /**
     * Show account page
     */
    public function index()
    {
        $user = auth()->user();

        // Generate temporary avatar URL
        $user->avatar_url = $user->profile_image
        ? Storage::disk('r2')->temporaryUrl(
            $user->profile_image,
            now()->addDays(7)
        ) : 'https://cdn.flyonui.com/fy-assets/avatar/avatar-5.png';

        // dd($user->avatar_url);
        return view('account.index', compact('user'));
    }

    /**
     * Update profile information
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'profile_image' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        if ($request->hasFile('profile_image')) {
            // delete OLD image
            Storage::disk('r2')->delete($user->profile_image);
            $user->profile_image = null;
            $path = $request->file('profile_image')
                ->store('profile_images', 'r2');
            $validated['profile_image'] = $path;
        }

        $user->update($validated);

        return back()->with(
            'success',
            'Profile updated successfully.'
        );
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Verify current password
        if (! Hash::check(
            $validated['current_password'],
            $user->password
        )) {
            return back()->withErrors([
                'current_password' => 'Current password is incorrect.',
            ]);
        }

        $user->update([
            'password' => Hash::make(
                $validated['password']
            ),
        ]);

        return back()->with(
            'success',
            'Password updated successfully.'
        );
    }
}
