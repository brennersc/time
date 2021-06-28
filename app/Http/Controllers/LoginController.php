<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Projeto;
use App\ProjetoUsuario;
use Illuminate\Support\Facades\Auth;
use \Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Middleware\Admin;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
