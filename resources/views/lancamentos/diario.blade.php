@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Lançamento de Horas Diarias</h3>
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
            @if (session('mensagem'))
                <div class="alert alert-success" role="alert">
                    {{ session('mensagem') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form action="/lancamento" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" value="dia" name="tipo">
                <input type="hidden" value="000:00" name="quantidadehorasmes">
                <div class="row">
                    <div class="form-group col-12">
                        <label for="usuarios">Login</label>
                        <input type="text" name="usuarios"
                            class="form-control {{ $errors->has('usuarios') ? 'is-invalid' : '' }}"
                            value="{{ Auth::user()->nome }}" id="usuario" name="usuarios" readonly>
                        @if ($errors->has('usuarios'))
                            <div class="invalid-feedback">{{ $errors->first('usuarios') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-12">
                        <label for="projetos">Projetos</label>
                        @if (count($projetos) > 0)
                            <select class="form-control {{ $errors->has('projetos') ? 'is-invalid' : '' }}" id="projetos"
                                name="projetos">
                                @foreach ($projetos as $projeto)
                                    <option value="{{ $projeto->id }}">{{ $projeto->nome }}</option>
                                @endforeach
                            </select>
                        @else
                            <input type="text" name="projetos"
                                class="form-control {{ $errors->has('projetos') ? 'is-invalid' : '' }}" readonly
                                placeholder="Projetos não atribuido para esse Usuário">
                        @endif
                        @if ($errors->has('projetos'))
                            <div class="invalid-feedback">{{ $errors->first('projetos') }}</div>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-12">
                        <label for="periodo">Periodo</label>
                        <select class="form-control {{ $errors->has('periodo') ? 'is-invalid' : '' }}" id="periodo"
                            name="periodos">
                            @foreach ($periodos as $periodo)
                                <option value="{{ $periodo->id }}" {{ $periodo->status == false ? 'disabled' : '' }}
                                    {{ $periodo->mes == $mesAtual ? 'selected' : '' }}>{{ $periodo->mes }} -
                                    {{ $periodo->ano }}
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('periodos'))
                            <div class="invalid-feedback">{{ $errors->first('periodos') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-2">
                        <label for="quantidadehoras">Horas</label>
                        <input type="number" class="form-control {{ $errors->has('quantidadehoras') ? 'is-invalid' : '' }}"
                            value="{{ old('quantidadehoras') }}" id="quantidadehoras" name="quantidadehoras">
                        @if ($errors->has('quantidadehoras'))
                            <div class="invalid-feedback">{{ $errors->first('quantidadehoras') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-2">
                        <label for="quantidadehoras">Data</label>
                        <input type="date" class="form-control {{ $errors->has('data') ? 'is-invalid' : '' }}"
                            value="{{ old('data') }}" id="data" name="data">
                        @if ($errors->has('data'))
                            <div class="invalid-feedback">{{ $errors->first('data') }}</div>
                        @endif
                    </div>
                </div>
                <button type="submit" class="btn btn-success btn-sm float-right">Salvar</button>
            </form>

        </div>
        <div class="card-footer">
            <a type="button" href="/home" class="btn btn-secondary btn-sm">Voltar</a>
        </div>
    </div>
@endsection
