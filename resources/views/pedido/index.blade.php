@extends('adminlte::page')

@section('title', 'Pedidos')

@section('content_header')
    <h1><i class="fas fa-utensils"></i> Pedidos</h1>
@stop

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading clearfix">
            Comandas Abertas
            <div class="pull-right">
                <a href="{{ route('pedido.index') }}" class="btn btn-info"><i class="fas fa-sync"></i> Atualizar</a>
            </div>
        </div>

        <div class="panel-body">
            <table class="table table-striped table-bordered table-hover" id="table-pedido">
                <thead>
                    <tr>
                        <td>ID</td>
                        <td>Mesa</td>
                        <td>Cliente</td>
                        <td>Status</td>
                        <td>Ações</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($comandas as $comanda)
                    @if($comanda->status == 'ABERTA')
                    <tr>
                        <td>{{$comanda->id}}</td>
                        <td>{{$comanda->numero_mesa}}</td>
                        <td>{{$comanda->nome_cliente}}</td>
                        <td>{{$comanda->status}}</td>
                        <td>
                            <a href="{{ route('pedido.create', $comanda->id) }}" class="btn btn-xs btn-success">
                                <i class="fas fa-plus"></i> Realizar Pedido
                            </a>
                            <a href="{{ route('comanda.show', $comanda->id) }}" class="btn btn-xs btn-primary">
                                <i class="fas fa-eye"></i> Ver Detalhes
                            </a>
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
<script>
$(document).ready(function() {
    $('#table-pedido').DataTable({
        language: {
            url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json",
        }
    });
});
</script>
@stop
