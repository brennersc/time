call pivotwizard( "projetos_id",
                  "mes",
                  "qtdhora",
                  "(SELECT  periodo, mes, ano,
                    CASE
                        WHEN projetos_id is null THEN 'SEM PROJETO'
                        ELSE projetos_id
                    END projetos_id,
                    CASE
                        WHEN qtdhora is null THEN 0
                        ELSE qtdhora
                    END qtdhora
                    FROM periodos
                    LEFT JOIN (
                        SELECT usuarios_id, projetos_id, periodos_id, sum(quantidadehoras) as qtdhora
                        FROM `lancamentos`
                        where usuarios_id = 21
                        group by usuarios_id, projetos_id, periodos_id ) TMP ON TMP.periodos_id = `periodos`.periodo) as TMP",
                    "ano = '2021'")

BEGIN
   DECLARE done INT DEFAULT 0;
   DECLARE M_Count_Columns int DEFAULT 0;
   DECLARE M_Column_Field varchar(60);
   DECLARE M_Columns VARCHAR(8000) DEFAULT '';
   DECLARE M_sqltext VARCHAR(8000);
   DECLARE M_stmt VARCHAR(8000);
   DECLARE cur1 CURSOR FOR SELECT CAST(Column_Field AS CHAR) FROM temp_pivot;
   DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
   DROP TABLE IF EXISTS temp_pivot;
   SET @M_sqltext = CONCAT('CREATE TEMPORARY TABLE temp_pivot ',
                           ' SELECT DISTINCT ',P_Column_Field, ' AS Column_Field',
                           ' FROM ',P_From,
                           ' WHERE ',P_Where,
                           ' ORDER BY ', P_Column_Field);
   PREPARE M_stmt FROM @M_sqltext;
   EXECUTE M_stmt;
   SELECT COUNT(*) INTO M_Count_Columns
   FROM temp_pivot
   WHERE Column_Field IS NOT NULL;
   IF (M_Count_Columns > 0) THEN
      OPEN cur1;
      REPEAT
         FETCH cur1 INTO M_Column_Field;
         IF (NOT done) and (M_Column_Field IS NOT NULL) THEN
            SET M_Columns = CONCAT(M_Columns,
                            ' SUM( CASE WHEN ',P_Column_Field,'=''',M_Column_Field,'''',
                            ' THEN ',P_Value,
                            ' ELSE 0 END) AS `', M_Column_Field ,'`,');
         END IF;
      UNTIL done END REPEAT;
      SET M_Columns = Left(M_Columns,Length(M_Columns)-1);
      SET @M_sqltext = CONCAT('SELECT ',P_Row_Field,',',M_Columns,
                              ' FROM ', P_From,
                              ' WHERE ', P_Where,
                              ' GROUP BY ', P_Row_Field,
                              ' ORDER BY ', P_Row_Field);
      PREPARE M_stmt FROM @M_sqltext;
      EXECUTE M_stmt;
   END IF;
END


SELECT projetos.nome, periodo.mes, lancamentos.quantidadehoras
FROM(
        SELECT  periodo, mes, ano,
            CASE
                WHEN projetos_id is null THEN 'SEM PROJETO'
                ELSE projetos_id
            END projetos_id,
            CASE
                WHEN qtdhora is null THEN 0
                ELSE qtdhora
            END qtdhora
            FROM `periodos`) periodos
LEFT JOIN (
            SELECT usuarios_id, projetos_id, periodos_id, sum(quantidadehoras) as qtdhora
            FROM `lancamentos`
            where usuarios_id = 21
            group by usuarios_id, projetos_id, periodos_id ) TMP ON TMP.periodos_id = `periodos`.periodo) as TMP 
            WHERE ano = '2021' )


SELECT EXTRACT(ano  FROM invoice_date) ano
     , EXTRACT(mes FROM invoice_date) mes
     , SUM(lancamentos.quantidadehoras) horas
  FROM periodos
  LEFT JOIN lancamentos ON lancamentos.periodos_id = periodos.id
 GROUP BY EXTRACT(ano  FROM invoice_date)
        , EXTRACT(mes FROM invoice_date)

SELECT EXTRACT(ANO FROM invoice_date) ano
, SUM(revenue) total_revenue
FROM periodos
GROUP BY EXTRACT(ANO FROM invoice_date)



$texto = " 'SEM PROJETO' ";
$ano = " '2021' ";
$sql = 'call pivotwizard( "projetos_id", "mes", "qtdhora", "(SELECT periodo, mes, ano, CASE WHEN projetos_id is null THEN 
'.$texto.' ELSE projetos_id END projetos_id, CASE WHEN qtdhora is null THEN 0 ELSE qtdhora END qtdhora FROM periodos LEFT JOIN ( SELECT usuarios_id, projetos_id, periodos_id, sum(quantidadehoras) as qtdhora FROM `lancamentos` where usuarios_id = 21 group by usuarios_id, projetos_id, periodos_id ) TMP ON TMP.periodos_id = `periodos`.periodo) as TMP", "ano = '.$ano.' ") ';

$teste =  DB::select($sql);

return $teste;



// $horasMes = Lancamento::selectRaw('time_format( SEC_TO_TIME( SUM( TIME_TO_SEC( quantidadehorasmes ) ) ),"%H:%i:%s") as horas')
//             ->leftjoin('periodos', 'lancamentos.periodos_id', '=', 'periodos.id')
//             ->where('usuarios_id', Auth::user()->id)
//             ->where('mes', $mesAtual)
//             ->where('tipo', 'mes')
//             ->value('horas');

// $projetos = Projeto::select('projetos.id', 'nome')
//             ->join('projetousuario', 'projetos.id', '=', 'projetousuario.projetos_id')
//             ->where('projetousuario.usuarios_id', Auth::user()->id)
//             ->where('data_inicio', '<=', date('Y-m-d'))
//             ->where('data_fim', '>=', date('Y-m-d'))
//             ->where('status', true)
//             ->paginate(5);

// if ($horasMes >= '240:00:00') {
//     $mensagemMes =  'Usuário já completou às 240 horas mensais';
//     return view('home', compact('mensagemMes'));
// }





select `projetos`.`nome`, `janeiro`, `fevereiro`, `março`, `abril`, `maio`, `junho`, `julho`, `agosto`, `setembro`, `outubro`, `novembro`, `dezembro`, 
GROUP_CONCAT(periodos.status ORDER BY periodos.periodo ASC SEPARATOR ";") as status 
from `mes` 
left join `periodos` on `mes`.`ano` = `periodos`.`ano` 
left join `projetos` on `mes`.`projetos_id` = `projetos`.`id` 
where `usuarios_id` = 21 group by `projetos`.`nome`, `janeiro`, `fevereiro`, `março`, `abril`, `maio`, `junho`, `julho`, `agosto`, `setembro`, `outubro`, `novembro`, `dezembro`





// GRAVAR LANÇAMENTO DE HORAS MES
    public function storeMes(Request $request)
    {
        return $request;
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'projetos.*' => 'required|numeric',
                    'periodos' =>   'required|numeric',
                ],
                [
                    'projetos.*.required'  => 'O projeto é obrigatorio!',
                    'required'  => 'O :attribute é obrigatorio!',
                    'numeric'   => 'Insira valores númericos',
                ]
            );

            if ($validator->fails()) {
                return redirect('/lancamento/createMes')->withErrors($validator)->withInput();
            }

            $field = $request->all(); //PEGAR TODOS OS DADOS EM UM ARRAY

            $somaHoras = 0;
            $count = count($field['horas']);

            setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
            date_default_timezone_set('America/Sao_Paulo');
            $mesAtual = strftime('%B');

            $horasMes = Lancamento::selectRaw('time_format( SEC_TO_TIME( SUM( TIME_TO_SEC( quantidadehorasmes ) ) ),"%H:%i:%s") as horas')
                    ->leftjoin('periodos', 'lancamentos.periodos_id', '=', 'periodos.id')
                    ->where('usuarios_id', Auth::user()->id)
                    ->where('mes', $mesAtual)
                    ->where('tipo', 'mes')
                    ->value('horas');


            $cont = 0;
            for ($i = 0; $i < $count; $i++) {
                $horas = $field['horas'][$i];

                if($field['horas'][$i] == '') {
                    $cont++;
                }
            }
            if ($cont == $count) {
                $mensagemMes =  'Campos em branco, por favor informe as horas!!!';
                return redirect('/lancamento/createMes')->with('mensagemMes', $mensagemMes);
            }

            for ($i = 0; $i < $count; $i++) {
                $horas = $field['horas'][$i];

                //tratar campos SE ambos vazios ignorar, SE vazio atribuir zero
                $minutos = $request['minutos'][$i];
                if (($field['horas'][$i] == '') and ($request['minutos'][$i] == '')) {
                    continue;
                }
                if (($field['horas'][$i] == '')) {
                    $horas = '00';
                }
                if (($field['minutos'][$i] == '')) {
                    $minutos = '00';
                }

                //echo 'entrada  '. $horas.':'.$minutos . '</br>';
                $entrada        =  $horas.':'.$minutos;
                $hora1          =  explode(":", $entrada);
                $acumulador1    = ($hora1[0] * 3600) + ($hora1[1] * 60);
                if (isset($tempo)) {
                    $hora2          = explode(":", $tempo);
                    $acumulador2    = ($hora2[0] * 3600) + ($hora2[1] * 60);
                    $resultado      = $acumulador1 + $acumulador2;
                } else {
                    $resultado      = $acumulador1;
                }
                $hora_ponto     = floor($resultado / 3600);
                $resultado      = $resultado - ($hora_ponto * 3600);
                $min_ponto      = floor($resultado / 60);
                $resultado      = $resultado - ($min_ponto * 60);
                $secs_ponto     = $resultado;
                $tempo          = $hora_ponto.":".$min_ponto.':00';
                if( $hora_ponto > 240){
                //if ($tempo >  '240:00:00') {
                    $mensagemMes =  'Horas informadas ultrapassam às 240 horas mensais';
                    return redirect('/lancamento/createMes')->with('mensagemMes', $mensagemMes);
                }
            }

            if (isset($horasMes)) {
                $banco          = $horasMes;
                $hora1          = explode(":", $banco);
                $hora2          = explode(":", $tempo);
                $acumulador1    = ($hora1[0] * 3600) + ($hora1[1] * 60);
                $acumulador2    = ($hora2[0] * 3600) + ($hora2[1] * 60);
                $resultado      = $acumulador2 + $acumulador1;
                $hora_ponto     = floor($resultado / 3600);
                $resultado      = $resultado - ($hora_ponto * 3600);
                $min_ponto      = floor($resultado / 60);
                $resultado      = $resultado - ($min_ponto * 60);
                $secs_ponto     = $resultado;
                $tempo          = $hora_ponto.":".$min_ponto.':00';

                if( $hora_ponto > 240){
                //if ($tempo > $horafixa) {
                    $mensagemMes =  'Horas informadas somadas com as horas no banco ultrapassam às 240 horas mensais';
                    return redirect('/lancamento/createMes')->with('mensagemMes', $mensagemMes);
                }
            }
       
            // GRAVAR NO BANCO
            for ($i = 0; $i < $count; $i++) {
                $horas = $field['horas'][$i];
                $minutos = $request['minutos'][$i];

                if (($field['horas'][$i] == '') and ($request['minutos'][$i] == '')) {
                    continue;
                }
                if (($field['horas'][$i] == '')) {
                    $horas = '00';
                }
                if (($field['minutos'][$i] == '')) {
                    $minutos = '00';
                }

                $quantidadeMes  = $horas.':'.$minutos;
                Lancamento::create([
                    'quantidadehorasmes'=> $quantidadeMes,
                    'quantidadehoras'   => '00:00:00',
                    'tipo'              => 'mes',
                    'data'              => Now(),
                    'usuarios_id'       => Auth::user()->id,
                    'projetos_id'       => $field['projetos'][$i],
                    'periodos_id'       => $field['periodos'],
                ]);

                $projetoUsuario = ProjetoUsuario::where('usuarios_id', Auth::user()->id)->where('projetos_id', $field['projetos'][$i])->first();
            
                if (!isset($projetoUsuario)) {
                    ProjetoUsuario::create([
                    'usuarios_id'       => Auth::user()->id,
                    'projetos_id'       => $field['projetos'][$i],
                ]);
                }
            }

            return redirect('/lancamento/createMes')->with('sucesso', ' Horas Mensais lançadas com sucesso!');
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


// $sql = Mes::create([
//     'ano'           => $request->ano,
//     'janeiro'       => $field['janeiro'][$i],
//     'fevereiro'     => $field['fevereiro'][$i],
//     'marco'         => $field['marco'][$i],
//     'abril'         => $field['abril'][$i],
//     'maio'          => $field['maio'][$i],
//     'junho'         => $field['junho'][$i],
//     'julho'         => $field['julho'][$i],
//     'agosto'        => $field['agosto'][$i],
//     'setembro'      => $field['setembro'][$i],
//     'outubro'       => $field['outubro'][$i],
//     'novembro'      => $field['novembro'][$i],
//     'dezembro'      => $field['dezembro'][$i],
//     'status'        => $status,
//     'usuarios_id'   => Auth::user()->id,
//     'projetos_id'   => $field['projetos'][$i],
//     'periodos_id'   => 240,
//  ]);

// return $sql;



                            @else
                                <th colspan="13">
                                    <input type="text" name="projetos[]" value=""
                                        class="form-control {{ $errors->has('projeto') ? 'is-invalid' : '' }}" readonly
                                        placeholder="Nenhum projeto atribuido">
                                </th>
                            @endif