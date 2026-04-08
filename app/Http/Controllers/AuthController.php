<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLogin(Request $request)
    {
        if (Session::has('user_id')) {
            return redirect()->route('home');
        }

        return view('auth.login');
    }

    /**
     * Login customer pakai name + phone.
     * Jika nomor HP belum ada → buat akun otomatis.
     *
     * FIX: email di-set ke null eksplisit agar tidak trigger NOT NULL constraint.
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|min:2|max:100',
            'phone' => 'required|string|min:8|max:20',
        ]);

        // Normalisasi nomor HP
        $phone = $this->normalizePhone($validated['phone']);

        // Cari user berdasarkan nomor HP
        $user = User::where('phone', $phone)
                    ->where('role', 'customer')
                    ->first();

        if (!$user) {
            // Buat akun baru otomatis — email dikosongkan dengan null eksplisit
            $user = User::create([
                'name'     => $validated['name'],
                'phone'    => $phone,
                'email'    => null,   // ← FIX: null eksplisit, bukan '?' atau missing key
                'password' => null,
                'role'     => 'customer',
            ]);
            Log::info('Customer auto-registered', ['user_id' => $user->id, 'phone' => $phone]);
        } else {
            // Update nama jika berubah
            if ($user->name !== $validated['name']) {
                $user->update(['name' => $validated['name']]);
            }
        }

        $this->storeUserSession($request, $user);
        Log::info('Customer login successful', ['user_id' => $user->id]);

        return redirect()->route('home')->with('success', 'Selamat datang, ' . $user->name . '!');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|min:3|max:100',
            'email'    => 'nullable|email|unique:users,email',
            'phone'    => 'required|string|min:10|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $phone = $this->normalizePhone($validated['phone']);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'] ?? null,
            'phone'    => $phone,
            'password' => isset($validated['password']) ? Hash::make($validated['password']) : null,
            'role'     => 'customer',
        ]);

        $this->storeUserSession($request, $user);

        return redirect()->route('home')->with('success', 'Akun berhasil dibuat.');
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
            'email'    => 'required|string|max:255',
            'password' => 'required|string|min:8|max:255',
        ]);

        // Support login pakai email ATAU username/name
        $admin = User::where(function ($q) use ($validated) {
                        $q->where('email', $validated['email'])
                          ->orWhere('name', $validated['email']);
                    })
                    ->where('role', 'admin')
                    ->first();

        if (!$admin || !$admin->password || !Hash::check($validated['password'], $admin->password)) {
            Log::warning('Admin login failed', ['input' => $validated['email']]);
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Email/username atau password admin tidak valid.');
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->regenerate();

        Session::put('admin_id',    $admin->id);
        Session::put('admin_name',  $admin->name);
        Session::put('admin_email', $admin->email);
        Session::put('admin_role',  $admin->role);

        Log::info('Admin login successful', ['admin_id' => $admin->id]);

        return redirect()->route('admin.dashboard')->with('success', 'Login admin berhasil.');
    }

    public function logout(Request $request)
    {
        Session::forget(['user_id', 'user_name', 'user_email', 'user_phone']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }

    public function adminLogout(Request $request)
    {
        Session::forget(['admin_id', 'admin_name', 'admin_email', 'admin_role']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Admin telah logout.');
    }

    protected function storeUserSession(Request $request, User $user): void
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->regenerate();

        Session::put('user_id',    $user->id);
        Session::put('user_name',  $user->name);
        Session::put('user_email', $user->email ?? '');
        Session::put('user_phone', $user->phone ?? '');
    }

    /**
     * Normalisasi nomor HP ke format +62...
     */
    protected function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);

        if (str_starts_with($phone, '08')) {
            $phone = '+62' . substr($phone, 1);
        } elseif (str_starts_with($phone, '62')) {
            $phone = '+' . $phone;
        } elseif (!str_starts_with($phone, '+')) {
            $phone = '+62' . $phone;
        }

        return $phone;
    }
}