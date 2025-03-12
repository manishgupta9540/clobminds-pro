<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\IpLogData;
use Auth;

class ActivityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $ip = $request->ip();
        $userAgent = $request->header('User-Agent');
        $url = $request->fullUrl();
        $method = $request->method();
        $user_id =  Auth::user() ? Auth::user()->id : '';

        // Controller aur method information
        $route = Route::getCurrentRoute();
        $controllerAction = $route ? $route->getActionName() : 'N/A';

        // Request parameters
        $parameters = $request->all();

        // Custom log entry create karna
        $logEntry = [
            'user_id'  => $user_id,
            'ip'       => $ip,
            'user_agent' => $userAgent,
            'url'        => $url,
            'method'     => $method,
            'controller_action' => $controllerAction,
            'parameters'        => json_encode($parameters), // Ensure parameters are in string format
        ];
        IpLogData::create($logEntry);
        // Log entry ko external API pe bhejna
      

        return $next($request);
    }
}
