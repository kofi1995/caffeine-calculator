<?php

namespace App\Models\Helpers;


class JsonReturn
{
    public function success(string $message, $data = null, $code = 200) {
        return response()->json([
                'success'    => true,
                'message'   => $message,
                'data'      => $data,
        ], $code);
    }
    public function error(string $message, $error_code = 500, $data = null) {
        return response()->json([
                'success'    => false,
                'message'   => $message,
                'data'      => $data,
        ], $error_code);
    }

    public static function assetCreated(string $message = null, $data = null) {
        return (new self)->success($message ?? 'Asset created', $data, 201);
    }
    public static function assetUpdated(string $message = null) {
        return (new self)->success($message ?? 'Asset updated', null, 204);
    }
    public static function assetFetched(string $message = null, $data = null) {
        return (new self)->success($message ?? 'Asset fetched', $data, 200);
    }
    public static function assetNotFound(string $message = null) {
        return (new self)->error($message ?? 'Asset Not created', 404);
    }
    public static function assetNotCreated(string $message = null) {
        return (new self)->error($message ?? 'Asset Not created', 400);
    }


    public static function favoriteDrinkNotSelected() {
        return (new self)->error('Favorite drink not selected', 403);
    }
    public static function canDrinkFavorite(int $caffeine_left, int $servings_allowed, int $serving_left) {
        return (new self)->success('You can drink your favorite drink', [
            'servings_allowed' => $servings_allowed,
            'servings_left' => $serving_left,
            'caffeine_left_mg' => $caffeine_left,
        ]);
    }
    public static function exceededCaffeineLimit(int $limit) {
        return (new self)->error('This is unhealthy. You have already exceeded your caffeine limit by: ' . abs($limit) . ' mg', 403, [
            'caffeine_left_mg' => $limit,
        ]);
    }
    public static function cannotDrinkMaySuggest(int $caffeine_left, $suggestions = null) {
        $suggestionMessage = $suggestions ? ' Here are a list of other drinks you can try and their serving size:' : '';
        return (new self)->error(
            'You cannot take your favorite drink. You have ' . $caffeine_left . 'mg of caffeine left.' . $suggestionMessage,
            409,
            [
                'suggestions' => $suggestions,
                'caffeine_left_mg' => $caffeine_left,
            ]
        );
    }
}
