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
    {   //si el rol es cliente 
        
         $user = Auth::user();
         if (!Auth::check()  && !($user->empresa->nombre!="MapTerra") ) {

            return redirect()->route('index');
        }

        return $next($request);


    }
}
