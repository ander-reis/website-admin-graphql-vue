<?php

use Illuminate\Database\Seeder;

class NoticiasCategoriasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\WebsiteAdmin\Models\NoticiasCategoria::class)->create([
            'ds_categoria' => 'Avaliação Postural'
        ]);

        factory(\WebsiteAdmin\Models\NoticiasCategoria::class)->create([
            'ds_categoria' => 'Campanha Salarial'
        ]);

        factory(\WebsiteAdmin\Models\NoticiasCategoria::class)->create([
            'ds_categoria' => 'Canal Afro-brasil'
        ]);

        factory(\WebsiteAdmin\Models\NoticiasCategoria::class)->create([
            'ds_categoria' => 'Colônia de Férias'
        ]);

        factory(\WebsiteAdmin\Models\NoticiasCategoria::class)->create([
            'ds_categoria' => 'Convênios'
        ]);

//        factory(\WebsiteAdmin\Models\NoticiasCategoria::class)->create([
//            'ds_categoria' => 'Cursos'
//        ]);
//
//        factory(\WebsiteAdmin\Models\NoticiasCategoria::class)->create([
//            'ds_categoria' => 'Direitos'
//        ]);
//
//        factory(\WebsiteAdmin\Models\NoticiasCategoria::class)->create([
//            'ds_categoria' => 'EaD'
//        ]);
//
//        factory(\WebsiteAdmin\Models\NoticiasCategoria::class)->create([
//            'ds_categoria' => 'Educação Básica'
//        ]);
//
//        factory(\WebsiteAdmin\Models\NoticiasCategoria::class)->create([
//            'ds_categoria' => 'Ensino Superior'
//        ]);
//
//        factory(\WebsiteAdmin\Models\NoticiasCategoria::class)->create([
//            'ds_categoria' => 'Eventos'
//        ]);
//
//        factory(\WebsiteAdmin\Models\NoticiasCategoria::class)->create([
//            'ds_categoria' => 'Geral'
//        ]);
//
//        factory(\WebsiteAdmin\Models\NoticiasCategoria::class)->create([
//            'ds_categoria' => 'Mackenzie'
//        ]);
//
//        factory(\WebsiteAdmin\Models\NoticiasCategoria::class)->create([
//            'ds_categoria' => 'Pós-graduação'
//        ]);
//
//        factory(\WebsiteAdmin\Models\NoticiasCategoria::class)->create([
//            'ds_categoria' => 'Sesi/Senai'
//        ]);
//
//        factory(\WebsiteAdmin\Models\NoticiasCategoria::class)->create([
//            'ds_categoria' => 'Uniban'
//        ]);
//
//        factory(\WebsiteAdmin\Models\NoticiasCategoria::class)->create([
//            'ds_categoria' => 'Voz do Professor'
//        ]);
    }
}
