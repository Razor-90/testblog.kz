<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Category;
use Faker\Generator as Faker;

$factory->define(Category::class, function (Faker $faker) {
    $name = $faker->realText(rand(40, 50));
    return [
        'name' => $name,
        'content' => $faker->realText(rand(200, 500)),
        'slug' => Str::slug($name),
    ];
});
