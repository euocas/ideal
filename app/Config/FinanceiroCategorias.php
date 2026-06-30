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
        '13º Salário',
        'Hora Extra',
        'Adicional Noturno',
        'Periculosidade',
        'Insalubridade',
        'Comissão',
        'Bônus',
        'PLR (Participação nos Lucros)',
        'Ajuda de Custo',
        'Outros'
    ];

    public const DESCONTOS = [
        'INSS',
        'IRRF',
        'Vale Transporte',
        'Vale Refeição',
        'Vale Alimentação',
        'Plano de Saúde',
        'Plano Odontológico',
        'Empréstimo',
        'Adiantamento Salarial',
        'Faltas',
        'Atrasos',
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
        'Transferência',
        'Depósito',
        'Cartão de Débito',
        'Cartão de Crédito',
        'Boleto',
        'Cheque'
    ];



    
}