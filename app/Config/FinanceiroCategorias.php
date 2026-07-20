<?php

namespace App\Config;

class FinanceiroCategorias
{
    /**
     * ==========================================================
     * FINANCEIRO - FUNCIONÁRIOS
     * ==========================================================
     */

    public const PROVENTOS = [
        'Salário',
        'Férias',
        'Horas Extras',
        'Periculosidade',
        '13º Salário',
        'Insalubridade',
        'Comissão',
        'Bônus',
        'Ajuda de Custo',
        'Adicional Noturno',
        'Participação nos Lucros',
        'Outros'
    ];

    public const DESCONTOS = [
        'INSS',
        'IRRF',
        'Faltas',
        'Atrasos',
        'Vale Transporte',
        'Plano de Saúde',
        'Plano Odontológico',
        'Adiantamento Salarial',
        'Pensão Alimentícia',
        'Outros'
    ];

    /**
     * ==========================================================
     * FINANCEIRO - OBRAS
     * ==========================================================
     */

    public const ENTRADAS_OBRA = [
        'Pagamento do Cliente',
        'Medição',
        'Aditivo Contratual',
        'Reembolso',
        'Outros'
    ];

    public const SAIDAS_OBRA = [
        'Material',
        'Equipamentos',
        'Mão de Obra',
        'Serviços Terceirizados',
        'Ferramentas',
        'EPIs',
        'Transporte',
        'Hospedagem',
        'Alimentação',
        'Outros'
    ];

    /**
     * ==========================================================
     * FINANCEIRO - VEÍCULOS
     * ==========================================================
     */

    public const ENTRADAS_VEICULO = [
        'Venda de Veículo',
        'Reembolso',
        'Indenização',
        'Outros Recebimentos'
    ];

    public const SAIDAS_VEICULO = [
        'Compra de Veículo',
        'Combustível',
        'Manutenção',
        'IPVA',
        'Licenciamento',
        'Seguro',
        'Pneus',
        'Pedágio',
        'Estacionamento',
        'Lavagem',
        'Multas',
        'Outros'
    ];

    /**
     * ==========================================================
     * FORMAS DE PAGAMENTO
     * ==========================================================
     */

    public const FORMAS_PAGAMENTO = [
        'Dinheiro',
        'PIX',
        'TED',
        'Boleto Bancário',
        'Cartão de Débito',
        'Cartão de Crédito',
        'Folha',

    ];

    public const CONTAS_PAGAMENTO = [
    'Conta Corrente Empresa',
    'Conta Poupança Empresa',
    'Caixa',
    'PIX',
];



}