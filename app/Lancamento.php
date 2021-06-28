<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lancamento extends Model
{
    protected $table = 'lancamentos';

    public $primaryKey = 'id';

    protected $keyType = 'bigInteger';

    protected $casts = [
        'id'                => 'integer',
        'quantidadehoras'   => 'time',
        'quantidadehorasmes'=> 'string',
        'tipo'              => 'string',
        'data'              => 'date',
        'usuarios_id'       => 'integer',
        'projetos_id'       => 'integer',
        'periodos_id'       => 'integer',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',        
    ];

    protected $fillable = [      
        'id',
        'tipo',
        'data',
        'quantidadehoras',
        'quantidadehorasmes',
        'usuarios_id',
        'projetos_id',
        'periodos_id',
    ];
    
    public function usuario()
    {
        return $this->belongsTo('\App\User', 'usuarios_id', 'id');
    }
    public function periodo()
    {
        return $this->belongsTo('\App\Periodo', 'periodos_id', 'id');
    }
    public function projeto()
    {
        return $this->belongsTo('\App\Projeto', 'projetos_id', 'id');
    }
}
