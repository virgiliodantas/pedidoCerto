<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendasTable extends Migration
{
    public function up()
    {
        Schema::create('vendas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_comanda')->unsigned();
            $table->string('numero_mesa', 5);
            $table->string('nome_cliente', 20);
            $table->decimal('valor_total', 10, 2);
            $table->timestamps();

            $table->foreign('id_comanda')->references('id')->on('comanda');
        });

        Schema::create('venda_itens', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_venda')->unsigned();
            $table->integer('id_item')->unsigned();
            $table->integer('quantidade');
            $table->decimal('preco_unitario', 10, 2);
            $table->decimal('preco_total', 10, 2);
            $table->timestamps();

            $table->foreign('id_venda')->references('id')->on('vendas');
            $table->foreign('id_item')->references('id')->on('item');
        });
    }

    public function down()
    {
        Schema::dropIfExists('venda_itens');
        Schema::dropIfExists('vendas');
    }
} 