<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Projeto;
use App\Lancamento;
use App\Mes;
use \Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Validator;
use App\Http\Middleware\Admin;

class ProjetoController extends Controller
{
    public function __construct()
    {
        $this->middleware(Admin::class);
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
            $projetos = Projeto::all();
            return view('projetos.projetos', compact('projetos'));
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
    public function create()
    {
        return view('projetos.novo');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return $request;
        //if (($request->nome == null) or ($request->codigo == null) or ($request->descricao = null) or ($request->data_inicio == null) or ($request->centrodecusto == null)){
        $validator = Validator::make(
            $request->all(),
            [
                'nome'              => 'required|unique:projetos|max:255|min:3',
                'descricao'         => 'required|max:400|min:3',
                'data_inicio'       => 'required|min:10|date',
                'centrodecusto'     => 'required|numeric'
            ],
            [
                'required'  => 'O :attribute é obrigatorio!',
                'min'       => 'O :attribute não pode ter menos de :min caracteres!',
                'max'       => 'O :attribute não pode ter mais de :max caracteres!',
                'unique'    => 'O :attribute já esta sendo usado!',
                'date'      => 'Insira uma data válida',
                'numeric'   => 'Insira valores númericos',
            ]
        );
            
        if ($validator->fails()) {
            return redirect('/projeto/create')->withErrors($validator)->withInput();
        }
        // }
        
        try {

            //Projeto::create($request->all());
            
            $projeto = new Projeto;
            $projeto->nome          = $request->nome;
            $projeto->descricao     = $request->descricao;
            $projeto->data_inicio   = $request->data_inicio;
            $projeto->centrodecusto = $request->centrodecusto;

            if ($request->data_fim == null) {
                $projeto->data_fim = '2222-12-12';
            } else {
                $projeto->data_fim = $request->data_fim;
            }
            
            $projeto->save();

            return redirect()->route('projeto.index');
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $projeto = Projeto::find($id);

            if (!$projeto) {
                return redirect()->back();
            }
            
            $contador = MES::selectRaw('SUM(
                                            (CASE WHEN janeiro IS NULL THEN 0 ELSE janeiro END) + 
                                            (CASE WHEN fevereiro IS NULL THEN 0 ELSE fevereiro END) + 
                                            (CASE WHEN marco IS NULL THEN 0 ELSE marco END) +
                                            (CASE WHEN abril IS NULL THEN 0 ELSE abril END) +
                                            (CASE WHEN maio IS NULL THEN 0 ELSE maio END) +
                                            (CASE WHEN junho IS NULL THEN 0 ELSE junho END) +
                                            (CASE WHEN julho IS NULL THEN 0 ELSE julho END) +
                                            (CASE WHEN agosto IS NULL THEN 0 ELSE agosto END) +
                                            (CASE WHEN setembro IS NULL THEN 0 ELSE setembro END) +
                                            (CASE WHEN outubro IS NULL THEN 0 ELSE outubro END) +
                                            (CASE WHEN novembro IS NULL THEN 0 ELSE novembro END) +
                                            (CASE WHEN dezembro IS NULL THEN 0 ELSE dezembro END) +
                                            (CASE WHEN abril IS NULL THEN 0 ELSE abril END)
                                        ) as horas')
                            ->selectRaw('COUNT( DISTINCT usuarios_id) as usuarios')
                            ->where('mes.projetos_id', '=', $id)
                            ->first();
            //return $contador;
                            
            $colaboradores = Mes::select('usuarios.nome', 'usuarios.email', 'usuarios.id')
                            ->selectRaw('SUM(
                                (CASE WHEN janeiro IS NULL THEN 0 ELSE janeiro END) + 
                                (CASE WHEN fevereiro IS NULL THEN 0 ELSE fevereiro END) + 
                                (CASE WHEN marco IS NULL THEN 0 ELSE marco END) +
                                (CASE WHEN abril IS NULL THEN 0 ELSE abril END) +
                                (CASE WHEN maio IS NULL THEN 0 ELSE maio END) +
                                (CASE WHEN junho IS NULL THEN 0 ELSE junho END) +
                                (CASE WHEN julho IS NULL THEN 0 ELSE julho END) +
                                (CASE WHEN agosto IS NULL THEN 0 ELSE agosto END) +
                                (CASE WHEN setembro IS NULL THEN 0 ELSE setembro END) +
                                (CASE WHEN outubro IS NULL THEN 0 ELSE outubro END) +
                                (CASE WHEN novembro IS NULL THEN 0 ELSE novembro END) +
                                (CASE WHEN dezembro IS NULL THEN 0 ELSE dezembro END) +
                                (CASE WHEN abril IS NULL THEN 0 ELSE abril END)
                            ) as horas')
                            ->selectRaw('COUNT(usuarios_id) as usuarios')
                            ->leftjoin('usuarios', 'mes.usuarios_id', '=', 'usuarios.id')
                            ->where('mes.projetos_id', '=', $id)
                            ->groupBy('usuarios.nome', 'usuarios.email', 'usuarios.id')
                            ->paginate(10);

            //return $colaboradores;

            //$projeto->load('lancamento.usuario', 'lancamento.periodo');

            return view('projetos.detalhes', compact('projeto', 'colaboradores', 'contador'));
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
        try {
            $projeto = Projeto::find($id);

            if (!$projeto) {
                return redirect()->back();
            }

            return view('projetos.editar', compact('projeto'));
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nome'              => 'required|max:255|min:3',
                'descricao'         => 'required|max:600|min:3',
                'data_inicio'       => 'required|min:10|date',
                'centrodecusto'     => 'required|numeric',
            ],
            [
                'required'  => 'O :attribute é obrigatorio!',
                'min'       => 'O :attribute não pode ter menos de :min caracteres!',
                'max'       => 'O :attribute não pode ter mais de :max caracteres!',
                'date'      => 'Insira uma data válida',
                'numeric'   => 'Insira valores númericos',
            ]
        );

        if ($validator->fails()) {
            return redirect('/projeto/'. $id .'/edit')->withErrors($validator)->withInput();
        }

        try {
            $projeto = Projeto::find($id);

            if (!$projeto) {
                return redirect()->back();
            }

            $projeto->nome          = $request->nome;
            $projeto->descricao     = $request->descricao;
            $projeto->data_inicio   = $request->data_inicio;
            $projeto->centrodecusto = $request->centrodecusto;

            if ($request->data_fim == null) {
                $projeto->data_fim = '2222-12-12';
            } else {
                $projeto->data_fim = $request->data_fim;
            }
            $projeto->save();

            //$projeto = $projeto->update($request->all());

            return redirect()->route('projeto.index');
            
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
            $status = Projeto::select('status')->where('id', $id)->first();

            if ($status->status == true) {
                Projeto::where('id', $id)->update([
                    'status'          => false,
                    'updated_at'     => NOW()
                ]);
            } else {
                Projeto::where('id', $id)->update([
                    'status'          => true,
                    'updated_at'     => NOW()
                ]);
            }
            return redirect()->route('projeto.index');
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
