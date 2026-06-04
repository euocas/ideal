<!-- HEADER PHP -->
<?php

/*
|--------------------------------------------------------------------------
| TÍTULO
|--------------------------------------------------------------------------
*/

$titulo = 'Relatórios';
require_once __DIR__ . '/../includes/header.php';

/*
|--------------------------------------------------------------------------
| RELATÓRIO SELECIONADO
|--------------------------------------------------------------------------
*/
$relatorio = $_GET['relatorio'] ?? 'funcionarios';

$tiposRelatorios = [

    'clientes' => 'Clientes',
    'funcionarios' => 'Funcionários',
    'obras' => 'Obras',
    'veiculos' => 'Veículos',
    'financeiro' => 'Financeiro'

];

/*
|--------------------------------------------------------------------------
| VALIDA RELATÓRIO
|--------------------------------------------------------------------------
*/

if (!array_key_exists($relatorio, $tiposRelatorios)) {

    $relatorio = 'funcionarios';
}

$tipoSelecionado = $tiposRelatorios[$relatorio];

?>

<link rel="shortcut icon" href="/ideal/public/assets/icons/financeiro3.png" type="image/x-icon">
<link rel="stylesheet" href="/ideal/public/assets/css/dashboard.css">
<link rel="stylesheet" href="/ideal/public/assets/css/relatorios.css?v=<?= time() ?>">
</head>

