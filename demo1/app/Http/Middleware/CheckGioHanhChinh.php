<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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
        $gioHienTai = now()->format('H:i');
        $gioBatDau = '08:30';
        $gioKetThuc = '17:30';

        if ($gioHienTai < $gioBatDau || $gioHienTai > $gioKetThuc) {
            return response()->json(['message' => 'Outside working hours'], 403);
        }

        return $next($request);
    }
}
