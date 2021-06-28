@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-9 col-sm-12">
                    <h3><i class="fas fa-clipboard-list"></i> Períodos</h3>
                </div>
                <div class="col-md-3 col-sm-12">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal"
                        data-target="#exampleModal"><i class="fas fa-plus"></i> 
                        Novo Período
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
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
            <table class="table table-hover table-sm">
                <thead>
                    <tr>
                        <th scope="col">Período</th>
                        <th scope="col">Mês</th>
                        <th scope="col">Ano</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($periodos as $periodo)
                        <tr>
                            <td>{{ $periodo->periodo }}</td>
                            <td>{{ $periodo->mes }}</td>
                            <td>{{ $periodo->ano }}</td>
                            @if ($periodo->status == true)
                                <td>
                                    <button type="button" id="{{ $periodo->id }}"
                                        class="button btn btn-success btn-sm">Ativo</button>
                                </td>
                            @else
                                <td>
                                    <a type="button" id="{{ $periodo->id }}"
                                        class="button btn btn-danger btn-sm">Desativado</a>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-4">
                    <a type="button" href="/home" class="btn btn-secondary btn-sm">Voltar</a>
                </div>
                <div class="col-8 float-center">
                    {{ $periodos->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Crie um Novo Período</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/periodo" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <input type="number" class="form-control ano {{ $errors->has('ano') ? 'is-invalid' : '' }}"
                                    id="ano" name="ano" placeholder="Ano" max="9999" min="2020" maxlength="4" minlength="4"
                                    value="{{ old('ano') }}">
                                @if ($errors->has('ano'))
                                    <div class="invalid-feedback">{{ $errors->first('ano') }}</div>
                                @endif
                            </div>
                            <div class="col-6 float-right">
                                <button type="submit" class="btn btn-success btn-mb">Salvar</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>




    <script type="text/javaScript">

        $(".button").click(function() {
                        var id = $(this).attr("id");
                        var acao = $(this).text();
                        console.log(id);
                        // se o usuario estiver ativo, desative ele, você precisa adicionar um ajax para enviar a acao para o php, pode ser um update where cliente = nomeUsuario.
                        if (acao === "Ativo") {
                            $(this).text("Desativado").removeClass("btn-success").addClass("btn-danger");
                            $.ajax({
                                type: "get",
                                url: "/periodo/getByPeriodo/",
                                dataType: "json",
                                data: {
                                    id: id,
                                    status: false
                                },
                                success: function(data) {
                                    if (data.sucesso == 0) {
                                        //alert(data.mensagem);
                                        console.log(data.id);
                                        console.log(data.status);
                                        console.log(data.mensagem);
                                    }
                                },
                            });
                        } else {
                            $(this).text("Ativo").removeClass("btn-danger").addClass("btn-success");
                            $.ajax({
                                type: "get",
                                url: "/periodo/getByPeriodo/",
                                dataType: "json",
                                data: {
                                    id: id,
                                    status: true
                                },
                                success: function(data) {
                                    if (data.sucesso == 1) {
                                        //alert(data.mensagem);
                                        console.log(data.id);
                                        console.log(data.status);
                                        console.log(data.mensagem);
                                    }
                                },
                            });
                        }
                    });

                </script>
@endsection
