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

        <?php include __DIR__ . '/../includes/sidebar.php'; ?>

        <main class="main-content">

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

            <section class="card">

                <div class="grid-busca">

                    <div class="busca-box">

                        <h2>
                            <i class="fa-solid fa-users"></i>
                            BUSCAR CLIENTE
                        </h2>

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

                                <input type="text" id="documento" name="documento" placeholder="000.000.000-00"
                                    maxlength="14" oninput="mascaraDocumento(this)"
                                    value="<?= isset($_GET['documento']) ? htmlspecialchars($_GET['documento']) : '' ?>">

                            </div>

                            <button type="submit" class="btn-buscar">
                                BUSCAR
                            </button>

                        </form>

                    </div>

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

            <section class="card">

                <h2>Dados do Cliente</h2>

                <form id="form-dados" method="POST">

                    <input type="hidden" name="idCliente" value="<?= isset($cliente) ? $cliente->getIdCliente() : '' ?>">

                    <div class="grid-form">

                        <div class="form-group">

                            <label>Nome do Cliente</label>

                            <input type="text" name="nomeCliente" minlength="3" maxlength="45"
                                placeholder="Digite o nome do cliente" required
                                value="<?= isset($cliente) ? htmlspecialchars($cliente->getNomeCliente() ?? '') : '' ?>">

                        </div>

                        <div class="form-group">

                            <label>CPF</label>

                            <input type="text" name="cpf" id="cpf" placeholder="000.000.000-00" maxlength="14"
                                oninput="mascaraCPF(this)"
                                value="<?= isset($cliente) ? htmlspecialchars($cliente->getCpf() ?? '') : '' ?>">

                        </div>

                        <div class="form-group">

                            <label>CNPJ</label>

                            <input type="text" name="cnpj" id="cnpj" placeholder="00.000.000/0000-00" maxlength="18"
                                oninput="mascaraCNPJ(this)"
                                value="<?= isset($cliente) ? htmlspecialchars($cliente->getCnpj() ?? '') : '' ?>">

                        </div>

                        <h2 class="subtitulo-form">
                            Contato
                        </h2>

                        <div class="form-group">

                            <label>Telefone</label>

                            <input type="text" name="telefone" placeholder="(00) 00000-0000" maxlength="15"
                                oninput="mascaraTelefone(this)"
                                value="<?= isset($cliente) ? htmlspecialchars($cliente->getTelefone() ?? '') : '' ?>">

                        </div>

                        <div class="form-group">

                            <label>E-mail</label>

                            <input type="email" name="email" placeholder="cliente@email.com"
                                value="<?= isset($cliente) ? htmlspecialchars($cliente->getEmail() ?? '') : '' ?>">

                        </div>

                        <div class="form-group">

                            <label>Tipo de Cliente</label>

                            <select name="tipoCliente">

                                <option value="">Selecione</option>

                                <option value="PESSOA_FISICA" <?= (isset($cliente) && ($cliente->getTipoCliente() === 'Pessoa Física' || $cliente->getTipoCliente() === 'PESSOA_FISICA')) ? 'selected' : '' ?>>
                                    Pessoa Física
                                </option>

                                <option value="PESSOA_JURIDICA" <?= (isset($cliente) && ($cliente->getTipoCliente() === 'Pessoa Jurídica' || $cliente->getTipoCliente() === 'PESSOA_JURIDICA')) ? 'selected' : '' ?>>
                                    Pessoa Jurídica
                                </option>

                            </select>

                        </div>

                        <h2 class="subtitulo-form">
                            Endereço
                        </h2>

                        <div class="form-group">

                            <label>CEP</label>

                            <input type="text" name="cep" placeholder="00000-000" maxlength="9"
                                oninput="mascaraCEP(this)"
                                value="<?= isset($cliente) ? htmlspecialchars($cliente->getCep() ?? '') : '' ?>">

                        </div>

                        <div class="form-group">

                            <label>Cidade</label>

                            <input type="text" name="cidade" placeholder="Digite a cidade"
                                value="<?= isset($cliente) ? htmlspecialchars($cliente->getCidade() ?? '') : '' ?>">

                        </div>

                        <div class="form-group">

                            <label>Estado</label>

                            <?php $estadoAtual = isset($cliente) ? $cliente->getEstado() : ''; ?>
                            <select name="estado">

                                <option value="">Selecione</option>

                                <option value="SP" <?= $estadoAtual === 'SP' ? 'selected' : '' ?>>SP</option>
                                <option value="RJ" <?= $estadoAtual === 'RJ' ? 'selected' : '' ?>>RJ</option>
                                <option value="MG" <?= $estadoAtual === 'MG' ? 'selected' : '' ?>>MG</option>
                                <option value="PR" <?= $estadoAtual === 'PR' ? 'selected' : '' ?>>PR</option>
                                <option value="SC" <?= $estadoAtual === 'SC' ? 'selected' : '' ?>>SC</option>

                            </select>

                        </div>

                        <div class="form-group observacoes">

                            <label>Observações</label>

                            <textarea name="observacoes"><?= isset($cliente) ? htmlspecialchars($cliente->getObservacoes() ?? '') : '' ?></textarea>

                        </div>

                    </div>

                </form>

            </section>

            <div class="acoes">

                <button type="submit" form="form-dados" class="btn novo" 
                        formaction="/ideal/public/index.php?url=clientes/store">
                    Novo
                </button>

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

                <button type="button" class="btn limpar" onclick="window.location.href='/ideal/public/index.php?url=clientes'">
                    Limpar
                </button>

            </div>

        </main>

    </div>

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

    <script>

        function mascaraTelefone(input) {

            let valor = input.value.replace(/\D/g, '');

            valor = valor.substring(0, 11);

            valor = valor.replace(/^(\d{2})(\d)/g, '($1) $2');

            valor = valor.replace(/(\d)(\d{4})$/, '$1-$2');

            input.value = valor;

        }

    </script>

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