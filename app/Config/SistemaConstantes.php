<?php

namespace App\Config;

class SistemaConstantes
{
    public const MESES = [
        1 => 'Janeiro',
        2 => 'Fevereiro',
        3 => 'Março',
        4 => 'Abril',
        5 => 'Maio',
        6 => 'Junho',
        7 => 'Julho',
        8 => 'Agosto',
        9 => 'Setembro',
        10 => 'Outubro',
        11 => 'Novembro',
        12 => 'Dezembro',
    ];

    public const ESTADOS = [
        'AC' => 'Acre',
        'AL' => 'Alagoas',
        'AP' => 'Amapá',
        'AM' => 'Amazonas',
        'BA' => 'Bahia',
        'CE' => 'Ceará',
        'DF' => 'Distrito Federal',
        'ES' => 'Espírito Santo',
        'GO' => 'Goiás',
        'MA' => 'Maranhão',
        'MT' => 'Mato Grosso',
        'MS' => 'Mato Grosso do Sul',
        'MG' => 'Minas Gerais',
        'PA' => 'Pará',
        'PB' => 'Paraíba',
        'PR' => 'Paraná',
        'PE' => 'Pernambuco',
        'PI' => 'Piauí',
        'RJ' => 'Rio de Janeiro',
        'RN' => 'Rio Grande do Norte',
        'RS' => 'Rio Grande do Sul',
        'RO' => 'Rondônia',
        'RR' => 'Roraima',
        'SC' => 'Santa Catarina',
        'SP' => 'São Paulo',
        'SE' => 'Sergipe',
        'TO' => 'Tocantins'
    ];

    public const SEXOS = [
        'Masculino',
        'Feminino',
        'Outro'
    ];
    public const STATUS = [
        'ativo' => 'Ativo',
        'inativo' => 'Inativo'
    ];

    public const CATEGORIAS_OBRA = [
        "Material",
        "Alimentação",
        "Transporte",
        "Hospedagem",
        "Equipamento",
        "Serviço",
        "Outros",

    ];

    public const FORMAS_PAGAMENTO = [
        "PIX",
        "Boleto",
        "Cartão",
        "Transferência",
    ];

    public const CATEGORIAS_FIN_AUTO = [
        "Aquisição de veículo",
        "Combustível",
        "IPVA",
        "Licenciamento",
        "Seguro",
        "Multa",
        "Pedágio",
        "Manutenção",
        "Outros",
    ];

    public const RECEBIMENTOS_FIN_AUTO = [
        "Reembolso",
        "Venda de peças",
        "Venda de pneus",
        "Venda do veículo",
        "Indenização de seguro",
        "Outros recebimentos",
    ];
}