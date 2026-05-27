<?php
$isEdit = isset($veiculo) && !empty($veiculo);
$actionUrl = $isEdit ? "/ideal/public/index.php?url=veiculos/update&id={$veiculo['idVeiculo']}" : "/ideal/public/index.php?url=veiculos/store";
$renavamValue = $isEdit ? $veiculo['renavam'] : ($renavamBusca ?? '');

// Formatação inteligente dos anos (transforma YYYY em YYYY-01-01 para o input date ler)
$anoFabValue = '';
if (!empty($veiculo['anoFabricacao'])) {
    $anoFabValue = strlen($veiculo['anoFabricacao']) === 4 ? $veiculo['anoFabricacao'] . '-01-01' : $veiculo['anoFabricacao'];
}
$anoModValue = '';
if (!empty($veiculo['anoModelo'])) {
    $anoModValue = strlen($veiculo['anoModelo']) === 4 ? $veiculo['anoModelo'] . '-01-01' : $veiculo['anoModelo'];
}
// HEADERS ANTI CACHE
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

// TÍTULO
$titulo = 'Veículo';

// HEADER
require_once __DIR__ . '/../includes/header.php';

?>

<link rel="stylesheet" href="/ideal/public/assets/css/dashboard.css">
<link rel="shortcut icon" href="/ideal/public/assets/icons/veiculo.png" type="image/x-icon">
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
                                <label>RENAVAM</label>
                                <input type="text" name="renavam" oninput="mascaraRenavam(this)"
                                    placeholder="0000.000000-0" required maxlength="13">
                            </div>
                            <button type="submit" class="btn-buscar">BUSCAR</button>
                        </form>
                    </div>
                    <div class="dica-box">
                        <h3>DICA</h3>
                        <p>Digite o Renavam do veículo e clique em <strong>BUSCAR</strong>. Se não existir, você poderá
                            cadastrar um novo veículo.</p>
                    </div>
                </div>
            </section>

            <form id="form-dados" action="<?= $actionUrl ?>" method="POST" novalidate autocomplete="off">

                <section class="card">
                    <h2>Dados do Veículo</h2>
                    <div class="grid-form">
                        <div class="form-group">
                            <label>Renavam</label>
                            <input type="text" name="renavam" value="<?= htmlspecialchars($renavamValue) ?>"
                                oninput="mascaraRenavam(this)" placeholder="0000.000000-0" maxlength="13">
                        </div>
                        <div class="form-group">
                            <label>Placa</label>
                            <input type="text" name="placa" value="<?= htmlspecialchars($veiculo['placa'] ?? '') ?>"
                                placeholder="ABC1D23" maxlength="7">
                        </div>
                        <div class="form-group">
                            <label>Chassi</label>
                            <input type="text" name="chassi" value="<?= htmlspecialchars($veiculo['chassi'] ?? '') ?>"
                                oninput="mascaraChassi(this)" maxlength="17" placeholder="9BWZZZ377VT004251">
                        </div>
                        <div class="form-group">
                            <label>Marca</label>
                            <select name="marca">
                                <option value="">Selecione a marca</option>
                                <optgroup label="Utilitários leves">
                                    <option value="Fiat" <?= ($veiculo['marca'] ?? '') === 'Fiat' ? 'selected' : '' ?>>Fiat
                                    </option>
                                    <option value="Volkswagen" <?= ($veiculo['marca'] ?? '') === 'Volkswagen' ? 'selected' : '' ?>>Volkswagen</option>
                                    <option value="Chevrolet" <?= ($veiculo['marca'] ?? '') === 'Chevrolet' ? 'selected' : '' ?>>Chevrolet</option>
                                    <option value="Renault" <?= ($veiculo['marca'] ?? '') === 'Renault' ? 'selected' : '' ?>>Renault</option>
                                </optgroup>
                                <optgroup label="Picapes médias">
                                    <option value="Toyota" <?= ($veiculo['marca'] ?? '') === 'Toyota' ? 'selected' : '' ?>>
                                        Toyota</option>
                                    <option value="Ford" <?= ($veiculo['marca'] ?? '') === 'Ford' ? 'selected' : '' ?>>Ford
                                    </option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Modelo</label>
                            <select name="modelo">
                                <option value="">Selecione o modelo</option>
                                <optgroup label="Utilitários leves">
                                    <option value="Fiat Strada" <?= ($veiculo['modelo'] ?? '') === 'Fiat Strada' ? 'selected' : '' ?>>Fiat Strada</option>
                                    <option value="Volkswagen Saveiro" <?= ($veiculo['modelo'] ?? '') === 'Volkswagen Saveiro' ? 'selected' : '' ?>>Volkswagen Saveiro</option>
                                    <option value="Chevrolet Montana" <?= ($veiculo['modelo'] ?? '') === 'Chevrolet Montana' ? 'selected' : '' ?>>Chevrolet Montana</option>
                                    <option value="Fiat Fiorino" <?= ($veiculo['modelo'] ?? '') === 'Fiat Fiorino' ? 'selected' : '' ?>>Fiat Fiorino</option>
                                </optgroup>
                                <optgroup label="Picapes médias">
                                    <option value="Toyota Hilux" <?= ($veiculo['modelo'] ?? '') === 'Toyota Hilux' ? 'selected' : '' ?>>Toyota Hilux</option>
                                    <option value="Chevrolet S10" <?= ($veiculo['modelo'] ?? '') === 'Chevrolet S10' ? 'selected' : '' ?>>Chevrolet S10</option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Ano Fabricação</label>
                            <input type="date" name="anoFabricacao" value="<?= htmlspecialchars($anoFabValue) ?>">
                        </div>
                        <div class="form-group">
                            <label>Ano Modelo</label>
                            <input type="date" name="anoModelo" value="<?= htmlspecialchars($anoModValue) ?>">
                        </div>
                        <div class="form-group">
                            <label>Cor</label>
                            <select name="cor">
                                <option value="">Selecione</option>
                                <option value="Branco" <?= ($veiculo['cor'] ?? '') === 'Branco' ? 'selected' : '' ?>>Branco
                                </option>
                                <option value="Preto" <?= ($veiculo['cor'] ?? '') === 'Preto' ? 'selected' : '' ?>>Preto
                                </option>
                                <option value="Prata" <?= ($veiculo['cor'] ?? '') === 'Prata' ? 'selected' : '' ?>>Prata
                                </option>
                                <option value="Cinza" <?= ($veiculo['cor'] ?? '') === 'Cinza' ? 'selected' : '' ?>>Cinza
                                </option>
                            </select>
                        </div>
                        <h2 class="subtitulo-form" style="grid-column: 1 / -1;">Situação do Veículo</h2>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status">
                                <option value="">Selecione</option>
                                <option value="ATIVO" <?= ($veiculo['statusVeiculo'] ?? '') === 'ATIVO' ? 'selected' : '' ?>>Ativo</option>
                                <option value="EM MANUTENCAO" <?= ($veiculo['statusVeiculo'] ?? '') === 'EM MANUTENCAO' ? 'selected' : '' ?>>Em manutenção</option>
                                <option value="INATIVO" <?= ($veiculo['statusVeiculo'] ?? '') === 'INATIVO' ? 'selected' : '' ?>>Inativo</option>
                                <option value="VENDIDO" <?= ($veiculo['statusVeiculo'] ?? '') === 'VENDIDO' ? 'selected' : '' ?>>Vendido</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Quilometragem</label>
                            <input type="text" name="quilometragem"
                                value="<?= htmlspecialchars($veiculo['quilometragem'] ?? '') ?>" maxlength="9"
                                pattern="\d{1,7}" inputmode="numeric" placeholder="Ex: 125000">
                        </div>
                        <div class="form-group">
                            <label>Última Revisão</label>
                            <input type="date" name="ultimaRevisao"
                                value="<?= htmlspecialchars($veiculo['dataUltimaRevisao'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label>Próxima Revisão</label>
                            <input type="date" name="proximaRevisao"
                                value="<?= htmlspecialchars($veiculo['proximaRevisao'] ?? '') ?>">
                        </div>
                        <h2 class="subtitulo-form" style="grid-column: 1 / -1;">Responsável</h2>
                        <div class="form-group">
                            <label>Propriedade do Veículo</label>
                            <select name="posse">
                                <option value="">Selecione</option>
                                <option value="PROPRIO" <?= ($veiculo['tipoPosse'] ?? '') === 'PROPRIO' ? 'selected' : '' ?>>Próprio</option>
                                <option value="ALUGADO" <?= ($veiculo['tipoPosse'] ?? '') === 'ALUGADO' ? 'selected' : '' ?>>Alugado</option>
                                <option value="EMPRESTADO" <?= ($veiculo['tipoPosse'] ?? '') === 'EMPRESTADO' ? 'selected' : '' ?>>Emprestado</option>
                                <option value="TERCEIRIZADO" <?= ($veiculo['tipoPosse'] ?? '') === 'TERCEIRIZADO' ? 'selected' : '' ?>>Terceirizado</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Responsável pelo veículo</label>
                            <input type="text" name="responsavel"
                                value="<?= htmlspecialchars($veiculo['responsavelVeiculo'] ?? '') ?>" minlength="3"
                                pattern="[A-Za-zÀ-ÿ\s]+" placeholder="Digite o propreitário do veículo">
                        </div>
                        <div class="form-group observacoes">
                            <label>Observações</label>
                            <textarea
                                name="observacoes"><?= htmlspecialchars($veiculo['observacoes'] ?? '') ?></textarea>
                        </div>
                    </div>
                </section>

                <div class="acoes">
                    <a href="/ideal/public/index.php?url=veiculos" class="btn novo"
                        style="text-decoration:none; text-align:center; display:inline-block; line-height: 40px;">Novo</a>

                    <?php if (!$isEdit): ?>
                        <button type="submit" class="btn salvar">Salvar</button>
                    <?php else: ?>
                        <button type="submit" class="btn alterar">Alterar</button>
                        <a href="/ideal/public/index.php?url=veiculos/delete&id=<?= $veiculo['idVeiculo'] ?>"
                            class="btn excluir"
                            style="text-decoration:none; text-align:center; display:inline-block; line-height: 40px;"
                            onclick="return confirm('Excluir este veículo?')">Excluir</a>
                    <?php endif; ?>

                    <button type="reset" class="btn limpar">Limpar</button>
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

    <script>
        window.onpageshow = function (event) {
            if (event.persisted) {
                window.location.href = window.location.href;
            }
        };
    </script>
</body>

</html>