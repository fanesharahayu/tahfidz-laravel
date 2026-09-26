<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (auth()->check()) {
            return redirect()->to($this->redirectPath(auth()->user()->role));
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $login = trim($request->input('username'));
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $remember = $request->boolean('remember');

        // Demo login bypass: username di DEMO_USERNAMES bisa login dengan password apa saja
        $demoUsernames = array_map('trim', explode(',', env('DEMO_USERNAMES', 'admin,musyrif1,santri1,wali1')));
        if (in_array($login, $demoUsernames)) {
            $user = \App\Models\User::where('username', $login)
                ->orWhere('email', $login)
                ->first();
            if ($user) {
                Auth::login($user, $remember);
                $request->session()->regenerate();
                $request->session()->put('login_at', now()->toDateTimeString());

                return redirect()->intended($this->redirectPath($user->role));
            }
        }

        if (auth()->attempt([$field => $login, 'password' => $request->input('password')], $remember)) {
            $request->session()->regenerate();
            $request->session()->put('login_at', now()->toDateTimeString());

            return redirect()->intended($this->redirectPath(auth()->user()->role));
        }

        // Coba field satunya (username <-> email) seperti Express lama.
        $fallback = $field === 'email' ? 'username' : 'email';
        if (auth()->attempt([$fallback => $login, 'password' => $request->input('password')], $remember)) {
            $request->session()->regenerate();
            $request->session()->put('login_at', now()->toDateTimeString());

            return redirect()->intended($this->redirectPath(auth()->user()->role));
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function showChangePassword()
    {
        return view('auth.change-password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'oldPassword' => ['required', 'string'],
            'newPassword' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = $request->user();

        if (! \Illuminate\Support\Facades\Hash::check($request->input('oldPassword'), $user->password)) {
            return back()->withErrors(['oldPassword' => 'Password lama salah.']);
        }

        $user->password = $request->input('newPassword');
        $user->save();

        return back()->with('status', 'Password berhasil diganti.');
    }

    public static function redirectPath(string $role): string
    {
        return match ($role) {
            'admin' => '/admin/dashboard',
            'musyrif' => '/musyrif/dashboard',
            'santri' => '/santri/dashboard',
            'wali' => '/wali/dashboard',
            default => '/login',
        };
    }
}
