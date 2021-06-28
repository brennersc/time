@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-9 col-sm-12">
                    <h3>Usuário - {{ $usuario->nome }} </h3>
                </div>
                <div class="col-md-3 col-sm-12">
                    <div class="btn-group float-right" role="group" aria-label="Basic example">
                        <a href="{{ $usuario->id }}/edit/" class="btn btn-sm btn-outline-primary "><i class="far fa-edit"></i> Editar</a>
                        <form action="{{ $usuario->id }}" method="POST">
                            @csrf @method('DELETE')
                            @if ($usuario->status == true)
                                <button type="submit" class="btn btn-sm btn-outline-danger "><i class="far fa-trash-alt"></i> Desativar</button>
                            @else
                                <button type="submit" class="btn btn-sm btn-outline-success "><i class="fas fa-check-circle"></i> Ativar</button>
                            @endif

                        </form>

                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 col-12">
                    <h5><b>Login</b> - {{ $usuario->usuario }}</h5>
                </div>
                <div class="col-md-6 col-12">
                    <h5><b>Email</b> - {{ $usuario->email }}</h5>
                </div>
                <div class="col-md-6 col-12">
                    <h5><b>Matrícula</b> - {{ $usuario->matricula }}</h5>
                </div>
                <div class="col-md-6 col-12">
                    <h5><b>Filial</b> - {{ $usuario->filial }}</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-12">
                    <h5> <b>Projetos - </b></h5>
                </div>
                <div class="col-md-9 col-12">
                    @foreach ($projetos as $projeto)
                        <h6 class="ml-3"><b>{{ $projeto->nome }}</b> - {{ $projeto->descricao }}</h5>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card-footer">
            <a type="button" href="JavaScript: window.history.back();" class="btn btn-secondary btn-sm">Voltar</a>
        </div>
    </div>
@endsection
