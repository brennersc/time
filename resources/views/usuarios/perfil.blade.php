@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-9 col-sm-12">
                    <h3><i class="fas fa-user"></i> Perfil - {{ $usuario->nome }} </h3>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <h5><b>Login</b> - {{ $usuario->usuario }}</h5>
                                </div>
                                <div class="col-12">
                                    <h5><b>Email</b> - {{ $usuario->email }}</h5>
                                </div>
                                <div class="col-12">
                                    <h5><b>Matrícula</b> - {{ $usuario->matricula }}</h5>
                                </div>
                                <div class="col-12">
                                    <h5><b>Filial</b> - {{ $usuario->filial }}</h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <h5> <b>Projetos - </b></h5>
                                </div>
                                <div class="col-12">
                                    @foreach ($projetos as $projeto)
                                        <h6 class="ml-3"><b>{{ $projeto->nome }}</b> - {{ $projeto->descricao }}
                                            </h5>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <h4>Alterar Senha:</h4>
                            <form id="trocarsenha" method="POST">
                                @csrf
                                <div class="form-group row">
                                    <label for="senha" class="col-sm-4 col-form-label">Senha:</label>
                                    <div class="col-sm-8">
                                        <input type="password" id="senha" name="senha" class="form-control form-control-sm"
                                            placeholder="*******">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="confirmasenha" class="col-sm-4 col-form-label">Confirme:</label>
                                    <div class="col-sm-8">
                                        <input type="password" id="confirmasenha" name="confirmasenha"
                                            class="form-control form-control-sm" placeholder="*******">
                                        <div id="erro" class="invalid-feedback">Senhas diferentes!</div>
                                    </div>
                                </div>

                                <button type='submit' class="btn btn-sm btn-success float-right" role="button"><i
                                        class="fas fa-key"></i> Trocar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a type="button" href="/home" class="btn btn-secondary btn-sm">Voltar</a>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $("#trocarsenha").submit(function() {
                console.clear();
                var campo_vazio = false;
                if (($('#senha').val() == '') || ($('#confirmasenha').val() == '')) {
                    $('#senha').addClass("is-invalid");
                    $('#confirmasenha').addClass("is-invalid");
                    return false;
                    campo_vazio = true;
                }
                $.ajax({
                    type: 'post',
                    url: 'perfil/senha',
                    data: {
                        senha: $('#senha').val(),
                        confirmasenha: $('#confirmasenha').val(),
                    },
                    dataType: 'JSON',
                    success: function(data) {
                        if (data.sucesso == 0) {
                            //alert('sucesso');
                            $('#senha').addClass("is-valid").val('');
                            $('#confirmasenha').addClass("is-valid").val('');
                            setTimeout(function() {
                                $('#senha').removeClass("is-valid").val('');
                                $('#confirmasenha').removeClass("is-valid").val('');
                            }, 3000);
                        }
                        if (data.sucesso == 1) {
                            //alert('igual');
                            $('#senha').addClass("is-invalid").val('');
                            $('#confirmasenha').addClass("is-invalid").val('');
                            $('#erro').show();
                            setTimeout(function() {
                                $('#senha').removeClass("is-invalid").val('');
                                $('#confirmasenha').removeClass("is-invalid").val('');
                                $('#erro').hide();
                            }, 3000);
                        }
                    }
                });
                return false;
                console.clear();
            });

            setTimeout(function() {
                $('#senha').removeClass("is-invalid");
                $('#confirmasenha').removeClass("is-invalid");
            }, 3000);

        });

    </script>
@endsection
