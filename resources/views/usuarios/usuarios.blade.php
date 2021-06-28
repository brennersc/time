@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-9 col-sm-12">
                    <h3><i class="fas fa-users"></i> Usuário</h3>
                </div>
                <div class="col-md-3 col-sm-12">
                    <a type="button" href="/usuario/create" class="btn btn-primary btn-sm float-right"><i class="fas fa-plus"></i> Novo
                        Usuário</a>
                </div>
            </div>
        </div>

        <div class="card-body">

            <table class="table table-hover table-sm">
                <thead>
                    <tr>
                        <th scope="col">Usuário</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Email</th>
                        <th scope="col">Matricula</th>
                        <th scope="col">Filia</th>
                        <th scope="col">Status</th>
                        <th scope="col">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($usuarios as $usuario)
                        <tr class="{{ $usuario->status == false ? 'table-secondary' : '' }}">
                            <td>{{ $usuario->usuario }}</td>
                            <td>{{ $usuario->nome }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td>{{ $usuario->matricula }}</td>
                            <td>{{ $usuario->filial }}</td>
                            @if ($usuario->status == true)
                                <td>Ativo</td>
                            @else
                                <td>Desativado</td>
                            @endif
                            <td>
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a type="button" href="/usuario/{{ $usuario->id }}/edit"
                                        class="btn btn-primary btn-sm"><i class="far fa-edit"></i> Editar</a>
                                    <a type="button" href="/usuario/{{ $usuario->id }}"
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
