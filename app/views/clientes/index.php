<?php
// TÍTULO DA PÁGINA
$titulo = 'Clientes';
require_once __DIR__ . '/../includes/header.php';
?>

<link rel="stylesheet" href="/ideal/public/assets/css/dashboard.css">
<link rel="stylesheet" href="/ideal/public/assets/css/variables.css">
<link rel="stylesheet" href="/ideal/public/assets/css/base.css">
<link rel="stylesheet" href="/ideal/public/assets/css/components.css">
<link rel="stylesheet" href="/ideal/public/assets/css/forms.css">
<link rel="shortcut icon" href="/ideal/public/assets/icons/clientes2.png" type="image/x-icon">
<link rel="stylesheet" href="/ideal/public/assets/css/clientes.css?v=<?= time() ?>">
</head>

<body>

    <div class="dashboard-container">

        <!-- SIDEBAR -->
        <?php include __DIR__ . '/../includes/sidebar.php'; ?>

        <!-- CONTEÚDO -->
        <main class="main-content">

            <!-- ALERTAS (Feedback do Controller) -->
            <?php if (isset($mensagem) && $mensagem): ?>
                <div class="alert alert-warning" style="background: #fff3cd; color: #856404; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
                    <?= $mensagem ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['mensagem_sucesso'])): ?>
                <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
                    <?= $_SESSION['mensagem_sucesso']; unset($_SESSION['mensagem_sucesso']); ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['mensagem_erro'])): ?>
                <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
                    <?= $_SESSION['mensagem_erro']; unset($_SESSION['mensagem_erro']); ?>
                </div>
            <?php endif; ?>

            <!-- BUSCA CLIENTE -->
            <section class="card">

                <div class="grid-busca">

                    <!-- FORM BUSCA -->
                    <div class="busca-box">

                        <h2>
                            <i class="fa-solid fa-users"></i>
                            BUSCAR CLIENTE
                        </h2>

                        <!-- Action apontando para o método principal de busca no Controller -->
                        <form class="form-busca" action="/ideal/public/index.php?url=clientes" method="POST">

                            <div class="input-group">

                                <label>Tipo</label>

                                <select name="tipoDocumento" id="tipoDocumento" onchange="alterarMascaraDocumento()">

                                    <option value="cpf">CPF</option>
                                    <option value="cnpj">CNPJ</option>

                                </select>

                            </div>

                            <div class="input-group">

                                <label id="labelDocumento">
                                    CPF
                                </label>

                                <!-- Value adicionado para recuperar o documento se não for encontrado -->
                                <input type="text" id="documento" name="documento" placeholder="000.000.000-00"
                                    maxlength="14" oninput="mascaraDocumento(this)"
                                    value="<?= isset($_GET['documento']) ? htmlspecialchars($_GET['documento']) : '' ?>">

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
                            Selecione CPF ou CNPJ, digite o documento do cliente e clique em
                            <strong>BUSCAR</strong>.
                            Se não existir, você poderá cadastrar um novo cliente.
                        </p>

                    </div>

                </div>

            </section>

            <!-- DADOS CLIENTE -->
            <section class="card">

                <h2>Dados do Cliente</h2>

                <!-- Action removido daqui, pois as ações serão disparadas pelos botões -->
                <form id="form-dados" method="POST">

                    <!-- Campo oculto para o banco de dados saber qual cliente atualizar/excluir -->
                    <input type="hidden" name="idCliente" value="<?= isset($cliente) ? $cliente->getIdCliente() : '' ?>">

                    <div class="grid-form">

                        <!-- DADOS PRINCIPAIS -->

                        <div class="form-group">

                            <label>Nome do Cliente</label>

                            <input type="text" name="nomeCliente" minlength="3" maxlength="45"
                                placeholder="Digite o nome do cliente" required
                                value="<?= isset($cliente) ? htmlspecialchars($cliente->getNomeCliente()) : '' ?>">

                        </div>

                        <div class="form-group">

                            <label>CPF</label>

                            <input type="text" name="cpf" id="cpf" placeholder="000.000.000-00" maxlength="14"
                                oninput="mascaraCPF(this)"
                                value="<?= isset($cliente) ? htmlspecialchars($cliente->getCpf()) : '' ?>">

                        </div>

                        <div class="form-group">

                            <label>CNPJ</label>

                            <input type="text" name="cnpj" id="cnpj" placeholder="00.000.000/0000-00" maxlength="18"
                                oninput="mascaraCNPJ(this)"
                                value="<?= isset($cliente) ? htmlspecialchars($cliente->getCnpj()) : '' ?>">

                        </div>

                        <!-- CONTATO -->

                        <h2 class="subtitulo-form">
                            Contato
                        </h2>

                        <div class="form-group">

                            <label>Telefone</label>

                            <input type="text" name="telefone" placeholder="(00) 00000-0000" maxlength="15"
                                oninput="mascaraTelefone(this)">

                        </div>

                        <div class="form-group">

                            <label>E-mail</label>

                            <input type="email" name="email" placeholder="cliente@email.com">

                        </div>

                        <div class="form-group">

                            <label>Tipo de Cliente</label>

                            <select name="tipoCliente">

                                <option value="">Selecione</option>

                                <option value="PESSOA_FISICA">
                                    Pessoa Física
                                </option>

                                <option value="PESSOA_JURIDICA">
                                    Pessoa Jurídica
                                </option>

                            </select>

                        </div>

                        <!-- ENDEREÇO -->

                        <h2 class="subtitulo-form">
                            Endereço
                        </h2>

                        <div class="form-group">

                            <label>CEP</label>

                            <input type="text" name="cep" placeholder="00000-000" maxlength="9"
                                oninput="mascaraCEP(this)">

                        </div>

                        <div class="form-group">

                            <label>Cidade</label>

                            <input type="text" name="cidade" placeholder="Digite a cidade">

                        </div>

                        <div class="form-group">

                            <label>Estado</label>

                            <select name="estado">

                                <option value="">Selecione</option>

                                <option value="SP">SP</option>
                                <option value="RJ">RJ</option>
                                <option value="MG">MG</option>
                                <option value="PR">PR</option>
                                <option value="SC">SC</option>

                            </select>

                        </div>

                        <div class="form-group observacoes">

                            <label>Observações</label>

                            <textarea name="observacoes"></textarea>

                        </div>

                    </div>

                </form>

            </section>

            <!-- BOTÕES (Agora com rotas para o Controller) -->
            <div class="acoes">

                <button type="submit" form="form-dados" class="btn novo" 
                        formaction="/ideal/public/index.php?url=clientes/store">
                    Novo
                </button>

                <!-- Desabilita o botão caso não haja um cliente sendo visualizado (sem ID) -->
                <button type="submit" form="form-dados" class="btn alterar" 
                        formaction="/ideal/public/index.php?url=clientes/update&id=<?= isset($cliente) ? $cliente->getIdCliente() : '' ?>"
                        <?= isset($cliente) ? '' : 'disabled' ?>>
                    Alterar
                </button>

                <button type="submit" form="form-dados" class="btn excluir" 
                        formaction="/ideal/public/index.php?url=clientes/delete&id=<?= isset($cliente) ? $cliente->getIdCliente() : '' ?>"
                        onclick="return confirm('Tem certeza que deseja excluir este cliente?');"
                        <?= isset($cliente) ? '' : 'disabled' ?>>
                    Excluir
                </button>

                <!-- Alterado para type="button" com redirecionamento, garantindo que o form limpe totalmente e volte ao estado inicial -->
                <button type="button" class="btn limpar" onclick="window.location.href='/ideal/public/index.php?url=clientes'">
                    Limpar
                </button>

            </div>

        </main>

    </div>

    <!-- SCRIPT DOCUMENTO -->
    <script>

        function alterarMascaraDocumento() {

            const tipo = document.getElementById('tipoDocumento').value;
            const input = document.getElementById('documento');
            const label = document.getElementById('labelDocumento');

            input.value = '';

            if (tipo === 'cpf') {

                label.innerText = 'CPF';
                input.placeholder = '000.000.000-00';
                input.maxLength = 14;

            } else {

                label.innerText = 'CNPJ';
                input.placeholder = '00.000.000/0000-00';
                input.maxLength = 18;

            }

        }

        function mascaraDocumento(input) {

            const tipo = document.getElementById('tipoDocumento').value;

            if (tipo === 'cpf') {

                mascaraCPF(input);

            } else {

                mascaraCNPJ(input);

            }

        }

    </script>

    <!-- SCRIPT CPF -->
    <script>

        function mascaraCPF(input) {

            let valor = input.value.replace(/\D/g, '');

            valor = valor.substring(0, 11);

            valor = valor.replace(/(\d{3})(\d)/, '$1.$2');

            valor = valor.replace(/(\d{3})(\d)/, '$1.$2');

            valor = valor.replace(/(\d{3})(\d{1,2})$/, '$1-$2');

            input.value = valor;

        }

    </script>

    <!-- SCRIPT CNPJ -->
    <script>

        function mascaraCNPJ(input) {

            let valor = input.value.replace(/\D/g, '');

            valor = valor.substring(0, 14);

            valor = valor.replace(/^(\d{2})(\d)/, '$1.$2');

            valor = valor.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');

            valor = valor.replace(/\.(\d{3})(\d)/, '.$1/$2');

            valor = valor.replace(/(\d{4})(\d)/, '$1-$2');

            input.value = valor;

        }

    </script>

    <!-- SCRIPT TELEFONE -->
    <script>

        function mascaraTelefone(input) {

            let valor = input.value.replace(/\D/g, '');

            valor = valor.substring(0, 11);

            valor = valor.replace(/^(\d{2})(\d)/g, '($1) $2');

            valor = valor.replace(/(\d)(\d{4})$/, '$1-$2');

            input.value = valor;

        }

    </script>

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