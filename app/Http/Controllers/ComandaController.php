<?php

namespace App\Http\Controllers;

use App\Comanda;
use App\Venda;
use App\VendaItem;
use App\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComandaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comandas = Comanda::all();
        return view('comanda.index', compact('comandas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('comanda.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Comanda::create($request->all());
        return redirect()->route('comanda.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Comanda  $comanda
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $comanda = Comanda::find($id);
        // select * from corretor where id = $id;
        $pedidos = DB::table('pedido')
            ->join('item','pedido.id_item','=','item.id')
            ->select('pedido.*','item.*',DB::raw('(item.preco*pedido.quantidade) as preco_total'))
            ->where('pedido.id_comanda',$id)
            ->get();
        // select * pedidos da comanda $id
        $total = $pedidos->sum('preco_total');
        // calcula valor total da comanda
        return view('comanda.show', compact('comanda', 'pedidos', 'total'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Comanda  $comanda
     * @return \Illuminate\Http\Response
     */
    public function edit(Comanda $comanda)
    {
        $comanda = Comanda::find($comanda->id);
        return view('comanda.edit', compact('comanda'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comanda  $comanda
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $comanda = Comanda::find($id);
        
        // Se estiver mudando para FECHADA, consolida a venda
        if ($request->status === 'FECHADA' && $comanda->status !== 'FECHADA') {
            DB::beginTransaction();
            try {
                // Cria a venda
                $venda = Venda::create([
                    'id_comanda' => $comanda->id,
                    'numero_mesa' => $comanda->numero_mesa,
                    'nome_cliente' => $comanda->nome_cliente,
                    'valor_total' => DB::table('pedido')
                        ->join('item', 'pedido.id_item', '=', 'item.id')
                        ->where('pedido.id_comanda', $comanda->id)
                        ->sum(DB::raw('item.preco * pedido.quantidade'))
                ]);

                // Move os itens para venda_itens
                $pedidos = DB::table('pedido')
                    ->join('item', 'pedido.id_item', '=', 'item.id')
                    ->where('pedido.id_comanda', $comanda->id)
                    ->select('pedido.*', 'item.preco')
                    ->get();

                foreach ($pedidos as $pedido) {
                    VendaItem::create([
                        'id_venda' => $venda->id,
                        'id_item' => $pedido->id_item,
                        'quantidade' => $pedido->quantidade,
                        'preco_unitario' => $pedido->preco,
                        'preco_total' => $pedido->preco * $pedido->quantidade
                    ]);
                }

                // Remove os pedidos da comanda
                Pedido::where('id_comanda', $comanda->id)->delete();

                // Atualiza o status da comanda para AGUARDANDO
                $comanda->status = 'AGUARDANDO';
                $comanda->save();

                DB::commit();
                return redirect()->route('comanda.index')->with('success', 'Comanda fechada e venda consolidada com sucesso!');
            } catch (\Exception $e) {
                DB::rollback();
                return redirect()->back()->with('error', 'Erro ao consolidar venda: ' . $e->getMessage());
            }
        }

        // Atualiza o status normalmente
        $comanda->update([
            'numero_mesa' => $request->numero_mesa,
            'nome_cliente' => $request->nome_cliente,
            'status' => $request->status
        ]);

        return redirect()->route('comanda.index')->with('success', 'Comanda atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comanda  $comanda
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Comanda::destroy($id);
//        $item = item::all();

        return redirect()->route('comanda.index');
        //return view('item.index', compact('item'));
    }

    public function podeReceberPedidos($id)
    {
        $comanda = Comanda::find($id);
        return $comanda && $comanda->status === 'ABERTA';
    }
}
