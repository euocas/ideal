<?php

use App\Config\FinanceiroCategorias;
use App\Config\SistemaConstantes;

/** @var \App\Models\Funcionario|null $funcionarioBusca */
/** @var \App\Models\FinanceiroFuncionario|null $financeiroFuncionario */

$tipo = $tipo ?? ($_GET["tipo"] ?? "entrada");
$tipos = ["entrada", "saida", "periodo"];
$isEditFuncionario = isset($financeiroFuncionario) && $financeiroFuncionario instanceof \App\Models\FinanceiroFuncionario;

if (!in_array($tipo, $tipos)) {
    $tipo = "entrada";
}

$cpfBusca = $cpfBusca ?? ($_GET["cpf"] ?? ($_POST["cpf"] ?? ""));
$mesBusca = $mesBusca ?? ($_GET["mes"] ?? ($_POST["mes"] ?? date("m")));
$anoBusca = $anoBusca ?? ($_GET["ano"] ?? ($_POST["ano"] ?? date("Y")));

$funcModelExiste =
    isset($funcionarioBusca) && is_object($funcionarioBusca);

$mesStr = str_pad($mesBusca, 2, "0", STR_PAD_LEFT);

if ($mesBusca == date("m") && $anoBusca == date("Y")) {
    $dataPadrao = date("Y-m-d");
} else {
    $dataPadrao = date(
        "Y-m-t",
        strtotime("{$anoBusca}-{$mesStr}-01")
    );
}

$fnNome = $funcModelExiste
    ? $funcionarioBusca->getNome()
    : "—";

$fnStatus = $funcModelExiste
    ? ucfirst($funcionarioBusca->getStatus())
    : "—";

$fnStatusClass =
    strtolower($fnStatus) === "ativo"
    ? "ativo"
    : "inativo";

$fnCpf = $funcModelExiste
    ? $funcionarioBusca->getCpf()
    : "—";

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
    ? date(
        "d/m/Y",
        strtotime($funcionarioBusca->getDataAdmissao())
    )
    : "—";

$fnCargo = $funcModelExiste
    ? $funcionarioBusca->getCargoFuncao()
    : "—";

$fnContrato = $funcModelExiste
    ? $funcionarioBusca->getTipoContrato()
    : "—";

$fnBanco = $funcModelExiste
    ? $funcionarioBusca->getAgencia() .
    " / " .
    $funcionarioBusca->getConta()
    : "—";
?>


