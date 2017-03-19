<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'avatar' => 'default.jpg',
        'password' => $password ?: $password = bcrypt('123456'),
        'star' => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 5),
        'remember_token' => str_random(10),
        'role' => config('settings.role.user'),
        'phone_number' => $faker->phoneNumber,
    ];
});

$factory->define(App\Models\Campaign::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->sentence,
        'description' => $faker->paragraph,
        'address' => $faker->address,
        'lat' => $faker->latitude,
        'lng' => $faker->longitude,
        'start_time' => $faker->dateTime,
        'end_time' => $faker->dateTime,
        'status' => $faker->boolean,
    ];
});

$factory->define(App\Models\Comment::class, function (Faker\Generator $faker) {
    static $userIds;
    static $campaignIds;

    return [
        'user_id' => $faker->randomElement($userIds ?: $userIds = App\Models\User::pluck('id')->toArray()),
        'campaign_id' => $faker->randomElement($campaignIds ?: $campaignIds = App\Models\Campaign::pluck('id')->toArray()),
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'text' => $faker->sentence,
    ];
});

$factory->define(App\Models\Like::class, function (Faker\Generator $faker) {
    static $userIds;
    static $campaignIds;

    return [
        'user_id' => $faker->randomElement($userIds ?: $userIds = App\Models\User::pluck('id')->toArray()),
        'campaign_id' => $faker->randomElement($campaignIds ?: $campaignIds = App\Models\Campaign::pluck('id')->toArray()),
    ];
});

$factory->define(App\Models\Relationship::class, function (Faker\Generator $faker) {
    static $userIds;

    return [
        'user_id' => $faker->randomElement($userIds ?: $userIds = App\Models\User::pluck('id')->toArray()),
        'target_id' => $faker->randomElement($userIds ?: $userIds = App\Models\User::pluck('id')->toArray()),
        'status' => $faker->boolean,
    ];
});

$factory->define(App\Models\Event::class, function (Faker\Generator $faker) {
    static $campaignIds;

    return [
        'title' => $faker->sentence,
        'description' => $faker->paragraph,
        'campaign_id' => $faker->randomElement($campaignIds ?: $campaignIds = App\Models\Campaign::pluck('id')->toArray()),
    ];
});

$factory->define(App\Models\Schedule::class, function (Faker\Generator $faker) {
    static $eventIds;

    return [
        'name' => $faker->sentence,
        'description' => $faker->paragraph,
        'event_id' => $faker->randomElement($eventIds ?: $eventIds = App\Models\Event::pluck('id')->toArray()),
        'start_time' => $faker->dateTime,
        'end_time' => $faker->dateTime,
    ];
});
