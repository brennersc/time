<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Periodo;
use \Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Middleware\Admin;

class PeriodoController extends Controller
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
            $periodos = Periodo::orderBy('ano')->paginate(12);
            return view('periodos.periodos', compact('periodos'));
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
                'ano'       => 'required|unique:periodos|numeric|min:2020|max:9999',
            ]
        );

        if ($validator->fails()) {
            return redirect('/periodo')->withErrors($validator)->withInput();
        }

        try {
            //
            setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
            date_default_timezone_set('America/Sao_Paulo');
            $i = 0;

            for ($i = 1; $i <= 12; $i++) {
                $mes = strftime('%B', strtotime('+'.$i.' month'));
                $mes = mb_convert_encoding($mes, 'UTF-8', 'UTF-8');

                if ($mes == 'mar?o') {
                    $mes = 'março';
                }
                $num = $i;
                $periodo = Periodo::create([
                    'periodo'   => $num,
                    'mes'       => $mes,
                    'ano'       => $request->ano
                ]);
            }

            return redirect()->route('periodo.index')->with('status', 'Periodo de '.$request->ano.' criado com sucesso!');
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
            $periodo = Periodo::find($id);
            $periodo->load('lancamento');
            return $periodo;

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
            $periodo = Periodo::find($id);
            $periodo = $periodo->update($request->all());

            return json_encode($periodo);
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
            $periodo = Periodo::findOrFail($id);
            $periodo->delete();
            return $periodo;
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
    public function getByPeriodo(Request $request)
    {
        try {
            $id = $request->input('id');
            $periodo = Periodo::find($id);
        
            if ($periodo->status == true) {
                $periodo->update(['status' => false, 'updated_at'   => NOW()]);
                $periodo = Periodo::find($id);
                //retornar mensagem SALVO
                $retorno = array(
                'sucesso'   => 0,
                'mensagem'  => 'Periodo Desativado com sucesso',
                'id'        => $periodo->id,
                'status'    => $periodo->status,
            );
                return $retorno;
            } else {
                $periodo->update(['status' => true, 'updated_at'   => NOW()]);
                $periodo = Periodo::find($id);
                //retornar mensagem SALVO
                $retorno = array(
                'sucesso'   => 1,
                'mensagem'  => 'Periodo Ativado com sucesso',
                'id'        => $periodo->id,
                'status'    => $periodo->status,
            );
                return $retorno;
            }
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
