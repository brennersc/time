<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjetoUsuario extends Model
{
    protected $table = 'projetousuario';

    public $primaryKey = 'id';

    protected $keyType = 'bigInteger';

    protected $casts = [
        'id'            => 'integer',
        'usuarios_id'   => 'integer',
        'projetos_id'   => 'longtext',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',        
    ];

    protected $fillable = [      
        'id',
        'usuarios_id',
        'projetos_id',
    ];

    public function projeto()
    {
        return $this->belongsTo('\App\Projeto', 'projetos_id', 'id');
    }

    public function usuario()
    {
        return $this->belongsTo('\App\User', 'usuarios_id', 'id');
    }
}
