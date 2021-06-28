@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header"><i class="fas fa-desktop"></i> Dashboard</div>

        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if (isset($mensagem))
                <div class="alert alert-danger" role="alert">
                    {{ $mensagem }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            {{-- inicio --}}
            <div class="row">

                {{-- Admin --}}
                @if (Auth::user()->administrador == true)
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-project-diagram"></i> Projetos</h5>
                                <p class="card-text">Liste, Crie e Edite um projeto para sua equipe.</p>
                                <a href="/projeto" class="btn btn-primary">Projetos</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-users"></i> Usuário</h5>
                                <p class="card-text">Cadastre e Edite um usuário do sistema.</p>
                                <a href="/usuario" class="btn btn-primary">Usuários</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 mt-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-clipboard-list"></i> Períodos</h5>
                                <p class="card-text">Crie e Libere Períodos de Trabalho.</p>
                                <a href="/periodo" class="btn btn-primary">Períodos</a>
                            </div>
                        </div>
                    </div>


                @endif
                {{-- ADMIN fim --}}

                <div class="col-sm-6  mt-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-calendar-alt"></i> Lançamento Mensal</h5>
                            <p class="card-text">Lance suas horas trabalhdas no mês.</p>
                            <a href="/lancamento"
                                class="btn btn-primary  {{ isset($mensagemMes) ? 'is-invalid' : '' }}">Mês</a>
                            @if (isset($mensagemMes))
                                <div class="invalid-feedback mt-2">{{ $mensagemMes }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 mt-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-calendar-check"></i> Relatório</h5>
                            <p class="card-text">Gerar relatório de lançamentos por projetos.</p>
                            <a href="/relatorio" class="btn btn-primary">Visualizar</a>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 mt-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-user"></i> Perfil </h5>
                            <p class="card-text">Visualize o perfil do seu usuário.</p>
                            <a href="/perfil" class="btn btn-primary">Visualizar</a>
                        </div>
                    </div>
                </div>

            </div>
            {{-- fim --}}
        </div>
    </div>
@endsection
