<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddDefaultItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('item')->insert([
            [
                'titulo_prato' => 'almoço',
                'desc_prato' => 'almoço padrao',
                'preco' => 64.9,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'titulo_prato' => 'lata350',
                'desc_prato' => 'refrigerante lata 350ml',
                'preco' => 7.0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'titulo_prato' => 'latazero350',
                'desc_prato' => 'refrigerante lata zero 350ml',
                'preco' => 7.5,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('item')
            ->whereIn('titulo_prato', ['almoço', 'lata350', 'latazero350'])
            ->delete();
    }
} 