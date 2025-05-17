<?php

namespace App\Http\Controllers;

use App\Pedido;
use App\Comanda;
use App\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $comandas = DB::table('comanda')
            ->where('status', 'ABERTA')
            ->get();
        
        return view('pedido.index', compact('comandas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $comanda = Comanda::find($id);
        $itens = Item::all();
        return view('pedido.create', compact('comanda', 'itens'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id_comanda = $request->id_comanda;
        $id_itens = $request->id_item;
        $quantidades = $request->quantidade;

        for ($i = 0; $i < count($id_itens); $i++) {
            Pedido::create([
                'id_comanda' => $id_comanda,
                'id_item' => $id_itens[$i],
                'quantidade' => $quantidades[$i],
                'status' => 'PREPARACAO'
            ]);
        }

        return redirect()->route('comanda.show', $id_comanda);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\pedido  $pedido
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pedido = DB::table('pedido')
            ->join('item', 'pedido.id_item', '=', 'item.id')
            ->join('comanda', 'comanda.id', '=', 'pedido.id_comanda')
            ->where('pedido.id', $id)
            ->select('pedido.*', 'item.*', 'comanda.*', DB::raw('(item.preco*pedido.quantidade) as preco_total'))
            ->first();

        if (!$pedido) {
            return redirect()->route('pedido.index')->with('error', 'Pedido não encontrado.');
        }

        return view('pedido.show', compact('pedido'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\pedido  $pedido
     * @return \Illuminate\Http\Response
     */
    public function edit(pedido $pedido)
    {
        $pedido = Pedido::find($pedido->id);
        return view('pedido.edit', compact('pedido'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\pedido  $pedido
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::table('pedido')
            ->where('id', $id)
            ->update(
                [
                    'id_comanda' => $request->id_comanda,
                    'id_item' => $request->id_item,
                    'quantidade' => $request->quantidade,
                    'status' => $request->status
                ]
            );
        return redirect()->route('pedido.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\pedido  $pedido
     * @return \Illuminate\Http\Response
     */
    public function destroy($id_pedido)
    {

        Pedido::destroy($id_pedido);
//        $pedido = pedido::all();

        return redirect()->route('pedido.index');
        //return view('pedido.index', compact('pedido'));
    }

    public function addPedido($id) {
        $comanda = Comanda::find($id);
        $item = Item::all();
        return view('pedido.create',compact('comanda', 'item'));

    }
    public function statusPronto($id){
        DB::table('pedido')
            ->where('id', $id)
            ->update(
                [
                    'status' => 'PRONTO'
                ]
            );
        return redirect()->route('pedido.index');
    }

    public function statusCancelado($id){
        DB::table('pedido')
            ->where('id', $id)
            ->update(
                [
                    'status' => 'CANCELADO'
                ]
            );
        return redirect()->route('pedido.index');
    }

    public function realizar()
    {
        $itens = Item::all();
        return view('pedido.realizar', compact('itens'));
    }
}
