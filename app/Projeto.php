<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Projeto extends Model
{

    protected $table = 'projetos';

    public $primaryKey = 'id';

    protected $keyType = 'bigInteger';

    protected $casts = [
        'id'            => 'integer',
        'codigo'        => 'integer',
        'descricao'     => 'longtext',
        'matricula'     => 'string',
        'data_inicio'   => 'date',
        'data_fim'      => 'date',
        'centrodecusto' => 'string',
        'status'        => 'boolean',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',        
    ];

    protected $fillable = [      
        'id',
        'nome',
        'codigo',
        'descricao',
        'matricula',
        'data_inicio',
        'data_fim',
        'centrodecusto',
        'status'
    ];

    public function projetousuario()
    {
        return $this->belongsToMany('\App\ProjetoUsuario', 'projeto_id', 'id');
    }

    public function lancamento()
    {
        return $this->hasMany('\App\Lancamento', 'projetos_id', 'id');
    }

}
