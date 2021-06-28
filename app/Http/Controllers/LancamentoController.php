<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lancamento;
use App\User;
use App\Projeto;
use App\Periodo;
use App\ProjetoUsuario;
use App\Mes;
use \Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LancamentoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$usuarioLogado = auth()->user();

        try {
            $periodosAno = Periodo::SelectRaw('DISTINCT ano')->orderBy('ano')->get();
            $tabelas = 1;
            $anoSelecionado = false;
            return view('lancamentos.mensal', compact('periodosAno', 'tabelas', 'anoSelecionado'));
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    // CADASTRAR LANÇAMENTO MENSAL
    public function create(Request $request)
    {
        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');
    
        $mesAtual = strftime('%B');
        $anoAtual = strftime('%Y');

        $naoAtribuidos  = 0;
        $tabelas        = 0;
        $anoSelecionado = $request->ano;
        $sumirLinha     = 0;
        $naoAhDiferenca = 0;
        $todosProjetos  = 8;

        //PEGAR TODOS PROJETOS QUE JA TEM HORAS ATRIBUIDAS

        $projetos = DB::select('SELECT DISTINCT projetos.nome, projetos.id, janeiro, fevereiro, marco, abril, maio, junho,  julho, agosto, setembro, outubro, novembro, dezembro FROM mes
                                join projetousuario on mes.usuarios_id = projetousuario.usuarios_id
                                join projetos on projetos.id = mes.projetos_id
                                WHERE (ano = '.$request->ano.'
                                OR ano is NULL)
                                and projetos.data_inicio <= Now()
                                and projetos.data_fim >= Now() 
                                and projetousuario.usuarios_id = '. Auth::user()->id.' ');

        //return $projetos;
        $contrarProjetos = count($projetos);
        
        //caso não haja nenhum mes salvo pegar meses atribuidos
        if (count($projetos) == 0) {
            $projetos = Projeto::select('projetos.id', 'nome')
                    ->join('projetousuario', 'projetos.id', '=', 'projetousuario.projetos_id')
                    ->where('projetousuario.usuarios_id', Auth::user()->id)
                    ->where('data_inicio', '<=', date('Y-m-d'))
                    ->where('data_fim', '>=', date('Y-m-d'))
                    ->where('status', true)
                    ->paginate(10);
            $contrarProjetos = 0;
        }
        
        if (count($projetos) == 0) {
            $projetos = Projeto::select('projetos.id', 'nome')->where('data_inicio', '<=', date('Y-m-d'))->where('data_fim', '>=', date('Y-m-d'))->where('status', true)->paginate(10);
            $naoAtribuidos = 1;
            $contrarProjetos = 0;
            $sumirLinha = 5;
        }

        //pegar status dos meses
        $status = DB::select('SELECT GROUP_CONCAT(periodos.status ORDER BY periodos.periodo ASC SEPARATOR ";") as status FROM periodos where ano = '.$request->ano.' limit 1');

        $status = explode(";", $status[0]->status);
        //return $status;

        $projetosNovo   = DB::select('SELECT
                        GROUP_CONCAT(DISTINCT proj.id  ORDER BY proj.id ASC SEPARATOR ";") as id,
                        GROUP_CONCAT(DISTINCT proj.nome ORDER BY proj.id ASC SEPARATOR ";") as nome,
                        GROUP_CONCAT(DISTINCT pj.nome   ORDER BY pj.id ASC SEPARATOR ";") as pj_nome,
                        GROUP_CONCAT(DISTINCT pj.id ORDER BY pj.id ASC SEPARATOR ";") as projetos_id  
                        FROM projetousuario
                        INNER JOIN projetos as proj
                        INNER JOIN projetos as pj on pj.id = projetousuario.projetos_id
                        where usuarios_id = '.Auth::user()->id.' 
                        and pj.status = TRUE 
                        and proj.status = TRUE
                        and proj.data_inicio <= Now()
                        and proj.data_fim >= Now()     
                        and pj.data_inicio <= Now()
                        and pj.data_fim >= Now()    
                        limit 1');

        //return $projetosNovo;

        //ARRAY nome DO PROJETOS
        $pjCadastro = explode(';', $projetosNovo[0]->pj_nome);
        $pjTodos    = explode(';', $projetosNovo[0]->nome);

        //ARRAY ID DO PROJETOS
        $pjId           = explode(';', $projetosNovo[0]->id);
        $pjProjetosId   = explode(';', $projetosNovo[0]->projetos_id);

        //SEPARA nomes IGUAIS E DIFERENTES
        $pjDife     = array_diff($pjTodos, $pjCadastro);

        //SEPARA id IGUAIS E DIFERENTES
        $IdDife     = array_diff($pjId, $pjProjetosId);

        if (count($pjId) == count($pjProjetosId)) {
            $naoAhDiferenca = 1;
        }
        //return $naoAhDiferenca .' '. count($pjProjetosId) . ' ' . count($IdDife);

        $projetosAtribuidos = ProjetoUsuario::where('usuarios_id', Auth::user()->id)->get();
        $projetosCadastrados = Projeto::all();

        //return count($projetosCadastrados);
        if (count($projetosAtribuidos) == count($projetosCadastrados)) {
            $sumirLinha = 3;
        }


        // if ($contrarProjetos == count($IdDife) or $contrarProjetos == count($pjProjetosId) and count($IdDife) == null) {
        //     if ($sumirLinha != 5) {
        //         $sumirLinha = 3;
        //     }
        // }

        //return $contrarProjetos .' '. count($IdDife) . ' ' . count($pjProjetosId);
        //return ' naoAhDiferenca- '.$naoAhDiferenca .', sumirLinha- '. $sumirLinha . ', naoAtribuidos- ' . $naoAtribuidos;
        $periodos = Periodo::SelectRaw('DISTINCT mes')->OrderBy('periodo')->get();
        $periodosAno = Periodo::SelectRaw('DISTINCT ano')->orderBy('ano')->get();
        
        return view('lancamentos.mensal', compact('projetos', 'periodos', 'mesAtual', 'IdDife', 'pjDife', 'periodosAno', 'anoAtual', 'projetosNovo', 'naoAhDiferenca', 'status', 'naoAtribuidos', 'tabelas', 'anoSelecionado', 'sumirLinha'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    // GRAVAR LANÇAMENTO DE HORAS MES
    public function store(Request $request)
    {
        $janeiro = 0;
        $fevereiro = 0;
        $marco = 0;
        $abril = 0;
        $maio = 0;
        $junho = 0;
        $julho = 0;
        $agosto = 0;
        $setembro = 0;
        $outubro = 0;
        $novembro = 0;
        $dezembro = 0;

        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');

        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'ano' => 'required|numeric',
                ],
                [
                    'projetos.*.required'   => 'O projeto é obrigatorio!',
                    'required'              => 'O :attribute é obrigatorio!',
                    'max'                   => 'Valor maximo 240 horas',
                    'numeric.*'             => 'Insira valores númericos :attribute ',
                ]
            );

            if ($validator->fails()) {
                return redirect('/lancamento/createMes')->withErrors($validator)->withInput();
            }

            $field = $request->all(); //PEGAR TODOS OS DADOS EM UM ARRAY

            $status   = DB::select('select GROUP_CONCAT(status ORDER BY periodo ASC SEPARATOR "/") as status from `periodos` where `ano` = ' .$request->ano. ' ');

            $status = $status[0]->status;

            $count = count($field['janeiro']);

            // VERIFICAR SOMA DE 240 HORAS ANTES DE GRAVAR NO BANCO
            for ($i = 0; $i < $count; $i++) {
                for ($j = 1; $j <= 12; $j++) {
                    $mes = strftime('%B', strtotime('+'.$j.' month'));
                    $mes = mb_convert_encoding($mes, 'UTF-8', 'UTF-8');
                    $mes = str_replace("?", "c", $mes);
                    // ACRECENTAR HORAS POR MES
                    switch ($mes) {
                        case 'janeiro':
                            $janeiro += $field[$mes][$i];
                        break;
                        case 'fevereiro':
                            $fevereiro += $field[$mes][$i];
                        break;
                        case 'marco':
                            $marco += $field[$mes][$i];
                        break;
                        case 'abril':
                            $abril += $field[$mes][$i];
                        break;
                        case 'maio':
                            $maio += $field[$mes][$i];
                        break;
                        case 'junho':
                            $junho += $field[$mes][$i];
                        break;
                        case 'julho':
                            $julho += $field[$mes][$i];
                        break;
                        case 'agosto':
                            $agosto += $field[$mes][$i];
                        break;
                        case 'setembro':
                            $setembro += $field[$mes][$i];
                        break;
                        case 'outubro':
                            $outubro += $field[$mes][$i];
                        break;
                        case 'novembro':
                            $novembro += $field[$mes][$i];
                        break;
                        case 'dezembro':
                            $dezembro += $field[$mes][$i];
                        break;
                    }
                }
            }
            // VERIFICAR QUANTIDADE DE HORAS POR MES
            if ($janeiro > 240) {
                $mensagem =  'Horas informadas em Janeiro ultrapassam às 240 horas diárias';
                return redirect('/lancamento/'.$request->ano.'/ano')->with('status', $mensagem);
            }
            if ($fevereiro > 240) {
                $mensagem =  'Horas informadas em Fevereiro ultrapassam às 240 horas diárias';
                return redirect('/lancamento/'.$request->ano.'/ano')->with('status', $mensagem);
            }
            if ($marco > 240) {
                $mensagem =  'Horas informadas em Março ultrapassam às 240 horas diárias';
                return redirect('/lancamento/'.$request->ano.'/ano')->with('status', $mensagem);
            }
            if ($abril > 240) {
                $mensagem =  'Horas informadas em Abril ultrapassam às 240 horas diárias';
                return redirect('/lancamento/'.$request->ano.'/ano')->with('status', $mensagem);
            }
            if ($maio > 240) {
                $mensagem =  'Horas informadas em Maio ultrapassam às 240 horas diárias';
                return redirect('/lancamento/'.$request->ano.'/ano')->with('status', $mensagem);
            }
            if ($junho > 240) {
                $mensagem =  'Horas informadas em Junho ultrapassam às 240 horas diárias';
                return redirect('/lancamento/'.$request->ano.'/ano')->with('status', $mensagem);
            }
            if ($julho > 240) {
                $mensagem =  'Horas informadas em Julho ultrapassam às 240 horas diárias';
                return redirect('/lancamento/'.$request->ano.'/ano')->with('status', $mensagem);
            }
            if ($agosto > 240) {
                $mensagem =  'Horas informadas em Agosto ultrapassam às 240 horas diárias';
                return redirect('/lancamento/'.$request->ano.'/ano')->with('status', $mensagem);
            }
            if ($setembro > 240) {
                $mensagem =  'Horas informadas em Setembro ultrapassam às 240 horas diárias';
                return redirect('/lancamento/'.$request->ano.'/ano')->with('status', $mensagem);
            }
            if ($outubro > 240) {
                $mensagem =  'Horas informadas em Outubro ultrapassam às 240 horas diárias';
                return redirect('/lancamento/'.$request->ano.'/ano')->with('status', $mensagem);
            }
            if ($novembro > 240) {
                $mensagem =  'Horas informadas em Novembro ultrapassam às 240 horas diárias';
                return redirect('/lancamento/'.$request->ano.'/ano')->with('status', $mensagem);
            }
            if ($dezembro > 240) {
                $mensagem =  'Horas informadas em Dezembro ultrapassam às 240 horas diárias';
                return redirect('/lancamento/'.$request->ano.'/ano')->with('status', $mensagem);
            }

            for ($i = 0; $i < $count; $i++) {
                $cont = 0;

                $projetoUsuario = ProjetoUsuario::where('usuarios_id', Auth::user()->id)->where('projetos_id', $field['projetos'][$i])->first();

                // VERIFICAR SE NOVO PROJETOS ESTÁ COM TODOS OS CAMPOS PREENCIDOS E SE HORAS É MENOR QUE 240
                for ($j = 1; $j <= 12; $j++) {
                    $mes = strftime('%B', strtotime('+'.$j.' month'));
                    $mes = mb_convert_encoding($mes, 'UTF-8', 'UTF-8');
                    
                    //echo $j . ' - ' . $mes . '</br>';
                    $mes = str_replace("?", "c", $mes);

                    if ($field[$mes][$i] == '') {
                        $cont++;
                    }
                    if ($field[$mes][$i] > 240) {
                        $field[$mes][$i] = '';
                        $mensagem =  'Horas informadas ultrapassam às 240 horas diárias';
                    }
                }

                if (!isset($projetoUsuario) and $cont == 12) {
                    continue;
                }

                //Apaga existente projeto antes de salvar
                $projetos = Mes::where('usuarios_id', Auth::user()->id)->where('projetos_id', $field['projetos'][$i])->where('ano', $request->ano)->delete();

                $sql = DB::insert('insert into mes (ano, janeiro, fevereiro, marco, abril, maio, junho, julho, agosto, setembro, outubro, novembro, dezembro, status, usuarios_id, projetos_id, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [$request->ano ,$field['janeiro'][$i]  ,$field['fevereiro'][$i] ,
                        $field['marco'][$i],$field['abril'][$i] ,$field['maio'][$i] ,$field['junho'][$i] ,$field['julho'][$i] ,$field['agosto'][$i] ,$field['setembro'][$i] ,$field['outubro'][$i] ,$field['novembro'][$i] ,$field['dezembro'][$i] ,$status ,Auth::user()->id, $field['projetos'][$i], NOW(), NOW()]);

                if (!isset($projetoUsuario)) {
                    ProjetoUsuario::create([
                        'usuarios_id'       => Auth::user()->id,
                        'projetos_id'       => $field['projetos'][$i],
                    ]);
                }
            }

            if (isset($mensagem)) {
                return redirect('/lancamento/'.$request->ano.'/ano')->with('status', $mensagem);
            }
            return redirect('/lancamento/'.$request->ano.'/ano')->with('sucesso', ' Horas Mensais lançadas com sucesso!');
        } catch (\Exception $e) {
            $json = [
                'success' => false,
                'error' => [
                    'code'      => $e->getCode(),
                    'message'   => $e->getMessage(),
                    'line'      => $e->getLine(),
                    'string'      => $e->getTraceAsString(),
                ],
            ];
            return response()->json($json, 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        try {
            $lancamentos = Lancamento::select('lancamentos.quantidadehoras', 'periodos.mes', 'periodos.ano', 'projetos.nome', 'lancamentos.data')
                        ->leftjoin('periodos', 'lancamentos.periodos_id', '=', 'periodos.id')
                        ->leftjoin('usuarios', 'lancamentos.usuarios_id', '=', 'usuarios.id')
                        ->leftjoin('projetos', 'lancamentos.projetos_id', '=', 'projetos.id')
                        ->where('lancamentos.tipo', '=', 'dia')->paginate(1);

            //return $lancamentos;

            return view('lancamentos.detalhes', compact('lancamentos'));

            $lancamento = Lancamento::find($id);
            $lancamento = $lancamento->load('usuario', 'projeto', 'periodo');
            return $lancamento;

            //return Projeto::find($id);
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $lancamento = Lancamento::find($id);
            $lancamento = $lancamento->update($request->all());

            return json_encode($lancamento);
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $lancamento = Lancamento::findOrFail($id);
            $lancamento->delete();
            return $lancamento;
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
