<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;
use Auth;

/**
 * Class Drink
 * @package App\Models
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $caffeine_mg_per_serving
 * @property int $serving
 * @property int $user_id
 */
class Drink extends Model
{
    protected $fillable = [
        'name', 'description', 'caffeine_mg_per_serving', 'serving', 'user_id',
    ];

    public static function getDrinks() {
        $drinks = (new static)->get();

        return $drinks->transform(function (Drink $item, $key) {
            return [
                'id' => $item->id,
                'drink' => $item->name,
                'description' => $item->description,
                'servings' => $item->serving,
                'caffeine_in_mg_per_serving' => $item->caffeine_mg_per_serving
            ];
        });
    }

}
