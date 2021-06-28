<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';

    public $primaryKey = 'id';

    protected $keyType = 'bigInteger';

    protected $fillable = [
        'usuario',
        'nome', 
        'email',
        'filial',
        'matricula',
        'status',
        'administrador',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                => 'integer',
        'usuario'           => 'string',
        'nome'              => 'string',
        'email'             => 'string',
        'filial'            => 'string',
        'matricula'         => 'integer',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',    
        'email_verified_at' => 'datetime',
    ];

    public function ProjetoUsuario()
    {
        return $this->belongsTo('\App\ProjetoUsuario', 'usuarios_id', 'id');
    }

    public function lancamento()
    {
        return $this->hasMany('\App\Lancamento', 'usuarios_id', 'id');
    }
}
