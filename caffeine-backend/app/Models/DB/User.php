<?php

namespace App\Models\DB;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * Class User
 * @package App\Models
 *
 * @property int $id
 * @property string $name
 * @property int $drink_id
 * @property Drink $favoriteDrink
 */
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    private const CAFFEINE_LIMIT_PER_DAY_MG = 500;
    public const CAFFEINE_NOT_EXCEED_LIMIT_PER_DAY_MG = 1000;
    public const CAFFEINE_EXCEED_LIMIT_PER_DAY_MG = 2000;
    public const CAFFEINE_DANGER_EXCEED_LIMIT_PER_DAY_MG = 3000;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'drink_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function favoriteDrink() {
        return $this->belongsTo('App\Models\DB\Drink', 'drink_id');
    }

    public function canDrinkFavoriteDrink(int $quantity, int $serving): array {
        $favoriteDrink = $this->favoriteDrink;

        $caffeineLeft = $this::CAFFEINE_LIMIT_PER_DAY_MG - ($favoriteDrink->caffeine_mg_per_serving * $serving * $quantity);
        //0 = cannot drink favorite drink, 1 = can drink favorite drink, 2 = below legal limit, not safe

       if ($caffeineLeft < 0) {
           $data = [
               'code' => $this::CAFFEINE_DANGER_EXCEED_LIMIT_PER_DAY_MG,
               'caffeine_left_mg' => $caffeineLeft,
           ];
       }
       elseif ($favoriteDrink->caffeine_mg_per_serving <= $caffeineLeft) {
           $data = [
               'code' => $this::CAFFEINE_NOT_EXCEED_LIMIT_PER_DAY_MG,
               'caffeine_left_mg' => $caffeineLeft,
               'serving_size_of_favorite_drink' => $this->calculateSuggestServings(
                                                        $caffeineLeft,
                                                        $favoriteDrink->caffeine_mg_per_serving,
                                                        $favoriteDrink->serving
                                                    )
           ];
       }
       else {
           $data = [
               'code' => $this::CAFFEINE_EXCEED_LIMIT_PER_DAY_MG,
               'caffeine_left_mg' => $caffeineLeft,
           ];
       }

       return $data;
    }

    public function suggestOtherDrinks(int $caffeine_left) {
        $drinks = Drink::where('caffeine_mg_per_serving', '<=', $caffeine_left)->get();

        $drinks->transform(function (Drink $item, $key) use ($caffeine_left) {
            return [
                'id'                  => $item->id,
                'drink'               => $item->name,
                'description'         => $item->description,
                'servings_allowed'    => $this->calculateSuggestServings(
                                            $caffeine_left,
                                            $item->caffeine_mg_per_serving,
                                            $item->serving
                                         ),
                'caffeine_in_mg'       => $item->caffeine_mg_per_serving,
            ];
        });

        return $drinks->first() ? $drinks : null;
    }

    private function calculateSuggestServings(int $caffeine_left, int $caffeine_per_serving, int $drink_serving = 1): int {
            for ($i = $drink_serving; $i >= 1; $i--) {
                if(($i * $caffeine_per_serving) <= $caffeine_left) {
                    return $i;
                }
            }
    }

}
