<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLancamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lancamentos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->time('quantidadehoras');
            $table->string('quantidadehorasmes');
            $table->string('tipo');
            $table->bigInteger('usuarios_id')->unsigned();
            $table->bigInteger('projetos_id')->unsigned();
            $table->bigInteger('periodos_id')->unsigned();
            $table->timestamps();

            $table->foreign('usuarios_id')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreign('projetos_id')->references('id')->on('projetos')->onDelete('cascade');
            $table->foreign('periodos_id')->references('id')->on('periodos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lancamentos');
    }
}
