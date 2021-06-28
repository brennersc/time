@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Novo Projeto</h3>
        </div>

        <div class="card-body">

            <form action="/projeto" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="nome">Nome do Projeto</label>
                    <input type="text" name="nome" class="form-control {{ $errors->has('nome') ? 'is-invalid' : '' }}"
                        value="{{ old('nome') }}">
                    @if ($errors->has('nome'))
                        <div class="invalid-feedback">{{ $errors->first('nome') }}</div>
                    @endif
                </div>
                <div class="form-group">
                    <label for="descricao">Descrição</label>
                    <textarea class="form-control {{ $errors->has('descricao') ? 'is-invalid' : '' }}" id="descricao"
                        name="descricao" rows="3">{{ old('descricao') }}</textarea>
                    @if ($errors->has('descricao'))
                        <div class="invalid-feedback">{{ $errors->first('descricao') }}</div>
                    @endif
                </div>
                <div class="row">
                    <div class="form-group col-3">
                        <label for="data_inicio">Data de Início</label>
                        <input type="date" class="form-control {{ $errors->has('data_inicio') ? 'is-invalid' : '' }}"
                            value="{{ old('data_inicio') }}" id="data_inicio" name="data_inicio">
                        @if ($errors->has('data_inicio'))
                            <div class="invalid-feedback">{{ $errors->first('data_inicio') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-3">
                        <label for="data_fim">Data de Término</label>
                        <input type="date" class="form-control" value="{{ old('data_fim') }}" id="data_fim" name="data_fim">
                    </div>
                    <div class="form-group col-6">
                        <label for="centrodecusto">Centro de Custo</label>
                        <input type="number" name="centrodecusto"
                            class="form-control {{ $errors->has('centrodecusto') ? 'is-invalid' : '' }}"
                            value="{{ old('centrodecusto') }}" id="centrodecusto" min="0">
                        @if ($errors->has('centrodecusto'))
                            <div class="invalid-feedback">{{ $errors->first('centrodecusto') }}</div>
                        @endif
                    </div>
                </div>
                <button type="submit" class="btn btn-success btn-sm float-right">Salvar</button>
            </form>

        </div>
        <div class="card-footer">
            <a type="button" href="/projeto" class="btn btn-secondary btn-sm">Voltar</a>
        </div>
    </div>
@endsection
