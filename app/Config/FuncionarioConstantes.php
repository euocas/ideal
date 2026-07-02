<?php

namespace App\Config;

class FuncionarioConstantes
{
    //  * ==========================================================
    //  * TIPOS DE CONTRATO
    //  * ==========================================================
    public const TIPOS_CONTRATO = [
        'CLT' => 'CLT',
        'CONTRATO TEMPORARIO' => 'Contrato Temporário',
        'PESSOA JURÍDICA' => 'Pessoa Jurídica',
        'TERCEIRIZADO' => 'Terceirizado'
    ];

    //  * ==========================================================
    //  * CARGOS
    //  * ==========================================================
    public const CARGOS = [
        'Almoxarife',
        'Analista Financeiro',
        'Auxiliar Administrativo',
        'Auxiliar de Eletricista',
        'Cabista',
        'Eletricista de Manutenção',
        'Eletricista Industrial',
        'Eletricista Montador',
        'Eletricista Predial',
        'Encarregado de Obras Elétricas',
        'Instalador Elétrico',
        'Mestre de Obras',
        'Montador de Painéis Elétricos',
        'Oficial Eletricista',
        'Recursos Humanos',
    ];

    //  * ==========================================================
    //  * TIPOS DE CONTA
    //  * ==========================================================

    public const TIPOS_CONTA = [
        'CORRENTE' => 'Corrente',
        'POUPANCA' => 'Poupança',
        'SALARIO'  => 'Salário',
    ];
}