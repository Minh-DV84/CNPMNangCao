<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckGioHanhChinh
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Admin không bị giới hạn giờ hành chính
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        $gioHienTai = now()->format('H:i');
        $gioBatDau = '08:30';
        $gioKetThuc = '17:30';

        if ($gioHienTai < $gioBatDau || $gioHienTai > $gioKetThuc) {
            return response()->json(['message' => 'Outside working hours'], 403);
        }

        return $next($request);
    }
}
