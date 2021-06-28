@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-9 col-sm-12">
                    <h3>Detalhes Projeto - {{ $projeto->nome }} / {{ $projeto->id }} </h3>
                </div>
                <div class="col-md-3 col-sm-12">
                    <div class="btn-group float-right" role="group" aria-label="Basic example">
                        <a href="{{ $projeto->id }}/edit/" class="btn btn-sm btn-outline-primary "><i class="far fa-edit"></i> Editar</a>
                        <form action="{{ $projeto->id }}" method="POST">
                            @csrf @method('DELETE')
                            @if ($projeto->status == true)
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
                    <h5><b>Data de Início</b> - {{ date('d/m/Y', strtotime($projeto->data_inicio)) }}</h5>
                </div>
                <div class="col-md-6 col-12">
                    <h5><b>Data de Término</b> - {{ date('d/m/Y', strtotime($projeto->data_fim)) }}</h5>
                </div>
                <div class="col-md-6 col-12">
                    <h5><b>Centro de Custo</b> - {{ $projeto->centrodecusto }}</h5>
                </div>
                <div class="col-md-6 col-12">
                    <h5><b>Total de Horas</b> - {{ $contador->horas }}</h5>
                </div>
                <div class="col-md-6 col-12">
                    <h5><b>Total de Colaboradores</b> - {{ $contador->usuarios }}</h5>
                </div>
                <div class="col-md-12 col-12">
                    <h5><b>Descrição</b> - {{ $projeto->descricao }}</h5>
                </div>
            </div>
            <div class="row">
                @foreach ($colaboradores as $itens)

                    <div class="col-sm-4 mt-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Colaborador</h5>
                                <h5 class="card-subtitle mb-2 text-muted">{{ $itens->nome }}</h5>
                                <p class="card-text">Horas trabalhadas - {{ $itens->horas }}</p>
                                <p class="card-text">Email - {{ $itens->email }}</p>
                                <a href="/usuario/{{ $itens->id }}" class="card-link"><i class="fas fa-plus"></i> Mais detalhes</a>
                            </div>
                        </div>
                    </div>

                @endforeach
            </div>
        </div>

        <div class="card-footer">
            <a type="button" href="/projeto" class="btn btn-secondary btn-sm">Voltar</a>
        </div>
    </div>
@endsection
