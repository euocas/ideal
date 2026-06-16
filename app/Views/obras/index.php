<!--HEADER PHP  -->
<?php
$actionUrl ??= '/ideal/public/index.php?url=obras/store';
// TÍTULO DA PÁGINA
$titulo = 'Obras';
$favicon = '/ideal/public/assets/icon/obra2.png';

require_once __DIR__ . '/../includes/header.php';
?>


<link rel="shortcut icon" href="/ideal/public/assets/icons/obra2.png" type="image/x-icon">
<link rel="stylesheet" href="/ideal/public/assets/css/dashboard.css">
<link rel="stylesheet" href="/ideal/public/assets/css/variables.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="/ideal/public/assets/css/obras.css?v=<?= time() ?>">
</head>

<body>

    <div class="dashboard-container">

        <!-- SIDEBAR -->
        <?php include __DIR__ . '/../includes/sidebar.php'; ?>

        <!-- CONTEÚDO -->
        <main class="main-content">

            <?php if (isset($_SESSION['mensagem_sucesso'])): ?>
                <div
                    style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c3e6cb; font-weight: bold;">
                    ✅ <?= $_SESSION['mensagem_sucesso']; ?>
                </div>
                <?php unset($_SESSION['mensagem_sucesso']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['mensagem_erro'])): ?>
                <div
                    style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c6cb; font-weight: bold;">
                    ❌ <?= $_SESSION['mensagem_erro']; ?>
                </div>
                <?php unset($_SESSION['mensagem_erro']); ?>
            <?php endif; ?>


            <!-- BUSCA OBRA -->
            <section class="card">

                <div class="grid-busca">

                    <!-- FORM BUSCA -->
                    <div class="busca-box">

                        <h2>
                            <i class="fa-solid fa-building"></i>
                            BUSCAR OBRA
                        </h2>

                        <form class="form-busca" action="/ideal/public/index.php?url=obras" method="POST">

                            <div class="input-group">

                                <label>Contrato</label>

                                <input type="text" name="contratoBusca" placeholder="Digite o número do contrato">

                            </div>

                            <button type="submit" class="btn-buscar">
                                <i class="bi bi-search"> </i> BUSCAR

                            </button>

                        </form>

                    </div>

                    <!-- DICA -->
                    <div class="dica-box">

                        <h3>
                            <i class="fa-solid fa-circle-info"></i>
                            DICA
                        </h3>

                        <p>
                            Digite o número do contrato e clique em
                            <strong>BUSCAR</strong>.
                            Se não existir, você poderá cadastrar uma nova obra.
                        </p>

                    </div>

                </div>

            </section>

            <!-- DADOS DA OBRA -->
            <section class="card">

                <h2 class="titulo-card">
                    <i class="fa-regular fa-clipboard"></i>
                    Dados da Obra
                </h2>

                <form id="form-dados" action="<?= $actionUrl ?>" method="POST">

                    <div class="grid-form">

                        <!-- DADOS PRINCIPAIS -->

                        <div class="form-group">

                            <label>Contrato</label>

                            <input type="text" name="contrato" maxlength="45" placeholder="Digite o contrato"
                                value="<?= isset($obra) ? $obra->getContrato() : '' ?>">

                        </div>

                        <div class="form-group">

                            <label>Status da Obra</label>

                            <select name="status" required>

                                <option value="">Selecione</option>

                                <option value="Em andamento" <?= isset($obra) && $obra->getStatus() === 'Em andamento' ? 'selected' : '' ?>>
                                    Em andamento
                                </option>

                                <option value="Concluída" <?= isset($obra) && $obra->getStatus() === 'Concluída' ? 'selected' : '' ?>>
                                    Concluída
                                </option>

                                <option value="Cancelada" <?= isset($obra) && $obra->getStatus() === 'Cancelada' ? 'selected' : '' ?>>
                                    Cancelada
                                </option>

                            </select>

                        </div>

                        <div class="form-group">

                            <label>Data de Início</label>

                            <input type="datetime-local" name="dataInicio" value="<?= isset($obra) && $obra->getDataInicio()
                                ? $obra->getDataInicio()->format('Y-m-d\TH:i')
                                : '' ?>" required>

                        </div>

                        <div class="form-group">

                            <label>Data de Finalização</label>

                            <input type="datetime-local" name="dataFim" value="<?= isset($obra) && $obra->getDataFim()
                                ? $obra->getDataFim()->format('Y-m-d\TH:i')
                                : '' ?>">

                        </div>

                        <div class="cliente-area">

                            <div class="form-group">

                                <label>CNPJ Cliente</label>

                                <input type="text" name="cnpjCliente" placeholder="00.000.000/0000-00">

                            </div>

                            <div class="cliente-card">

                                <h3>
                                    <i class="fa-solid fa-user"></i>
                                    Dados do Cliente
                                </h3>

                                <div class="cliente-grid">

                                    <div class="cliente-info">
                                        <span>Nome / Razão Social</span>
                                        <strong>-</strong>
                                    </div>

                                    <div class="cliente-info">
                                        <span>CPF/CNPJ</span>
                                        <strong>-</strong>
                                    </div>

                                    <div class="cliente-info">
                                        <i class="fa-brands fa-whatsapp"></i>
                                        <span>WhatsApp</span>
                                        <strong>-</strong>
                                    </div>

                                </div>

                            </div>

                        </div>



                    </div>


            </section>

            <!-- ENDEREÇO DA OBRA -->
            <section class="card">

                <h2 class="titulo-card">
                    <i class="fa-solid fa-location-dot"></i>
                    Endereço da Obra
                </h2>

                <div class="grid-form">

                    <div class="form-group">
                        <label>CEP</label>
                        <input type="text" name="cep" placeholder="00000-000">
                    </div>

                    <div class="form-group">
                        <label>Estado</label>

                        <select name="estado">
                            <option>Selecione</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Cidade</label>
                        <input type="text" name="cidade" placeholder="Digite a cidade">
                    </div>

                    <div class="form-group">
                        <label>Logradouro</label>
                        <input type="text" name="logradouro" placeholder="Rua, Avenida, Alameda...">
                    </div>

                    <div class="form-group">
                        <label>Endereço</label>
                        <input type="text" name="endereco" placeholder="Digite o endereço">
                    </div>

                    <div class="form-group">
                        <label>Número</label>
                        <input type="text" name="numero" placeholder="1234">
                    </div>

                    <div class="form-group">
                        <label>Complemento</label>
                        <input type="text" name="complemento" placeholder="Apartamento, bloco, sala...">
                    </div>

                    <div class="form-group observacoes">
                        <label>Observações</label>

                        <textarea name="observacoes" placeholder="Digite as observações (opcional)">
            </textarea>
                    </div>

                </div>

            </section>

            <section class="card">

                <h2 class="titulo-card">
                    <i class="fa-solid fa-users"></i>
                    Funcionários Vinculados à Obra
                </h2>

                <div class="grid-funcionario">

                    <div class="form-group">
                        <label>Funcionário</label>
                        <select name="idFuncionario">
                            <option>Selecione</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Função</label>
                        <select name="funcao">
                            <option>Selecione</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Veículo</label>
                        <select name="idVeiculo">
                            <option>Selecione</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select name="statusFuncionario">
                            <option>Ativo</option>
                            <option>Inativo</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Data Início</label>
                        <input type="date" name="dataInicioFuncionario">
                    </div>

                    <div class="form-group">
                        <label>Data Saída</label>
                        <input type="date" name="dataSaidaFuncionario">
                    </div>

                    <div class="form-group btn-area">

                        <button type="button" class="btn-adicionar">
                            <i class="fa-solid fa-plus"></i>
                            Adicionar
                        </button>

                    </div>

                </div>

                <section class="card">

                    <h2 class="titulo-card">
                        <i class="fa-solid fa-users"></i>
                        Funcionários Vinculados à Obra
                    </h2>

                    <!-- FORMULÁRIO -->
                    <div class="grid-funcionario">

                        <!-- Funcionário -->
                        <!-- Função -->
                        <!-- Veículo -->
                        <!-- Status -->
                        <!-- Data Início -->
                        <!-- Data Saída -->
                        <!-- Botão Adicionar -->

                    </div>

                    <!-- TABELA -->
                    <div class="tabela-funcionarios">

                        <table>

                            <thead>
                                <tr>
                                    <th>Funcionário</th>
                                    <th>Função / Cargo</th>
                                    <th>Veículo</th>
                                    <th>Placa</th>
                                    <th>Tipo</th>
                                    <th>Data de Início</th>
                                    <th>Data de Saída</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>

                            <tbody>

                                <tr>
                                    <td>João Silva</td>
                                    <td>Pedreiro</td>
                                    <td>Fiat Strada</td>
                                    <td>ABC1D23</td>
                                    <td>Utilitário</td>
                                    <td>01/01/2026</td>
                                    <td>—</td>
                                    <td>
                                        <span class="status ativo">Ativo</span>
                                    </td>
                                    <td class="acoes-tabela">

                                        <button type="button" class="btn-editar">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>

                                        <button type="button" class="btn-excluir">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>

                                    </td>
                                </tr>

                            </tbody>

                        </table>

                    </div>

                    <!-- AVISO -->
                    <div class="info-tabela">

                        <i class="fa-solid fa-circle-info"></i>

                        Informe o veículo utilizado pelo funcionário para deslocamento até a obra.

                    </div>

                </section>

            </section>

            <!-- BOTÕES -->
            <div class="acao">

                <button type="submit" form="form-dados" class="btn novo">
                     <i class="bi bi-plus-lg"></i> 
                    Novo
                </button>

                <button type="submit" form="form-dados" class="btn alterar">
                    <i class="bi bi-pencil-square"></i> 
                    Alterar
                </button>

                <button type="submit" form="form-dados" class="btn excluir">
                    <i class="bi bi-trash"></i>
                    Excluir

                </button>

                <button type="reset" form="form-dados" class="btn limpar">
                    <i class="bi bi-eraser"></i>
                    Limpar

                </button>

            </div>

        </main>

    </div>

    <!-- SCRIPT CEP -->
    <script>
        function mascaraCEP(input) {

            let valor = input.value.replace(/\D/g, '');

            valor = valor.substring(0, 8);

            valor = valor.replace(/^(\d{5})(\d)/, '$1-$2');

            input.value = valor;

        }
    </script>

</body>

</html>