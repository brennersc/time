@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-calendar-alt"></i> Lançamento de Horas Mensal</h3>
        </div>

        <div class="card-body">
            @if (session('mensagemMes'))
                <div class="alert alert-danger" role="alert">
                    {{ session('mensagemMes') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if (session('status'))
                <div class="alert alert-danger" role="alert">
                    {{ session('status') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if (session('sucesso'))
                <div class="alert alert-success" role="alert">
                    {{ session('sucesso') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <form action="/lancamento/createAno" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="form-group col-12">
                        <label for="periodos">Periodo</label>
                        <select class="form-control {{ $errors->has('ano') ? 'is-invalid' : '' }}" id="ano" name="ano">
                            <option value=" " disabled {{ isset($ano) ? '' : 'selected' }}>
                                Selecione o Periodo...
                            </option>
                            @foreach ($periodosAno as $ano)
                                <option value="{{ $ano->ano }}" {{ $anoSelecionado == $ano->ano ? 'selected' : '' }}>
                                    {{ $ano->ano }}
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('ano'))
                            <div class="invalid-feedback">{{ $errors->first('ano') }}
                            </div>
                        @endif
                    </div>
                </div>
            </form>
            @if ($tabelas == 0)
                <form action="/lancamento" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="ano" value={{ $anoSelecionado }}>
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">PROJETOS</th>
                                @foreach ($periodos as $periodo)
                                    <th scope="col" class="text-center">{{ mb_strtoupper($periodo->mes) }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($projetos) > 0 and $naoAtribuidos == 0)
                                @foreach ($projetos as $projeto)
                                    <tr>
                                        <th scope="row">{{ $projeto->nome }}</th>
                                        <input type="hidden" name="projetos[]" value={{ $projeto->id }}>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="janeiro[]" value="{{ $projeto->janeiro }}" 
                                                {{ $status[0] == true ? '' : 'readonly' }}></th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="fevereiro[]" value="{{ $projeto->fevereiro }}"
                                                {{ $status[1] == true ? '' : 'readonly' }}>
                                        </th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="marco[]" value="{{ $projeto->marco }}"
                                                {{ $status[2] == true ? '' : 'readonly' }}></th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="abril[]" value="{{ $projeto->abril }}"
                                                {{ $status[3] == true ? '' : 'readonly' }}></th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="maio[]" value="{{ $projeto->maio }}"
                                                {{ $status[4] == true ? '' : 'readonly' }}></th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="junho[]" value="{{ $projeto->junho }}"
                                                {{ $status[5] == true ? '' : 'readonly' }}></th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="julho[]" value="{{ $projeto->julho }}"
                                                {{ $status[6] == true ? '' : 'readonly' }}></th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="agosto[]" value="{{ $projeto->agosto }}"
                                                {{ $status[7] == true ? '' : 'readonly' }}></th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="setembro[]" value="{{ $projeto->setembro }}"
                                                {{ $status[8] == true ? '' : 'readonly' }}>
                                        </th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="outubro[]" value="{{ $projeto->outubro }}"
                                                {{ $status[9] == true ? '' : 'readonly' }}></th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="novembro[]" value="{{ $projeto->novembro }}"
                                                {{ $status[10] == true ? '' : 'readonly' }}>
                                        </th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="dezembro[]" value="{{ $projeto->dezembro }}"
                                                {{ $status[11] == true ? '' : 'readonly' }}>
                                        </th>
                                    </tr>
                                @endforeach
                            @endif

                            
                            @if ($sumirLinha != 3)
                                @if ($IdDife != null)
                                    <tr>
                                        <th>
                                            <select class="form-control {{ $errors->has('projeto') ? 'is-invalid' : '' }}"
                                                id="projetos" name="projetos[]">
                                                @if ($IdDife != null)
                                                    @foreach ($IdDife as $key => $PD)
                                                        {{ $IdD = $pjDife[$key] }}
                                                        <option value="{{ $PD }}">{{ $IdD }}</option>
                                                    @endforeach
                                                @else
                                                    @foreach ($projetos as $novoprojeto)
                                                        <option value="{{ $novoprojeto->id }}">{{ $novoprojeto->nome }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="janeiro[]" value="" {{ $status[0] == true ? '' : 'readonly' }}></th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="fevereiro[]" value="" {{ $status[1] == true ? '' : 'readonly' }}></th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="marco[]" value="" {{ $status[2] == true ? '' : 'readonly' }}></th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="abril[]" value="" {{ $status[3] == true ? '' : 'readonly' }}></th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="maio[]" value="" {{ $status[4] == true ? '' : 'readonly' }}></th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="junho[]" value="" {{ $status[5] == true ? '' : 'readonly' }}></th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="julho[]" value="" {{ $status[6] == true ? '' : 'readonly' }}></th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="agosto[]" value="" {{ $status[7] == true ? '' : 'readonly' }}></th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="setembro[]" value="" {{ $status[8] == true ? '' : 'readonly' }}></th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="outubro[]" value="" {{ $status[9] == true ? '' : 'readonly' }}></th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="novembro[]" value="" {{ $status[10] == true ? '' : 'readonly' }}></th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="dezembro[]" value="" {{ $status[11] == true ? '' : 'readonly' }}></th>

                                        @if ($errors->has('projetos'))
                                            <div class="invalid-feedback">{{ $errors->first('projetos') }}</div>
                                        @endif
                                    </tr>
                                @elseif(($naoAhDiferenca == 1) and ( $naoAtribuidos == 1))
                                    <tr>
                                        <th>
                                            <select class="form-control {{ $errors->has('projeto') ? 'is-invalid' : '' }}"
                                                id="projetos" name="projetos[]">
                                                @foreach ($projetos as $novoprojeto)
                                                    <option value="{{ $novoprojeto->id }}">{{ $novoprojeto->nome }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="janeiro[]" value="" {{ $status[0] == true ? '' : 'readonly' }}></th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="fevereiro[]" value="" {{ $status[1] == true ? '' : 'readonly' }}></th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="marco[]" value="" {{ $status[2] == true ? '' : 'readonly' }}></th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="abril[]" value="" {{ $status[3] == true ? '' : 'readonly' }}></th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="maio[]" value="" {{ $status[4] == true ? '' : 'readonly' }}></th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="junho[]" value="" {{ $status[5] == true ? '' : 'readonly' }}></th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="julho[]" value="" {{ $status[6] == true ? '' : 'readonly' }}></th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="agosto[]" value="" {{ $status[7] == true ? '' : 'readonly' }}></th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="setembro[]" value="" {{ $status[8] == true ? '' : 'readonly' }}></th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="outubro[]" value="" {{ $status[9] == true ? '' : 'readonly' }}></th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="novembro[]" value="" {{ $status[10] == true ? '' : 'readonly' }}></th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="dezembro[]" value="" {{ $status[11] == true ? '' : 'readonly' }}></th>

                                        @if ($errors->has('projetos'))
                                            <div class="invalid-feedback">{{ $errors->first('projetos') }}</div>
                                        @endif
                                    </tr>
                                @else
                                    <tr>
                                        <th>
                                            <select class="form-control {{ $errors->has('projeto') ? 'is-invalid' : '' }}"
                                                id="projetos" name="projetos[]">
                                                @foreach ($projetos as $novoprojeto)
                                                    <option value="{{ $novoprojeto->id }}">{{ $novoprojeto->nome }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="janeiro[]" value="" {{ $status[0] == true ? '' : 'readonly' }}></th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="fevereiro[]" value="" {{ $status[1] == true ? '' : 'readonly' }}></th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="marco[]" value="" {{ $status[2] == true ? '' : 'readonly' }}></th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="abril[]" value="" {{ $status[3] == true ? '' : 'readonly' }}></th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="maio[]" value="" {{ $status[4] == true ? '' : 'readonly' }}></th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="junho[]" value="" {{ $status[5] == true ? '' : 'readonly' }}></th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="julho[]" value="" {{ $status[6] == true ? '' : 'readonly' }}></th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="agosto[]" value="" {{ $status[7] == true ? '' : 'readonly' }}></th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="setembro[]" value="" {{ $status[8] == true ? '' : 'readonly' }}></th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="outubro[]" value="" {{ $status[9] == true ? '' : 'readonly' }}></th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="novembro[]" value="" {{ $status[10] == true ? '' : 'readonly' }}></th>
                                        <th scope="row"><input class="form-control horas" type="number" pattern="/^[0-9]$/" maxlength="3" max="240"
                                                name="dezembro[]" value="" {{ $status[11] == true ? '' : 'readonly' }}></th>

                                        @if ($errors->has('projetos'))
                                            <div class="invalid-feedback">{{ $errors->first('projetos') }}</div>
                                        @endif
                                    </tr>
                                @endif
                            @endif
                        </tbody>
                    </table>

                    <button type="submit" class="btn btn-success btn-sm float-right mt-3">Salvar</button>
                </form>
            @endif
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-4">
                    <a type="button" href="/home" class="btn btn-secondary btn-sm">Voltar</a>
                </div>
                <div class="col-8 float-center">
                    {{-- @if ($tabelas == 0)
                        @if (count($projetos) > 0)
                            {{ $projetos->links() }}
                        @endif
                    @endif --}}
                </div>
            </div>
        </div>
    </div>

    <script type="text/javaScript">
        $(document).ready(function($){
            document.getElementById('ano').addEventListener('change', function() {
                this.form.submit();
            });
        });
        </script>

@endsection
