@extends('adminlte::page')

@section('title', 'Detalhes do Pedido')

@section('content_header')
    <h1><i class="fas fa-utensils"></i> Detalhes do Pedido</h1>
@stop

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading clearfix">
            Informações do Pedido
            <div class="pull-right">
                <a href="{{ route('pedido.index') }}" class="btn btn-info">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <h4>Informações da Comanda</h4>
                    <table class="table table-bordered">
                        <tr>
                            <th>Número da Comanda</th>
                            <td>#{{ $pedido->id_comanda }}</td>
                        </tr>
                        <tr>
                            <th>Status da Comanda</th>
                            <td>{{ $pedido->status }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h4>Informações do Item</h4>
                    <table class="table table-bordered">
                        <tr>
                            <th>Item</th>
                            <td>{{ $pedido->titulo_prato }}</td>
                        </tr>
                        <tr>
                            <th>Quantidade</th>
                            <td>{{ $pedido->quantidade }}</td>
                        </tr>
                        <tr>
                            <th>Valor Unitário</th>
                            <td>R$ {{ number_format($pedido->preco, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Total</th>
                            <td>R$ {{ number_format($pedido->preco_total, 2, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <h4>Ações</h4>
                    @if($pedido->status == 'PREPARACAO')
                        <a href="{{ route('statusPronto', $pedido->id) }}" class="btn btn-success">
                            <i class="fas fa-check"></i> Marcar como Pronto
                        </a>
                        <a href="{{ route('statusCancelado', $pedido->id) }}" class="btn btn-danger">
                            <i class="fas fa-ban"></i> Cancelar Pedido
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
<style>
    .table th {
        width: 200px;
    }
</style>
@stop

@section('js')

@stop