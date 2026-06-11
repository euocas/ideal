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
                                BUSCAR
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

                <h2>Dados da Obra</h2>

                <form id="form-dados" action="<?= $actionUrl ?>" method="POST">

                    <div class="grid-form">

                        <!-- DADOS PRINCIPAIS -->

                        <div class="form-group">

                            <label>Contrato</label>

                            <input
                                type="text"
                                name="contrato"
                                maxlength="45"
                                placeholder="Digite o contrato"
                                value="<?= isset($obra) ? $obra->getContrato() : '' ?>">

                        </div>

                        <div class="form-group">

                            <label>Status da Obra</label>

                            <select name="status" required>

                                <option value="">Selecione</option>

                                <option value="Em andamento"
                                    <?= isset($obra) && $obra->getStatus() === 'Em andamento' ? 'selected' : '' ?>>
                                    Em andamento
                                </option>

                                <option value="Concluída"
                                    <?= isset($obra) && $obra->getStatus() === 'Concluída' ? 'selected' : '' ?>>
                                    Concluída
                                </option>

                                <option value="Cancelada"
                                    <?= isset($obra) && $obra->getStatus() === 'Cancelada' ? 'selected' : '' ?>>
                                    Cancelada
                                </option>

                            </select>

                        </div>

                        <div class="form-group">

                            <label>Data de Início</label>

                            <input
                                type="datetime-local"
                                name="dataInicio"
                                value="<?= isset($obra) && $obra->getDataInicio()
                                            ? $obra->getDataInicio()->format('Y-m-d\TH:i')
                                            : '' ?>"
                                required>

                        </div>

                        <div class="form-group">

                            <label>Data de Finalização</label>

                            <input
                                type="datetime-local"
                                name="dataFim"
                                value="<?= isset($obra) && $obra->getDataFim()
                                            ? $obra->getDataFim()->format('Y-m-d\TH:i')
                                            : '' ?>">

                        </div>

                        <!-- ENDEREÇO -->

                        <h2 class="subtitulo-form">
                            Endereço da Obra
                        </h2>

                        <div class="form-group">

                            <label>CEP</label>

                            <input
                                type="text"
                                name="cep"
                                value="<?= isset($obra) ? $obra->getCep() : '' ?>"
                                placeholder="00000-000"
                                maxlength="9"
                                oninput="mascaraCEP(this)"
                                required>

                        </div>

                        <div class="form-group">

                            <label>Estado</label>

                            <select name="estado" required>

                                <option value="">Selecione</option>

                                <option value="SP" <?= isset($obra) && $obra->getEstado() === 'SP' ? 'selected' : '' ?>>SP</option>
                                <option value="RJ" <?= isset($obra) && $obra->getEstado() === 'RJ' ? 'selected' : '' ?>>RJ</option>
                                <option value="MG" <?= isset($obra) && $obra->getEstado() === 'MG' ? 'selected' : '' ?>>MG</option>
                                <option value="PR" <?= isset($obra) && $obra->getEstado() === 'PR' ? 'selected' : '' ?>>PR</option>
                                <option value="SC" <?= isset($obra) && $obra->getEstado() === 'SC' ? 'selected' : '' ?>>SC</option>

                            </select>

                        </div>

                        <div class="form-group">

                            <label>Cidade</label>

                            <input
                                type="text"
                                name="cidade"
                                maxlength="45"
                                placeholder="Digite a cidade"
                                value="<?= isset($obra) ? $obra->getCidade() : '' ?>"
                                required>

                        </div>

                        <div class="form-group">

                            <label>Logradouro</label>

                            <input
                                type="text"
                                name="logradouro"
                                maxlength="80"
                                placeholder="Rua, Avenida, Alameda..."
                                value="<?= isset($obra) ? $obra->getLogradouro() : '' ?>"
                                required>

                        </div>

                        <div class="form-group">

                            <label>Endereço</label>

                            <input
                                type="text"
                                name="endereco"
                                maxlength="50"
                                placeholder="Digite o endereço"
                                value="<?= isset($obra) ? $obra->getEndereco() : '' ?>"
                                required>
                        </div>

                        <div class="form-group">

                            <label>Número</label>

                            <input
                                type="text"
                                name="numero"
                                maxlength="4"
                                placeholder="1234"
                                value="<?= isset($obra) ? $obra->getNumero() : '' ?>"
                                required>

                        </div>

                        <div class="form-group">

                            <label>Complemento</label>

                            <input
                                type="text"
                                name="complemento"
                                maxlength="45"
                                placeholder="Apartamento, bloco, sala..."
                                value="<?= isset($obra) ? $obra->getComplemento() : '' ?>">

                        </div>

                        <!-- OBSERVAÇÕES -->

                        <div class="form-group observacoes">

                            <label>Observações</label>

                            <textarea name="observacoes"></textarea>

                        </div>

                    </div>

                </form>

            </section>

            <!-- BOTÕES -->
            <div class="acao">

                <button type="submit" form="form-dados" class="btn novo">
                    Novo
                </button>

                <button type="submit" form="form-dados" class="btn alterar">
                    Alterar
                </button>

                <button type="submit" form="form-dados" class="btn excluir">

                    Excluir

                </button>

                <button type="reset" form="form-dados" class="btn limpar">

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