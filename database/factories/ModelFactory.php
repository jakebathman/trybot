<?php

use Illuminate\Support\Str;

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

$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => Str::random(10),
        'slack_user_id' => 'U' . $faker->regexify('[A-Z0-9]{8}'),
        'slack_user_name' => $faker->userName,
        'slack_team_id' => 'T' . $faker->regexify('[A-Z0-9]{8}'),
        'slack_team_domain' => 'reddittryhard',

    ];
});

$factory->define(App\Http\Models\Twitch::class, function (Faker\Generator $faker) {
    return [
        'twitch_username' => $faker->userName,
        'twitch_user_id' => $faker->randomNumber(8),
        'is_active' => 1,
    ];
});
