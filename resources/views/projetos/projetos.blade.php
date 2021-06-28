@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-9 col-sm-12">
                    <h3><i class="fas fa-project-diagram"></i> Projetos</h3>
                </div>
                <div class="col-md-3 col-sm-12">
                    <a type="button" href="/projeto/create" class="btn btn-primary btn-sm float-right"><i class="fas fa-plus"></i> Novo
                        Projeto</a>
                </div>
            </div>
        </div>

        <div class="card-body">

            <table class="table table-hover table-sm">
                <thead>
                    <tr>
                        <th scope="col">Nome</th>
                        <th scope="col">Descrição</th>
                        <th scope="col">Data de Inicio</th>
                        <th scope="col">Data Final</th>
                        <th scope="col">Status</th>
                        <th scope="col">Centro de Custo</th>
                        <th scope="col">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($projetos as $projeto)
                        <tr class="{{ $projeto->status == false ? 'table-secondary' : '' }}">
                            <td>{{ $projeto->nome }}</td>
                            <td>{{ substr($projeto->descricao, 0, 10) }}</td>
                            <td>{{ date('d/m/Y', strtotime($projeto->data_inicio)) }}</td>
                            @if ($projeto->data_fim == '2222-12-12 00:00:00')
                                <td>Sem data definida</td>
                            @else
                                <td>{{ date('d/m/Y', strtotime($projeto->data_fim)) }} </td>
                            @endif

                            @if ($projeto->status == true)
                                <td>Ativo</td>
                            @else
                                <td>Desativado</td>
                            @endif

                            <td>{{ $projeto->centrodecusto }}</td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a type="button" href="/projeto/{{ $projeto->id }}/edit"
                                        class="btn btn-primary btn-sm"><i class="far fa-edit"></i> Editar</a>
                                    <a type="button" href="/projeto/{{ $projeto->id }}"
                                        class="btn btn-primary btn-sm"><i class="fas fa-info-circle"></i> Detalhes</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
        <div class="card-footer">
            <a type="button" href="/home" class="btn btn-secondary btn-sm">Voltar</a>
        </div>
    </div>
@endsection
