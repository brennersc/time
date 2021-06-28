<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Periodo extends Model
{
    protected $table = 'periodos';

    public $primaryKey = 'id';

    protected $keyType = 'bigInteger';

    protected $casts = [
        'id'            => 'integer',
        'periodo'       => 'integer',
        'mes'           => 'string',
        'ano'           => 'year',
        'status'        => 'boolean',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',        
    ];

    protected $fillable = [      
        'id',
        'periodo',
        'mes',
        'ano',
        'status'
    ];
    public function lancamento()
    {
        return $this->hasMany('\App\Lancamento', 'periodos_id', 'id');
    }
}
