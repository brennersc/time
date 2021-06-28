@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Novo Usuário</h3>
        </div>

        <div class="card-body">

            <form action="/usuario" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="form-group col-6">
                        <label for="usuario">Login</label>
                        <input type="text" name="usuario"
                            class="form-control {{ $errors->has('usuario') ? 'is-invalid' : '' }}"
                            value="{{ old('usuario') }}" id="usuario">
                        @if ($errors->has('usuario'))
                            <div class="invalid-feedback">{{ $errors->first('usuario') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-6">
                        <label for="nome">Nome</label>
                        <input type="nome" name="nome" class="form-control {{ $errors->has('nome') ? 'is-invalid' : '' }}"
                            value="{{ old('nome') }}" id="nome">
                        @if ($errors->has('nome'))
                            <div class="invalid-feedback">{{ $errors->first('nome') }}</div>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-6">
                        <label for="email">Email</label>
                        <input type="email" name="email"
                            class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}"
                            id="email">
                        @if ($errors->has('email'))
                            <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-3">
                        <label for="matricula">Matrícula</label>
                        <input type="text" class="form-control {{ $errors->has('matricula') ? 'is-invalid' : '' }}"
                            value="{{ old('matricula') }}" id="matricula" name="matricula">
                        @if ($errors->has('matricula'))
                            <div class="invalid-feedback">{{ $errors->first('matricula') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-3">
                        <label for="filial">Filial</label>
                        <input type="text" class="form-control {{ $errors->has('filial') ? 'is-invalid' : '' }}"
                            value="{{ old('filial') }}" id="filial" name="filial">
                        @if ($errors->has('filial'))
                            <div class="invalid-feedback">{{ $errors->first('filial') }}</div>
                        @endif
                    </div>
                </div>
                <button type="submit" class="btn btn-success btn-sm float-right">Salvar</button>
            </form>

        </div>
        <div class="card-footer">
            <a type="button" href="/usuario" class="btn btn-secondary btn-sm">Voltar</a>
        </div>
    </div>
@endsection