<body>

    <div class="dashboard-container">

        <!-- SIDEBAR -->
        <?php include __DIR__ . '/../includes/sidebar.php'; ?>

        <!-- CONTEÚDO -->
        <main class="main-content">

            <div class="relatorios-container">

                <!-- TOPO -->
                <div class="titulo-pagina">
                    <h1>Relatórios</h1>
                    <p>
                        Gere e visualize relatórios personalizados do sistema.
                    </p>
                </div>

                <div class="conteudo-relatorios">

                    <!-- PAINEL -->
                    <section class="painel-relatorio">

                        <!-- FILTROS -->
                        <div class="card card-filtros">

                            <h2>
                                Filtros do Relatório -
                                <span><?= $tipoSelecionado ?></span>
                            </h2>

                            <!-- FORM DINÂMICO -->
                            <div class="filtros-grid">

                                <?php if ($relatorio == 'clientes'): ?>

                                    <!-- CLIENTES -->
                                    <div class="campo">
                                        <label>Nome do Cliente</label>
                                        <input type="text" placeholder="Digite o nome">
                                    </div>

                                    <div class="campo">
                                        <label>CPF</label>
                                        <input type="text" placeholder="000.000.000-00">
                                    </div>

                                    <div class="campo">
                                        <label>CNPJ</label>
                                        <input type="text" placeholder="00.000.000/0000-00">
                                    </div>

                                    <div class="campo">
                                        <label>Status</label>

                                        <select>
                                            <option>Selecione</option>
                                            <option>Ativo</option>
                                            <option>Inativo</option>
                                        </select>
                                    </div>

                                <?php elseif ($relatorio == 'funcionarios'): ?>

                                    <!-- FUNCIONÁRIOS -->
                                    <div class="campo">
                                        <label>Nome</label>
                                        <input type="text" placeholder="Digite o nome">
                                    </div>

                                    <div class="campo">
                                        <label>CPF</label>
                                        <input type="text" placeholder="000.000.000-00">
                                    </div>

                                    <div class="campo">
                                        <label>Status</label>

                                        <select>
                                            <option>Selecione</option>
                                            <option>Ativo</option>
                                            <option>Inativo</option>
                                        </select>
                                    </div>

                                <?php elseif ($relatorio == 'veiculos'): ?>

                                    <!-- VEÍCULOS -->
                                    <div class="campo">
                                        <label>Placa</label>
                                        <input type="text" placeholder="ABC-1234">
                                    </div>

                                    <div class="campo">
                                        <label>Renavam</label>
                                        <input type="text" placeholder="Digite o renavam">
                                    </div>

                                    <div class="campo">
                                        <label>Status</label>

                                        <select>
                                            <option>Selecione</option>
                                            <option>Disponível</option>
                                            <option>Em uso</option>
                                            <option>Manutenção</option>
                                        </select>
                                    </div>

                                <?php elseif ($relatorio == 'obras'): ?>
                                    <!-- OBRAS -->

                                    <div class="campo">
                                        <label>Nome da Obra</label>
                                        <input type="text" placeholder="Digite o nome da obra">
                                    </div>

                                    <div class="campo">
                                        <label>Cidade</label>
                                        <input type="text" placeholder="Digite a cidade">
                                    </div>

                                    <div class="campo">
                                        <label>Status</label>

                                        <select>
                                            <option>Selecione</option>
                                            <option>Em andamento</option>
                                            <option>Finalizada</option>
                                        </select>
                                    </div>

                                <?php elseif ($relatorio == 'financeiro'): ?>

                                    <!-- FINANCEIRO -->
                                    <div class="campo">
                                        <label>Data Inicial</label>
                                        <input type="date">
                                    </div>

                                    <div class="campo">
                                        <label>Data Final</label>
                                        <input type="date">
                                    </div>

                                    <div class="campo">
                                        <label>Tipo</label>

                                        <select>
                                            <option>Selecione</option>
                                            <option>Entrada</option>
                                            <option>Saída</option>
                                        </select>
                                    </div>

                                <?php endif; ?>

                            </div>

                            <!-- BOTÕES -->
                            <div class="acoes-filtros">

                                <button type="reset" class="btn-limpar">
                                    LIMPAR
                                </button>

                                <button type="submit" name="acao" value="filtrar" class="btn-filtrar">
                                    FILTRAR
                                </button>

                                <button type="submit" name="acao" value="todos" class="btn-todos">
                                    GERAR TODOS
                                </button>
                            </div>

                            <!-- PRÉ-VISUALIZAÇÃO -->
                            <div class="card card-preview">

                                <h2>
                                    Pré-visualização do Relatório
                                </h2>

                                <div class="alerta-preview">

                                    Exibindo pré-visualização dos dados que serão exportados.

                                </div>

                                <!-- TABELA DINÂMICA -->
                                <div class="tabela-container">

                                    <table>

                                        <thead>

                                            <?php if ($relatorio == 'clientes'): ?>

                                                <tr>
                                                    <th>ID</th>
                                                    <th>Nome</th>
                                                    <th>CPF</th>
                                                    <th>CNPJ</th>
                                                    <th>Status</th>
                                                </tr>

                                            <?php elseif ($relatorio == 'funcionarios'): ?>

                                                <tr>
                                                    <th>ID</th>
                                                    <th>Nome</th>
                                                    <th>CPF</th>
                                                    <th>Status</th>
                                                </tr>

                                            <?php elseif ($relatorio == 'veiculos'): ?>

                                                <tr>
                                                    <th>ID</th>
                                                    <th>Placa</th>
                                                    <th>Renavam</th>
                                                    <th>Status</th>
                                                </tr>

                                            <?php elseif ($relatorio == 'obras'): ?>

                                                <tr>
                                                    <th>ID</th>
                                                    <th>Nome da Obra</th>
                                                    <th>Cidade</th>
                                                    <th>Status</th>
                                                </tr>

                                            <?php elseif ($relatorio == 'financeiro'): ?>

                                                <tr>
                                                    <th>ID</th>
                                                    <th>Tipo</th>
                                                    <th>Valor</th>
                                                    <th>Data</th>
                                                </tr>

                                            <?php endif; ?>

                                        </thead>

                                        <tbody>

                                            <tr>

                                                <td>1</td>

                                                <td>Exemplo</td>

                                                <td>Informação</td>

                                                <td>Ativo</td>

                                            </tr>

                                        </tbody>

                                    </table>

                                </div>

                                <!-- FOOTER -->
                                <div class="footer-preview">

                                    <p>
                                        Exibindo registros do relatório de <?= $tipoSelecionado ?>
                                    </p>

                                    <div class="acoes-exportar">

                                        <button class="btn-excel">
                                            EXPORTAR EXCEL
                                        </button>

                                        <button class="btn-pdf">
                                            GERAR PDF
                                        </button>

                                    </div>

                                </div>

                            </div>

                    </section>

                    <!-- SIDEBAR -->
                    <aside class="sidebar-relatorios">

                        <!-- CLIENTES -->
                        <a href="/ideal/public/index.php?url=relatorios&relatorio=clientes" class="card-relatorio">

                            <img src="/ideal/public/assets/icons/clientes.png" alt="Clientes">

                            <div>

                                <h3>RELATÓRIO DE CLIENTES</h3>

                                <p>
                                    Relatório completo com todos os clientes cadastrados.
                                </p>

                            </div>

                        </a>

                        <!-- FUNCIONÁRIOS -->
                        <a href="/ideal/public/index.php?url=relatorios&relatorio=funcionarios" class="card-relatorio">

                            <img src="/ideal/public/assets/icons/funcionario2.png" alt="Funcionários">

                            <div>

                                <h3>RELATÓRIO DE FUNCIONÁRIOS</h3>

                                <p>
                                    Relatório com todos os funcionários cadastrados.
                                </p>

                            </div>

                        </a>

                        <!-- OBRAS -->
                        <a href="/ideal/public/index.php?url=relatorios&relatorio=obras" class="card-relatorio">

                            <img src="/ideal/public/assets/icons/obra2.png" alt="Obras">

                            <div>

                                <h3>RELATÓRIO DE OBRAS</h3>

                                <p>
                                    Relatório com informações das obras cadastradas.
                                </p>

                            </div>

                        </a>

                        <!-- VEÍCULOS -->
                        <a href="/ideal/public/index.php?url=relatorios&relatorio=veiculos" class="card-relatorio">

                            <img src="/ideal/public/assets/icons/veiculo.png" alt="Veículos">

                            <div>

                                <h3>RELATÓRIO DE VEÍCULOS</h3>

                                <p>
                                    Relatório com informações dos veículos cadastrados.
                                </p>

                            </div>

                        </a>

                        <!-- FINANCEIRO -->
                        <a href="/ideal/public/index.php?url=relatorios&relatorio=financeiro" class="card-relatorio">

                            <img src="/ideal/public/assets/icons/financeiro3.png" alt="Financeiro">

                            <div>

                                <h3>RELATÓRIO FINANCEIRO</h3>

                                <p>
                                    Relatório com entradas e saídas financeiras.
                                </p>

                            </div>

                        </a>

                    </aside>

                </div>

            </div>

        </main>

    </div>

</body>

</html>