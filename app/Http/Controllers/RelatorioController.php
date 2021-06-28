<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lancamento;
use App\User;
use App\Projeto;
use App\Relatorio;
use App\Periodo;
use App\ProjetoUsuario;
use App\Mes;
use \Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RelatorioExport;

class RelatorioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        try {
            setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
            date_default_timezone_set('America/Sao_Paulo');
        
            $mesAtual = strftime('%B');
            $anoAtual = strftime('%Y');

            $periodos = Periodo::where('ano', $anoAtual)->get();
            if (Auth::user()->administrador != true) {
                $projetos = Projeto::select('projetos.id', 'nome')->join('projetousuario', 'projetos.id', '=', 'projetousuario.projetos_id')->where('projetousuario.usuarios_id', Auth::user()->id)->get();
            } else {
                $projetos = Projeto::all();
            }
            return view('relatorios.relatorio', compact('periodos', 'projetos'));
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

    public function store(Request $request)
    {
        //return  $request;
        try {
            setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
            date_default_timezone_set('America/Sao_Paulo');
    
            $mesAtual = strftime('%B');
            $anoAtual = strftime('%Y');
           
            $periodos = Periodo::where('ano', $anoAtual)->get();
            if (Auth::user()->administrador != true) {
                $projetos = Projeto::select('projetos.id', 'nome')->join('projetousuario', 'projetos.id', '=', 'projetousuario.projetos_id')->where('projetousuario.usuarios_id', Auth::user()->id)->get();
            } else {
                $projetos = Projeto::all();
            }

 
            $relatorios = Mes::select('usuarios.nome', 'ano', 'janeiro', 'fevereiro', 'marco', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro', 'projetos.nome as proj')
                            ->leftjoin('usuarios', 'mes.usuarios_id', '=', 'usuarios.id')
                            ->leftjoin('projetos', 'mes.projetos_id', '=', 'projetos.id')
                            ->where('projetos.id', '=', $request->projeto)
                            ->where('usuarios.id', '=', Auth::user()->id)->get();
            
            if ($request->checkbox == true and Auth::user()->administrador == true) {
                $relatorios = Mes::select('usuarios.nome', 'ano', 'janeiro', 'fevereiro', 'marco', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro', 'projetos.nome as proj')
                ->leftjoin('usuarios', 'mes.usuarios_id', '=', 'usuarios.id')
                ->leftjoin('projetos', 'mes.projetos_id', '=', 'projetos.id')
                ->where('projetos.id', '=', $request->projeto)->get();
            }

            $proj = $request->projeto;

            if ($request->checkbox == true) {
                $checkbox = 1;
            } else {
                $checkbox = 0;
            }

            return view('relatorios.relatorio', compact('periodos', 'projetos', 'relatorios'))->with(['proj' => $proj, 'checkbox' => $checkbox]);
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

    public function export($periodo, $checkbox)
    {
        try {
            return Excel::download(new RelatorioExport($periodo, $checkbox), 'relatorio.xlsx');
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
