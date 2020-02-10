<?php

namespace App\Http\Controllers;

use App\Models\DB\User;
use App\Models\Helpers\JsonReturn;
use Illuminate\Http\Request;
use App\Models\DB\Drink;
use Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use Laravel\Passport\Client as OAuthClient;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('auth:api')->except(['login']);
        $this->middleware('check.favorite.drink')->only(['calculateCaffeineIntake']);
    }

    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        $client = OAuthClient::where('password_client', 1)->whereNull('user_id')->first();

        if (!$client) {
            return response()->json([
                'message' => 'No client exists for oAuth'
            ], 500);
        }
        $request->request->add([
            "client_id"     => $client->id,
            "client_secret" => $client->secret,
            "grant_type"    => 'password',
            "scope"          => '*',
            "username"      => $request->input('email'),
        ]);

        $tokenRequest = $request->create('oauth/token', 'POST', $request->all());

        $response = app()->handle($tokenRequest);

        $json = (array) json_decode($response->getContent());
        if (isset($json['access_token'])) {
            $json['user'] = User::with('favoriteDrink')->where('email', $request->input('email'))->first();
        }
        $response->setContent(json_encode($json));

        return $response;
    }

    public function listDrinks() {
        $drinks = Drink::getDrinks();

        return JsonReturn::assetFetched('List of Drinks', [
            'drinks' => $drinks,
        ]);
    }

    public function chooseFavoriteDrink(Request $request) {
        $request->validate([
            'drink_id' => ['required','integer','exists:drinks,id'],
        ]);
        $user = Auth::user();
        $user->drink_id = $request->input('drink_id');
        $user->save();

        return JsonReturn::assetCreated('Favorite drink selected.', [
            'favorite_drink' => Auth::user()->favoriteDrink
        ]);
    }

    public function calculateCaffeineIntake(Request $request) {
        $user = Auth::user()->load('favoriteDrink');

        $request->validate([
            'quantity' => 'required|integer',
            'serving' => 'required|integer|min:1|max:' . $user->favoriteDrink->serving,
        ]);

        $caffeineLeft = $user->canDrinkFavoriteDrink($request->input('quantity'), $request->input('serving'));

        $response = null;

        switch ($caffeineLeft['code']){
            case $user::CAFFEINE_NOT_EXCEED_LIMIT_PER_DAY_MG:
                $response = JsonReturn::canDrinkFavorite(
                    $caffeineLeft['caffeine_left_mg'],
                    $caffeineLeft['serving_size_of_favorite_drink'],
                    $caffeineLeft['serving_left']
                );
                break;
            case $user::CAFFEINE_EXCEED_LIMIT_PER_DAY_MG:
                $suggestions = $user->suggestOtherDrinks($caffeineLeft['caffeine_left_mg']);
                $response = JsonReturn::cannotDrinkMaySuggest($caffeineLeft['caffeine_left_mg'], $suggestions);
                break;
            case $user::CAFFEINE_DANGER_EXCEED_LIMIT_PER_DAY_MG:
                $response = JsonReturn::exceededCaffeineLimit($caffeineLeft['caffeine_left_mg']);
                break;
        }

        return $response;
    }

    public function logout() {
        $accessToken = Auth::user()->token();
        DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true
            ]);

        $accessToken->revoke();
        return response()->json(null, 204);
    }
}
