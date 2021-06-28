<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Projeto;
use App\Mes;
use App\ProjetoUsuario;
use Illuminate\Support\Facades\Auth;
use \Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Middleware\Admin;

class UsuarioController extends Controller
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
            $usuarios =  User::all();
            return view('usuarios.usuarios', compact('usuarios'));
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
        try {
            $projetos =  Projeto::where('data_inicio', '<=', date('Y-m-d'))
                                ->where('data_fim', '>=', date('Y-m-d'))
                                ->where('status', true)
                                ->get();

            return view('usuarios.novo', compact('projetos'));
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'usuario'   => 'required|unique:usuarios|alpha',
                'nome'      => 'required|regex:/^[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ ]+$/',
                'email'     => 'required|email|unique:usuarios',
                'matricula' => 'required|numeric',
                'filial'    => 'required|numeric'
            ],
            [
                'alpha'     => 'Insira caracteres válidos',
                'required'  => 'O :attribute é obrigatorio!',
                'min'       => 'O :attribute não pode ter menos de :min caracteres!',
                'max'       => 'O :attribute não pode ter mais de :max caracteres!',
                'unique'    => 'O :attribute já esta sendo usado!',
                'numeric'   => 'Insira valores númericos',
            ]
        );

        if ($validator->fails()) {
            return redirect('/usuario/create')->withErrors($validator)->withInput();
        }

        try {
            $senha = '123456';
            
            $usuario = new User;
            $usuario->usuario   = $request['usuario'];
            $usuario->nome      = $request['nome'];
            $usuario->email     = $request['email'];
            $usuario->filial    = $request['filial'];
            $usuario->matricula = $request['matricula'];
            $usuario->password  = Hash::make($senha);

            if (isset($request['admin'])) {
                $usuario->administrador  = true;
            } else {
                $usuario->administrador  = false;
            }
            
            $usuario->save();
            
            if ($request->projetos != '') {
                foreach ($request->projetos as $projetos) {
                    ProjetoUsuario::create([
                        'usuarios_id'       => $usuario->id,
                        'projetos_id'       => $projetos,
                    ]);

                    Mes::create([
                        'usuarios_id'       => $usuario->id,
                        'projetos_id'       => $projetos,
                    ]);
                }
            }

            return redirect()->route('usuario.index')->with('status', 'Usuário cadastrado com sucesso!');
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
            $usuario = User::find($id);
            if (!$usuario) {
                return redirect()->back();
            }
            $projetos = ProjetoUsuario::select('nome', 'descricao')
                                        ->join('projetos', 'projetos_id', '=', 'projetos.id')
                                        ->where('usuarios_id', $id)
                                        ->where('data_inicio', '<=', date('Y-m-d'))
                                        ->where('data_fim', '>=', date('Y-m-d'))
                                        ->where('status', true)
                                        ->get();

            return view('usuarios.detalhes', compact('usuario', 'projetos'));
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
            $usuario        = User::find($id);
            $projetos       = Projeto::all();
            $todosProjetos = 0;
            $semProjetos   = 0;

            $projetoUsuario = DB::select('SELECT
                        GROUP_CONCAT(DISTINCT proj.id   ORDER BY proj.id ASC SEPARATOR ";") as id,
                        GROUP_CONCAT(DISTINCT proj.nome ORDER BY proj.id ASC SEPARATOR ";") as nome,
                        GROUP_CONCAT(DISTINCT pj.nome   ORDER BY pj.id ASC SEPARATOR ";") as pj_nome,
                        GROUP_CONCAT(DISTINCT pj.id     ORDER BY pj.id ASC SEPARATOR ";") as projetos_id  
                        FROM projetousuario
                        INNER JOIN projetos as proj
                        INNER JOIN projetos as pj on pj.id = projetousuario.projetos_id
                        where usuarios_id = '.$id.'
                        and pj.status = TRUE 
                        and proj.status = TRUE
                        and proj.data_inicio <= Now()
                        and proj.data_fim >= Now()     
                        and pj.data_inicio <= Now()
                        and pj.data_fim >= Now()                        
                        limit 1');
                        
            //ARRAY nome DO PROJETOS
            $pjCadastro = explode(';', $projetoUsuario[0]->pj_nome);
            $pjTodos    = explode(';', $projetoUsuario[0]->nome);

            //ARRAY ID DO PROJETOS
            $pjId           = explode(';', $projetoUsuario[0]->id);
            $pjProjetosId   = explode(';', $projetoUsuario[0]->projetos_id);

            //SEPARA nomes IGUAIS E DIFERENTES
            $pjIguais   = array_intersect($pjCadastro, $pjTodos);
            $pjDife     = array_diff($pjTodos, $pjCadastro);

            //SEPARA id IGUAIS E DIFERENTES
            $IdIguais   = array_intersect($pjProjetosId, $pjId);
            $IdDife     = array_diff($pjId, $pjProjetosId);

            if (isset($projetoUsuario[0]->pj_nome) and isset($projetoUsuario[0]->nome)
             and isset($projetoUsuario[0]->id) and isset($projetoUsuario[0]->projetos_id)) {
                $semProjetos = 1;
            }

            if (!$usuario) {
                return redirect()->back();
            }

            return view('usuarios.editar', compact('usuario', 'projetos', 'pjIguais', 'IdIguais', 'pjDife', 'IdDife', 'todosProjetos', 'semProjetos'));
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
        //return $request->projetos;
        $validator = Validator::make(
            $request->all(),
            [
                'usuario'   => 'required|unique:usuarios,usuario,'.$id.'|alpha',
                'nome'      => 'required|regex:/^[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ ]+$/',
                'email'     => 'required|email|unique:usuarios,email,'.$id.'',
                'matricula' => 'required|numeric',
                'filial'    => 'required|numeric'
            ],
            [
                'alpha'     => 'Insira caracteres válidos',
                'required'  => 'O :attribute é obrigatorio!',
                'min'       => 'O :attribute não pode ter menos de :min caracteres!',
                'max'       => 'O :attribute não pode ter mais de :max caracteres!',
                'unique'    => 'O :attribute já esta sendo usado!',
                'numeric'   => 'Insira valores númericos',
            ]
        );

        if ($validator->fails()) {
            //return redirect('/usuario/create')->withErrors($validator)->withInput();
            return redirect('/usuario/'.$id.'/edit')->withErrors($validator)->withInput();
        }
        
        try {
            $user = User::find($id);

            if (!$user) {
                return redirect()->back();
            }

            if (isset($request->checkbox)) {
                $admin = true;
            } else {
                $admin = false;
            }

            $user = $user->update([
                'usuario'   => $request['usuario'],
                'nome'      => $request['nome'],
                'email'     => $request['email'],
                'filial'    => $request['filial'],
                'matricula' => $request['matricula'],
                'administrador' => $admin,
            ]);
            
            $todosProjetos = DB::select('SELECT GROUP_CONCAT(DISTINCT id ORDER BY id ASC SEPARATOR ";") as id FROM projetos');

            $todosProjetos = explode(';', $todosProjetos[0]->id);

            $nome = '';

            if ($request->projetos != '') {
                $idDife = array_diff($todosProjetos, $request->projetos);

                foreach ($idDife as $idD) {
                    $mes = DB::select('SELECT mes.*, projetos.nome FROM mes JOIN projetos ON projetos.id = mes.projetos_id WHERE (usuarios_id = '.$id.' and projetos_id = '.$idD.')
                    and (janeiro is not null or fevereiro is not null or marco is not null or abril is not null 
                    or maio is not null or junho is not null or julho is not null or agosto is not null 
                    or setembro is not null or outubro is not null or novembro is not null or dezembro is not null)');

                    if (isset($mes[0]->id)) {
                        $nome = $nome . ', ' .$mes[0]->nome ;
                        continue;
                    }

                    //REMOVER PROJETO TABELA MES
                    DB::select('DELETE FROM mes  WHERE (usuarios_id = '.$id.' and projetos_id = '.$idD.')
                                and (janeiro is null and fevereiro is null and marco is null and abril is null 
                                and maio is null and junho is null and julho is null and agosto is null 
                                and setembro is null and outubro is null and novembro is null and dezembro is null)');
                }

                if (isset($mes[0]->id)) {
                    return redirect()->back()->with('status', 'Usuário já informou horas no projeto '. $nome .'!');
                }
            }

            if ($request->projetos != '') {
                $projetos = ProjetoUsuario::where('usuarios_id', $id)->delete();

                foreach ($request->projetos as $projetos) {
                    ProjetoUsuario::create([
                        'usuarios_id'       => $id,
                        'projetos_id'       => $projetos
                    ]);
                    
                    $mes = Mes::where('usuarios_id', $id)->where('projetos_id', $projetos)->first();

                    if (!isset($mes)) {
                        Mes::create([
                            'usuarios_id'       => $id,
                            'projetos_id'       => $projetos
                        ]);
                    }
                }
            } else {
                $projetosUsuarios = ProjetoUsuario::where('usuarios_id', $id)->get();

                foreach ($projetosUsuarios as $projetosUsuariosId) {
                    $mes = DB::select('SELECT mes.*, projetos.nome FROM mes JOIN projetos ON projetos.id = mes.projetos_id WHERE (usuarios_id = '.$id.' and projetos_id = '.$projetosUsuariosId->projetos_id.')
                    and (janeiro is not null or fevereiro is not null or marco is not null or abril is not null 
                    or maio is not null or junho is not null or julho is not null or agosto is not null 
                    or setembro is not null or outubro is not null or novembro is not null or dezembro is not null)');
                    
                    //return $mes;

                    if (isset($mes[0]->id)) {
                        $nome = $nome . ', ' .$mes[0]->nome ;
                        continue;
                    } else {
                        $projetos = ProjetoUsuario::where('usuarios_id', $id)
                                                    ->where('projetos_id', $projetosUsuariosId->projetos_id)->delete();

                        //REMOVER PROJETO TABELA MES
                        DB::select('DELETE FROM mes  WHERE (usuarios_id = '.$id.' and projetos_id = '.$projetosUsuariosId->projetos_id.')
                            and (janeiro is null and fevereiro is null and marco is null and abril is null 
                            and maio is null and junho is null and julho is null and agosto is null 
                            and setembro is null and outubro is null and novembro is null and dezembro is null)');
                    }
                }
                if (isset($mes[0]->id)) {
                    return redirect()->back()->with('status', 'Usuário já informou horas no projeto '. $nome .'!');
                }
            }

            return redirect()->route('usuario.index');
        } catch (\Exception $e) {
            $json = [
                'success' => false,
                'error' => [
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                    'line'      => $e->getLine(),
                    'string'      => $e->getTraceAsString(),
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
            //$user = User::findOrFail($id);
            $status = User::select('status')->where('id', $id)->first();
            if ($status->status == true) {
                User::where('id', $id)->update([
                    'status'          => false,
                    'updated_at'     => NOW()
                ]);
            } else {
                User::where('id', $id)->update([
                    'status'          => true,
                    'updated_at'     => NOW()
                ]);
            }


            return redirect()->route('usuario.index');
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
    public function perfil()
    {
        try {
            $usuario = User::find(Auth::user()->id);
            if (!$usuario) {
                return redirect()->back();
            }
            $projetos = ProjetoUsuario::select('nome', 'descricao')
                                        ->join('projetos', 'projetos_id', '=', 'projetos.id')
                                        ->where('usuarios_id', Auth::user()->id)
                                        ->where('data_inicio', '<=', date('Y-m-d'))
                                        ->where('data_fim', '>=', date('Y-m-d'))
                                        ->where('status', true)
                                        ->get();

            return view('usuarios.perfil', compact('usuario', 'projetos'));
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
    public function senha(Request $request)
    {
        $senha          = $request->input('senha');
        $confirmasenha  = $request->input('confirmasenha');
        $id             = Auth::user()->id;

        if ($senha == $confirmasenha) {
            $usuario = user::find($id);
            if (isset($usuario)) {
                $usuario->password = Hash::make($senha);
                $usuario->save();

                $retorno = array(
                    'mensagem'          => "sucesso!",
                    'sucesso'           => 0
                );
                return response(json_encode($retorno, 200));
            }
        } else {
            $retorno = array(
                'mensagem'          => "ERRO SENHAS DIFERENTES!",
                'sucesso'           => 1
            );
            return response(json_encode($retorno, 200));
        }
    }
}
