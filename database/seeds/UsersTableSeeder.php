<?php

use Illuminate\Database\Seeder;
use App\Periodo;
use App\User;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $periodo = [[
            'id'  => 1,
            'periodo'  => 1,
            'mes' => 'janeiro',
            'ano' => '2020'
        ],
        [
            'id'  => 2,
            'periodo'  => 2,
            'mes' => 'fevereiro',
            'ano' => '2020'
        ],
        [
            'id'  => 3,
            'periodo'  => 3,
            'mes' => 'março',
            'ano' => '2020'
        ],
        [
            'id'  => 4,
            'periodo'  => 4,
            'mes' => 'abril',
            'ano' => '2020'
        ],
        [
            'id'  => 5,
            'periodo'  => 5,
            'mes' => 'maio',
            'ano' => '2020'
        ],
        [
            'id'  => 6,
            'periodo'  => 6,
            'mes' => 'junho',
            'ano' => '2020'
        ],
        [
            'id'  => 7,
            'periodo'  => 7,
            'mes' => 'julho',
            'ano' => '2020'
        ],
        [
            'id'  => 8,
            'periodo'  => 8,
            'mes' => 'agosto',
            'ano' => '2020'
        ],
        [
            'id'  => 9,
            'periodo'  => 9,
            'mes' => 'setembro',
            'ano' => '2020'
        ],
        [
            'id'  => 10,
            'periodo'  => 10,
            'mes' => 'outubro',
            'ano' => '2020'
        ],
        [
            'id'  => 11,
            'periodo'  => 11,
            'mes' => 'novembro',
            'ano' => '2020'
        ],
        [
            'id'  => 12,
            'periodo'  => 12,
            'mes' => 'dezembro',
            'ano' => '2020'
        ]];

        Periodo::insert($periodo);

        $usuario = [
            [
                'usuario'       => 'brennersc',
                'nome'          => 'Brenner Silva Cunha',
                'email'         => 'brenner@gmail.com',
                'filial'        => '1234',
                'matricula'     => '1234',
                'administrador'  => true,
                'password'      => '$2y$10$vJfykP7PRwKsbv5aYF7VzOFo/IptsfK3Az9NmHFzt7trovmQNLtQC',
            ],
            [
                'usuario'       => 'admin',
                'nome'          => 'administrador',
                'email'         => 'admin@admin.com',
                'filial'        => '1234',
                'matricula'     => '1234',
                'administrador'  => true,
                'password'      => '$2y$10$vJfykP7PRwKsbv5aYF7VzOFo/IptsfK3Az9NmHFzt7trovmQNLtQC',
            ],
            [
                'usuario'       => 'usuario',
                'nome'          => 'usuario teste',
                'email'         => 'usuario@usuario.com',
                'filial'        => '1234',
                'matricula'     => '1234',
                'administrador'  => false,
                'password'      => '$2y$10$vJfykP7PRwKsbv5aYF7VzOFo/IptsfK3Az9NmHFzt7trovmQNLtQC',
            ],
            [
                'usuario'       => 'teste',
                'nome'          => 'teste teste',
                'email'         => 'teste@usuario.com',
                'filial'        => '1234',
                'matricula'     => '1234',
                'administrador'  => false,
                'password'      => '$2y$10$vJfykP7PRwKsbv5aYF7VzOFo/IptsfK3Az9NmHFzt7trovmQNLtQC',
            ],
        ];

        User::insert($usuario);

    }
}
