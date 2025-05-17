@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1><i class="fas fa-fx fa-user"></i> Edicao dos dados Item do Cardápio</h1>
@stop

@section('content')

    <div class="panel panel-default">
        <div class="panel-heading">
            <i class="fas fa-fx fa-user"></i> Tela de alteraçao de dados
        </div>

       <div class="panel-body">
          <form method="post" action="{{ route('item.update', $item->id) }}" enctype="multipart/form-data">
          {{ csrf_field() }}
          <input type="hidden" name="_method" value="PUT">

          <div class="form-group">
               <label for="titulo_prato">Nome do Item/Prato <span class="text-red">*</span></label>
               <input type="text" name="titulo_prato" id="titulo_prato" class="form-control" value="{{ $item->titulo_prato }}">
          </div>

          <div class="form-group">
               <label for="desc_prato">Descrição do Prato <span class="text-red">*</span></label>
               <input type="text" name="desc_prato" id="creci" class="form-control" value="{{ $item->desc_prato }}">
          </div>

          <div class="form-group">
               <label for="preco">Preço</label>
               <input type="text" name="preco" id="preco" class="form-control" value="{{ $item->preco }}">
          </div>
          <a href="{{ route('item.index') }}" class="btn btn-default">
               <i class="fas fa-reply"></i> Voltar
          </a>

          <button type="submit" class="btn btn-success">
               <i class="fas fa-save"></i> Gravar
          </button>

          </form>
       </div>
    </div>
@stop

@section('css')
@stop

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const precoInput = document.getElementById('preco');
    
    precoInput.addEventListener('input', function(e) {
        // Remove qualquer caractere que não seja número ou ponto
        let value = e.target.value.replace(/[^\d.]/g, '');
        
        // Garante que só existe um ponto
        const parts = value.split('.');
        if (parts.length > 2) {
            value = parts[0] + '.' + parts.slice(1).join('');
        }
        
        // Limita a 2 casas decimais
        if (parts.length > 1) {
            value = parts[0] + '.' + parts[1].slice(0, 2);
        }
        
        e.target.value = value;
    });
});
</script>
@stop
