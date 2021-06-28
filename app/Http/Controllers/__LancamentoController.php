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
    public function index($ano)
    {
        //$usuarioLogado = auth()->user();

        try {
            $periodosAno = Periodo::SelectRaw('DISTINCT ano')->get();
            $tabelas = 1;
            $anoSelecionado == $ano;
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

    // CADASTRAR LANÇAMENTO DIARIO
    public function create()
    {
        //$user = User::find($id);

        if (!isset(Auth::user()->id)) {
            return redirect()->back();
        }
        //$lancamento = Lancamento::where('usuarios_id' == Auth::user()->id)->where('created_at' == Now())->get();
        //SELECT time_format( SEC_TO_TIME( SUM( TIME_TO_SEC( quantidadehoras ) ) ),'%H:%i:%s') as horas FROM lancamentos WHERE usuarios_id = 7

        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');
    
        $mesAtual = strftime('%B');
        $anoAtual = strftime('%Y');

        // $horasDiarias = Lancamento::selectRaw('SUM( quantidadehoras ) as horas')
        //                 ->where('usuarios_id', Auth::user()->id)
        //                 ->where('data', date('Y-m-d'))
        //                 ->where('tipo', 'dia')
        //                 ->value('horas');

        // //return $horasDiarias;

        // if ($horasDiarias >= 8) {
        //     $mensagem =  'Usuário já completou às 8 horas diárias';
        //     return view('home', compact('mensagem'));
        // }

        $projetos = Projeto::select('projetos.id', 'nome')
                    ->join('projetousuario', 'projetos.id', '=', 'projetousuario.projetos_id')
                    ->where('projetousuario.usuarios_id', '=', Auth::user()->id)
                    ->where('data_inicio', '<=', date('Y-m-d'))
                    ->where('data_fim', '>=', date('Y-m-d'))
                    ->where('status', true)
                    ->get();

        //return $projetos;
        
        $periodos = Periodo::where('ano', $anoAtual)->where('status', true)->get();

        return view('lancamentos.diario', compact('projetos', 'periodos', 'mesAtual'));
    }
    public function createMes(Request $request)
    {
        $periodosAno = Periodo::SelectRaw('DISTINCT ano')->get();
        $tabelas = 1;
        $anoSelecionado = false;
        return view('lancamentos.mensal', compact('periodosAno', 'tabelas', 'anoSelecionado'));
    }

    // CADASTRAR LANÇAMENTO MENSAL
    public function createAno(Request $request)
    {
        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');
    
        $mesAtual = strftime('%B');
        $anoAtual = strftime('%Y');

        $nãoAtribuidos  = 0;
        $tabelas        = 0;
        $anoSelecionado = $request->ano;
        $sumirLinha     = 0;
        $naoAhDiferenca = 0;
        $todosProjetos  = 8;

        //PEGAR TODOS PROJETOS QUE JA TEM HORAS ATRIBUIDAS

        $projetos = DB::select('SELECT DISTINCT projetos.nome, projetos.id, janeiro, fevereiro, marco, abril, maio, junho, julho, agosto, setembro, outubro, novembro, dezembro FROM mes
                                join projetousuario on mes.usuarios_id = projetousuario.usuarios_id
                                join projetos on projetos.id = mes.projetos_id
                                WHERE ano = '.$request->ano.'
                                and projetos.data_inicio <= Now()
                                and projetos.data_fim >= Now() 
                                and projetousuario.usuarios_id = '. Auth::user()->id.' ');

        // $projetos = Mes::select('projetos.nomes', 'projetos.id', 'janeiro', 'fevereiro', 'marco', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro')
        //                 ->leftjoin('projetos', 'mes.projetos_id', '=', 'projetos.id')
        //                 ->join('projetousuario', 'projetos.id', '=', 'projetousuario.projetos_id')
        //                 ->groupBy('projetos.nome', 'projetos.id', 'janeiro', 'fevereiro', 'marco', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro')
        //                 ->where('mes.usuarios_id', Auth::user()->id)->where('mes.ano', $request->ano)->paginate(10);

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
            $nãoAtribuidos = 1;
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
        //return $contrarProjetos .' '. count($pjProjetosId) . ' ' . count($IdDife);
        if ($contrarProjetos == count($IdDife) or $contrarProjetos == count($pjProjetosId) and count($IdDife) == null) {
            if ($sumirLinha != 5) {
                $sumirLinha = 3;
            }
        }

        $periodos = Periodo::SelectRaw('DISTINCT mes')->OrderBy('periodo')->get();
        $periodosAno = Periodo::SelectRaw('DISTINCT ano')->get();
        

        return view('lancamentos.mensal', compact('projetos', 'periodos', 'mesAtual', 'IdDife', 'pjDife', 'periodosAno', 'anoAtual', 'projetosNovo', 'naoAhDiferenca', 'status', 'nãoAtribuidos', 'tabelas', 'anoSelecionado', 'sumirLinha'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    // GRAVAR LANÇAMENTO DE HORAS DIAS
    public function store(Request $request)
    {
        try {
            // return $request;
            // validar erros
            $validator = Validator::make(
                $request->all(),
                [
                'projetos'              => 'required|numeric',
                'periodos'              => 'required|numeric',
                'quantidadehoras'       => 'required|numeric',
                'data'                  => 'required|min:10|date',
                ],
                [
                'projetos.required'  => 'O projeto é obrigatorio!',
                'alpha'     => 'Insira caracteres válidos',
                'required'  => 'O :attribute é obrigatorio!',
                'min'       => 'O :attribute não pode ter menos de :min caracteres!',
                'max'       => 'O :attribute não pode ter mais de :max caracteres!',
                'unique'    => 'O :attribute já esta sendo usado!',
                'numeric'   => 'Insira valores númericos',
                'date'      => 'Insira uma data válida',
                ]
            );

            if ($validator->fails()) {
                return redirect('/lancamento/create')->withErrors($validator)->withInput();
            }

            if ($request['quantidadehoras'] > 8) {
                $mensagem =  'Horas informadas ultrapassam às 8 horas diárias';
                return redirect()->route('lancamento.create')->with('status', $mensagem);
            }

            //VERIFICAR QUANTIDADE de horas NO BANCO
            $horasDiarias = Lancamento::selectRaw('SUM( quantidadehoras ) as horas')
                        ->where('usuarios_id', Auth::user()->id)
                        ->where('data', $request['data'])
                        ->where('tipo', 'dia')
                        ->value('horas');

            if ($horasDiarias >=  8) {
                $mensagem =  'Usuário já completou às 8 horas diárias';
                return redirect()->route('lancamento.create')->with('status', $mensagem);
            }

            //CALCULAR HORAS NO BANCO COM AS INFORMADAS PELO USUARIO
            if (isset($horasDiarias)) {
                //Grava na variável resultado final
                $tempo = $horasDiarias + $request['quantidadehoras'];

                if ($tempo > 8) {
                    $mensagem =  'Horas informadas ultrapassam às 8 horas diárias';
                    return redirect()->route('lancamento.create')->with('status', $mensagem);
                }
            }
        
            Lancamento::create([
                'quantidadehorasmes'=> 0,
                'quantidadehoras'   => $request['quantidadehoras'],
                'tipo'              => 'dia',
                'usuarios_id'       => Auth::user()->id,
                'projetos_id'       => $request['projetos'],
                'periodos_id'       => $request['periodos'],
                'data'              => $request['data'],
            ]);

            $projetoUsuario = ProjetoUsuario::where('usuarios_id', Auth::user()->id)->where('projetos_id', $request['projetos'])->first();
            
            if (!isset($projetoUsuario)) {
                ProjetoUsuario::create([
                    'usuarios_id'       => Auth::user()->id,
                    'projetos_id'       => $request['projetos'],
                ]);
            }

            return redirect()->route('lancamento.create')->with('mensagem', 'Horas Diárias lançadas com sucesso!');
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

    // GRAVAR LANÇAMENTO DE HORAS MES
    public function storeMes(Request $request)
    {
        
        //return $request;

        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');

        try {
            $validator = Validator::make(
                $request->all(),
                [
                    //'projetos.*'    => 'required|numeric',
                    'ano'           => 'required|numeric',
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

            for ($i = 0; $i < $count; $i++) {
                $cont = 0;

                $projetoUsuario = ProjetoUsuario::where('usuarios_id', Auth::user()->id)->where('projetos_id', $field['projetos'][$i])->first();

                // VERIFICAR SE NOVO PROJETOS ESTÁ COM TODOS OS CAMPOS PREENCIDOS E SE HORAS É MENOR QUE 240
                for ($j = 2; $j <= 13; $j++) {
                    $mes = strftime('%B', strtotime('+'.$j.' month'));
                    $mes = mb_convert_encoding($mes, 'UTF-8', 'UTF-8');

                    echo $j . ' - ' . $mes . '</br>';
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
            // SELECT lancamentos.quantidadehoras, periodos.mes, periodos.ano, projetos.nome, lancamentos.data
            // FROM `lancamentos`
            // LEFT JOIN periodos on lancamentos.periodos_id = periodos.id
            // LEFT JOIN usuarios on lancamentos.usuarios_id = usuarios.id
            // LEFT JOIN projetos on lancamentos.projetos_id = projetos.id
            // WHERE lancamentos.tipo = 'dia'

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
