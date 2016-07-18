<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\DataController;

class Statistics
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      $stat = new DataController;
      $stat->setStat();
      
      return $next($request);
    }
}
