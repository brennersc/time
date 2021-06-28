@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-calendar-check"></i> Relatorio de Lançamento</h3>
        </div>
        <div class="card-body">
            <form action="/relatorio" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-row">

                    <div class="col-3">
                        <label class="" for="projeto">Projeto</label>
                        <select class="custom-select" id="projeto" name="projeto">
                            <option selected disabled>Selecione...</option>
                            @foreach ($projetos as $projeto)
                                <option value="{{ $projeto->id }}" 
                                @if(isset($proj)) {{ $projeto->id == $proj ? 'selected' : '' }} @endif >
                                    {{ $projeto->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @if (Auth::user()->administrador == true)
                        <div class="col-2">
                            <div style="margin-left: 30px; margin-top: 40px">
                                <input type="checkbox" class="form-check-input" name="checkbox" id="checkbox" value="true"
                                @if(isset($checkbox)) {{ $checkbox == 1 ? 'checked' : '' }} @endif>
                                <label class="form-check-label" for="admin">Lançamento Geral (Todos Usuário)?</label>
                            </div>
                        </div>
                    @endif
                    <div class="col-2">
                        <button type="submit" class="btn btn-primary float-right" style="margin-top: 30px"><i class="fas fa-search"></i> Buscar</button>
                    </div>
                </div>
            </form>

            @if (isset($relatorios))
                @if (count($relatorios) > 0)
                    <br>
                    <hr>
                    <a href="{{ route('relatorioExportar', ['proj' => $proj, 'checkbox' => $checkbox]) }}"
                        class="btn btn-md btn-outline-success float-right mb-3"><i class="fas fa-file-export"></i>
                        Exportar</a>
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th scope="col">Nome </th>
                                <th scope="col">Ano</th>
                                <th scope="col">Janeiro</th>
                                <th scope="col">Fevereiro</th>
                                <th scope="col">Março</th>
                                <th scope="col">Abril</th>
                                <th scope="col">Maio</th>
                                <th scope="col">Junho</th>
                                <th scope="col">Julho</th>
                                <th scope="col">Agosto</th>
                                <th scope="col">Setembro</th>
                                <th scope="col">Outubro</th>
                                <th scope="col">Novembro</th>
                                <th scope="col">Dezembro</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($relatorios as $relatorio)
                                <tr>
                                    <td>{{ $relatorio->nome }}</td>
                                    <td>{{ $relatorio->ano }}</td>
                                    <th>{{ $relatorio->janeiro }}</th>
                                    <th> {{ $relatorio->fevereiro }}</th>
                                    <th>{{ $relatorio->marco }}</th>
                                    <th>{{ $relatorio->abril }}</th>
                                    <th> {{ $relatorio->maio }}</th>
                                    <th>{{ $relatorio->junho }}</th>
                                    <th> {{ $relatorio->julho }}</th>
                                    <th>{{ $relatorio->agosto }}</th>
                                    <th>{{ $relatorio->setembro }}</th>
                                    <th>{{ $relatorio->outubro }}</th>
                                    <th>{{ $relatorio->novembro }}</th>
                                    <th>{{ $relatorio->dezembro }}</th>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            @endif
        </div>
        <div class="card-footer">
            <a type="button" href="/home" class="btn btn-secondary btn-sm">Voltar</a>
        </div>
    </div>
@endsection
