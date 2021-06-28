<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Lancamento;
use App\User;
use App\Projeto;
use App\Relatorio;
use App\Periodo;
use App\ProjetoUsuario;
use App\Mes;
use \Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Auth;

class RelatorioExport implements FromView
{
    private $proj;
    private $checkbox;

    public function __construct($proj, $checkbox)
    {
        $this->projeto  = $proj;
        $this->checkbox = $checkbox;
    }

    public function view(): View
    {
        try {
            $relatorios = Mes::select('usuarios.nome', 'ano', 'janeiro', 'fevereiro', 'marco', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro', 'projetos.nome as proj')
                ->leftjoin('usuarios', 'mes.usuarios_id', '=', 'usuarios.id')
                ->leftjoin('projetos', 'mes.projetos_id', '=', 'projetos.id')
                ->where('projetos.id', '=', $this->projeto)
                ->where('usuarios.id', '=', Auth::user()->id)->get();

            if ($this->checkbox == 1) {
                $relatorios = Mes::select('usuarios.nome', 'ano', 'janeiro', 'fevereiro', 'marco', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro',  'projetos.nome as proj')
                ->leftjoin('usuarios', 'mes.usuarios_id', '=', 'usuarios.id')
                ->leftjoin('projetos', 'mes.projetos_id', '=', 'projetos.id')
                ->where('projetos.id', '=', $this->projeto)->get();
            }

            return view('exportar.relatorio', ['relatorios' => $relatorios]);
        } catch (\Exception $e) {
            $json = [
                'success' => false,
                'error' => [
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ],
            ];
            return response()->json($json, 400);
        }
    }
}
