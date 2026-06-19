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
use App\Controllers\FinanceiroObraController;

return [
    'login'          => [AuthController::class, 'index'],
    'logar'          => [AuthController::class, 'login'],
    'logout'         => [AuthController::class, 'logout'],
    'dashboard'      => [DashboardController::class, 'index'],
    'esqueci-senha'  => [EsqueciSenhaController::class, 'index'],
    'redefinir-senha'=> [EsqueciSenhaController::class, 'redefinir'],

    'funcionarios'        => [FuncionariosController::class, 'index'],
    'funcionarios/create' => [FuncionariosController::class, 'create'],
    'funcionarios/edit'   => [FuncionariosController::class, 'edit'],
    'funcionarios/store'  => [FuncionariosController::class, 'store'],
    'funcionarios/update' => [FuncionariosController::class, 'update'],
    'funcionarios/delete' => [FuncionariosController::class, 'delete'],

    'veiculos'        => [VeiculosController::class, 'index'],
    'veiculos/create' => [VeiculosController::class, 'create'],
    'veiculos/edit'   => [VeiculosController::class, 'edit'],
    'veiculos/store'  => [VeiculosController::class, 'store'],
    'veiculos/update' => [VeiculosController::class, 'update'],
    'veiculos/delete' => [VeiculosController::class, 'delete'],

    'clientes'                => [ClientesController::class, 'index'],
    'clientes/create'         => [ClientesController::class, 'create'],
    'clientes/edit'           => [ClientesController::class, 'edit'],
    'clientes/store'          => [ClientesController::class, 'store'],
    'clientes/update'         => [ClientesController::class, 'update'],
    'clientes/delete'         => [ClientesController::class, 'delete'],
    'clientes/buscarPorCnpj'  => [ClientesController::class, 'buscarPorCnpj'], // ✅ NOVA ROTA

    'obras'        => [ObrasController::class, 'index'],
    'obras/create' => [ObrasController::class, 'create'],
    'obras/edit'   => [ObrasController::class, 'edit'],
    'obras/store'  => [ObrasController::class, 'store'],
    'obras/update' => [ObrasController::class, 'update'],
    'obras/delete' => [ObrasController::class, 'delete'],

    'financeiros' => [FinanceirosController::class, 'index'],

    'financeiros/createFuncionario' => [FinanceirosController::class, 'createFuncionario'],
    'financeiros/editFuncionario'   => [FinanceirosController::class, 'editFuncionario'],
    'financeiros/storeFuncionario'  => [FinanceirosController::class, 'storeFuncionario'],
    'financeiros/updateFuncionario' => [FinanceirosController::class, 'updateFuncionario'],
    'financeiros/deleteFuncionario' => [FinanceirosController::class, 'deleteFuncionario'],

    'financeiros/createObra' => [FinanceirosController::class, 'createObra'],
    'financeiros/editObra'   => [FinanceirosController::class, 'editObra'],
    'financeiros/storeObra'  => [FinanceirosController::class, 'storeObra'],
    'financeiros/updateObra' => [FinanceirosController::class, 'updateObra'],
    'financeiros/deleteObra' => [FinanceirosController::class, 'deleteObra'],

    'financeiros/createAutomovel' => [FinanceirosController::class, 'createAutomovel'],
    'financeiros/editAutomovel'   => [FinanceirosController::class, 'editAutomovel'],
    'financeiros/storeAutomovel'  => [FinanceirosController::class, 'storeAutomovel'],
    'financeiros/updateAutomovel' => [FinanceirosController::class, 'updateAutomovel'],
    'financeiros/deleteAutomovel' => [FinanceirosController::class, 'deleteAutomovel'],

    'relatorios'     => [RelatoriosController::class, 'index'],

    'financeiro-obra'        => [FinanceiroObraController::class, 'index'],
    'financeiro-obra/create' => [FinanceiroObraController::class, 'create'],
    'financeiro-obra/store'  => [FinanceiroObraController::class, 'store'],
    'financeiro-obra/edit'   => [FinanceiroObraController::class, 'edit'],
    'financeiro-obra/update' => [FinanceiroObraController::class, 'update'],
    'financeiro-obra/delete' => [FinanceiroObraController::class, 'delete'],

    'credenciais'         => [CredenciaisController::class, 'index'],
    'credenciais/buscar'  => [CredenciaisController::class, 'buscar'],
    'credenciais/alterar' => [CredenciaisController::class, 'alterar'],
];