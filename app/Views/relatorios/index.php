<!--HEADER PHP-->
<?php

$titulo = 'Relatórios';
$favicon = '/ideal/public/assets/icon/relatorio.png';

require_once __DIR__ . '/../includes/header.php';

?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="/ideal/public/assets/css/dashboard.css">
<link rel="stylesheet" href="/ideal/public/assets/css/variables.css">
<link rel="stylesheet" href="/ideal/public/assets/css/relatorios.css?v=<?= time() ?>">
</head>

<body>

    <div class="dashboard-container">

        <?php include __DIR__ . '/../includes/sidebar.php'; ?>

        <main class="main-content">

            <?php if (isset($_SESSION['mensagem_sucesso'])): ?>
                <div class="alert alert-success"
                    style="background: #d4edda; color: #155724; padding: 15px; margin-bottom: 15px; border-radius: 5px; border: 1px solid #c3e6cb;">
                    ✅ <?= $_SESSION['mensagem_sucesso'];
                    unset($_SESSION['mensagem_sucesso']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['mensagem_erro'])): ?>
                <div class="alert alert-danger"
                    style="background: #f8d7da; color: #721c24; padding: 15px; margin-bottom: 15px; border-radius: 5px; border: 1px solid #f5c6cb;">
                    ❌ <?= $_SESSION['mensagem_erro'];
                    unset($_SESSION['mensagem_erro']); ?>
                </div>
            <?php endif; ?>

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

                            <form method="POST"
                                action="/ideal/public/index.php?url=relatorios&relatorio=<?= $relatorio ?>">

                                <!-- FORM DINÂMICO -->
                                <div class="filtros-grid">

                                    <?php if ($relatorio == 'clientes'): ?>

                                        <!-- CLIENTES -->
                                        <div class="campo">
                                            <label>Nome do Cliente</label>
                                            <input type="text" name="nomeCliente" placeholder="Digite o nome">
                                        </div>

                                        <div class="campo">
                                            <label>CPF</label>
                                            <input type="text" name="cpf" placeholder="000.000.000-00">
                                        </div>

                                        <div class="campo">
                                            <label>CNPJ</label>
                                            <input type="text" name="cnpj" placeholder="00.000.000/0000-00">
                                        </div>



                                    <?php elseif ($relatorio == 'funcionarios'): ?>

                                        <!-- FUNCIONÁRIOS -->
                                        <div class="campo">
                                            <label>Nome</label>
                                            <input type="text" name="nome" placeholder="Digite o nome">
                                        </div>

                                        <div class="campo">
                                            <label>CPF</label>
                                            <input type="text" name="cpf" placeholder="000.000.000-00">
                                        </div>

                                        <div class="campo">
                                            <label>Status</label>

                                            <select name="status">
                                                <option value="">Selecione</option>
                                                <option value="Ativo">Ativo</option>
                                                <option value="Inativo">Inativo</option>
                                            </select>
                                        </div>

                                    <?php elseif ($relatorio == 'veiculos'): ?>

                                        <!-- VEÍCULOS -->
                                        <div class="campo">
                                            <label>Placa</label>
                                            <input type="text" name="placa" placeholder="ABC-1234">
                                        </div>

                                        <div class="campo">
                                            <label>Renavam</label>
                                            <input type="text" name="renavam" placeholder="Digite o renavam">
                                        </div>

                                        <div class="campo">
                                            <label>Status</label>

                                            <select name="statusVeiculo">
                                                <option value="">Selecione</option>
                                                <option value="Disponível">Disponível</option>
                                                <option value="Em uso">Em uso</option>
                                                <option value="Manutenção">Manutenção</option>
                                            </select>
                                        </div>

                                    <?php elseif ($relatorio == 'obras'): ?>
                                        <!-- OBRAS -->

                                        <div class="campo">
                                            <label>Nome da Obra</label>
                                            <input type="text" name="nomeObra" placeholder="Digite o nome da obra">
                                        </div>

                                        <div class="campo">
                                            <label>Cidade</label>
                                            <input type="text" name="cidade" placeholder="Digite a cidade">
                                        </div>

                                        <div class="campo">
                                            <label>Status</label>

                                            <select name="statusObra">
                                                <option value="">Selecione</option>
                                                <option value="Em andamento">Em andamento</option>
                                                <option value="Finalizada">Finalizada</option>
                                            </select>
                                        </div>

                                    <?php elseif ($relatorio == 'financeiro'): ?>

                                        <!-- FINANCEIRO -->
                                        <div class="campo">
                                            <label>Data Inicial</label>
                                            <input type="date" name="dataInicio">
                                        </div>

                                        <div class="campo">
                                            <label>Data Final</label>
                                            <input type="date" name="dataFim">
                                        </div>

                                        <div class="campo">
                                            <label>Tipo</label>

                                            <select name="tipoFinanceiro">
                                                <option value="">Selecione</option>
                                                <option value="entrada">Entrada</option>
                                                <option value="saida">Saída</option>
                                            </select>
                                        </div>

                                    <?php endif; ?>

                                </div>

                                <!-- BOTÕES -->
                                <div class="acoes-filtros">

                                    <button type="reset" class="btn-limpar">
                                        <i class="bi bi-eraser"></i>
                                        LIMPAR
                                    </button>

                                    <button type="submit" name="acao" value="filtrar" class="btn-filtrar">
                                        <i class="bi bi-funnel"></i>
                                        FILTRAR
                                    </button>

                                    <button type="submit" name="acao" value="todos" class="btn-todos">
                                        <i class="bi bi-list-ul"></i>
                                        GERAR TODOS
                                    </button>
                                </div>
                            </form>
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
                                            <?php if (!empty($dados['dados'])): ?>
                                                <?php foreach ($dados['dados'] as $linha): ?>
                                                    <tr>
                                                        <?php if ($relatorio == 'clientes'): ?>
                                                            <td><?= htmlspecialchars($linha['idCliente'] ?? '') ?></td>
                                                            <td><?= htmlspecialchars($linha['nomeCliente'] ?? '') ?></td>
                                                            <td><?= preg_replace(
                                                                '/(\d{3})(\d{3})(\d{3})(\d{2})/',
                                                                '$1.$2.$3-$4',
                                                                $linha['cpf'] ?? ''
                                                            ) ?></td>

                                                            <td><?= preg_replace(
                                                                '/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/',
                                                                '$1.$2.$3/$4-$5',
                                                                $linha['cnpj'] ?? ''
                                                            ) ?>
                                                            </td>

                                


                                                        <?php elseif ($relatorio == 'funcionarios'): ?>
                                                            <td><?= htmlspecialchars($linha['idFuncionario'] ?? '') ?></td>
                                                            <td><?= htmlspecialchars($linha['nome'] ?? '') ?></td>
                                                            <td><?= preg_replace(
                                                                '/(\d{3})(\d{3})(\d{3})(\d{2})/',
                                                                '$1.$2.$3-$4',
                                                                $linha['cpf'] ?? ''
                                                            ) ?></td>
                                                            <td>
                                                                <?php $status = strtolower(trim($linha['status'] ?? '')); ?>
                                                                <span class="status <?= $status ?>">
                                                                    <?= ucfirst($status) ?>
                                                                </span>
                                                            </td>



                                                        <?php elseif ($relatorio == 'veiculos'): ?>
                                                            <td><?= htmlspecialchars($linha['idVeiculo'] ?? '') ?></td>
                                                            <td><?= htmlspecialchars($linha['placa'] ?? '') ?></td>
                                                            <td><?= preg_replace(
                                                                '/(\d{4})(\d{6})(\d{1})/',
                                                                '$1.$2-$3',
                                                                $linha['renavam'] ?? ''
                                                            ) ?>
                                                            </td>

                                                            <td>
                                                                <?php $status = strtolower(trim($linha['statusVeiculo'] ?? ''));
                                                                $classeStatus = str_replace(' ', '-', $status);
                                                                ?>
                                                                <span class="status <?= $classeStatus ?>">
                                                                    <?= ucfirst($status) ?>
                                                                </span>
                                                            </td>


                                                        <?php elseif ($relatorio == 'obras'): ?>
                                                            <td><?= htmlspecialchars($linha['idObra'] ?? '') ?></td>
                                                            <td><?= htmlspecialchars($linha['nomeObra'] ?? '') ?></td>
                                                            <td><?= htmlspecialchars($linha['cidade'] ?? '') ?></td>

                                                            <!-- <td>

                                                                <?php $status = strtolower(trim($linha['status'] ?? '')); ?>
                                                                <span class="status <?= $status ?>">
                                                                    <?= ucfirst($status) ?>

                                                                </span>

                                                            </td> -->

                                                                                                                        <td>
                                                                <?php $status = strtolower(trim($linha['status'] ?? ''));
                                                                $classeStatus = str_replace(' ', '-', $status);
                                                                ?>
                                                                <span class="status <?= $classeStatus ?>">
                                                                    <?= ucfirst($status) ?>
                                                                </span>
                                                            </td>




                                                            <!-- <td><?= htmlspecialchars($linha['status'] ?? '') ?></td> -->

                                                        <?php elseif ($relatorio == 'financeiro'): ?>
                                                            <td><?= htmlspecialchars($linha['id'] ?? '') ?></td>
                                                            <td><?= htmlspecialchars($linha['tipo'] ?? '') ?></td>
                                                            <td><?= htmlspecialchars($linha['valor'] ?? '') ?></td>
                                                            <td><?= htmlspecialchars($linha['data'] ?? '') ?></td>
                                                        <?php endif; ?>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan=\"5\" style=\"text-align:center; padding: 20px;\">
                                                        Clique em \"GERAR TODOS\" para carregar os dados.
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>

                                    </table>

                                </div>

                                <!-- FOOTER -->
                                <div class="footer-preview">

                                    <p>
                                        <?php if (!empty($dados['total'])): ?>
                                            Exibindo <?= $dados['total'] ?> registro(s) do relatório de
                                            <?= $tipoSelecionado ?>
                                        <?php else: ?>
                                            Exibindo registros do relatório de <?= $tipoSelecionado ?>
                                        <?php endif; ?>
                                    </p>

                                    <div class="acoes-exportar">

                                        <button class="btn-excel"
                                            onclick="window.location.href='/ideal/public/index.php?url=relatorios/exportar-csv&relatorio=<?= $relatorio ?>'">
                                            <i class="bi bi-filetype-xlsx"></i>
                                            EXPORTAR EXCEL
                                        </button>

                                        <button class="btn-pdf"
                                            onclick="window.location.href='/ideal/public/index.php?url=relatorios/exportar-pdf&relatorio=<?= $relatorio ?>'">
                                            <i class="bi bi-filetype-pdf"></i>
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

                            <img src="/ideal/public/assets/icon/cliente.png" alt="Clientes">

                            <div>

                                <h3>RELATÓRIO DE CLIENTES</h3>

                                <p>
                                    Relatório completo com todos os clientes cadastrados.
                                </p>

                            </div>

                        </a>

                        <!-- FUNCIONÁRIOS -->
                        <a href="/ideal/public/index.php?url=relatorios&relatorio=funcionarios" class="card-relatorio">

                            <img src="/ideal/public/assets/icon/funcionario2.png" alt="Funcionários">

                            <div>

                                <h3>RELATÓRIO DE FUNCIONÁRIOS</h3>

                                <p>
                                    Relatório com todos os funcionários cadastrados.
                                </p>

                            </div>

                        </a>

                        <!-- OBRAS -->
                        <a href="/ideal/public/index.php?url=relatorios&relatorio=obras" class="card-relatorio">

                            <img src="/ideal/public/assets/icon/obra2.png" alt="Obras">

                            <div>

                                <h3>RELATÓRIO DE OBRAS</h3>

                                <p>
                                    Relatório com informações das obras cadastradas.
                                </p>

                            </div>

                        </a>

                        <!-- VEÍCULOS -->
                        <a href="/ideal/public/index.php?url=relatorios&relatorio=veiculos" class="card-relatorio">

                            <img src="/ideal/public/assets/icon/veiculo.png" alt="Veículos">

                            <div>

                                <h3>RELATÓRIO DE VEÍCULOS</h3>

                                <p>
                                    Relatório com informações dos veículos cadastrados.
                                </p>

                            </div>

                        </a>

                        <!-- FINANCEIRO -->
                        <a href="/ideal/public/index.php?url=relatorios&relatorio=financeiro" class="card-relatorio">

                            <img src="/ideal/public/assets/icon/financeiro3.png" alt="Financeiro">

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