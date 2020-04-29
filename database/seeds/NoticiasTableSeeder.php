<?php

use Illuminate\Database\Seeder;
use WebsiteAdmin\Models\Noticias;
use WebsiteAdmin\Models\NoticiasCategoria;

class NoticiasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $noticiaCategoriaId = NoticiasCategoria::all();
        $noticias = factory(Noticias::class, 5)->make();

        $noticias->each(function(Noticias $model) use($noticiaCategoriaId){
            $id = $noticiaCategoriaId->random()->id;
            $model->categoria()->associate($id)->save();
        });
    }
}
