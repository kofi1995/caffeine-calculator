<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Helpers\JsonReturn;
use Psy\Util\Json;

class CheckFavoriteDrinkSelected
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
        if (!$request->user()->favoriteDrink) {
            return JsonReturn::favoriteDrinkNotSelected();
        }

        return $next($request);
    }
}
