<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    protected $table = 'vendas';
    
    protected $fillable = [
        'id_comanda',
        'numero_mesa',
        'nome_cliente',
        'valor_total'
    ];

    public function itens()
    {
        return $this->hasMany(VendaItem::class, 'id_venda');
    }

    public function comanda()
    {
        return $this->belongsTo(Comanda::class, 'id_comanda');
    }
} 