<section class="card">
    <div class="card-titulo">
        <i class="fa-solid fa-user-tie icone-aba"></i>
        <div>
            <h2>Financeiro do Funcionário</h2>
            <p>Gerencie os lançamentos financeiros do funcionário.</p>
        </div>
    </div>

    <form id="form-busca-func" action="/ideal/public/index.php?url=financeiro-funcionario/buscar&tipo=<?= $tipo ?>"
        method="POST">
        <div class="financeiro-topo">
            <div class="grid-busca">
                <div class="busca-box">
                    <h2><i class="fa-solid fa-user"></i> LOCALIZAR FUNCIONÁRIO</h2>
                    <div class="form-busca">
                        <div class="input-group">
                            <label>CPF</label>
                            <input type="text" name="cpf" value="<?= htmlspecialchars(
                                $cpfBusca,
                            ) ?>" placeholder="000.000.000-00" maxlength="14" oninput="mascaraCPF(this)" required>
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
                            <option value="<?= $numero ?>" <?= $numero == $mesBusca ? "selected" : "" ?>>
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
                            <option value="<?= $ano ?>" <?= $ano == $anoBusca ? "selected" : "" ?>><?= $ano ?>
                            </option>
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
                : "" ?>"><i class="fa-solid fa-plus"></i> Novo Lançamento (Proventos)</a>
        <a href="?url=financeiros&aba=funcionario&tipo=saida&cpf=<?= $cpfBusca ?>&mes=<?= $mesBusca ?>&ano=<?= $anoBusca ?>"
            class="subaba saida <?= $tipo === "saida"
                ? "ativa"
                : "" ?>"><i class="fa-solid fa-minus"></i>
            Nova Saída (Descontos)</a>
        <a href="?url=financeiros&aba=funcionario&tipo=periodo&cpf=<?= $cpfBusca ?>&mes=<?= $mesBusca ?>&ano=<?= $anoBusca ?>"
            class="subaba periodo <?= $tipo === "periodo"
                ? "ativa"
                : "" ?>"><i class="fa-solid fa-list"></i> Lançamentos do Período</a>
    </div>

    <div class="conteudo-aba">

        <?php if ($tipo === "entrada"): ?>
            <div class="entrada-container">
                <div class="entrada-formulario">

                    <form id="form-entrada"
                        action="/ideal/public/index.php?url=financeiro-funcionario/<?= $isEditFuncionario ? 'update' : 'store' ?>"
                        method="POST">
                        <input type="hidden" name="tipo" value="entrada">
                        <input type="hidden" name="idFuncionario" value="<?= $funcModelExiste
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
                        <?php if ($isEditFuncionario): ?>
                            <input type="hidden" name="idFinanceiroFuncionario"
                                value="<?= $financeiroFuncionario->getIdFinanceiroFuncionario() ?>">
                        <?php endif; ?>

                        <div class="grid-entrada">
                            <div class="form-group">
                                <label>Tipo de Proventos (Categoria) <span class="obrigatorio">*</span></label>
                                <select name="categoria" required>
                                    <option value="">Selecione o tipo</option>

                                    <?php foreach (FinanceiroCategorias::PROVENTOS as $provento): ?>
                                        <option value="<?= $provento ?>" <?= $isEditFuncionario && $financeiroFuncionario->getCategoria() === $provento ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($provento) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                            </div>

                            <div class="form-group">
                                <label>Descrição <span class="obrigatorio">*</span></label>
                                <input type="text" name="descricao" placeholder="Descreva o provento" maxlength="100"
                                    value="<?= $isEditFuncionario ? htmlspecialchars($financeiroFuncionario->getDescricao()) : '' ?>"
                                    required>
                            </div>

                            <div class="form-group">
                                <label>Data do Pagamento <span class="obrigatorio">*</span></label>
                                <input type="date" name="dataReferencia" value="<?= $isEditFuncionario ? htmlspecialchars($financeiroFuncionario->getDataReferencia())
                                    : $dataPadrao ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Forma de Pagamento <span class="obrigatorio">*</span></label>
                                <select name="formaPagamento" required>
                                    <option value="">Selecione</option>

                                    <?php
                                    $formaSelecionada = $isEditFuncionario
                                        ? $financeiroFuncionario->getFormaPagamento()
                                        : '';
                                    ?>

                                    <?php foreach (FinanceiroCategorias::FORMAS_PAGAMENTO as $forma): ?>
                                        <option value="<?= htmlspecialchars($forma) ?>" <?= $formaSelecionada === $forma ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($forma) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="contaPagamento">Conta Pagamento</label>
                                <select name="contaPagamento" required>
                                    <option value="">Selecione</option>

                                    <?php
                                    $contaSelecionada = $isEditFuncionario
                                        ? $financeiroFuncionario->getContaPagamento()
                                        : '';
                                    ?>

                                    <?php foreach (FinanceiroCategorias::CONTAS_PAGAMENTO as $conta): ?>
                                        <option value="<?= htmlspecialchars($conta) ?>" <?= $contaSelecionada === $conta ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($conta) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Valor <span class="obrigatorio">*</span></label>
                                <div class="input-prefixo">
                                    <span class="prefixo">R$</span>
                                    <input type="number" step="0.01" name="valor" placeholder="0,00"
                                        value="<?= $isEditFuncionario ? htmlspecialchars((string) $financeiroFuncionario->getValor()) : '' ?>"
                                        required>
                                </div>
                            </div>

                            <div class="form-group span-2">
                                <label>Observação (Opcional)</label>
                                <textarea name="observacao" rows="4" maxlength="250"
                                    placeholder="Informações adicionais sobre o provento..."><?= htmlspecialchars($financeiroFuncionario?->getObservacao() ?? '') ?></textarea>
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
                            <?php foreach (FinanceiroCategorias::PROVENTOS as $provento): ?>
                                <li><?= $provento ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </aside>
            </div>

        <?php elseif ($tipo === "saida"): ?>
            <div class="saida-container">
                <div class="saida-formulario">
                    <form id="form-saida"
                        action="/ideal/public/index.php?url=financeiro-funcionario/<?= $isEditFuncionario ? 'update' : 'store' ?>"
                        method="POST">
                        <input type="hidden" name="tipo" value="saida">
                        <input type="hidden" name="idFuncionario" value="<?= $funcModelExiste
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
                        <?php if ($isEditFuncionario): ?>
                            <input type="hidden" name="idFinanceiroFuncionario"
                                value="<?= $financeiroFuncionario->getIdFinanceiroFuncionario() ?>">
                        <?php endif; ?>

                        <div class="grid-saida">
                            <div class="form-group">
                                <label>Tipo de Descontos (Categoria) <span class="obrigatorio">*</span></label>

                                <select name="categoria" required>
                                    <option value="">Selecione o tipo</option>

                                    <?php foreach (FinanceiroCategorias::DESCONTOS as $desconto): ?>
                                        <option value="<?= $desconto ?>" <?= $isEditFuncionario && $financeiroFuncionario->getCategoria() === $desconto ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($desconto) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Descrição <span class="obrigatorio">*</span></label>
                                <input type="text" name="descricao" placeholder="Descreva o desconto" maxlength="100"
                                    value="<?= $isEditFuncionario ? htmlspecialchars($financeiroFuncionario->getDescricao()) : '' ?>"
                                    required>
                            </div>

                            <div class="form-group">
                                <label>Data do Pagamento <span class="obrigatorio">*</span></label>
                                <input type="date" name="dataReferencia" value="<?= $isEditFuncionario ? htmlspecialchars($financeiroFuncionario->getDataReferencia())
                                    : $dataPadrao ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Forma de Pagamento <span class="obrigatorio">*</span></label>
                                <select name="formaPagamento" required>
                                    <option value="">Selecione</option>
                                    <?php
                                    $formaSelecionada = $isEditFuncionario
                                        ? $financeiroFuncionario->getFormaPagamento()
                                        : '';
                                    ?>
                                    <?php foreach (FinanceiroCategorias::FORMAS_PAGAMENTO as $forma): ?>
                                        <option value="<?= htmlspecialchars($forma) ?>" <?= $formaSelecionada === $forma ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($forma) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="contaPagamento">Conta Pagamento</label>

                                <select name="contaPagamento" required>

                                    <option value="">Selecione</option>

                                    <?php
                                    $contaSelecionada = $isEditFuncionario
                                        ? $financeiroFuncionario->getContaPagamento()
                                        : '';
                                    ?>

                                    <?php foreach (FinanceiroCategorias::CONTAS_PAGAMENTO as $conta): ?>
                                        <option value="<?= htmlspecialchars($conta) ?>" <?= $contaSelecionada === $conta ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($conta) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Valor <span class="obrigatorio">*</span></label>
                                <div class="input-prefixo">
                                    <span class="prefixo">R$</span>
                                    <input type="number" step="0.01" name="valor" placeholder="0,00"
                                        value="<?= $isEditFuncionario ? htmlspecialchars((string) $financeiroFuncionario->getValor()) : '' ?>"
                                        required>
                                </div>
                            </div>

                            <div class="form-group span-2">
                                <label>Observação (Opcional)</label>
                                <textarea name="observacao" rows="4" maxlength="250"
                                    placeholder="Informações adicionais sobre o provento..."><?= htmlspecialchars($financeiroFuncionario?->getObservacao() ?? '') ?></textarea>
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
                            2,
                            ",",
                            ".",
                        ) ?></strong>
                    </div>
                </div>
                <div class="card-resumo saida">
                    <div class="icone"><i class="fas fa-arrow-down"></i></div>
                    <div class="conteudo">
                        <span>Total de Descontos</span>
                        <strong>R$ <?= number_format(
                            $resumo["saidas"],
                            2,
                            ",",
                            ".",
                        ) ?></strong>
                    </div>
                </div>
                <div class="card-resumo saldo">
                    <div class="icone"><i class="fas fa-dollar-sign"></i></div>
                    <div class="conteudo">
                        <span>Salário Líquido</span>
                        <strong>R$ <?= number_format(
                            $resumo["saldo"],
                            2,
                            ",",
                            ".",
                        ) ?></strong>
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
                                as $l): ?>
                                <tr>
                                    <td><?= date("d/m/Y", strtotime($l->getDataReferencia()), ) ?></td>
                                    <td>
                                        <?php if ($l->getTipo() === "ENTRADA"): ?>
                                            <span class="badge-entrada">
                                                <i class="fa-solid fa-arrow-up"></i>
                                                <?= htmlspecialchars($l->getCategoria()) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge-saida">
                                                <i class="fa-solid fa-arrow-down"></i>
                                                <?= htmlspecialchars($l->getCategoria()) ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($l->getDescricao()) ?></td>
                                    <td class="<?= $l->getTipo() === "ENTRADA"
                                        ? "valor-positivo"
                                        : "valor-negativo" ?>">

                                        <?= $l->getTipo() === "ENTRADA"
                                            ? "+"
                                            : "-" ?> R$

                                        <?= number_format(
                                            $l->getValor(),
                                            2,
                                            ",",
                                            ".",
                                        ) ?>
                                    </td>


                                    <td class="text-center">
                                        <div class="acoes">

                                            <!-- Editar -->
                                            <a href="/ideal/public/index.php?url=financeiro-funcionario/visualizar&id=<?= $l->getIdFinanceiroFuncionario() ?>&editar=1"
                                                class="btn-acao editar" title="Editar">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>

                                            <!-- Excluir -->
                                            <form
                                                action="/ideal/public/index.php?url=financeiro-funcionario/delete&id=<?= $l->getIdFinanceiroFuncionario() ?>"
                                                method="POST">

                                                <input type="hidden" name="cpf_hidden" value="<?= htmlspecialchars($cpfBusca) ?>">
                                                <input type="hidden" name="mes_hidden" value="<?= htmlspecialchars($mesBusca) ?>">
                                                <input type="hidden" name="ano_hidden" value="<?= htmlspecialchars($anoBusca) ?>">

                                                <button type="submit" class="btn-acao excluir" title="Excluir"
                                                    onclick="return confirm('Tem certeza que deseja apagar este lançamento?')">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>

                                        </div>
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