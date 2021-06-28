<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mes extends Model
{
    protected $table = 'mes';

    public $primaryKey = 'id';

    protected $keyType = 'bigInteger';

    protected $casts = [
        'id'        => 'integer',
        'ano'       => 'string',
        'janeiro'   => 'string',
        'fevereiro' => 'string',
        'marco'     => 'string',
        'abril'     => 'string',
        'maio'      => 'string',
        'junho'     => 'string',
        'julho'     => 'string',
        'agosto'    => 'string',
        'setembro'  => 'string',
        'outubro'   => 'string',
        'novembro'  => 'string',
        'dezembro'  => 'string',
        'status'    => 'string',
        'usuarios_id'   => 'integer',
        'projetos_id'   => 'integer',
        'periodos_id'   => 'integer',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',        
    ];

    protected $fillable = [      
        'id',
        'ano',
        'janeiro',
        'fevereiro',
        'março',
        'abril',
        'maio',
        'junho',
        'julho',
        'agosto',
        'setembro',
        'outubro',
        'novembro',
        'dezembro',
        'status',
        'usuarios_id',
        'projetos_id',
        'periodos_id'
    ];
}
