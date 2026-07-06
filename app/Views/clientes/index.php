<?php

use App\Config\SistemaConstantes;
// TÍTULO DA PÁGINA
$titulo = 'Clientes';
$favicon = '/ideal/public/assets/icon/cliente.png';
require_once __DIR__ . '/../includes/header.php';

// Estado da tela
$modoNovo = isset($_GET['novo']);
$modoEdicao = isset($cliente);
?>

<link rel="stylesheet" href="/ideal/public/assets/css/dashboard.css">
<link rel="stylesheet" href="/ideal/public/assets/css/variables.css">
<link rel="stylesheet" href="/ideal/public/assets/css/base.css">
<link rel="stylesheet" href="/ideal/public/assets/css/component.css">
<link rel="stylesheet" href="/ideal/public/assets/css/forms.css">
<link rel="stylesheet" href="/ideal/public/assets/css/alerts.css">
<link rel="shortcut icon" href="/ideal/public/assets/icons/clientes2.png" type="image/x-icon">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="/ideal/public/assets/css/cliente.css?v=<?= time() ?>">
</head>

<body>

    <div class="dashboard-container">

        <?php include __DIR__ . '/../includes/sidebar.php'; ?>

        <main class="main-content">


            <section class="card">

                <div class="grid-busca">

                    <div class="busca-box">

                        <h2>
                            <i class="fa-solid fa-users"></i>
                            BUSCAR CLIENTE
                        </h2>

                        <?php if (isset($mensagem) && $mensagem): ?>
                            <div class="alert alert-warning" style="margin:15px 0 20px 0;">
                                <?= htmlspecialchars($mensagem) ?>
                            </div>
                        <?php endif; ?>



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
                                <i class="bi bi-search"></i>
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

                <h2><i class="fa-regular fa-clipboard icone-titulo"> </i> Dados do Cliente</h2>

                <form id="form-dados" method="POST">


                    <?php if (isset($_SESSION['mensagem_sucesso'])): ?>
                        <div class="alert alert-success">
                            <?= htmlspecialchars($_SESSION['mensagem_sucesso']) ?>
                        </div>
                        <?php unset($_SESSION['mensagem_sucesso']); ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['mensagem_erro'])): ?>
                        <div class="alert alert-error">
                            <?= htmlspecialchars($_SESSION['mensagem_erro']) ?>
                        </div>
                        <?php unset($_SESSION['mensagem_erro']); ?>
                    <?php endif; ?>

                    <input type="hidden" name="idCliente"
                        value="<?= isset($cliente) ? $cliente->getIdCliente() : '' ?>">

                    <div class="grid-form">

                        <div class="form-group">

                            <label>Nome do Cliente</label>

                            <input type="text" name="nomeCliente" minlength="3" maxlength="45"
                                placeholder="Digite o nome do cliente" required
                                value="<?= isset($cliente) ? htmlspecialchars($cliente->getNomeCliente() ?? '') : '' ?>">

                        </div>

                        <div class="form-group">

                            <label>CPF</label>

                            <?php
                            $cpfValue = isset($cliente)
                                ? $cliente->getCpf()
                                : ($cpfBusca ?? '');

                            $cpfFormatado = !empty($cpfValue)
                                ? preg_replace(
                                    '/(\d{3})(\d{3})(\d{3})(\d{2})/',
                                    '$1.$2.$3-$4',
                                    preg_replace('/\D/', '', $cpfValue)
                                )
                                : '';
                            ?>

                            <input type="text" name="cpf" id="cpf" placeholder="000.000.000-00" maxlength="14"
                                oninput="mascaraCPF(this)" value="<?= htmlspecialchars($cpfFormatado) ?>">

                        </div>

                        <div class="form-group">

                            <label>CNPJ</label>

                            <?php
                            $cnpjValue = isset($cliente)
                                ? $cliente->getCnpj()
                                : ($cnpjBusca ?? '');
                            $cnpjFormatado = !empty($cnpjValue)
                                ? preg_replace(
                                    '/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/',
                                    '$1.$2.$3/$4-$5',
                                    preg_replace('/\D/', '', $cnpjValue)
                                )
                                : '';
                            ?>

                            <input type="text" name="cnpj" id="cnpj" placeholder="00.000.000/0000-00" maxlength="18"
                                oninput="mascaraCNPJ(this)" value="<?= htmlspecialchars($cnpjFormatado) ?>">

                        </div>

                        <h2 class="subtitulo-form">
                            Contato
                        </h2>

                        <div class="form-group">

                            <label>Telefone</label>

                            <?php
                            $telefoneValue = isset($cliente) ? $cliente->getTelefone() : '';
                            $telefoneFormatado = '';
                            if (!empty($telefoneValue)) {
                                $telefone = preg_replace('/\D/', '', $telefoneValue);
                                if (strlen($telefone) === 11) {
                                    // Celular
                                    $telefoneFormatado = preg_replace(
                                        '/(\d{2})(\d{5})(\d{4})/',
                                        '($1) $2-$3',
                                        $telefone
                                    );

                                } elseif (strlen($telefone) === 10) {

                                    // Telefone fixo
                                    $telefoneFormatado = preg_replace(
                                        '/(\d{2})(\d{4})(\d{4})/',
                                        '($1) $2-$3',
                                        $telefone
                                    );
                                }
                            }
                            ?>

                            <input type="text" name="telefone" placeholder="(00) 00000-0000" maxlength="15"
                                oninput="mascaraTelefone(this)" value="<?= htmlspecialchars($telefoneFormatado) ?>">

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

                            <?php
                            // Verifica e formata o CEP para exibição correta na tela
                            $cepValue = '';
                            if (isset($cliente) && !empty($cliente->getCep())) {
                                $c = preg_replace('/\D/', '', $cliente->getCep());
                                $cepValue = strlen($c) === 8 ? substr($c, 0, 5) . '-' . substr($c, 5) : $cliente->getCep();
                            }
                            ?>
                            <input type="text" name="cep" placeholder="00000-000" maxlength="9"
                                oninput="mascaraCEP(this)" value="<?= htmlspecialchars($cepValue) ?>">

                        </div>

                        <div class="form-group">

                            <label>Cidade</label>

                            <input type="text" name="cidade" id="cidade" placeholder="Digite a cidade"
                                value="<?= isset($cliente) ? htmlspecialchars($cliente->getCidade() ?? '') : '' ?>">

                        </div>

                        <div class="form-group">
                            <label>Estado</label>
                            <?php $estadoAtual = isset($cliente) ? $cliente->getEstado() : ''; ?>

                            <select name="estado" id="estado">
                                <option value="">Selecione</option>
                                <?php foreach (SistemaConstantes::ESTADOS as $sigla => $nome): ?>
                                    <option value="<?= $sigla ?>" <?= $estadoAtual === $sigla ? 'selected' : '' ?>>
                                        <?= $sigla ?> - <?= $nome ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                        </div>

                        <div class="form-group observacao">

                            <label>Observações</label>

                            <textarea
                                name="observacoes"><?= isset($cliente) ? htmlspecialchars($cliente->getObservacoes() ?? '') : '' ?></textarea>

                        </div>

                    </div>

                </form>

            </section>

            <div class="acoes">

                <button type="submit" form="form-dados" class="btn novo"
                    formaction="/ideal/public/index.php?url=clientes/store" <?= $modoNovo ? '' : 'disabled' ?>>
                    Cadastrar
                </button>

                <button type="submit" form="form-dados" class="btn alterar"
                    formaction="/ideal/public/index.php?url=clientes/update&id=<?= isset($cliente) ? $cliente->getIdCliente() : '' ?>"
                    <?= $modoEdicao ? '' : 'disabled' ?>>
                    Alterar
                </button>

                <button type="submit" form="form-dados" class="btn excluir"
                    formaction="/ideal/public/index.php?url=clientes/delete&id=<?= isset($cliente) ? $cliente->getIdCliente() : '' ?>"
                    onclick="return confirm('Tem certeza que deseja excluir este cliente?');" <?= $modoEdicao ? '' : 'disabled' ?>>
                    Excluir
                </button>

                <button type="button" class="btn limpar"
                    onclick="window.location.href='/ideal/public/index.php?url=clientes'">
                    Limpar
                </button>

            </div>

        </main>

    </div>

    <script src="/ideal/public/assets/js/mascaras.js?v=<?= time() ?>"></script>

    <script>
        function mascaraCEP(input) {
            let valor = input.value.replace(/\D/g, '');

            // Busca os dados automaticamente ao digitar os 8 números do CEP
            if (valor.length === 8) {
                fetch(`https://viacep.com.br/ws/${valor}/json/`)
                    .then(response => response.json())
                    .then(dados => {
                        if (!dados.erro) {
                            document.getElementById('cidade').value = dados.localidade;
                            document.getElementById('estado').value = dados.uf;
                        }
                    })
                    .catch(error => console.error('Erro ao buscar o CEP:', error));
            }

            valor = valor.substring(0, 8);
            valor = valor.replace(/^(\d{5})(\d)/, '$1-$2');
            input.value = valor;
        }
    </script>

</body>

</html>