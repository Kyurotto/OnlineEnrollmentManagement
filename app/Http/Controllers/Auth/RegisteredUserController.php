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
    public function create(): View
    {
        return view('auth.login');
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
        $parts = explode(' ', $request->name);
        $parts = array_values(array_filter($parts));
        $count = count($parts);

        $firstName = '';
        $middleName = '';
        $lastName = '';

        if ($count == 1) {
            $firstName = $parts[0];
        } elseif ($count > 1) {
            $lastName = array_pop($parts);
            $potentialMiddle = end($parts);
            if (preg_match('/^[a-zA-Z]\.?$/', $potentialMiddle)) {
                $middleName = array_pop($parts);
                $firstName = implode(' ', $parts);
            } else {
                $firstName = array_shift($parts);
                $middleName = implode(' ', $parts);
            }
        }

        // 2. CREATE USER SECURELY (forceCreate bypasses fillable protection)
        $user = User::forceCreate([
            'name' => $request->name,
            'username' => explode('@', $request->email)[0],
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student', // Safe here because we are hardcoding it
            'status' => null, // Students default to NULL (Active status comes from Application)

            'first_name' => $firstName,
            'middle_name' => $middleName,
            'last_name' => $lastName,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
