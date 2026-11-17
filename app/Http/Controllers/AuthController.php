<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        return response()->json([
            'message' => 'Register berhasil',
            'user' => $user
        ], 201);
    }

    // For Web Login
    public function webLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // For Web Register
    public function webRegister(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        Auth::login($user);

        return redirect('/')->with('success', 'Register berhasil!');
    }

    // For Web Logout
    public function webLogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    // Rest of your existing methods for API...
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json([
                'message' => 'Email atau password salah'
            ], 401);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        return response()->json(auth('api')->user());
    }

    public function logout()
    {
        auth('api')->logout();

        return response()->json([
            'message' => 'Logout berhasil'
        ]);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

        // ✅ ================= FORGOT PASSWORD (SIMPLE) =================

    /**
     * Tampilkan form input email
     */
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Cek email, redirect ke form reset password
     */
    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()
                ->withErrors(['email' => 'Email tidak terdaftar di sistem.'])
                ->onlyInput('email');
        }

        return redirect()->route('password.reset.form', ['email' => $user->email])
                         ->with('success', 'Email ditemukan. Silakan masukkan password baru.');
    }

    /**
     * Tampilkan form reset password
     */
    public function showResetForm(Request $request)
    {
        $email = $request->get('email');

        if (!$email || !User::where('email', $email)->exists()) {
            return redirect()->route('password.request')
                             ->withErrors(['email' => 'Sesi tidak valid. Masukkan email kembali.']);
        }

        return view('auth.reset-password', compact('email'));
    }

    /**
     * Simpan password baru
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'    => 'required|email|exists:users,email',
            'password' => 'required|min:6|max:40|confirmed',
        ], [
            'email.required'     => 'Email wajib diisi.',
            'email.exists'       => 'Email tidak terdaftar.',
            'password.required'  => 'Password baru wajib diisi.',
            'password.min'       => 'Password minimal 6 karakter.',
            'password.max'       => 'Password maksimal 20 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('login')
                         ->with('success', 'Password berhasil direset! Silakan login dengan password baru.');
    }

    // ✅ ============ END FORGOT PASSWORD ============
}