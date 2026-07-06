<?php

/** @var \App\Models\Veiculo|null $veiculo */
// Estado da tela
$modoNovo = isset($_GET['novo']);
$modoEdicao = isset($veiculo) && is_object($veiculo);

// Ação do formulário
$actionUrl = $modoEdicao
    ? "/ideal/public/index.php?url=veiculos/update&id={$veiculo->getIdVeiculo()}"
    : "/ideal/public/index.php?url=veiculos/store";

// Valores dos campos
$renavamValue = $modoEdicao ? $veiculo->getRenavam() : '';

// Pega a placa buscada caso seja um cadastro novo
$placaBusca = $_GET['placa'] ?? ($_POST['placa'] ?? '');
$placaValue = $modoEdicao ? $veiculo->getPlaca() : $placaBusca;

// Formatação inteligente dos anos (transforma YYYY em YYYY-01-01 para o input date ler)
$anoFabValue = '';

if ($modoEdicao && !empty($veiculo->getAnoFabricacao())) {
    $anoFabValue = strlen((string) $veiculo->getAnoFabricacao()) === 4
        ? $veiculo->getAnoFabricacao() . '-01-01'
        : $veiculo->getAnoFabricacao();
}

$anoModValue = '';

if ($modoEdicao && !empty($veiculo->getAnoModelo())) {
    $anoModValue = strlen((string) $veiculo->getAnoModelo()) === 4
        ? $veiculo->getAnoModelo() . '-01-01'
        : $veiculo->getAnoModelo();
}

// HEADERS ANTI CACHE
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

// TÍTULO
$titulo = 'Veículos';
$favicon = '/ideal/public/assets/icon/veiculo.png';

// HEADER
require_once __DIR__ . '/../includes/header.php';

?>


<link rel="stylesheet" href="/ideal/public/assets/css/variables.css">
<link rel="stylesheet" href="/ideal/public/assets/css/base.css">
<link rel="stylesheet" href="/ideal/public/assets/css/component.css">
<link rel="stylesheet" href="/ideal/public/assets/css/forms.css">
<link rel="stylesheet" href="/ideal/public/assets/css/alerts.css">
<link rel="stylesheet" href="/ideal/public/assets/css/tables.css">

<link rel="stylesheet" href="/ideal/public/assets/css/dashboard.css">

<link rel="stylesheet" href="/ideal/public/assets/css/veiculos.css?v=<?= time() ?>">

</head>

