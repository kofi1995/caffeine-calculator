<?php

namespace Tests\Feature;

use App\Models\DB\User;
use App\Models\Helpers\JsonReturn;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Passport\Client as OAuthClient;
use Laravel\Passport\Passport;
use App\Models\DB\Drink;

class ApiEndpointsTest extends TestCase
{
    use RefreshDatabase;
    /**
     * API tests.
     *
     * @return void
     */

    private $user;

    private $drink;

    private $drinks;

    function setUp() :void {
        parent::setUp();
        $this->artisan('db:seed');
        //$this->artisan('passport:client --password --name=test_client -n');
        $this->user = User::first();
        $this->drinks = Drink::getDrinks();
        $this->drink = $this->drinks->first();
        $this->user->drink_id = $this->drink['id'];
        $this->user->save();
    }

    public function testOauthAuthentication()
    {
        $client = OAuthClient::where('password_client', 1)->first();

        $response = $this->json('POST', '/oauth/token', [
            'username' => 'test@example.test',
            'password' => 'password',
            "client_id"     => $client->id,
            "client_secret" => $client->secret,
            "grant_type"    => 'password',
            "scope"          => '*',
        ]);

        $response->assertStatus(200);
    }

    public function testLogin()
    {
        $response = $this->json('POST', '/api/login', [
            'email' => 'test@example.test',
            'password' => 'password',
        ]);

        $response->assertStatus(200);
    }

    public function testGetDrinks() {
        Passport::actingAs(
            $this->user,
            ['*']
        );
        $response = $this->json('GET', '/api/drinks');
        $response->assertStatus(200)->assertJson([
            'success' => true,
            'data' => [
                'drinks' => [
                    $this->drink
                ],
            ],
        ]);
    }

    public function testFavoriteDrinkNotSelectedMiddleware() {
        $this->user->drink_id = null;
        $this->user->save();

        Passport::actingAs(
            $this->user,
            ['*']
        );
        $response = $this->json('POST', '/api/calculate-caffeine-intake', [
            'quantity' => 1,
            'serving' => 1,
        ]);
        $response->assertStatus(403)->assertJson([
            'success' => false,
        ]);
    }

    public function testChooseFavoriteDrink() {
        Passport::actingAs(
            $this->user,
            ['*']
        );
        $response = $this->json('PUT', '/api/favorite-drink', [
            'drink_id' => $this->drink['id'],
        ]);
        $response->assertStatus(201)->assertJson([
            'success' => true
        ]);

        $this->assertDatabaseHas('users', [
            'drink_id' => $this->drink['id'],
        ]);
    }

    public function testCalculateCaffeineSuccess() {
        Passport::actingAs(
            $this->user->fresh(),
            ['*']
        );
        $response = $this->json('POST', '/api/calculate-caffeine-intake', [
            'quantity' => 1,
            'serving' => 1,
        ]);
        $response->assertStatus(200)->assertJson([
            'success' => true,
        ]);
    }

    public function testCalculateCaffeineServingValidationFail() {
        Passport::actingAs(
            $this->user->fresh(),
            ['*']
        );
        $response = $this->json('POST', '/api/calculate-caffeine-intake', [
            'quantity' => 1,
            'serving' => $this->drink['servings'] + 1,
        ]);
        $response->assertStatus(422);
    }

    public function testCalculateCaffeineServingCalculations() {

            $test_data = $this->calculationsTestData();
            $this->user->drink_id = $test_data['drink_id'];
            $this->user->save();

            Passport::actingAs(
                $this->user,
                ['*']
            );
            $internalCode = $this->user->canDrinkFavoriteDrink($test_data['data']['quantity'], $test_data['data']['serving']);
            $response = $this->json('POST', '/api/calculate-caffeine-intake', $test_data['data']);

            $response->assertStatus($this->jsonCode($internalCode['code']));
    }

    private function calculationsTestData() {

        $drink = $this->drinks->random();

            return $postData = [
                'drink_id' => $drink['id'],
                'data' => [
                    'quantity' => rand(1,10),
                    'serving' => rand(1, $drink['servings']),
                ],
            ];
    }

    private function jsonCode($internal_code) {
        $user = $this->user;
        $response = 200;
        switch ($internal_code){
            case $user::CAFFEINE_NOT_EXCEED_LIMIT_PER_DAY_MG:
                $response = 200;
                break;
            case $user::CAFFEINE_EXCEED_LIMIT_PER_DAY_MG:
                $response = 409;
                break;
            case $user::CAFFEINE_DANGER_EXCEED_LIMIT_PER_DAY_MG:
                $response = 403;
                break;
        }

        return $response;
    }
}
