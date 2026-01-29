<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 1. AUTOMATIC NAME SPLITTER
        // This takes "Gelmar Anora" and splits it into pieces
        $parts = explode(' ', $request->name);
        $firstName = $parts[0]; // "Gelmar"
        $lastName = (count($parts) > 1) ? end($parts) : ''; // "Anora" (Last part)

        // Handle Middle Name (Optional logic: takes everything in between)
        $middleName = '';
        if (count($parts) > 2) {
            $middleName = implode(' ', array_slice($parts, 1, -1));
        }

        // 2. CREATE USER WITH ALL FIELDS FILLED
        $user = User::create([
            'name' => $request->name,
            'username' => explode('@', $request->email)[0], // Auto-generate username
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student',

            // 3. FILL THE PROFILE DATA AUTOMATICALLY
            'first_name' => $firstName,
            'middle_name' => $middleName,
            'last_name' => $lastName,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
