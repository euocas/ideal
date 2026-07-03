<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
use App\Config\SistemaConstantes;
use App\Config\FinanceiroCategorias;



/** @var \App\Models\FinanceiroFuncionario|null $financeiroFuncionario */
/** @var \App\Models\FinanceiroObra|null $financeiroObra */
/** @var \App\Models\FinanceiroAutomovel|null $financeiroAutomovel */

// TÍTULO
$titulo = 'Financeiro';
$favicon = '/ideal/public/assets/icon/financeiro3.png';

require_once __DIR__ . '/../includes/header.php';

// Aba ativa (funcionario | obra | automovel)
$aba = $_GET['aba'] ?? 'funcionario';
$abas = ['funcionario', 'obra', 'automovel'];
if (!in_array($aba, $abas)) {
    $aba = 'funcionario';
}

//Sub-Aba ativa para o funcionário (entrada | saída | período)
$tipo = $_GET['tipo'] ?? 'entrada';
$tipos = ['entrada', 'saida', 'periodo'];
if (!in_array($tipo, $tipos)) {
    $tipo = 'entrada';
}



// Modo edição
$isEditFuncionario = isset($financeiroFuncionario) && is_object($financeiroFuncionario);
$isEditObra = isset($financeiroObra) && is_object($financeiroObra);
$isEditAutomovel = isset($financeiroAutomovel) && is_object($financeiroAutomovel);

// URLs de action
$actionFuncionario = $isEditFuncionario
    ? "/ideal/public/index.php?url=financeiros/updateFuncionario&id={$financeiroFuncionario->getIdFinanceiroFuncionario()}"
    : "/ideal/public/index.php?url=financeiros/storeFuncionario";

$actionObra = $isEditObra
    ? "/ideal/public/index.php?url=financeiros/updateObra&id={$financeiroObra->getIdFinanceiroObra()}"
    : "/ideal/public/index.php?url=financeiros/storeObra";

$actionAutomovel = $isEditAutomovel
    ? "/ideal/public/index.php?url=financeiros/updateAutomovel&id={$financeiroAutomovel->getIdFinanceiroAutomovel()}"
    : "/ideal/public/index.php?url=financeiros/storeAutomovel";
?>



<link rel="stylesheet" href="/ideal/public/assets/css/variables.css">
<link rel="stylesheet" href="/ideal/public/assets/css/base.css">
<link rel="stylesheet" href="/ideal/public/assets/css/component.css">
<link rel="stylesheet" href="/ideal/public/assets/css/forms.css">
<link rel="stylesheet" href="/ideal/public/assets/css/alerts.css">
<link rel="stylesheet" href="/ideal/public/assets/css/tables.css">

<link rel="stylesheet" href="/ideal/public/assets/css/dashboard.css">

<link rel="stylesheet" href="/ideal/public/assets/css/financeiro.css?v=<?= time() ?>">


</head>

