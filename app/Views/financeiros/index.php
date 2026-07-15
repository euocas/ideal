<?php
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

use App\Config\SistemaConstantes;
use App\Config\FinanceiroCategorias;

/** @var \App\Models\Funcionario|null $funcionarioBusca */
/** @var \App\Models\FinanceiroObra|null $financeiroObra */
/** @var \App\Models\FinanceiroAutomovel|null $financeiroAutomovel */

$titulo = "Financeiro";
$favicon = "/ideal/public/assets/icon/financeiro3.png";

require_once __DIR__ . "/../includes/header.php";

$aba = $_GET["aba"] ?? "funcionario";
$abas = ["funcionario", "obra", "automovel"];
if (!in_array($aba, $abas)) {
    $aba = "funcionario";
}

$tipo = $_GET["tipo"] ?? "entrada";
$tipos = ["entrada", "saida", "periodo"];
if (!in_array($tipo, $tipos)) {
    $tipo = "entrada";
}

$cpfBusca = $_GET["cpf"] ?? ($_POST["cpf"] ?? "");
$mesBusca = $_GET["mes"] ?? ($_POST["mes"] ?? date("m"));
$anoBusca = $_GET["ano"] ?? ($_POST["ano"] ?? date("Y"));

$funcModelExiste = isset($funcionarioBusca) && is_object($funcionarioBusca);

// Regra inteligente para Data Padrão de formulários (Ajusta pro mês selecionado)
$mesStr = str_pad($mesBusca, 2, "0", STR_PAD_LEFT);
if ($mesBusca == date("m") && $anoBusca == date("Y")) {
    $dataPadrao = date("Y-m-d"); // Se for o mês atual, usa hoje
} else {
    $dataPadrao = date("Y-m-t", strtotime("{$anoBusca}-{$mesStr}-01")); // Usa o último dia do mês pesquisado
}

$fnNome = $funcModelExiste ? $funcionarioBusca->getNome() : "—";
$fnStatus = $funcModelExiste ? ucfirst($funcionarioBusca->getStatus()) : "—";
$fnStatusClass = strtolower($fnStatus) === "ativo" ? "ativo" : "inativo";
$fnCpf = $funcModelExiste ? $funcionarioBusca->getCpf() : "—";
if ($fnCpf !== "—" && strlen($fnCpf) === 11) {
    $fnCpf =
        substr($fnCpf, 0, 3) .
        "." .
        substr($fnCpf, 3, 3) .
        "." .
        substr($fnCpf, 6, 3) .
        "-" .
        substr($fnCpf, 9, 2);
}
$fnAdmissao =
    $funcModelExiste && $funcionarioBusca->getDataAdmissao()
        ? date("d/m/Y", strtotime($funcionarioBusca->getDataAdmissao()))
        : "—";
$fnCargo = $funcModelExiste ? $funcionarioBusca->getCargoFuncao() : "—";
$fnContrato = $funcModelExiste ? $funcionarioBusca->getTipoContrato() : "—";
$fnBanco = $funcModelExiste
    ? $funcionarioBusca->getAgencia() . " / " . $funcionarioBusca->getConta()
    : "—";

$isEditObra = isset($financeiroObra) && is_object($financeiroObra);

$actionObra = $isEditObra
    ? "/ideal/public/index.php?url=financeiro-obra/update&id={$financeiroObra->getIdFinanceiroObra()}"
    : "/ideal/public/index.php?url=financeiro-obra/store";

