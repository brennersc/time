@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Equipes</h3>
        </div>

        <div class="card-body">
            <div class="row">
                @foreach ($equipes as $equipe)
                    <div class="col-sm-6 mt-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Projeto - {{ $equipe->projeto->codigo }}</h5>
                                <h6 class="card-subtitle mb-2 text-muted">{{ $equipe->projeto->descricao }}</h6>
                                <p class="card-text">Colaborador - {{ $equipe->usuario->nome }}</p>
                                <p class="card-text">Email - {{ $equipe->usuario->email }}</p>
                                <a href="/usuario/{{ $equipe->usuario->id }}" class="card-link">Mais detalhes
                                    usuário</a>
                                <a href="/usuario/{{ $equipe->usuario->id }}" class="card-link">Mais detalhes
                                    projeto</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
        <div class="card-footer">
            <a type="button" href="/home" class="btn btn-secondary btn-sm">Voltar</a>
        </div>
    </div>
@endsection