<body>
    <div class="dashboard-container">

        <?php include __DIR__ . '/../includes/sidebar.php'; ?>

        <main class="main-content">

            <!-- TÍTULO -->
            <!-- <div class="fin-header">
                <div>
                    <h1><i class="fa-solid fa-dollar-sign"></i> Financeiro</h1>
                    <p>Gerencie as informações financeiras de funcionários, obras e automóveis.</p>
                </div>
            </div> -->

            <!-- ALERTAS DE SESSÃO -->
            <?php if (isset($_SESSION['mensagem_sucesso'])): ?>
                <div class="alert alert-success">
                    <?= $_SESSION['mensagem_sucesso'] ?>
                </div>
                <?php unset($_SESSION['mensagem_sucesso']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['mensagem_erro'])): ?>
                <div class="alert alert-error">
                    <?= $_SESSION['mensagem_erro'] ?>
                </div>
                <?php unset($_SESSION['mensagem_erro']); ?>
            <?php endif; ?>

            <!-- ABAS -->
            <div class="abas-container">
                <a href="?url=financeiros&aba=funcionario" class="aba <?= $aba === 'funcionario' ? 'ativa' : '' ?>">
                    <i class="fa-solid fa-user-tie"></i>
                    Funcionário
                </a>
                <a href="?url=financeiros&aba=obra" class="aba <?= $aba === 'obra' ? 'ativa' : '' ?>">
                    <i class="fa-solid fa-hard-hat"></i>
                    Obra
                </a>
                <a href="?url=financeiros&aba=automovel" class="aba <?= $aba === 'automovel' ? 'ativa' : '' ?>">
                    <i class="fa-solid fa-car"></i>
                    Automóvel
                </a>
            </div>

            <!-- ============================================================
             ABA: FINANCEIRO FUNCIONÁRIO
        ============================================================ -->
            <?php if ($aba === 'funcionario'): ?>

                <!-- CARD 1 -->
                <section class="card">

                    <div class="card-titulo">
                        <i class="fa-solid fa-user-tie icone-aba"></i>
                        <div>
                            <h2>Financeiro do Funcionário</h2>
                            <p>Gerencie os lançamentos financeiros do funcionário.</p>
                        </div>
                    </div>

                    <form id="form-funcionario" action="<?= $actionFuncionario ?>" method="POST">
                        <div class="financeiro-topo">

                            <div class="grid-busca">
                                <div class="busca-box">
                                    <h2>
                                        <i class="fa-solid fa-user"></i>
                                        LOCALIZAR FUNCIONÁRIO
                                    </h2>
                                    <?php if (!empty($mensagem)): ?>
                                        <div class="alert alert-warning">
                                            <?= $mensagem ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="form-busca"
                                        action="/ideal/public/index.php?url=financeiros&aba=funcionario" method="POST">
                                        <div class="input-group">
                                            <label>CPF</label>
                                            <input type="text" name="cpf" placeholder="000.000.000-00" maxlength="14"
                                                oninput="mascaraCPF(this)" required>
                                        </div>
                                        <button type="submit" name="acao" value="localizar" class="btn-buscar">
                                            <i class="bi bi-search"></i>
                                            LOCALIZAR
                                        </button>
                                    </div>
                                </div>

                                <div class="dica-box">
                                    <h3>
                                        <i class="fa-solid fa-circle-info"></i>
                                        DICA
                                    </h3>
                                    <p>
                                        Digite o CPF do funcionário e clique em
                                        <strong>LOCALIZAR</strong> para buscar os dados financeiros.
                                        Após localizar o funcionário, selecione o período desejado para registrar ou
                                        consultar lançamentos.
                                    </p>
                                </div>
                            </div>

                            <div class="form-group periodo">
                                <div class="titulo-periodo">
                                    <label>Período de Referência <span class="obrigatorio">*</span></label>
                                </div>
                                
                                <div class="periodo-grid">
                                    <select name="mes" required>
                                        <option value="">Mês</option>
                                        <?php foreach (SistemaConstantes::MESES as $numero => $nome): ?>
                                            <option value="<?= $numero ?>">
                                                <?= $nome ?>
                                            </option>

                                        <?php endforeach; ?>
                                    </select>
                                    <select name="ano" required>
                                        <option value="">Ano</option>
                                        <?php
                                        $anoAtual = date('Y');
                                        for ($ano = $anoAtual - 3; $ano <= $anoAtual + 5; $ano++):
                                            ?>
                                            <option value="<?= $ano ?>">
                                                <?= $ano ?>
                                            </option>
                                        <?php endfor; ?>

                                    </select>
                                </div>

                                <div class="acoes-topo">
                                    <button type="submit" name="acao" value="buscar" class="btn-buscar">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                        Buscar
                                    </button>
                                </div>


                            </div>

                        </div>
                    </form>

                    <div class="funcionario-card">

                        <!-- Avatar -->
                        <div class="funcionario-avatar">

                            <div class="avatar-circulo">
                                <i class="fa-solid fa-user"></i>
                            </div>

                        </div>

                        <!-- Informações -->
                        <div class="funcionario-conteudo">

                            <!-- Coluna 1 -->
                            <div class="funcionario-coluna">

                                <h2 class="funcionario-nome">
                                    Carlos Alberto da Silva
                                </h2>

                                <p class="funcionario-funcao">
                                    Eletricista
                                </p>

                                <p class="funcionario-cpf">
                                    CPF: 123.456.789-10
                                </p>

                                <p class="funcionario-admissao">
                                    Admissão: 15/03/2022
                                </p>

                                <span class="status ativo">
                                    Ativo
                                </span>

                            </div>

                            <!-- Coluna 2 -->
                            <div class="funcionario-coluna">

                                <div class="funcionario-item">

                                    <i class="fa-solid fa-users"></i>

                                    <div>

                                        <span class="funcionario-titulo">
                                            Departamento
                                        </span>

                                        <span class="funcionario-valor">
                                            Operacional
                                        </span>

                                    </div>

                                </div>

                                <div class="funcionario-item">

                                    <i class="fa-solid fa-user-tie"></i>

                                    <div>

                                        <span class="funcionario-titulo">
                                            Cargo
                                        </span>

                                        <span class="funcionario-valor">
                                            Eletricista
                                        </span>

                                    </div>

                                </div>

                                <div class="funcionario-item">

                                    <i class="fa-solid fa-gift"></i>

                                    <div>

                                        <span class="funcionario-titulo">
                                            Salário Base
                                        </span>

                                        <span class="funcionario-valor">
                                            R$ 3.500,00
                                        </span>

                                    </div>

                                </div>

                            </div>

                            <!-- Coluna 3 -->
                            <div class="funcionario-coluna">

                                <div class="funcionario-item">

                                    <i class="fa-solid fa-clipboard-list"></i>

                                    <div>

                                        <span class="funcionario-titulo">
                                            Tipo de Contrato
                                        </span>

                                        <span class="funcionario-valor">
                                            CLT
                                        </span>

                                    </div>

                                </div>

                                <div class="funcionario-item">

                                    <i class="fa-regular fa-clock"></i>

                                    <div>

                                        <span class="funcionario-titulo">
                                            Jornada
                                        </span>

                                        <span class="funcionario-valor">
                                            44h semanais
                                        </span>

                                    </div>

                                </div>

                                <div class="funcionario-item">

                                    <i class="fa-solid fa-building-columns"></i>

                                    <div>

                                        <span class="funcionario-titulo">
                                            Banco / Conta
                                        </span>

                                        <span class="funcionario-valor">
                                            Banco do Brasil - 001 / 12345-6
                                        </span>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </section>

                <!-- CARD 2  -->
                <!-- ============================================================
                    SUB-ABAS - LANÇAMENTOS DO FUNCIONÁRIO
                 ============================================================ -->
                <section class="card">

                    <div class="subabas-container">

                        <a href="?url=financeiros&aba=funcionario&tipo=entrada"
                            class="subaba entrada <?= $tipo === 'entrada' ? 'ativa' : '' ?>">
                            <i class="fa-solid fa-plus"></i>
                            Novo Lançamento (Proventos)
                        </a>

                        <a href="?url=financeiros&aba=funcionario&tipo=saida"
                            class="subaba saida <?= $tipo === 'saida' ? 'ativa' : '' ?>">
                            <i class="fa-solid fa-minus"></i>
                            Nova Saída (Descontos)
                        </a>

                        <a href="?url=financeiros&aba=funcionario&tipo=periodo"
                            class="subaba periodo <?= $tipo === 'periodo' ? 'ativa' : '' ?>">
                            <i class="fa-solid fa-list"></i>
                            Lançamentos do Período
                        </a>

                    </div>

                    <div class="conteudo-aba">

                        <?php if ($tipo === 'entrada'): ?>

                            <!-- Formulário Nova Entrada (proventos) -->
                            <div class="entrada-container">

                                <div class="entrada-formulario">

                                    <form id="form-entrada" action="<?= $actionFuncionario ?>" method="POST">

                                        <input type="hidden" name="tipo" value="entrada">

                                        <div class="grid-entrada">

                                            <!-- Tipo de Provento -->
                                            <div class="form-group">
                                                <label>Tipo de Proventos (Categoria) <span class="obrigatorio">*</span></label>
                                                <select name="categoria" required>
                                                    <option value="">Selecione o tipo</option>
                                                    <?php foreach (FinanceiroCategorias::PROVENTOS as $provento): ?>
                                                        <option value="<?= $provento ?>">
                                                            <?= $provento ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <!-- Descrição -->
                                            <div class="form-group">
                                                <label>Descrição <span class="obrigatorio">*</span></label>

                                                <input type="text" name="descricao" placeholder="Descreva o provento"
                                                    maxlength="100" required>
                                            </div>

                                            <!-- Referência -->
                                            <div class="form-group">
                                                <label>Referência</label>

                                                <input type="text" name="referencia" placeholder="Ex: 07/2026">
                                            </div>

                                            <!-- Data -->
                                            <div class="form-group">
                                                <label>Data do Pagamento <span class="obrigatorio">*</span></label>

                                                <input type="date" name="dataReferencia" required>
                                            </div>

                                            <!-- Forma de Pagamento -->
                                            <div class="form-group">
                                                <label>Forma de Pagamento <span class="obrigatorio">*</span></label>

                                                <select name="formaPagamento" required>
                                                    <option value="">Selecione</option>
                                                    <?php foreach (FinanceiroCategorias::FORMAS_PAGAMENTO as $forma): ?>
                                                        <option value="<?= $forma ?>">
                                                            <?= $forma ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <!-- Conta -->



                                            <div class="form-group">
                                                <label for="contaPagamento">Conta Pagamento</label>

                                                <select id="contaPagamento" name="contaPagamento">
                                                    <option value="">Selecione</option>

                                                    <?php foreach (FinanceiroCategorias::CONTAS_PAGAMENTO as $valor => $descricao): ?>
                                                        <option value="<?= $valor ?>">
                                                            <?= $descricao ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>


                                            <!-- Valor -->
                                            <div class="form-group">
                                                <label>Valor <span class="obrigatorio">*</span></label>

                                                <div class="input-prefixo">
                                                    <span class="prefixo">R$</span>

                                                    <input type="number" step="0.01" name="valor" placeholder="0,00" required>
                                                </div>
                                            </div>

                                            <!-- Observação -->
                                            <div class="form-group span-2">
                                                <label>Observação (Opcional)</label>

                                                <textarea name="observacao" rows="4" maxlength="250"
                                                    placeholder="Informações adicionais sobre o provento..."></textarea>
                                            </div>

                                        </div>

                                        <div class="acoes-entrada">

                                            <button type="submit" class="btn salvar">
                                                <i class="fa-solid fa-floppy-disk"></i>
                                                Salvar Entrada
                                            </button>

                                            <button type="submit" class="btn salvar-novo">
                                                <i class="fa-solid fa-plus"></i>
                                                Salvar e Novo
                                            </button>

                                            <button type="reset" class="btn limpar">
                                                <i class="fa-solid fa-rotate-right"></i>
                                                Limpar
                                            </button>

                                        </div>

                                    </form>



                                </div>

                                <aside class="entrada-info">

                                    <!-- Card Proventos -->
                                    <div class="info-card entrada">

                                        <div class="info-header">
                                            <div class="info-icon">
                                                <i class="fa-solid fa-arrow-up"></i>
                                            </div>
                                            <div>
                                                <h3>Proventos (Entradas)</h3>
                                            </div>
                                        </div>
                                        <p class="info-descricao">Registro de valores que o funcionário tem a receber.</p>

                                        <!-- <span class="info-subtitulo">Exemplos:</span> -->

                                        <ul>
                                            <?php foreach (FinanceiroCategorias::PROVENTOS as $provento): ?>
                                                <li><?= $provento ?></li>
                                            <?php endforeach; ?>
                                        </ul>

                                    </div>

                                    <!-- Card Descontos -->
                                    <!-- <div class="info-card saida">

                                        <div class="info-header">
                                            <div class="info-icon">
                                                <i class="fa-solid fa-arrow-down"></i>
                                            </div>
                                            <div><h3> Descontos (Saídas)</h3>
                                                <p>Registro de valores que serão descontados.</p>
                                            </div>
                                        </div>

                                        <span class="info-subtitulo">Exemplos:</span>

                                        <ul>
                                            <?php foreach (FinanceiroCategorias::DESCONTOS as $desconto): ?>
                                                <li><?= $desconto ?></li>
                                            <?php endforeach; ?>
                                        </ul>

                                    </div> -->

                                </aside>

                            </div>

                        <?php elseif ($tipo === 'saida'): ?>

                            <!-- Formulário Nova Saída -->
                            <div class="saida-container">

                                <div class="saida-formulario">

                                    <form id="form-saida" action="<?= $actionFuncionario ?>" method="POST">

                                        <input type="hidden" name="tipo" value="entrada">

                                        <div class="grid-saida">

                                            <!-- Tipo de Provento -->
                                            <div class="form-group">
                                                <label>Tipo de Descontos (Categoria) <span class="obrigatorio">*</span></label>
                                                <select name="categoria" required>
                                                    <option value="">Selecione o tipo</option>
                                                    <?php foreach (FinanceiroCategorias::DESCONTOS as $provento): ?>
                                                        <option value="<?= $provento ?>">
                                                            <?= $provento ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <!-- Descrição -->
                                            <div class="form-group">
                                                <label>Descrição <span class="obrigatorio">*</span></label>

                                                <input type="text" name="descricao" placeholder="Descreva o provento"
                                                    maxlength="100" required>
                                            </div>

                                            <!-- Referência -->
                                            <div class="form-group">
                                                <label>Referência</label>

                                                <input type="text" name="referencia" placeholder="Ex: 07/2026">
                                            </div>

                                            <!-- Data -->
                                            <div class="form-group">
                                                <label>Data do Pagamento <span class="obrigatorio">*</span></label>

                                                <input type="date" name="dataReferencia" required>
                                            </div>

                                            <!-- Forma de Pagamento -->
                                            <div class="form-group">
                                                <label>Forma de Pagamento <span class="obrigatorio">*</span></label>

                                                <select name="formaPagamento" required>
                                                    <option value="">Selecione</option>
                                                    <?php foreach (FinanceiroCategorias::FORMAS_PAGAMENTO as $forma): ?>
                                                        <option value="<?= $forma ?>">
                                                            <?= $forma ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <!-- Conta -->



                                            <div class="form-group">
                                                <label for="contaPagamento">Conta Pagamento</label>

                                                <select id="contaPagamento" name="contaPagamento">
                                                    <option value="">Selecione</option>

                                                    <?php foreach (FinanceiroCategorias::CONTAS_PAGAMENTO as $valor => $descricao): ?>
                                                        <option value="<?= $valor ?>">
                                                            <?= $descricao ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>


                                            <!-- Valor -->
                                            <div class="form-group">
                                                <label>Valor <span class="obrigatorio">*</span></label>

                                                <div class="input-prefixo">
                                                    <span class="prefixo">R$</span>

                                                    <input type="number" step="0.01" name="valor" placeholder="0,00" required>
                                                </div>
                                            </div>

                                            <!-- Observação -->
                                            <div class="form-group span-2">
                                                <label>Observação (Opcional)</label>

                                                <textarea name="observacao" rows="4" maxlength="250"
                                                    placeholder="Informações adicionais sobre o desconto..."></textarea>
                                            </div>

                                        </div>

                                        <div class="acoes-saida">

                                            <button type="submit" class="btn salvar">
                                                <i class="fa-solid fa-floppy-disk"></i>
                                                Salvar Entrada
                                            </button>

                                            <button type="submit" class="btn salvar-novo">
                                                <i class="fa-solid fa-plus"></i>
                                                Salvar e Novo
                                            </button>

                                            <button type="reset" class="btn limpar">
                                                <i class="fa-solid fa-rotate-right"></i>
                                                Limpar
                                            </button>

                                        </div>

                                    </form>



                                </div>

                                <aside class="saida-info">

                                    <!-- Card Descontos -->
                                    <div class="info-card saida">

                                        <div class="info-header">
                                            <div class="info-icon">
                                                <i class="fa-solid fa-arrow-down"></i>
                                            </div>
                                            <div>
                                                <h3> Descontos (Saídas)</h3>
                                                <p>Registro de valores que serão descontados.</p>
                                            </div>
                                        </div>



                                        <ul>
                                            <?php foreach (FinanceiroCategorias::DESCONTOS as $desconto): ?>
                                                <li><?= $desconto ?></li>
                                            <?php endforeach; ?>
                                        </ul>

                                    </div>

                                </aside>

                            </div>

                        <?php elseif ($tipo === 'periodo'): ?>

                            <!-- Tabela Lançamentos do Período -->

                        <?php endif; ?>

                    </div>
                </section>

                <div class="financeiro-dica">
                    <div class="financeiro-dica-conteudo">
                        <i class="fa-solid fa-circle-info"></i>
                        <span>
                            <strong>Dica:</strong> Use a aba <strong>"Lançamentos do Período"</strong> para visualizar todos
                            os registros de entradas e saídas deste funcionário no período selecionado.
                        </span>
                    </div>
                </div>






                <!-- ============================================================
             ABA: FINANCEIRO OBRA
        ============================================================ -->
            <?php elseif ($aba === 'obra'): ?>

                <section class="card">

                    <div class="card-titulo">
                        <i class="fa-solid fa-hard-hat icone-aba"></i>
                        <div>
                            <h2>Financeiro da Obra</h2>
                            <p>Registre gastos, categorias e pagamentos vinculados a obras.</p>
                        </div>
                    </div>

                    <form id="form-obra" action="<?= $actionObra ?>" method="POST">

                        <div class="grid-form">

                            <!-- ID OBRA -->
                            <div class="form-group">
                                <label><i class="fa-solid fa-building"></i> ID da Obra</label>
                                <input type="number" name="idObra"
                                    value="<?= htmlspecialchars($isEditObra ? $financeiroObra->getIdObra() : '') ?>"
                                    placeholder="Ex: 1" required min="1">
                            </div>

                            <!-- DESCRIÇÃO -->
                            <div class="form-group span-2">
                                <label><i class="fa-solid fa-file-lines"></i> Descrição <span
                                        class="obrigatorio">*</span></label>
                                <input type="text" name="descricao" maxlength="100"
                                    value="<?= htmlspecialchars($isEditObra ? $financeiroObra->getDescricao() : '') ?>"
                                    placeholder="Descreva o gasto" required>
                            </div>

                            <!-- CATEGORIA -->
                            <div class="form-group">
                                <label><i class="fa-solid fa-tags"></i> Categoria</label>
                                <select name="categoria">
                                    <option value="">Selecione</option>
                                    <?php
                                    $categorias = ['Material', 'Mão de Obra', 'Equipamento', 'Transporte', 'Serviço Terceirizado', 'Outros'];
                                    $catAtual = $isEditObra ? $financeiroObra->getCategoria() : '';
                                    foreach ($categorias as $c): ?>
                                        <option value="<?= $c ?>" <?= $catAtual === $c ? 'selected' : '' ?>><?= $c ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- VALOR -->
                            <div class="form-group">
                                <label><i class="fa-solid fa-money-bill-wave"></i> Valor <span
                                        class="obrigatorio">*</span></label>
                                <div class="input-prefixo">
                                    <span class="prefixo">R$</span>
                                    <input type="number" name="valor" step="0.01" min="0"
                                        value="<?= htmlspecialchars($isEditObra ? $financeiroObra->getValor() : '') ?>"
                                        placeholder="0,00" required>
                                </div>
                            </div>

                            <!-- DATA DO GASTO -->
                            <div class="form-group">
                                <label><i class="fa-solid fa-calendar-day"></i> Data do Gasto <span
                                        class="obrigatorio">*</span></label>
                                <input type="date" name="dataGasto"
                                    value="<?= htmlspecialchars($isEditObra ? $financeiroObra->getDataGasto() : '') ?>"
                                    required>
                            </div>

                            <!-- FORMA DE PAGAMENTO -->
                            <div class="form-group">
                                <label><i class="fa-solid fa-credit-card"></i> Forma de Pagamento</label>
                                <select name="formaPagamento">
                                    <option value="">Selecione</option>
                                    <?php
                                    $formas = ['Dinheiro', 'PIX', 'Cartão de Débito', 'Cartão de Crédito', 'Boleto', 'Transferência'];
                                    $formaAtual = $isEditObra ? $financeiroObra->getFormaPagamento() : '';
                                    foreach ($formas as $f): ?>
                                        <option value="<?= $f ?>" <?= $formaAtual === $f ? 'selected' : '' ?>><?= $f ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- OBSERVAÇÃO -->
                            <div class="form-group span-3">
                                <label><i class="fa-solid fa-note-sticky"></i> Observação</label>
                                <textarea name="observacao" maxlength="200"
                                    placeholder="Informações adicionais sobre o gasto..."><?= htmlspecialchars($isEditObra ? $financeiroObra->getObservacao() : '') ?></textarea>
                            </div>

                        </div>

                    </form>
                </section>


                <div class="acoes">
                    <a href="/ideal/public/index.php?url=financeiros&aba=funcionario" class="btn novo"> <i
                            class="bi bi-plus-lg"></i>Cadastrar</a>
                    <?php if (!$isEditObra): ?>
                        <button type="submit" form="form-obra" class="btn salvar"><i class="bi bi-floppy"></i>
                            Salvar</button>
                    <?php else: ?>
                        <button type="submit" form="form-obra" class="btn alterar"><i class="bi bi-pencil-square"></i>
                            Alterar</button>
                        <a href="/ideal/public/index.php?url=financeiros/deleteFuncionario&id=<?= $financeiroFuncionario->getIdFinanceiroFuncionario() ?>"
                            class="btn excluir" onclick="return confirm('Tem certeza que deseja excluir este registro?')">
                        <?php endif; ?>
                        <button type="reset" form="form-funcionario" class="btn limpar">
                            <i class="bi bi-eraser"></i>
                            Limpar
                        </button>
                </div>

                <!-- ============================================================
             ABA: FINANCEIRO AUTOMÓVEL
        ============================================================ -->
            <?php elseif ($aba === 'automovel'): ?>

                <section class="card">

                    <div class="card-titulo">
                        <i class="fa-solid fa-car icone-aba"></i>
                        <div>
                            <h2>Financeiro do Automóvel</h2>
                            <p>Registre gastos com combustível, manutenção e IPVA.</p>
                        </div>
                    </div>

                    <form id="form-automovel" action="<?= $actionAutomovel ?>" method="POST">

                        <div class="grid-form">

                            <!-- ID VEÍCULO -->
                            <div class="form-group">
                                <label><i class="fa-solid fa-car-side"></i> ID do Veículo</label>
                                <input type="number" name="idVeiculo"
                                    value="<?= htmlspecialchars($isEditAutomovel ? $financeiroAutomovel->getIdVeiculo() : '') ?>"
                                    placeholder="Ex: 1" required min="1">
                            </div>

                            <!-- COMBUSTÍVEL -->
                            <div class="form-group">
                                <label><i class="fa-solid fa-gas-pump"></i> Combustível</label>
                                <div class="input-prefixo">
                                    <span class="prefixo">R$</span>
                                    <input type="number" name="combustivel" step="0.01" min="0"
                                        value="<?= htmlspecialchars($isEditAutomovel ? $financeiroAutomovel->getCombustivel() : '') ?>"
                                        placeholder="0,00">
                                </div>
                            </div>

                            <!-- MANUTENÇÃO -->
                            <div class="form-group">
                                <label><i class="fa-solid fa-screwdriver-wrench"></i> Manutenção</label>
                                <div class="input-prefixo">
                                    <span class="prefixo">R$</span>
                                    <input type="number" name="manutencao" step="0.01" min="0"
                                        value="<?= htmlspecialchars($isEditAutomovel ? $financeiroAutomovel->getManutencao() : '') ?>"
                                        placeholder="0,00">
                                </div>
                            </div>

                            <!-- IPVA -->
                            <div class="form-group">
                                <label><i class="fa-solid fa-file-invoice-dollar"></i> IPVA</label>
                                <div class="input-prefixo">
                                    <span class="prefixo">R$</span>
                                    <input type="number" name="ipva" step="0.01" min="0"
                                        value="<?= htmlspecialchars($isEditAutomovel ? $financeiroAutomovel->getIpva() : '') ?>"
                                        placeholder="0,00">
                                </div>
                            </div>

                            <!-- CARD RESUMO TOTAL -->
                            <div class="form-group span-3">
                                <div class="resumo-total">
                                    <i class="fa-solid fa-calculator"></i>
                                    <span>Total estimado: </span>
                                    <strong id="total-automovel">R$ 0,00</strong>
                                </div>
                            </div>

                        </div>

                    </form>
                </section>

                <div class="acoes">

                    <a href="/ideal/public/index.php?url=financeiros&aba=automovel" class="btn novo">
                        <i class="bi bi-plus-lg"></i>
                        Cadastrar
                    </a>

                    <?php if (!$isEditAutomovel): ?>

                        <button type="submit" form="form-automovel" class="btn salvar">
                            <i class="bi bi-floppy"></i>
                            Salvar
                        </button>

                    <?php else: ?>

                        <button type="submit" form="form-automovel" class="btn alterar">
                            <i class="bi bi-pencil-square"></i>
                            Alterar
                        </button>

                        <a href="/ideal/public/index.php?url=financeiros/deleteAutomovel&id=<?= $financeiroAutomovel->getIdFinanceiroAutomovel() ?>"
                            class="btn excluir" onclick="return confirm('Tem certeza que deseja excluir este registro?')">
                            <i class="bi bi-trash"></i>
                            Excluir
                        </a>

                    <?php endif; ?>

                    <button type="reset" form="form-automovel" class="btn limpar">
                        <i class="bi bi-eraser"></i>
                        Limpar
                    </button>

                </div>

            <?php endif; ?>

        </main>
    </div>

    <script>
        // Calcula total em tempo real na aba automóvel
        function calcularTotalAutomovel() {
            const campos = ['combustivel', 'manutencao', 'ipva'];
            let total = 0;
            campos.forEach(campo => {
                const el = document.querySelector(`[name="${campo}"]`);
                if (el) total += parseFloat(el.value) || 0;
            });
            const el = document.getElementById('total-automovel');
            if (el) el.textContent = 'R$ ' + total.toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        document.querySelectorAll('[name="combustivel"], [name="manutencao"], [name="ipva"]')
            .forEach(el => el.addEventListener('input', calcularTotalAutomovel));

        calcularTotalAutomovel();
    </script>

</body>

</html>