$isEditAutomovel =
    isset($financeiroAutomovel) && is_object($financeiroAutomovel);
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
        <?php include __DIR__ . "/../includes/sidebar.php"; ?>

        <main class="main-content">


            <div class="abas-container">
                <a href="?url=financeiros&aba=funcionario" class="aba <?= $aba ===
                "funcionario"
                    ? "ativa"
                    : "" ?>"><i
                        class="fa-solid fa-user-tie"></i> Funcionário</a>
                <a href="?url=financeiros&aba=obra" class="aba <?= $aba ===
                "obra"
                    ? "ativa"
                    : "" ?>"><i
                        class="fa-solid fa-hard-hat"></i> Obra</a>
                <a href="?url=financeiros&aba=automovel" class="aba <?= $aba ===
                "automovel"
                    ? "ativa"
                    : "" ?>"><i
                        class="fa-solid fa-car"></i> Automóvel</a>
            </div>

            <?php if (isset($_SESSION["mensagem_sucesso"])): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($_SESSION["mensagem_sucesso"]) ?>
                </div>
                <?php unset($_SESSION["mensagem_sucesso"]); ?>
            <?php endif; ?>
            <?php if (isset($_SESSION["mensagem_erro"])): ?>
                <div class="alert alert-error">
                    <?= htmlspecialchars($_SESSION["mensagem_erro"]) ?>
                </div>
                <?php unset($_SESSION["mensagem_erro"]); ?>
            <?php endif; ?>

            <?php if ($aba === "funcionario"): ?>

                <section class="card">
                    <div class="card-titulo">
                        <i class="fa-solid fa-user-tie icone-aba"></i>
                        <div>
                            <h2>Financeiro do Funcionário</h2>
                            <p>Gerencie os lançamentos financeiros do funcionário.</p>
                        </div>
                    </div>

                    <form id="form-busca-func"
                        action="/ideal/public/index.php?url=financeiros&aba=funcionario&tipo=<?= $tipo ?>" method="POST">
                        <div class="financeiro-topo">
                            <div class="grid-busca">
                                <div class="busca-box">
                                    <h2><i class="fa-solid fa-user"></i> LOCALIZAR FUNCIONÁRIO</h2>
                                    <div class="form-busca">
                                        <div class="input-group">
                                            <label>CPF</label>
                                            <input type="text" name="cpf" value="<?= htmlspecialchars(
                                                $cpfBusca,
                                            ) ?>"
                                                placeholder="000.000.000-00" maxlength="14" oninput="mascaraCPF(this)"
                                                required>
                                        </div>
                                        <button type="submit" name="acao" value="localizar" class="btn-buscar"><i
                                                class="bi bi-search"></i> LOCALIZAR</button>
                                    </div>
                                </div>
                                <div class="dica-box">
                                    <h3><i class="fa-solid fa-circle-info"></i> DICA</h3>
                                    <p><strong>Localize</strong> o funcionário pelo <strong>CPF</strong>. Em seguida,
                                        selecione o período para consultar ou registrar lançamentos.</p>
                                </div>
                            </div>

                            <div class="form-group periodo">
                                <div class="titulo-periodo">
                                    <label>Período de Referência <span class="obrigatorio">*</span></label>
                                </div>
                                <div class="periodo-grid">
                                    <select name="mes" required>
                                        <option value="">Mês</option>
                                        <?php foreach (
                                            SistemaConstantes::MESES
                                            as $numero => $nome
                                        ): ?>
                                            <option value="<?= $numero ?>" <?= $numero == $mesBusca? "selected" : "" ?>>
                                                <?= $nome ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <select name="ano" required>
                                        <option value="">Ano</option>
                                        <?php
                                        $anoAtual = date("Y");
                                        for (
                                            $ano = $anoAtual - 3;
                                            $ano <= $anoAtual + 5;
                                            $ano++
                                        ): ?>
                                            <option value="<?= $ano ?>" <?= $ano == $anoBusca ? "selected": "" ?>><?= $ano ?></option>
                                        <?php endfor;
                                        ?>
                                    </select>
                                </div>
                                <div class="acoes-topo">
                                    <button type="submit" name="acao" value="buscar" class="btn-buscar"><i
                                            class="fa-solid fa-magnifying-glass"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="funcionario-card">
                        <div class="funcionario-conteudo">
                            <div class="funcionario-coluna funcionario-principal">
                                <div class="cabecalho-funcionario">
                                    <div class="nome-funcionario">
                                        <i class="fa-solid fa-helmet-safety"></i>
                                        <h2 class="funcionario-nome"><?= $fnNome ?></h2>
                                    </div>
                                    <span class="status <?= $fnStatusClass ?>"><i class="fa-solid fa-circle"></i>
                                        <?= $fnStatus ?></span>
                                </div>
                                <hr>
                                <div class="funcionario-info">
                                    <div class="info-item">
                                        <i class="fa-regular fa-id-card"></i>
                                        <div>
                                            <span class="funcionario-titulo">CPF</span>
                                            <span class="funcionario-valor"><?= $fnCpf ?></span>
                                        </div>
                                    </div>
                                    <span class="separador">•</span>
                                    <div class="info-item">
                                        <i class="fa-regular fa-calendar"></i>
                                        <div>
                                            <span class="funcionario-titulo">Admissão</span>
                                            <span class="funcionario-valor"><?= $fnAdmissao ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="funcionario-coluna">
                                <div class="funcionario-item">
                                    <i class="fa-solid fa-user-tie"></i>
                                    <div>
                                        <span class="funcionario-titulo">Cargo</span>
                                        <span class="funcionario-valor"><?= htmlspecialchars(
                                            $fnCargo,
                                        ) ?></span>
                                    </div>
                                </div>
                                <div class="funcionario-item">
                                    <i class="fa-solid fa-building-columns"></i>
                                    <div>
                                        <span class="funcionario-titulo">Banco / Conta</span>
                                        <span class="funcionario-valor"><?= htmlspecialchars(
                                            $fnBanco,
                                        ) ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="funcionario-coluna">
                                <div class="funcionario-item">
                                    <i class="fa-solid fa-clipboard-list"></i>
                                    <div>
                                        <span class="funcionario-titulo">Tipo de Contrato</span>
                                        <span class="funcionario-valor"><?= htmlspecialchars(
                                            $fnContrato,
                                        ) ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="card">
                    <div class="subabas-container">
                        <a href="?url=financeiros&aba=funcionario&tipo=entrada&cpf=<?= $cpfBusca ?>&mes=<?= $mesBusca ?>&ano=<?= $anoBusca ?>"
                            class="subaba entrada <?= $tipo === "entrada"
                                ? "ativa"
                                : "" ?>"><i
                                class="fa-solid fa-plus"></i> Novo Lançamento (Proventos)</a>
                        <a href="?url=financeiros&aba=funcionario&tipo=saida&cpf=<?= $cpfBusca ?>&mes=<?= $mesBusca ?>&ano=<?= $anoBusca ?>"
                            class="subaba saida <?= $tipo === "saida"
                                ? "ativa"
                                : "" ?>"><i class="fa-solid fa-minus"></i>
                            Nova Saída (Descontos)</a>
                        <a href="?url=financeiros&aba=funcionario&tipo=periodo&cpf=<?= $cpfBusca ?>&mes=<?= $mesBusca ?>&ano=<?= $anoBusca ?>"
                            class="subaba periodo <?= $tipo === "periodo"
                                ? "ativa"
                                : "" ?>"><i
                                class="fa-solid fa-list"></i> Lançamentos do Período</a>
                    </div>

                    <div class="conteudo-aba">

                        <?php if ($tipo === "entrada"): ?>
                            <div class="entrada-container">
                                <div class="entrada-formulario">
                                    <form id="form-entrada" action="/ideal/public/index.php?url=financeiros/storeFuncionario"
                                        method="POST">
                                        <input type="hidden" name="tipo" value="entrada">
                                        <input type="hidden" name="idFuncionario"
                                            value="<?= $funcModelExiste
                                                ? $funcionarioBusca->getIdFuncionario()
                                                : "" ?>">
                                        <input type="hidden" name="cpf_hidden" value="<?= htmlspecialchars(
                                            $cpfBusca,
                                        ) ?>">
                                        <input type="hidden" name="mes_hidden" value="<?= htmlspecialchars(
                                            $mesBusca,
                                        ) ?>">
                                        <input type="hidden" name="ano_hidden" value="<?= htmlspecialchars(
                                            $anoBusca,
                                        ) ?>">

                                        <div class="grid-entrada">
                                            <div class="form-group">
                                                <label>Tipo de Proventos (Categoria) <span class="obrigatorio">*</span></label>
                                                <select name="categoria" required>
                                                    <option value="">Selecione o tipo</option>
                                                    <?php foreach (
                                                        FinanceiroCategorias::PROVENTOS
                                                        as $provento
                                                    ): ?>
                                                        <option value="<?= $provento ?>"><?= $provento ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label>Descrição <span class="obrigatorio">*</span></label>
                                                <input type="text" name="descricao" placeholder="Descreva o provento"
                                                    maxlength="100" required>
                                            </div>

                                            <div class="form-group">
                                                <label>Data do Pagamento <span class="obrigatorio">*</span></label>
                                                <input type="date" name="dataReferencia" value="<?= $dataPadrao ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label>Forma de Pagamento <span class="obrigatorio">*</span></label>
                                                <select name="formaPagamento" required>
                                                    <option value="">Selecione</option>
                                                    <?php foreach (
                                                        FinanceiroCategorias::FORMAS_PAGAMENTO
                                                        as $forma
                                                    ): ?>
                                                        <option value="<?= $forma ?>"><?= $forma ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="contaPagamento">Conta Pagamento</label>
                                                <select id="contaPagamento" name="contaPagamento">
                                                    <option value="">Selecione</option>
                                                    <?php foreach (
                                                        FinanceiroCategorias::CONTAS_PAGAMENTO
                                                        as $valor => $descricao
                                                    ): ?>
                                                        <option value="<?= $valor ?>"><?= $descricao ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label>Valor <span class="obrigatorio">*</span></label>
                                                <div class="input-prefixo">
                                                    <span class="prefixo">R$</span>
                                                    <input type="number" step="0.01" name="valor" placeholder="0,00" required>
                                                </div>
                                            </div>

                                            <div class="form-group span-2">
                                                <label>Observação (Opcional)</label>
                                                <textarea name="observacao" rows="4" maxlength="250"
                                                    placeholder="Informações adicionais sobre o provento..."></textarea>
                                            </div>
                                        </div>


                                        <div class="acoes-entrada">
                                            <button type="submit" class="btn salvar"><i class="fa-solid fa-floppy-disk"></i>
                                                Salvar Entrada</button>
                                            <button type="reset" class="btn limpar"><i class="fa-solid fa-rotate-right"></i>
                                                Limpar</button>
                                        </div>


                                    </form>
                                </div>
                                <aside class="entrada-info">
                                    <div class="info-card entrada">
                                        <div class="info-header">
                                            <div class="info-icon"><i class="fa-solid fa-arrow-up"></i></div>
                                            <div>
                                                <h3>Proventos (Entradas)</h3>
                                            </div>
                                        </div>
                                        <p class="info-descricao">Registro de valores que o funcionário tem a receber.</p>
                                        <ul>
                                            <?php foreach ( FinanceiroCategorias::PROVENTOS as $provento ): ?>
                                                <li><?= $provento ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </aside>
                            </div>

                        <?php elseif ($tipo === "saida"): ?>
                            <div class="saida-container">
                                <div class="saida-formulario">
                                    <form id="form-saida" action="/ideal/public/index.php?url=financeiros/storeFuncionario"
                                        method="POST">
                                        <input type="hidden" name="tipo" value="saida">
                                        <input type="hidden" name="idFuncionario"
                                            value="<?= $funcModelExiste
                                                ? $funcionarioBusca->getIdFuncionario()
                                                : "" ?>">
                                        <input type="hidden" name="cpf_hidden" value="<?= htmlspecialchars(
                                            $cpfBusca,
                                        ) ?>">
                                        <input type="hidden" name="mes_hidden" value="<?= htmlspecialchars(
                                            $mesBusca,
                                        ) ?>">
                                        <input type="hidden" name="ano_hidden" value="<?= htmlspecialchars(
                                            $anoBusca,
                                        ) ?>">

                                        <div class="grid-saida">
                                            <div class="form-group">
                                                <label>Tipo de Descontos (Categoria) <span class="obrigatorio">*</span></label>
                                                <select name="categoria" required>
                                                    <option value="">Selecione o tipo</option>
                                                    <?php foreach (
                                                        FinanceiroCategorias::DESCONTOS
                                                        as $desconto
                                                    ): ?>
                                                        <option value="<?= $desconto ?>"><?= $desconto ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label>Descrição <span class="obrigatorio">*</span></label>
                                                <input type="text" name="descricao" placeholder="Descreva o desconto"
                                                    maxlength="100" required>
                                            </div>

                                            <div class="form-group">
                                                <label>Data do Pagamento <span class="obrigatorio">*</span></label>
                                                <input type="date" name="dataReferencia" value="<?= $dataPadrao ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label>Forma de Pagamento <span class="obrigatorio">*</span></label>
                                                <select name="formaPagamento" required>
                                                    <option value="">Selecione</option>
                                                    <?php foreach (
                                                        FinanceiroCategorias::FORMAS_PAGAMENTO
                                                        as $forma
                                                    ): ?>
                                                        <option value="<?= $forma ?>"><?= $forma ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="contaPagamento">Conta Pagamento</label>
                                                <select id="contaPagamento" name="contaPagamento">
                                                    <option value="">Selecione</option>
                                                    <?php foreach (
                                                        FinanceiroCategorias::CONTAS_PAGAMENTO
                                                        as $valor => $descricao
                                                    ): ?>
                                                        <option value="<?= $valor ?>"><?= $descricao ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label>Valor <span class="obrigatorio">*</span></label>
                                                <div class="input-prefixo">
                                                    <span class="prefixo">R$</span>
                                                    <input type="number" step="0.01" name="valor" placeholder="0,00" required>
                                                </div>
                                            </div>

                                            <div class="form-group span-2">
                                                <label>Observação (Opcional)</label>
                                                <textarea name="observacao" rows="4" maxlength="250"
                                                    placeholder="Informações adicionais sobre o desconto..."></textarea>
                                            </div>
                                        </div>

                                        <div class="acoes-saida">
                                            <button type="submit" class="btn salvar"><i class="fa-solid fa-floppy-disk"></i>
                                                Salvar Saída</button>
                                            <button type="reset" class="btn limpar"><i class="fa-solid fa-rotate-right"></i>
                                                Limpar</button>
                                        </div>
                                    </form>
                                </div>
                                <aside class="saida-info">
                                    <div class="info-card saida">
                                        <div class="info-header">
                                            <div class="info-icon"><i class="fa-solid fa-arrow-down"></i></div>
                                            <div>
                                                <h3>Descontos (Saídas)</h3>
                                                <p>Registro de valores descontados.</p>
                                            </div>
                                        </div>
                                        <ul>
                                            <?php foreach (
                                                FinanceiroCategorias::DESCONTOS
                                                as $desconto
                                            ): ?>
                                                <li><?= $desconto ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </aside>
                            </div>

                        <?php elseif ($tipo === "periodo"): ?>
                            <div class="resumo-lancamentos">
                                <div class="card-resumo entrada">
                                    <div class="icone"><i class="fas fa-arrow-up"></i></div>
                                    <div class="conteudo">
                                        <span>Salário Bruto</span>
                                        <strong>R$ <?= number_format(
                                            $resumo["entradas"],
                                            2,",",".",) ?></strong>
                                    </div>
                                </div>
                                <div class="card-resumo saida">
                                    <div class="icone"><i class="fas fa-arrow-down"></i></div>
                                    <div class="conteudo">
                                        <span>Total de Descontos</span>
                                        <strong>R$ <?= number_format(
                                            $resumo["saidas"],
                                            2,",", ".",) ?></strong>
                                    </div>
                                </div>
                                <div class="card-resumo saldo">
                                    <div class="icone"><i class="fas fa-dollar-sign"></i></div>
                                    <div class="conteudo">
                                        <span>Salário Líquido</span>
                                        <strong>R$ <?= number_format(
                                            $resumo["saldo"],
                                            2,",",".",) ?></strong>
                                    </div>
                                </div>
                            </div>

                            <div class="tabela-lancamentos">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Data</th>
                                            <th>Categoria</th>
                                            <th>Descrição</th>
                                            <th class="text-right">Valor</th>
                                            <th class="text-center">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($lancamentos)): ?>
                                            <tr>
                                                <td colspan="5" class="text-center">Nenhum lançamento encontrado para este período.
                                                </td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach (
                                                $lancamentos
                                                as $l  ): ?>
                                                <tr>
                                                    <td><?= date("d/m/Y",
                                                        strtotime( $l[ "dataReferencia" ],  ),) ?></td>
                                                    <td>
                                                        <?php if (
                                                            $l["categoriaTipo" ] === "ENTRADA"
                                                        ): ?>
                                                            <span class="badge-entrada"><i class="fa-solid fa-arrow-up"></i>
                                                                <?= htmlspecialchars(
                                                                    $l[ "categoriaNome"   ], ) ?></span>
                                                        <?php else: ?>
                                                            <span class="badge-saida"><i class="fa-solid fa-arrow-down"></i>
                                                                <?= htmlspecialchars(
                                                                    $l[  "categoriaNome"   ], ) ?></span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= htmlspecialchars( $l["descricao"],) ?></td>
                                                    <td
                                                        class="<?= $l[
                                                            "categoriaTipo"
                                                        ] === "ENTRADA"
                                                            ? "valor-positivo"
                                                            : "valor-negativo" ?>">
                                                        <?= $l[
                                                            "categoriaTipo"
                                                        ] === "ENTRADA"
                                                            ? "+"
                                                            : "-" ?> R$
                                                        <?= number_format(
                                                            $l["valor"],
                                                            2,  ",", ".", ) ?>
                                                    </td>
                                                    <td class="acoes text-center">
                                                        <a href="/ideal/public/index.php?url=financeiros/deleteFuncionario&id=<?= $l[
                                                            "idFinanceiroFuncionario"
                                                        ] ?>&cpf=<?= $cpfBusca ?>&mes=<?= $mesBusca ?>&ano=<?= $anoBusca ?>"
                                                            class="btn-acao excluir"
                                                            onclick="return confirm('Tem certeza que deseja apagar este lançamento?')">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>

                    </div>
                </section>

                <?php if ($tipo === "entrada" || $tipo === "saida"): ?>
                    <div class="financeiro-dica">
                        <div class="financeiro-dica-conteudo">
                            <i class="fa-solid fa-circle-info"></i>
                            <span>
                                <strong>Dica:</strong> Use a aba <strong>"Lançamentos do Período"</strong> para visualizar todos
                                os registros de entradas e saídas deste funcionário no período selecionado.
                            </span>
                        </div>
                    </div>
                <?php endif; ?>

            <?php elseif ($aba === "obra"): ?>
                <div class="obra-topo">

                    <div class="obra-card">

                        <!-- ==========================
                              CABEÇALHO
                  =========================== -->
                        <div class="card-titulo">
                            <i class="fa-solid fa-hard-hat icone-aba"></i>
                            <div>
                                <h2>Financeiro da Obra</h2>
                                <p>Registre categorias, gastos e pagamentos vinculados às obras.</p>
                            </div>
                        </div>

                        <!-- ==========================
                                    BUSCA
                            =========================== -->

                        <div class="obra-busca">

                            <div class="busca-box">

                                <?php if (isset($_SESSION["mensagem_erro"])): ?>
                                    <div class="alert alert-error">
                                        <?= htmlspecialchars(
                                            $_SESSION["mensagem_erro"],
                                        ) ?>
                                    </div>
                                    <?php unset($_SESSION["mensagem_erro"]); ?>
                                <?php endif; ?>

                                <form class="form-busca"
                                    action="/ideal/public/index.php?url=financeiro-obra/buscar&aba=obra" method="POST">

                                    <div class="input-group">
                                        <label>Digite o código ou contrato da obra</label>

                                        <input type="text" name="buscaObra" placeholder="Ex.: 1 ou Obra 1" maxlength="100"
                                            required>
                                    </div>

                                    <button type="submit" class="btn-buscar">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                        LOCALIZAR
                                    </button>

                                </form>

                            </div>

                            <div class="dica-box">

                                <h3>
                                    <i class="fa-solid fa-circle-info"></i>
                                    DICA
                                </h3>

                                <p>
                                    Localize a obra pelo <strong>código</strong> ou
                                    <strong>nome</strong>. Após selecionar uma obra,
                                    será possível registrar gastos, consultar lançamentos
                                    e acompanhar o resumo financeiro.
                                </p>

                            </div>

                        </div>

                    </div>

                    <!-- ==========================
                              DADOS DA OBRA
                    =========================== -->
                    <div class="obra-info">

                        <!-- Dados -->
                        <div class="obra-dados">

                            <!-- Cabeçalho -->
                            <div class="obra-cabecalho">
                                <h3 class="obra-nome">
                                    <?= isset($obra)
                                        ? htmlspecialchars($obra->getContrato())
                                        : "Nenhuma obra localizada" ?>
                                </h3>

                                <?php
                                $statusObra = isset($obra)
                                    ? $obra->getStatus()
                                    : "";

                                $classeStatus = match ($statusObra) {
                                    "Em andamento" => "andamento",
                                    "Concluida", "Concluída" => "concluida",
                                    "Cancelada" => "cancelada",
                                    default => "andamento",
                                };
                                ?>

                                <?php if ($statusObra): ?>
                                    <span class="status <?= $classeStatus ?>">
                                        <?= htmlspecialchars($statusObra) ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <!-- Informações -->
                            <div class="obra-informacoes">

                                <div class="obra-item">
                                    <i class="fa-solid fa-hashtag"></i>
                                    <div>
                                        <span class="obra-label">Código</span>
                                        <strong>
                                            <?= isset($obra)
                                                ? htmlspecialchars(
                                                    (string) $obra->getIdObra(),
                                                )
                                                : "—" ?>
                                        </strong>
                                    </div>
                                </div>

                                <div class="obra-item">
                                    <i class="fa-solid fa-building"></i>
                                    <div>
                                        <span class="obra-label">Cliente</span>
                                        <strong>
                                            <?= isset($cliente) && $cliente
                                                ? htmlspecialchars(
                                                    $cliente->getNomeCliente(),
                                                )
                                                : "—" ?>
                                        </strong>
                                    </div>
                                </div>

                                <div class="obra-item">
                                    <i class="fa-solid fa-user"></i>
                                    <div>
                                        <span class="obra-label">Responsável</span>
                                        <strong>
                                            <?= isset($responsavel) &&
                                            $responsavel
                                                ? htmlspecialchars(
                                                    $responsavel->getNome(),
                                                )
                                                : "—" ?>
                                        </strong>
                                    </div>
                                </div>

                                <div class="obra-item">
                                    <i class="fa-solid fa-calendar-day"></i>
                                    <div>
                                        <span class="obra-label">Data de Início</span>
                                        <strong>
                                            <?= isset($obra) &&
                                            $obra->getDataInicio()
                                                ? $obra
                                                    ->getDataInicio()
                                                    ->format("d/m/Y")
                                                : "—" ?>
                                        </strong>
                                    </div>
                                </div>

                                <div class="obra-item">
                                    <i class="fa-solid fa-calendar-check"></i>
                                    <div>
                                        <span class="obra-label">Previsão de Término</span>
                                        <strong>
                                            <?= isset($obra) &&
                                            $obra->getDataFim()
                                                ? $obra
                                                    ->getDataFim()
                                                    ->format("d/m/Y")
                                                : "—" ?>
                                        </strong>
                                    </div>
                                </div>

                                <div class="obra-item">
                                    <i class="fa-solid fa-dollar-sign"></i>
                                    <div>
                                        <span class="obra-label">Valor Contratado</span>
                                        <strong>
                                            <?= isset($obra) &&
                                            $obra->getValorContratado() !== null
                                                ? 'R$ ' .
                                                    number_format(
                                                        $obra->getValorContratado(),
                                                        2,   ",", ".", ) : "—" ?>
                                        </strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Resumo Financeiro -->
                    <div class="obra-resumo">
                        <div class="resumo-item">
                            <span class="resumo-titulo">
                                Valor Contratado
                            </span>

                            <strong class="valor-contratado">
                                <?= isset($obra) &&
                                $obra->getValorContratado() !== null
                                    ? 'R$ ' .
                                        number_format(
                                            $obra->getValorContratado(),
                                            2,",", ".", ) : 'R$ 0,00' ?>
                            </strong>
                        </div>

                        <div class="resumo-item">
                            <span class="resumo-titulo">
                                Gasto Atual
                            </span>

                            <strong class="valor-gasto">
                                R$ <?= number_format(
                                    $gastoAtual ?? 0,
                                    2, ",", ".",) ?>
                            </strong>
                        </div>

                        <div class="resumo-item">
                            <span class="resumo-titulo">
                                Saldo Disponível
                            </span>

                            <strong class="valor-saldo">
                                R$ <?= number_format(
                                    $saldoDisponivel ?? 0,
                                    2,  ",", ".", ) ?>
                            </strong>
                        </div>
                    </div>
                </div>
                <div class="obra-lancamentos">
                    <div class="obra-esquerda">
                        <div class="obra-formulario">

                            <!-- Cabeçalho -->
                            <div class="formulario-header">
                                <h3>
                                    <i class="fa-solid fa-paperclip"></i>
                                    Registrar Gasto
                                </h3>
                            </div>

                            <form id="form-obra" action="<?= $actionObra ?>" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="idObra" value="<?= isset(
                                    $obra,
                                )
                                    ? $obra->getIdObra()
                                    : "" ?>">
                                <div class="formulario-grid">

                                    <!-- Categoria -->
                                    <div class="form-group">
                                        <label>Categoria <span class="obrigatorio">*</span></label>

                                        <select name="categoria" required>
                                            <option value="">Selecione</option>

                                            <option value="Material" <?= $isEditObra &&
                                            $financeiroObra->getCategoria() ===
                                                "Material"
                                                ? "selected"
                                                : "" ?>> Material</option>

                                            <option value="Alimentação" <?= $isEditObra &&
                                            $financeiroObra->getCategoria() ===
                                                "Alimentação"
                                                ? "selected"
                                                : "" ?>> Alimentação</option>

                                            <option value="Transporte" <?= $isEditObra &&
                                            $financeiroObra->getCategoria() ===
                                                "Transporte"
                                                ? "selected"
                                                : "" ?>> Transporte</option>

                                            <option value="Hospedagem" <?= $isEditObra &&
                                            $financeiroObra->getCategoria() ===
                                                "Hospedagem"
                                                ? "selected"
                                                : "" ?>> Hospedagem</option>

                                            <option value="Equipamento" <?= $isEditObra &&
                                            $financeiroObra->getCategoria() ===
                                                "Equipamento"
                                                ? "selected"
                                                : "" ?>> Equipamento </option>

                                            <option value="Serviço" <?= $isEditObra &&
                                            $financeiroObra->getCategoria() ===
                                                "Serviço"
                                                ? "selected"
                                                : "" ?>> Serviço </option>
                                        </select>
                                    </div>

                                    <!-- Valor -->
                                    <div class="form-group">
                                        <label>Valor <span class="obrigatorio">*</span></label>

                                        <div class="input-prefixo">
                                            <span class="prefixo">R$</span>

                                            <input type="number" name="valor" step="0.01" min="0" placeholder="0,00"
                                                value="<?= $isEditObra
                                                    ? htmlspecialchars(
                                                        (string) $financeiroObra->getValor(),
                                                    )
                                                    : "" ?>"
                                                required>
                                        </div>
                                    </div>

                                    <!-- Data -->
                                    <div class="form-group">
                                        <label>Data do Gasto <span class="obrigatorio">*</span></label>

                                        <input type="date" name="dataGasto" value="<?= $isEditObra
                                            ? htmlspecialchars(
                                                $financeiroObra->getDataGasto(),
                                            )
                                            : "" ?>" required>
                                    </div>

                                    <!-- Forma de Pagamento -->
                                    <div class="form-group">
                                        <label>Forma de Pagamento <span class="obrigatorio">*</span></label>

                                        <select name="formaPagamento">
                                            <option value="">Selecione</option>
                                            <option value="PIX"
                                                <?= $isEditObra && $financeiroObra->getFormaPagamento() === "PIX"? "selected": "" ?>>PIX
                                             </option>

                                            <option value="Boleto"
                                                <?= $isEditObra && $financeiroObra->getFormaPagamento() === "Boleto"? "selected": "" ?>> Boleto
                                            </option>

                                            <option value="Cartão"
                                                <?= $isEditObra && $financeiroObra->getFormaPagamento() ==="Cartão" ? "selected": "" ?>> Cartão 
                                            </option>

                                            <option value="Transferência"
                                                <?= $isEditObra && $financeiroObra->getFormaPagamento() ==="Transferência"? "selected": "" ?>>Transferência
                                            </option>
                                        </select>
                                    </div>
                                    <!-- Fornecedor -->
                                <div class="form-group">
                                    <label>Fornecedor</label>
                                     <input type="text" name="fornecedor" maxlength="100" placeholder="Nome do fornecedor (opcional)" value="<?= $isEditObra? htmlspecialchars($financeiroObra->getFornecedor() ?? ""): "" ?>">
                                </div>

                                    <!-- Documento Fiscal -->
                                <div class="form-group">
                                    <label>Documento Fiscal</label>
                                    <input type="text" name="documentoFiscal" maxlength="100"  placeholder="Ex.: NF-e 125487 ou Recibo 4589" value="<?= $isEditObra ? htmlspecialchars($financeiroObra->getDocumentoFiscal() ?? ""): "" ?>">
                                </div>

                                    <!-- Descrição -->
                                    <div class="form-group span-2">
                                        <label>Descrição <span class="obrigatorio">*</span></label>
                                        <input type="text" name="descricao" maxlength="100" placeholder="Descrição do gasto" value="<?= $isEditObra ? htmlspecialchars( $financeiroObra->getDescricao(),) : "" ?>" required>
                                    </div>
                                </div>
                                <!-- Observação -->
                                <div class="form-group span-2">
                                    <label>Observação</label>
                                    <textarea name="observacao"maxlength="200" placeholder="Observações adicionais"><?= $isEditObra? htmlspecialchars( $financeiroObra->getObservacao() ?? "",): "" ?></textarea>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="obra-direita">
                        <div class="obra-historico">
                            <div class="historico-header">
                                <h3> <i class="fa-solid fa-list"></i>Últimos Lançamentos da Obra
                                </h3>
                                <a href="/ideal/public/index.php?url=financeiros&aba=obra&acao=historico"
                                    class="btn-historico"> Ver Todos
                                </a>
                            </div>

                            <div class="historico-tabela">
                                <table class="tabela-historico">
                                    <thead>
                                        <tr>
                                            <th>Data</th>
                                            <th>Descrição</th>
                                            <th>Categoria</th>
                                            <th>Valor</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php if (!empty($lancamentosObra)): ?>
                                            <?php foreach ($lancamentosObra as $lancamento): ?>
                                                <tr>
                                                    <td>
                                                        <?= date("d/m/Y",strtotime( $lancamento->getDataGasto(),  ),) ?>
                                                    </td>

                                                    <td>
                                                        <?= htmlspecialchars($lancamento->getDescricao(), ) ?>
                                                    </td>

                                                    <td>
                                                        <span class="badge">
                                                            <?= htmlspecialchars($lancamento->getCategoria() ??   "-", ) ?>
                                                        </span>
                                                    </td>

                                                    <td class="valor">
                                                        R$ <?= number_format($lancamento->getValor(),2,",", ".",) ?>
                                                    </td>
                                                    <td class="acoes">
                                                        <a href="/ideal/public/index.php?url=financeiro-obra/visualizar&aba=obra&id=<?= $lancamento->getIdFinanceiroObra() ?>"
                                                            class="btn-icon" title="Visualizar lançamento">
                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="5" class="sem-registros">
                                                    Nenhum lançamento financeiro encontrado para esta obra.
                                                </td>
                                            </tr>
                                        <?php endif; ?>

                                    </tbody>
                                </table>
                            </div>

                            <?php
                            $totalLancamentosExibidos = count(
                                $lancamentosObra ?? [], );
                            $totalUltimosLancamentos = array_reduce(
                                $lancamentosObra ?? [],
                                fn($total, $lancamento) => $total +
                                    $lancamento->getValor(), 0,);
                            ?>

                            <div class="lancamentos-footer">
                                <span>
                                    Total de Lançamentos:
                                    <strong>
                                        <?= $totalLancamentosExibidos ?>
                                    </strong>
                                </span>

                                <span>
                                    Total dos Últimos Lançamentos:
                                    <strong>
                                        R$ <?= number_format( $totalUltimosLancamentos,  2, ",",  ".", ) ?>
                                    </strong>
                                </span>

                            </div>

                        </div>

                    </div>
                </div>
                <!-- Botões -->
                <div class="formulario-acoes">

                    <?php if (!$isEditObra): ?>
                        <button type="submit" form="form-obra" class="btn salvar">
                            <i class="bi bi-floppy"></i>
                            Registrar Gasto
                        </button>

                    <?php else: ?>
                        <button type="submit" form="form-obra" class="btn alterar">
                            <i class="bi bi-pencil-square"></i>
                            Alterar
                        </button>

                        <a href="/ideal/public/index.php?url=financeiro-obra/delete&id=<?= $financeiroObra->getIdFinanceiroObra() ?>"
                            class="btn excluir" onclick="return confirm('Tem certeza que deseja excluir este registro?')">
                            <i class="bi bi-trash"></i>
                            Excluir
                        </a>
                    <?php endif; ?>

                    <button type="reset" form="form-obra" class="btn limpar">
                        <i class="bi bi-eraser"></i>
                        Limpar
                    </button>
                </div>
    </div>

    </div>

