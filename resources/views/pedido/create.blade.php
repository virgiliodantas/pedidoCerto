@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1><i class="fas fa-fx fa-user"></i> Realizar Pedido - Comanda #{{ $comanda->id }}</h1>
@stop

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <i class="fas fa-fx fa-user"></i> Adicionar Itens ao Pedido
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <h4>Itens Disponíveis</h4>
                    <div class="row">
                        @foreach($itens as $item)
                        <div class="col-md-3 mb-3">
                            <button type="button" class="btn btn-default btn-block item-btn" 
                                    data-id="{{ $item->id }}"
                                    data-nome="{{ $item->titulo_prato }}"
                                    data-preco="{{ $item->preco }}">
                                <strong>{{ $item->titulo_prato }}</strong><br>
                                R$ {{ number_format($item->preco, 2, ',', '.') }}
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-12">
                    <h4>Itens Adicionados</h4>
                    <form method="post" action="{{ route('pedido.store') }}" id="pedidoForm">
                        {{ csrf_field() }}
                        <input type="hidden" name="id_comanda" value="{{ $comanda->id }}">
                        
                        <div id="itensAdicionados">
                            <!-- Os itens serão adicionados aqui via JavaScript -->
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Finalizar Pedido
                            </button>
                            <a href="{{ route('pedido.index') }}" class="btn btn-default">
                                <i class="fas fa-reply"></i> Voltar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
<style>
.item-btn {
    height: 80px;
    margin-bottom: 10px;
    text-align: center;
    white-space: normal;
}
.item-selected {
    background-color: #dff0d8;
    border-color: #d6e9c6;
}
.quantidade-input {
    width: 60px;
    display: inline-block;
}
</style>
@stop

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const itensAdicionados = document.getElementById('itensAdicionados');
    const itensSelecionados = new Set();

    document.querySelectorAll('.item-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const itemId = this.dataset.id;
            const itemNome = this.dataset.nome;
            const itemPreco = this.dataset.preco;

            if (!itensSelecionados.has(itemId)) {
                // Adiciona o item à lista
                const div = document.createElement('div');
                div.className = 'form-group';
                div.innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <label>${itemNome}</label>
                            <input type="hidden" name="id_item[]" value="${itemId}">
                        </div>
                        <div class="col-md-4">
                            <input type="number" name="quantidade[]" class="form-control quantidade-input" 
                                   value="1" min="0.001" step="0.001" required>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger btn-sm remover-item" 
                                    data-id="${itemId}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
                itensAdicionados.appendChild(div);
                itensSelecionados.add(itemId);
                this.classList.add('item-selected');

                // Adiciona evento para remover item
                div.querySelector('.remover-item').addEventListener('click', function() {
                    div.remove();
                    itensSelecionados.delete(itemId);
                    btn.classList.remove('item-selected');
                });
            }
        });
    });
});
</script>
@stop
