<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next ,$roles): Response
    {
        $userRole = Auth::user()->role;

        //Mengubah roles menjadi array
        // Jika roles adalah string, pisahkan dengan '|'
        $rolesArray = explode( '|', $roles );

        if(in_array( $userRole, $rolesArray)){
            return $next($request);
        }

        //jika tidak sesuai, redirect atau tampilan error
        if($userRole == 'admin') {
            return redirect()->route('admin.index')->with('error', 'Anda tidak memiliki akses');
        } elseif ( $userRole == 'user' ) {
            return redirect()->route('user.index')->with('error', 'Anda tidak memiliki akses');
        } elseif ($userRole == 'bank') {
            return redirect()->route('bank.index')->with('error', 'Anda tidak memiliki akses');
        } else {
            return redirect()->route('login')->with('error', 'Anda tidak memiliki akses');
        }
    }
}
