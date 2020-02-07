<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models\DB{
/**
 * Class User
 *
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property int $drink_id
 * @property Drink $favoriteDrink
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read int|null $clients_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DB\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DB\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DB\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DB\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DB\User whereDrinkId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DB\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DB\User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DB\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DB\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DB\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DB\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DB\User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models\DB{
/**
 * Class Drink
 *
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $caffeine_mg_per_serving
 * @property int $serving
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DB\Drink newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DB\Drink newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DB\Drink query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DB\Drink whereCaffeineMgPerServing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DB\Drink whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DB\Drink whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DB\Drink whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DB\Drink whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DB\Drink whereServing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DB\Drink whereUpdatedAt($value)
 */
	class Drink extends \Eloquent {}
}

namespace App\Models\DB{
/**
 * App\Models\DB\DrinkLog
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DB\DrinkLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DB\DrinkLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DB\DrinkLog query()
 */
	class DrinkLog extends \Eloquent {}
}

