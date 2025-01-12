<?php

namespace App\Http\Middleware;
 
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthClienteMiddleware
{
    /**
     *  Middleware para los clientes 
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next): Response
    {   //si el rol es cliente  76638194 919479698 62126609
        
         $user = Auth::user();
         if (!Auth::check()  || !($user->rol_id==2) ) {

            return redirect()->route('index');
        }

        return $next($request);


    }
}
