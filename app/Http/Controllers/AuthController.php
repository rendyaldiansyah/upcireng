<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLogin(Request $request)
    {
        if (Session::has('user_id')) {
            return redirect()->route('order.create');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8|max:255',
        ]);

        // Find customer user
        $user = User::query()
            ->where('email', $validated['email'])
            ->where('role', 'customer')
            ->first();

        // Verify credentials
        if (!$user) {
            Log::warning('Customer login attempt - user not found', ['email' => $validated['email']]);
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Email atau password customer tidak valid.');
        }

        if (!$user->password) {
            Log::warning('Customer login attempt - no password set', ['email' => $validated['email'], 'user_id' => $user->id]);
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Email atau password customer tidak valid.');
        }

        if (!Hash::check($validated['password'], $user->password)) {
            Log::warning('Customer login attempt - password mismatch', ['email' => $validated['email'], 'user_id' => $user->id]);
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Email atau password customer tidak valid.');
        }

        // Login successful
        $this->storeUserSession($request, $user);
        Log::info('Customer login successful', ['user_id' => $user->id, 'email' => $user->email]);

        return redirect()->route('order.create')->with('success', 'Login berhasil.');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:100',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|min:10|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
        ]);

        $this->storeUserSession($request, $user);

        return redirect()->route('order.create')->with('success', 'Akun berhasil dibuat.');
    }

    public function showAdminLogin()
    {
        if (Session::has('admin_id')) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.admin_login');
    }

    public function adminLogin(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8|max:255',
        ]);

        // Find admin user
        $admin = User::query()
            ->where('email', $validated['email'])
            ->where('role', 'admin')
            ->first();

        // Verify credentials
        if (!$admin) {
            Log::warning('Admin login attempt - user not found', ['email' => $validated['email']]);
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Email atau password admin tidak valid.');
        }

        if (!$admin->password) {
            Log::warning('Admin login attempt - no password set', ['email' => $validated['email'], 'admin_id' => $admin->id]);
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Email atau password admin tidak valid.');
        }

        if (!Hash::check($validated['password'], $admin->password)) {
            Log::warning('Admin login attempt - password mismatch', ['email' => $validated['email'], 'admin_id' => $admin->id]);
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Email atau password admin tidak valid.');
        }

        // Regenerate session for security
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->regenerate();

        // Store admin info in session
        Session::put('admin_id', $admin->id);
        Session::put('admin_name', $admin->name);
        Session::put('admin_email', $admin->email);
        Session::put('admin_role', $admin->role);

        Log::info('Admin login successful', ['admin_id' => $admin->id, 'email' => $admin->email]);

        return redirect()->route('admin.dashboard')->with('success', 'Login admin berhasil.');
    }

    public function logout(Request $request)
    {
        Session::forget(['user_id', 'user_name', 'user_email']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }

    public function adminLogout(Request $request)
    {
        Session::forget(['admin_id', 'admin_name', 'admin_email']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Admin telah logout.');
    }

    protected function storeUserSession(Request $request, User $user): void
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->regenerate();

        Session::put('user_id', $user->id);
        Session::put('user_name', $user->name);
        Session::put('user_email', $user->email);
    }
}
