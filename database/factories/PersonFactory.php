<?php

use Faker\Generator as Faker;

$factory->define(App\Person::class, function (Faker $faker) {
    return [
        //
        'first_name' => $faker->text(50),
        'last_name' => $faker->text(50),
        'birthday' => $faker->dateTimeBetween('-30 years','now')
    ];
});