<body onunload="">
    <div class="dashboard-container">
        <?php include __DIR__ . '/../includes/sidebar.php'; ?>

        <main class="main-content">

            <section class="card">
                <div class="grid-busca">
                    <div class="busca-box">
                        <h2>🚘 BUSCAR VEÍCULO</h2>
                        <?php if (isset($_SESSION['mensagem_erro'])): ?>
                            <div class="alert alert-error">
                                 <?= htmlspecialchars($_SESSION['mensagem_erro']); ?>
                            </div>
                            <?php unset($_SESSION['mensagem_erro']); ?>
                        <?php endif; ?>
                         
                        <form class="form-busca" action="/ideal/public/index.php?url=veiculos" method="POST">
                            <div class="input-group">
                                <label>PLACA</label>
                                <input type="text" name="placa"
                                    oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '')"
                                    placeholder="ABC1D23" required maxlength="7" style="text-transform: uppercase;">
                            </div>
                            <button type="submit" class="btn-buscar"><i class="bi bi-search"></i> BUSCAR</button>
                        </form>
                    </div>
                    <div class="dica-box">

                        <h3>
                            <i class="fa-solid fa-circle-info"></i>
                            DICA
                        </h3>
                        <p>Digite a placa do veículo (padrão antigo ou Mercosul) e clique em <strong>BUSCAR</strong>. Se
                            não existir, você poderá cadastrar um novo veículo.</p>
                    </div>
                </div>
            </section>

            <form id="form-dados" action="<?= $actionUrl ?>" method="POST" novalidate autocomplete="off">

                <section class="card">
                    <!-- AVISOS DE SUCESSO OU ERRO DO BANCO DE DADOS -->
                    <?php if (isset($_SESSION['mensagem_sucesso'])): ?>
                        <div class="alert alert-success">
                            ✅ <?= htmlspecialchars($_SESSION['mensagem_sucesso']); ?>
                        </div>
                        <?php unset($_SESSION['mensagem_sucesso']); ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['mensagem_erro'])): ?>
                        <div class="alert alert-error">
                            ❌ <?= htmlspecialchars($_SESSION['mensagem_erro']); ?>
                        </div>
                        <?php unset($_SESSION['mensagem_erro']); ?>
                    <?php endif; ?>

                    <h2><i class="fa-regular fa-clipboard icone-titulo"> </i> Dados do Veículo</h2>
                    <div class="grid-form">
                        <div class="form-group">
                            <label>Renavam</label>
                            <input type="text" name="renavam" value="<?= htmlspecialchars($renavamValue ?? '') ?>"
                                oninput="mascaraRenavam(this)" placeholder="0000.000000-0" maxlength="13">
                        </div>
                        <div class="form-group">
                            <label>Placa</label>
                            <input type="text" name="placa" value="<?= htmlspecialchars($placaValue ?? '') ?>"
                                placeholder="ABC1D23" maxlength="7"
                                oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '')">
                        </div>
                        <div class="form-group">
                            <label>Chassi</label>
                            <input type="text" name="chassi"
                                value="<?= htmlspecialchars($modoEdicao ? ($veiculo->getChassi() ?? '') : '') ?>"
                                oninput="mascaraChassi(this)" maxlength="17" placeholder="9BWZZZ377VT004251">
                        </div>
                        <div class="form-group">
                            <label>Marca</label>
                            <select name="marca">
                                <option value="">Selecione a marca</option>
                                <optgroup label="Utilitários leves">
                                    <option value="Fiat" <?= ($modoEdicao && $veiculo->getMarca() === 'Fiat') ? 'selected' : '' ?>>Fiat</option>
                                    <option value="Volkswagen" <?= ($modoEdicao && $veiculo->getMarca() === 'Volkswagen') ? 'selected' : '' ?>>Volkswagen</option>
                                    <option value="Chevrolet" <?= ($modoEdicao && $veiculo->getMarca() === 'Chevrolet') ? 'selected' : '' ?>>Chevrolet</option>
                                    <option value="Renault" <?= ($modoEdicao && $veiculo->getMarca() === 'Renault') ? 'selected' : '' ?>>Renault</option>
                                </optgroup>
                                <optgroup label="Picapes médias">
                                    <option value="Toyota" <?= ($modoEdicao && $veiculo->getMarca() === 'Toyota') ? 'selected' : '' ?>>Toyota</option>
                                    <option value="Ford" <?= ($modoEdicao && $veiculo->getMarca() === 'Ford') ? 'selected' : '' ?>>Ford</option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Modelo</label>
                            <select name="modelo">
                                <option value="">Selecione o modelo</option>
                                <optgroup label="Utilitários leves">
                                    <option value="Fiat Strada" <?= ($modoEdicao && $veiculo->getModelo() === 'Fiat Strada') ? 'selected' : '' ?>>Fiat Strada</option>
                                    <option value="Volkswagen Saveiro" <?= ($modoEdicao && $veiculo->getModelo() === 'Volkswagen Saveiro') ? 'selected' : '' ?>>Volkswagen
                                        Saveiro</option>
                                    <option value="Chevrolet Montana" <?= ($modoEdicao && $veiculo->getModelo() === 'Chevrolet Montana') ? 'selected' : '' ?>>Chevrolet
                                        Montana</option>
                                    <option value="Fiat Fiorino" <?= ($modoEdicao && $veiculo->getModelo() === 'Fiat Fiorino') ? 'selected' : '' ?>>Fiat Fiorino</option>
                                </optgroup>
                                <optgroup label="Picapes médias">
                                    <option value="Toyota Hilux" <?= ($modoEdicao && $veiculo->getModelo() === 'Toyota Hilux') ? 'selected' : '' ?>>Toyota Hilux</option>
                                    <option value="Chevrolet S10" <?= ($modoEdicao && $veiculo->getModelo() === 'Chevrolet S10') ? 'selected' : '' ?>>Chevrolet S10</option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Ano Fabricação</label>
                            <input type="date" name="anoFabricacao" value="<?= htmlspecialchars($anoFabValue ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label>Ano Modelo</label>
                            <input type="date" name="anoModelo" value="<?= htmlspecialchars($anoModValue ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label>Cor</label>
                            <select name="cor">
                                <option value="">Selecione</option>
                                <option value="Branco" <?= ($modoEdicao && $veiculo->getCor() === 'Branco') ? 'selected' : '' ?>>Branco</option>
                                <option value="Preto" <?= ($modoEdicao && $veiculo->getCor() === 'Preto') ? 'selected' : '' ?>>
                                    Preto</option>
                                <option value="Prata" <?= ($modoEdicao && $veiculo->getCor() === 'Prata') ? 'selected' : '' ?>>
                                    Prata</option>
                                <option value="Cinza" <?= ($modoEdicao && $veiculo->getCor() === 'Cinza') ? 'selected' : '' ?>>
                                    Cinza</option>
                            </select>
                        </div>
                        <h2 class="subtitulo-form">Situação do Veículo</h2>
                        <div class="form-group">
                            <!-- CORRIGIDO: name="statusVeiculo" -->
                            <label>Status</label>
                            <select name="statusVeiculo">
                                <option value="">Selecione</option>
                                <option value="ATIVO" <?= ($modoEdicao && $veiculo->getStatusVeiculo() === 'ATIVO') ? 'selected' : '' ?>>Ativo</option>
                                <option value="EM MANUTENCAO" <?= ($modoEdicao && $veiculo->getStatusVeiculo() === 'EM MANUTENCAO') ? 'selected' : '' ?>>Em manutenção</option>
                                <option value="INATIVO" <?= ($modoEdicao && $veiculo->getStatusVeiculo() === 'INATIVO') ? 'selected' : '' ?>>Inativo</option>
                                <option value="VENDIDO" <?= ($modoEdicao && $veiculo->getStatusVeiculo() === 'VENDIDO') ? 'selected' : '' ?>>Vendido</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Quilometragem</label>
                            <input type="text" name="quilometragem"
                                value="<?= htmlspecialchars($modoEdicao ? ($veiculo->getQuilometragem() ?? '') : '') ?>"
                                maxlength="9" pattern="\d{1,7}" inputmode="numeric" placeholder="Ex: 125000">
                        </div>
                        <div class="form-group">
                            <!-- CORRIGIDO: name="dataUltimaRevisao" -->
                            <label>Última Revisão</label>
                            <input type="date" name="dataUltimaRevisao"
                                value="<?= htmlspecialchars($modoEdicao ? ($veiculo->getDataUltimaRevisao() ?? '') : '') ?>">
                        </div>
                        <div class="form-group">
                            <label>Próxima Revisão</label>
                            <input type="date" name="proximaRevisao"
                                value="<?= htmlspecialchars($modoEdicao ? ($veiculo->getProximaRevisao() ?? '') : '') ?>">
                        </div>
                        <h2 class="subtitulo-form" style="grid-column: 1 / -1;">Responsável</h2>
                        <div class="form-group">
                            <!-- CORRIGIDO: name="tipoPosse" -->
                            <label>Propriedade do Veículo</label>
                            <select name="tipoPosse">
                                <option value="">Selecione</option>
                                <option value="PROPRIO" <?= ($modoEdicao && $veiculo->getTipoPosse() === 'PROPRIO') ? 'selected' : '' ?>>Próprio</option>
                                <option value="ALUGADO" <?= ($modoEdicao && $veiculo->getTipoPosse() === 'ALUGADO') ? 'selected' : '' ?>>Alugado</option>
                                <option value="EMPRESTADO" <?= ($modoEdicao && $veiculo->getTipoPosse() === 'EMPRESTADO') ? 'selected' : '' ?>>Emprestado</option>
                                <option value="TERCEIRIZADO" <?= ($modoEdicao && $veiculo->getTipoPosse() === 'TERCEIRIZADO') ? 'selected' : '' ?>>Terceirizado
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <!-- CORRIGIDO: name="responsavelVeiculo" -->
                            <label>Responsável pelo veículo</label>
                            <input type="text" name="responsavelVeiculo"
                                value="<?= htmlspecialchars($modoEdicao ? ($veiculo->getResponsavelVeiculo() ?? '') : '') ?>"
                                minlength="3" pattern="[A-Za-zÀ-ÿ\s]+" placeholder="Digite o propreitário do veículo">
                        </div>
                        <div class="form-group observacao">
                            <label>Observações</label>
                            <textarea
                                name="observacoes"><?= htmlspecialchars($modoEdicao ? ($veiculo->getObservacoes() ?? '') : '') ?></textarea>
                        </div>
                    </div>
                </section>


                <div class="acoes">

                    <button type="submit" class="btn novo" <?= !$modoNovo ? 'disabled' : '' ?>>
                        <i class="bi bi-plus-lg"></i>
                        Cadastrar
                    </button>

                    <button type="submit" class="btn salvar" <?= !$modoEdicao ? 'disabled' : '' ?>>
                        <i class="bi bi-floppy"></i>
                        Salvar
                    </button>

                    <?php if ($modoEdicao): ?>
                        <form action="/ideal/public/index.php?url=veiculos/delete" method="POST" style="display:inline;"
                            onsubmit="return confirm('Excluir este veículo?');">

                            <input type="hidden" name="id" value="<?= $veiculo->getIdVeiculo() ?>">

                            <button type="submit" class="btn excluir">
                                <i class="bi bi-trash"></i>
                                Excluir
                            </button>
                        </form>
                    <?php else: ?>
                        <button type="button" class="btn excluir" disabled>
                            <i class="bi bi-trash"></i>
                            Excluir
                        </button>
                    <?php endif; ?>

                    <button type="reset" class="btn limpar">
                        <i class="bi bi-eraser"></i>
                        Limpar
                    </button>

                </div>

            </form>
        </main>
    </div>

    <script>
        function mascaraRenavam(input) {
            let valor = input.value.replace(/\D/g, '');
            valor = valor.substring(0, 11);
            valor = valor.replace(/^(\d{4})(\d)/, '$1.$2');
            valor = valor.replace(/^(\d{4})\.(\d{6})(\d)/, '$1.$2-$3');
            input.value = valor;
        }

        function mascaraChassi(input) {
            let valor = input.value.toUpperCase();
            valor = valor.replace(/[^A-Z0-9]/g, '');
            valor = valor.replace(/[IOQ]/g, '');
            valor = valor.substring(0, 17);
            input.value = valor;
        }
    </script>
</body>

</html>