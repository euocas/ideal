<?php
$isEdit = isset($veiculo) && is_object($veiculo);
$actionUrl = $isEdit ? "/ideal/public/index.php?url=veiculos/update&id={$veiculo->getIdVeiculo()}" : "/ideal/public/index.php?url=veiculos/store";

$renavamValue = $isEdit ? $veiculo->getRenavam() : '';
// Pega a placa buscada caso seja um cadastro novo
$placaBusca = $_GET['placa'] ?? ($_POST['placa'] ?? '');
$placaValue = $isEdit ? $veiculo->getPlaca() : $placaBusca;

// Formatação inteligente dos anos (transforma YYYY em YYYY-01-01 para o input date ler)
$anoFabValue = '';
if ($isEdit && !empty($veiculo->getAnoFabricacao())) {
    $anoFabValue = strlen((string)$veiculo->getAnoFabricacao()) === 4 ? $veiculo->getAnoFabricacao() . '-01-01' : $veiculo->getAnoFabricacao();
}
$anoModValue = '';
if ($isEdit && !empty($veiculo->getAnoModelo())) {
    $anoModValue = strlen((string)$veiculo->getAnoModelo()) === 4 ? $veiculo->getAnoModelo() . '-01-01' : $veiculo->getAnoModelo();
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
                        <?php if (!empty($mensagem)): ?>
                            <p style="color: #e74c3c; margin-bottom: 10px; font-weight: bold;">
                                <?= htmlspecialchars($mensagem); ?>
                            </p>
                        <?php endif; ?>
                        <form class="form-busca" action="/ideal/public/index.php?url=veiculos" method="POST">
                            <div class="input-group">
                                <label>PLACA</label>
                                <input type="text" name="placa" oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '')"
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
                        <p>Digite a placa do veículo (padrão antigo ou Mercosul) e clique em <strong>BUSCAR</strong>. Se não existir, você poderá cadastrar um novo veículo.</p>
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

                    <h2>Dados do Veículo</h2>
                    <div class="grid-form">
                        <div class="form-group">
                            <label>Renavam</label>
                            <input type="text" name="renavam" value="<?= htmlspecialchars($renavamValue ?? '') ?>"
                                oninput="mascaraRenavam(this)" placeholder="0000.000000-0" maxlength="13">
                        </div>
                        <div class="form-group">
                            <label>Placa</label>
                            <input type="text" name="placa" value="<?= htmlspecialchars($placaValue ?? '') ?>"
                                placeholder="ABC1D23" maxlength="7" oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '')">
                        </div>
                        <div class="form-group">
                            <label>Chassi</label>
                            <input type="text" name="chassi" value="<?= htmlspecialchars($isEdit ? ($veiculo->getChassi() ?? '') : '') ?>"
                                oninput="mascaraChassi(this)" maxlength="17" placeholder="9BWZZZ377VT004251">
                        </div>
                        <div class="form-group">
                            <label>Marca</label>
                            <select name="marca">
                                <option value="">Selecione a marca</option>
                                <optgroup label="Utilitários leves">
                                    <option value="Fiat" <?= ($isEdit && $veiculo->getMarca() === 'Fiat') ? 'selected' : '' ?>>Fiat</option>
                                    <option value="Volkswagen" <?= ($isEdit && $veiculo->getMarca() === 'Volkswagen') ? 'selected' : '' ?>>Volkswagen</option>
                                    <option value="Chevrolet" <?= ($isEdit && $veiculo->getMarca() === 'Chevrolet') ? 'selected' : '' ?>>Chevrolet</option>
                                    <option value="Renault" <?= ($isEdit && $veiculo->getMarca() === 'Renault') ? 'selected' : '' ?>>Renault</option>
                                </optgroup>
                                <optgroup label="Picapes médias">
                                    <option value="Toyota" <?= ($isEdit && $veiculo->getMarca() === 'Toyota') ? 'selected' : '' ?>>Toyota</option>
                                    <option value="Ford" <?= ($isEdit && $veiculo->getMarca() === 'Ford') ? 'selected' : '' ?>>Ford</option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Modelo</label>
                            <select name="modelo">
                                <option value="">Selecione o modelo</option>
                                <optgroup label="Utilitários leves">
                                    <option value="Fiat Strada" <?= ($isEdit && $veiculo->getModelo() === 'Fiat Strada') ? 'selected' : '' ?>>Fiat Strada</option>
                                    <option value="Volkswagen Saveiro" <?= ($isEdit && $veiculo->getModelo() === 'Volkswagen Saveiro') ? 'selected' : '' ?>>Volkswagen Saveiro</option>
                                    <option value="Chevrolet Montana" <?= ($isEdit && $veiculo->getModelo() === 'Chevrolet Montana') ? 'selected' : '' ?>>Chevrolet Montana</option>
                                    <option value="Fiat Fiorino" <?= ($isEdit && $veiculo->getModelo() === 'Fiat Fiorino') ? 'selected' : '' ?>>Fiat Fiorino</option>
                                </optgroup>
                                <optgroup label="Picapes médias">
                                    <option value="Toyota Hilux" <?= ($isEdit && $veiculo->getModelo() === 'Toyota Hilux') ? 'selected' : '' ?>>Toyota Hilux</option>
                                    <option value="Chevrolet S10" <?= ($isEdit && $veiculo->getModelo() === 'Chevrolet S10') ? 'selected' : '' ?>>Chevrolet S10</option>
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
                                <option value="Branco" <?= ($isEdit && $veiculo->getCor() === 'Branco') ? 'selected' : '' ?>>Branco</option>
                                <option value="Preto" <?= ($isEdit && $veiculo->getCor() === 'Preto') ? 'selected' : '' ?>>Preto</option>
                                <option value="Prata" <?= ($isEdit && $veiculo->getCor() === 'Prata') ? 'selected' : '' ?>>Prata</option>
                                <option value="Cinza" <?= ($isEdit && $veiculo->getCor() === 'Cinza') ? 'selected' : '' ?>>Cinza</option>
                            </select>
                        </div>
                        <h2 class="subtitulo-form">Situação do Veículo</h2>
                        <div class="form-group">
                            <!-- CORRIGIDO: name="statusVeiculo" -->
                            <label>Status</label>
                            <select name="statusVeiculo">
                                <option value="">Selecione</option>
                                <option value="ATIVO" <?= ($isEdit && $veiculo->getStatusVeiculo() === 'ATIVO') ? 'selected' : '' ?>>Ativo</option>
                                <option value="EM MANUTENCAO" <?= ($isEdit && $veiculo->getStatusVeiculo() === 'EM MANUTENCAO') ? 'selected' : '' ?>>Em manutenção</option>
                                <option value="INATIVO" <?= ($isEdit && $veiculo->getStatusVeiculo() === 'INATIVO') ? 'selected' : '' ?>>Inativo</option>
                                <option value="VENDIDO" <?= ($isEdit && $veiculo->getStatusVeiculo() === 'VENDIDO') ? 'selected' : '' ?>>Vendido</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Quilometragem</label>
                            <input type="text" name="quilometragem"
                                value="<?= htmlspecialchars($isEdit ? ($veiculo->getQuilometragem() ?? '') : '') ?>" maxlength="9"
                                pattern="\d{1,7}" inputmode="numeric" placeholder="Ex: 125000">
                        </div>
                        <div class="form-group">
                            <!-- CORRIGIDO: name="dataUltimaRevisao" -->
                            <label>Última Revisão</label>
                            <input type="date" name="dataUltimaRevisao"
                                value="<?= htmlspecialchars($isEdit ? ($veiculo->getDataUltimaRevisao() ?? '') : '') ?>">
                        </div>
                        <div class="form-group">
                            <label>Próxima Revisão</label>
                            <input type="date" name="proximaRevisao"
                                value="<?= htmlspecialchars($isEdit ? ($veiculo->getProximaRevisao() ?? '') : '') ?>">
                        </div>
                        <h2 class="subtitulo-form" style="grid-column: 1 / -1;">Responsável</h2>
                        <div class="form-group">
                            <!-- CORRIGIDO: name="tipoPosse" -->
                            <label>Propriedade do Veículo</label>
                            <select name="tipoPosse">
                                <option value="">Selecione</option>
                                <option value="PROPRIO" <?= ($isEdit && $veiculo->getTipoPosse() === 'PROPRIO') ? 'selected' : '' ?>>Próprio</option>
                                <option value="ALUGADO" <?= ($isEdit && $veiculo->getTipoPosse() === 'ALUGADO') ? 'selected' : '' ?>>Alugado</option>
                                <option value="EMPRESTADO" <?= ($isEdit && $veiculo->getTipoPosse() === 'EMPRESTADO') ? 'selected' : '' ?>>Emprestado</option>
                                <option value="TERCEIRIZADO" <?= ($isEdit && $veiculo->getTipoPosse() === 'TERCEIRIZADO') ? 'selected' : '' ?>>Terceirizado</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <!-- CORRIGIDO: name="responsavelVeiculo" -->
                            <label>Responsável pelo veículo</label>
                            <input type="text" name="responsavelVeiculo"
                                value="<?= htmlspecialchars($isEdit ? ($veiculo->getResponsavelVeiculo() ?? '') : '') ?>" minlength="3"
                                pattern="[A-Za-zÀ-ÿ\s]+" placeholder="Digite o propreitário do veículo">
                        </div>
                        <div class="form-group observacao">
                            <label>Observações</label>
                            <textarea
                                name="observacoes"><?= htmlspecialchars($isEdit ? ($veiculo->getObservacoes() ?? '') : '') ?></textarea>
                        </div>
                    </div>
                </section>

                <div class="acoes">
                    <a href="/ideal/public/index.php?url=veiculos"
   class="btn novo">
                        <i class="bi bi-plus-lg"></i>
                        Cadastrar</a>

                    <?php if (!$isEdit): ?>
                        <button type="submit" class="btn salvar">
                            <i class="bi bi-floppy"></i> Salvar
                        </button>
                    <?php else: ?>
                        <button type="submit" class="btn alterar">
                            <i class="bi bi-pencil-square"></i>
                            Alterar</button>
                        <a href="/ideal/public/index.php?url=veiculos/delete&id=<?= $veiculo->getIdVeiculo() ?>"
   class="btn excluir"
   onclick="return confirm('Excluir este veículo?')">
                    <?php endif; ?>

                    <button type="reset" class="btn limpar"><i class="bi bi-eraser"></i> Limpar</button>
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