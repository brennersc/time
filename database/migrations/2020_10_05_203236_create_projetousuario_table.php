<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjetoUsuarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projetousuario', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('usuarios_id')->unsigned();
            $table->bigInteger('projetos_id')->unsigned();
            $table->timestamps();

            $table->foreign('usuarios_id')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreign('projetos_id')->references('id')->on('projetos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projetousuario');
    }
}
