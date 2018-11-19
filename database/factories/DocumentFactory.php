<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Document::class, function (Faker $faker) {
    return [
        'name' => $faker->word . '.' . $faker->fileExtension,
        'sha256' => $faker->sha256,
        'size' => $faker->numberBetween(0, PHP_INT_MAX)
    ];
});
