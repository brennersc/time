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
                        <a href="{{ $usuario->id }}/edit/" class="btn btn-sm btn-outline-success ">Editar</a>
                        <form action="{{ $usuario->id }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger ">Apagar</button>
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
                @foreach ($usuario->lancamento as $itens)

                    {{-- <div class="col-sm-6 mt-3">
                        <div class="card" style="width: 18rem;">
                            <div class="card-body">
                                <h5 class="card-title">Colaborador</h5>
                                <h6 class="card-subtitle mb-2 text-muted">{{ $itens->usuario->nome }}</h6>
                                <p class="card-text">Horas trabalhadas - {{ $itens->quantidadehoras }}</p>
                                <p class="card-text">Email - {{ $itens->usuario->email }}</p>
                                <a href="/usuario/{{ $itens->usuario->id }}" class="card-link">Mais detalhes</a>
                            </div>
                        </div>
                    </div> --}}

                @endforeach
            </div>
        </div>

        <div class="card-footer">
            <a type="button" href="/usuario" class="btn btn-secondary btn-sm">Voltar</a>
        </div>
    </div>
@endsection
