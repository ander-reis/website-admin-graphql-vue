<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbSinproNoticiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('tb_sinpro_noticias', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned()->index();
            $table->integer('id_noticia')->default(0);
            $table->bigInteger('id_categoria')->unsigned()->default(0);
            $table->dateTime('dt_noticia', 3)->default(\Carbon\Carbon::now());
            $table->dateTime('dt_cadastro')->default(\Carbon\Carbon::now());
            $table->dateTime('dt_alteracao', 3)->default(\Carbon\Carbon::now());
            $table->dateTime('dt_expira', 3)->default('1900-01-01');
            $table->char('fl_exibir_destaque', 1)->default('S');
            $table->string('ds_resumo', 80)->default('');
            $table->text('ds_texto')->nullable();
            $table->string('ds_palavra_chave', 150)->default('');
            $table->char('fl_oculta', 1)->default('N');
            $table->smallInteger('fl_status')->default(1);
            $table->string('ds_social', 500)->default('');

            $table->foreign('id_categoria')->references('id')->on('tb_sinpro_noticias_categorias');
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('tb_sinpro_noticias');
        Schema::enableForeignKeyConstraints();
    }
}
