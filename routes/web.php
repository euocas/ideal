<?php

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\EsqueciSenhaController;
use App\Controllers\FuncionariosController;
use App\Controllers\VeiculosController;
use App\Controllers\ClientesController;
use App\Controllers\ObrasController;
use App\Controllers\FinanceirosController;
use App\Controllers\RelatoriosController;
use App\Controllers\CredenciaisController;
use App\Controllers\FinanceiroAutomovelController;
use App\Controllers\FinanceiroFuncionarioController;
use App\Controllers\FinanceiroObraController;

return [
    'login' => [AuthController::class, 'index'],
    'logar' => [AuthController::class, 'login'],
    'logout' => [AuthController::class, 'logout'],
    'dashboard' => [DashboardController::class, 'index'],
    'esqueci-senha' => [EsqueciSenhaController::class, 'index'],
    'esqueci-senha/enviar' => [EsqueciSenhaController::class, 'enviarCodigo'],
    'esqueci-senha/verificar' => [EsqueciSenhaController::class, 'telaVerificarCodigo'],
    'esqueci-senha/reenviar' => [EsqueciSenhaController::class, 'reenviarCodigo'],
    'esqueci-senha/validar' => [EsqueciSenhaController::class, 'validarCodigo'],
    'esqueci-senha/nova-senha' => [EsqueciSenhaController::class, 'telaNovaSenha'],
    'redefinir-senha' => [EsqueciSenhaController::class, 'redefinir'],

    'funcionarios' => [FuncionariosController::class, 'index'],
    'funcionarios/create' => [FuncionariosController::class, 'create'],
    'funcionarios/edit' => [FuncionariosController::class, 'edit'],
    'funcionarios/store' => [FuncionariosController::class, 'store'],
    'funcionarios/update' => [FuncionariosController::class, 'update'],
    'funcionarios/delete' => [FuncionariosController::class, 'delete'],

    'veiculos' => [VeiculosController::class, 'index'],
    'veiculos/create' => [VeiculosController::class, 'create'],
    'veiculos/edit' => [VeiculosController::class, 'edit'],
    'veiculos/store' => [VeiculosController::class, 'store'],
    'veiculos/update' => [VeiculosController::class, 'update'],
    'veiculos/delete' => [VeiculosController::class, 'delete'],

    'clientes' => [ClientesController::class, 'index'],
    'clientes/create' => [ClientesController::class, 'create'],
    'clientes/edit' => [ClientesController::class, 'edit'],
    'clientes/store' => [ClientesController::class, 'store'],
    'clientes/update' => [ClientesController::class, 'update'],
    'clientes/delete' => [ClientesController::class, 'delete'],

    'obras' => [ObrasController::class, 'index'],
    'obras/create' => [ObrasController::class, 'create'],
    'obras/edit' => [ObrasController::class, 'edit'],
    'obras/store' => [ObrasController::class, 'store'],
    'obras/update' => [ObrasController::class, 'update'],
    'obras/delete' => [ObrasController::class, 'delete'],

    'financeiros' => [FinanceirosController::class, 'index'],

    // Rotas de Financeiro -> Funcionário
    'financeiro-funcionario' => [FinanceiroFuncionarioController::class, 'index'],
    'financeiro-funcionario/buscar' => [FinanceiroFuncionarioController::class, 'buscarFuncionario'],
    'financeiro-funcionario/visualizar' => [FinanceiroFuncionarioController::class, 'visualizar'],
    'financeiro-funcionario/create' => [FinanceiroFuncionarioController::class, 'create'],
    'financeiro-funcionario/store' => [FinanceiroFuncionarioController::class, 'store'],
    'financeiro-funcionario/update' => [FinanceiroFuncionarioController::class, 'update'],
    'financeiro-funcionario/delete' => [FinanceiroFuncionarioController::class, 'delete'],

    // Rotas de Financeiro -> Obra
    // 'financeiros/createObra' => [FinanceirosController::class, 'createObra'],
    // 'financeiros/editObra' => [FinanceirosController::class, 'editObra'],
    // 'financeiros/storeObra' => [FinanceirosController::class, 'storeObra'],
    // 'financeiros/updateObra' => [FinanceirosController::class, 'updateObra'],
    // 'financeiros/deleteObra' => [FinanceirosController::class, 'deleteObra'],


    // Rotas do Financeiro -> Obra
    'financeiro-obra' => [FinanceiroObraController::class, 'index'],
    'financeiro-obra/buscar' => [FinanceiroObraController::class, 'buscarObra'],
    'financeiro-obra/visualizar' => [FinanceiroObraController::class, 'visualizar'],
    'financeiro-obra/create' => [FinanceiroObraController::class, 'create'],
    'financeiro-obra/store' => [FinanceiroObraController::class, 'store'],
    'financeiro-obra/update' => [FinanceiroObraController::class, 'update'],
    'financeiro-obra/delete' => [FinanceiroObraController::class, 'delete'],


    // Rotas de Financeiro -> Automóvel
    'financeiro-automovel' => [FinanceiroAutomovelController::class, 'index'],
    'financeiro-automovel/buscar' => [FinanceiroAutomovelController::class, 'buscarVeiculo'],
    'financeiro-automovel/visualizar' => [FinanceiroAutomovelController::class, 'visualizar'],
    'financeiro-automovel/create' => [FinanceiroAutomovelController::class, 'create'],
    'financeiro-automovel/store' => [FinanceiroAutomovelController::class, 'store'],
    'financeiro-automovel/update' => [FinanceiroAutomovelController::class, 'update'],
    'financeiro-automovel/delete' => [FinanceiroAutomovelController::class, 'delete'],

    'financeiro-obra/historico' => [FinanceiroObraController::class, 'historico'],

    'relatorios' => [RelatoriosController::class, 'index'],



    'credenciais' => [CredenciaisController::class, 'index'],
    'credenciais/alterar' => [CredenciaisController::class, 'alterar'],
    'clientes/buscarPorCnpj' => [ClientesController::class, 'buscarPorCnpj'],


];