<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use WebsiteAdmin\Model;

$factory->define(\WebsiteAdmin\Models\Account::class, function (Faker $faker) {
    return [
        'description' => $faker->text(20)
    ];
});