<?php elseif ($aba === "automovel"): ?>
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
                <div class="form-group">
                    <label><i class="fa-solid fa-car-side"></i> ID do Veículo</label>
                    <input type="number" name="idVeiculo"
                        value="<?= htmlspecialchars(
                            $isEditAutomovel
                                ? $financeiroAutomovel->getIdVeiculo()
                                : "",
                        ) ?>"
                        placeholder="Ex: 1" required min="1">
                </div>
                <div class="form-group">
                    <label><i class="fa-solid fa-gas-pump"></i> Combustível</label>
                    <div class="input-prefixo">
                        <span class="prefixo">R$</span>
                        <input type="number" name="combustivel" step="0.01" min="0"
                            value="<?= htmlspecialchars(
                                $isEditAutomovel
                                    ? $financeiroAutomovel->getCombustivel()
                                    : "",
                            ) ?>"
                            placeholder="0,00">
                    </div>
                </div>
                <div class="form-group">
                    <label><i class="fa-solid fa-screwdriver-wrench"></i> Manutenção</label>
                    <div class="input-prefixo">
                        <span class="prefixo">R$</span>
                        <input type="number" name="manutencao" step="0.01" min="0"
                            value="<?= htmlspecialchars(
                                $isEditAutomovel
                                    ? $financeiroAutomovel->getManutencao()
                                    : "",
                            ) ?>"
                            placeholder="0,00">
                    </div>
                </div>
                <div class="form-group">
                    <label><i class="fa-solid fa-file-invoice-dollar"></i> IPVA</label>
                    <div class="input-prefixo">
                        <span class="prefixo">R$</span>
                        <input type="number" name="ipva" step="0.01" min="0"
                            value="<?= htmlspecialchars(
                                $isEditAutomovel
                                    ? $financeiroAutomovel->getIpva()
                                    : "",
                            ) ?>"
                            placeholder="0,00">
                    </div>
                </div>
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
        <a href="/ideal/public/index.php?url=financeiros&aba=automovel" class="btn novo"><i class="bi bi-plus-lg"></i>
            Cadastrar</a>
        <?php if (!$isEditAutomovel): ?>
            <button type="submit" form="form-automovel" class="btn salvar"><i class="bi bi-floppy"></i>
                Salvar</button>
        <?php else: ?>
            <button type="submit" form="form-automovel" class="btn alterar"><i class="bi bi-pencil-square"></i>
                Alterar</button>
            <a href="/ideal/public/index.php?url=financeiros/deleteAutomovel&id=<?= $financeiroAutomovel->getIdFinanceiroAutomovel() ?>"
                class="btn excluir" onclick="return confirm('Tem certeza que deseja excluir este registro?')"><i
                    class="bi bi-trash"></i> Excluir</a>
        <?php endif; ?>
        <button type="reset" form="form-automovel" class="btn limpar"><i class="bi bi-eraser"></i>
            Limpar</button>
    </div>
<?php endif; ?>
</main>
</div>

<script>
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
    document.querySelectorAll('[name="combustivel"], [name="manutencao"], [name="ipva"]').forEach(el => el.addEventListener('input', calcularTotalAutomovel));
    calcularTotalAutomovel();
</script>
</body>

</html>