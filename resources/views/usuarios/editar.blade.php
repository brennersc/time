@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Editar Usuário</h3>
        </div>

        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-danger" role="alert">
                    {{ session('status') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <form action="/usuario/{{ $usuario->id }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="form-group col-3">
                        <label for="usuario">Login</label>
                        <input type="text" name="usuario"
                            class="form-control {{ $errors->has('usuario') ? 'is-invalid' : '' }}"
                            value="{{ $usuario->usuario }}" id="usuario">
                        @if ($errors->has('usuario'))
                            <div class="invalid-feedback">{{ $errors->first('usuario') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-4">
                        <label for="nome">Nome</label>
                        <input type="nome" name="nome" class="form-control {{ $errors->has('nome') ? 'is-invalid' : '' }}"
                            value="{{ $usuario->nome }}" id="nome">
                        @if ($errors->has('nome'))
                            <div class="invalid-feedback">{{ $errors->first('nome') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-5">
                        <label for="email">Email</label>
                        <input type="email" name="email"
                            class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                            value="{{ $usuario->email }}" id="email">
                        @if ($errors->has('email'))
                            <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-4">
                        <label for="matricula">Matrícula</label>
                        <input type="number" class="form-control {{ $errors->has('matricula') ? 'is-invalid' : '' }}"
                            value="{{ $usuario->matricula }}" id="matricula" name="matricula" min="0">
                        @if ($errors->has('matricula'))
                            <div class="invalid-feedback">{{ $errors->first('matricula') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-3">
                        <label for="filial">Filial</label>
                        <input type="number" class="form-control {{ $errors->has('filial') ? 'is-invalid' : '' }}"
                            value="{{ $usuario->filial }}" id="filial" name="filial" min="0">
                        @if ($errors->has('filial'))
                            <div class="invalid-feedback">{{ $errors->first('filial') }}</div>
                        @endif
                    </div>
                    <div class="form-check col-5">
                        <div style="margin-left: 10px; margin-top: 40px">
                            <input type="checkbox" class="form-check-input" name="checkbox" id="checkbox" value="true"
                                {{ $usuario->administrador == true ? 'checked' : '' }}>
                            <label class="form-check-label" for="admin">Administrador?</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-check col-5">
                        <label for="">Projetos</label>
                        <div class="ml-3">
                            @if ($semProjetos == 1)
                                @foreach ($pjIguais as $key => $PI)
                                    @php $Id = $IdIguais[$key] @endphp
                                    <input type="checkbox" class="form-check-input" name="projetos[]" 
                                        value="{{ $Id }}" checked>
                                    <label class="form-check-label" >{{ $PI }}</label>
                                <br>
                                @endforeach

                                @foreach ($IdDife as $key => $PD)
                                    @php $IdD = $pjDife[$key] @endphp
                                    <input type="checkbox" class="form-check-input" name="projetos[]"
                                        value="{{ $PD }}">
                                    <label class="form-check-label" >{{ $IdD }}</label><br>
                                @endforeach
                            @else
                                @foreach ($projetos as $projeto)
                                    <input type="checkbox" class="form-check-input" name="projetos[]"
                                        value="{{ $projeto->id }}">
                                    <label class="form-check-label">{{ $projeto->nome }}</label><br>
                                @endforeach
                            @endif
                        </div>
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
