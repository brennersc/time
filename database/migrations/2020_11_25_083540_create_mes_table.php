<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mes', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('ano')->nullable();

            $table->string('janeiro')->nullable();
            $table->string('fevereiro')->nullable();
            $table->string('marco')->nullable();
            $table->string('abril')->nullable();
            $table->string('maio')->nullable();
            $table->string('junho')->nullable();
            $table->string('julho')->nullable();
            $table->string('agosto')->nullable();
            $table->string('setembro')->nullable();
            $table->string('outubro')->nullable();
            $table->string('novembro')->nullable();
            $table->string('dezembro')->nullable();

            $table->string('status')->nullable();

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
        Schema::dropIfExists('mes');
    }
}
