<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use WebsiteAdmin\Model;

$factory->define(\WebsiteAdmin\Models\Noticias::class, function (Faker $faker) {
    static $index = 1;
    return [
        'id_noticia' => $index++,
        'dt_cadastro' => $faker->dateTime('now', 'UTC'),
        'fl_exibir_destaque' => rand(0, 1),
        'ds_resumo' => $faker->text(80),
        'ds_texto' => $faker->sentence(rand(10, 30)),
        'ds_palavra_chave' => "$faker->word, $faker->city, $faker->name",
        'fl_oculta' => rand(0, 1),
        'fl_status' => rand(0, 1)
    ];
});
