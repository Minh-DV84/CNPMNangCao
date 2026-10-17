<?php

namespace App\Http\Controllers;

use App\Models\SinhVien;
use App\Models\User;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Hiển thị trang đăng nhập
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('tongquan');
        }

        return view('auth.login');
    }

    /**
     * Xử lý đăng nhập
     */
    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        $login = $validated['login'];
        $password = $validated['password'];
        $remember = $validated['remember'] ?? false;

        // Kiểm tra rate limiting: 5 lần/phút theo (tài khoản + IP)
        $key = 'login:' . $login . ':' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw ValidationException::withMessages([
                'login' => 'Bạn đã đăng nhập sai quá nhiều lần. Vui lòng thử lại sau 1 phút.',
            ]);
        }

        $user = null;

        // Nếu nhập đúng 7 chữ số -> coi là MSV
        if (preg_match('/^\d{7}$/', $login)) {
            $sinhVien = SinhVien::where('msv', $login)->first();
            if ($sinhVien) {
                $user = User::where('sinh_vien_id', $sinhVien->id)->first();
            }
        } else {
            // Coi là email
            $user = User::where('email', $login)->first();
        }

        if ($user && Hash::check($password, $user->password)) {
            RateLimiter::clear($key);

            Auth::login($user, $remember);
            $request->session()->regenerate();

            return redirect()->intended(route('tongquan'));
        }

        RateLimiter::hit($key);

        throw ValidationException::withMessages([
            'login' => 'Tài khoản hoặc mật khẩu không đúng.',
        ]);
    }

    /**
     * Xử lý đăng xuất
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
