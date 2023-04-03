<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Post;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Post::class, function (Faker $faker) {
    $name = $faker->realText(rand(70, 100));
    return [
        'user_id' => rand(1, 10),
        'category_id' => rand(1, 5),
        'name' => $name,
        'excerpt' => $faker->realText(rand(300, 400)),
        'content' => $faker->realText(rand(400, 500)),
        'slug' => Str::slug($name),
        'published_by' => rand(1, 10),
    ];
});
