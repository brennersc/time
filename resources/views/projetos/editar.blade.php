@extends('layouts.app')

@section('content')
                <div class="card">
                    <div class="card-header">
                        <h3>Editar Projeto - {{ $projeto->id }}</h3>
                    </div>

                    <div class="card-body">

                        <form action="/projeto/{{ $projeto->id }}" method="POST" enctype="multipart/form-data">
                            @method('PATCH')
                            @csrf
                            <div class="form-group">
                                <label for="nome">Nome do Projeto</label>
                                <input type="text" name="nome"
                                    class="form-control {{ $errors->has('nome') ? 'is-invalid' : '' }}"
                                    value="{{ $projeto->nome }}">
                                @if ($errors->has('nome'))
                                    <div class="invalid-feedback">{{ $errors->first('nome') }}</div>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="descricao">Descrição</label>
                                <textarea class="form-control {{ $errors->has('descricao') ? 'is-invalid' : '' }}"
                                    id="descricao" name="descricao" rows="3"> {{ $projeto->descricao }}</textarea>
                                @if ($errors->has('descricao'))
                                    <div class="invalid-feedback">{{ $errors->first('descricao') }}</div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="form-group col-4">
                                    <label for="data_inicio">Data de Início</label>
                                    <input type="date"
                                        class="form-control {{ $errors->has('data_inicio') ? 'is-invalid' : '' }}"
                                        id="data_inicio" name="data_inicio"
                                        value="{{ date('Y-m-d', strtotime($projeto->data_inicio)) }}">
                                    @if ($errors->has('data_inicio'))
                                        <div class="invalid-feedback">{{ $errors->first('data_inicio') }}</div>
                                    @endif

                                </div>
                                <div class="form-group col-4">
                                    <label for="data_fim">Data de Término</label>
                                    <input type="date"
                                        class="form-control {{ $errors->has('data_fim') ? 'is-invalid' : '' }}"
                                        id="data_fim" name="data_fim" @if ($projeto->data_fim == '2222-12-12 00:00:00')
                                    value=""
                                @else
                                    value="{{ date('Y-m-d', strtotime($projeto->data_fim)) }}"
                                    @endif>
                                    @if ($errors->has('data_fim'))
                                        <div class="invalid-feedback">{{ $errors->first('data_fim') }}</div>
                                    @endif

                                </div>
                                <div class="form-group col-4">
                                    <label for="centrodecusto">Centro de Custo</label>
                                    <input type="number" name="centrodecusto"
                                        class="form-control {{ $errors->has('centrodecusto') ? 'is-invalid' : '' }}"
                                        value="{{ $projeto->centrodecusto }}" id="centrodecusto" min="0">
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
