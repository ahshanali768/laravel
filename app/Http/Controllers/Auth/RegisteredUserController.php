<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:agent'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 'pending', // set to pending until admin approval
            'email_verified_at' => now(),
        ]);
        $user->assignRole($request->role);

        // Send notification to admin
        Mail::raw(
            "A new user has registered and is awaiting approval.\n\nName: {$user->name}\nUsername: {$user->username}\nEmail: {$user->email}",
            function ($message) {
                $message->to('admin@acraltech.site')
                        ->subject('New User Registration - Awaiting Approval');
            }
        );

        // Show message to user
        return redirect(route('login'))->with('status', 'Registration successful! Please wait for admin approval. You will be notified by email once your account is approved.');
    }
}
