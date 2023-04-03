<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Tag;
use Faker\Generator as Faker;

$factory->define(Tag::class, function (Faker $faker) {
    $name = $faker->realText(rand(20, 30));
    return [
        'name' => $name,
        'slug' => Str::slug($name),
    ];
});
