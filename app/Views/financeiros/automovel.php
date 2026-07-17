<?php

use App\Config\FinanceiroCategorias;
use App\Config\SistemaConstantes;

/** @var \App\Models\Veiculo|null $veiculoBusca */

$tipo = $_GET["tipo"] ?? "entrada";
$tipos = ["entrada", "saida", "periodo"];

if (!in_array($tipo, $tipos)) {
    $tipo = "entrada";
}

$placaBusca = $_GET["placa"] ?? ($_POST["placa"] ?? "");
$mesBusca = $_GET["mes"] ?? ($_POST["mes"] ?? date("m"));
$anoBusca = $_GET["ano"] ?? ($_POST["ano"] ?? date("Y"));

$veiculoExiste =
    isset($veiculoBusca) && is_object($veiculoBusca);

$mesStr = str_pad($mesBusca, 2, "0", STR_PAD_LEFT);

if ($mesBusca == date("m") && $anoBusca == date("Y")) {
    $dataPadrao = date("Y-m-d");
} else {
    $dataPadrao = date(
        "Y-m-t",
        strtotime("{$anoBusca}-{$mesStr}-01")
    );
}
// Dados do veículo
$veMarcaModelo = $veiculoExiste
    ? $veiculoBusca->getMarca() . " " . $veiculoBusca->getModelo()
    : "—";

$vePlaca = $veiculoExiste
    ? strtoupper($veiculoBusca->getPlaca())
    : "—";

$veStatus = $veiculoExiste
    ? ucfirst(strtolower($veiculoBusca->getStatusVeiculo()))
    : "—";

$veStatusClass =
    strtolower($veStatus) === "ativo"
    ? "ativo"
    : "inativo";

$veResponsavel = $veiculoExiste
    ? $veiculoBusca->getResponsavelVeiculo()
    : "-";

$veQuilometragem =
    $veiculoExiste ? number_format($veiculoBusca->getQuilometragem(), 0, ",", ".") . " km"
    : "-";

$veProximaRevisao = $veiculoExiste && $veiculoBusca->getProximaRevisao()
    ? date("d/m/Y", strtotime($veiculoBusca->getProximaRevisao()))
    : "-";

$veTipoPosse = $veiculoExiste
    ? ucfirst(strtolower($veiculoBusca->getTipoPosse()))
    : "-";

$veAno = $veiculoExiste
    ? $veiculoBusca->getAnoFabricacao() . "/" . $veiculoBusca->getAnoModelo()
    : "-";
?>


<section class="card">
    <div class="card-titulo">
        <i class="fa-solid fa-user-tie icone-aba"></i>
        <div>
            <h2>Financeiro do Autonóvel</h2>
            <p>Gerencie os lançamentos financeiros do automóvel.</p>
        </div>
    </div>

    <form id="form-busca-veiculo" action="/ideal/public/index.php?url=financeiros&aba=automovel&tipo=<?= $tipo ?>"
        method="POST">

        <div class="veiculo-topo">
            <div class="grid-busca">
                <div class="busca-box">
                    <h2><i class="fa-solid fa-car"></i> LOCALIZAR VEÍCULO</h2>
                    <div class="form-busca">
                        <div class="input-group">
                            <label>Placa</label>
                            <input type="text" name="placa" value="<?= htmlspecialchars($placaBusca) ?>"
                                placeholder="ABC1D23" maxlength="7" style="text-transform: uppercase;" required>
                        </div>
                        <button type="submit" name="acao" value="localizar" class="btn-buscar"><i
                                class="bi bi-search"></i> LOCALIZAR</button>
                    </div>
                </div>
                <div class="dica-box">
                    <h3><i class="fa-solid fa-circle-info"></i> DICA</h3>
                    <p><strong>Localize</strong> o veículo pela <strong>placa</strong>. Em seguida,
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

    <div class="veiculo-card">
        <div class="veiculo-conteudo">
            <div class="veiculo-coluna veiculo-principal">
                <div class="cabecalho-veiculo">
                    <div class="nome-veiculo">
                        <i class="fa-solid fa-truck"></i>
                        <h2 class="veiculo-nome"><?= htmlspecialchars($veMarcaModelo) ?></h2>
                    </div>

                    <span class="status <?= $veStatusClass ?>"><i class="fa-solid fa-circle"></i>
                        <?= htmlspecialchars($veStatus) ?></span>
                </div>
                <hr>
                <div class="veiculo-info">
                    <div class="info-item">
                        <i class="fa-solid fa-car-side"></i>
                        <div>
                            <span class="veiculo-titulo">Placa</span>
                            <span class="veiculo-valor"><?= htmlspecialchars($vePlaca) ?></span>
                        </div>
                    </div>
                    <span class="separador">•</span>
                    <div class="info-item">
                        <i class="fa-solid fa-calendar-check"></i>
                        <div>
                            <span class="veiculo-titulo">Próxima Revisão</span>
                            <span class="veiculo-valor"><?= $veProximaRevisao ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="veiculo-coluna">
                <div class="veiculo-item">
                    <i class="fa-solid fa-user"></i>
                    <div>
                        <span class="veiculo-titulo">Cargo</span>
                        <span class="veiculo-valor"><?= htmlspecialchars($veResponsavel) ?> </span>
                    </div>
                </div>
                <div class="veiculo-item">
                    <i class="fa-solid fa-road"></i>
                    <div>
                        <span class="veiculo-titulo">Quilometragem</span>
                        <span class="veiculo-valor"><?= $veQuilometragem ?></span>
                    </div>
                </div>
            </div>

            <div class="veiculo-coluna">
                <div class="veiculo-item">
                    <i class="fa-solid fa-key"></i>
                    <div>
                        <span class="veiculo-titulo">Tipo de Posse</span>
                        <span class="veiculo-valor"><?= htmlspecialchars($veTipoPosse) ?></span>
                    </div>
                </div>
                <div class="veiculo-item">
                    <i class="fa-solid fa-calendar-days"></i>
                    <div>
                        <span class="veiculo-titulo">Ano</span>
                        <span class="veiculo-valor"><?= htmlspecialchars($veAno) ?></span>
                    </div>

                </div>
            </div>


        </div>
    </div>
</section>

<section class="card">
    <div class="subabas-container">
        <a href="?url=financeiros&aba=automovel&tipo=entrada&placa=<?= urlencode($placaBusca) ?>&mes=<?= $mesBusca ?>&ano=<?= $anoBusca ?>"
            class="subaba entrada <?= $tipo === "entrada" ? "ativa" : "" ?>">
            <i class="fa-solid fa-plus"></i> Nova Entrada (Recebimentos)
        </a>

        <a href="?url=financeiros&aba=automovel&tipo=saida&placa=<?= urlencode($placaBusca) ?>&mes=<?= $mesBusca ?>&ano=<?= $anoBusca ?>"
            class="subaba saida <?= $tipo === "saida" ? "ativa" : "" ?>">
            <i class="fa-solid fa-minus"></i> Nova Saída (Gastos)
        </a>

        <a href="?url=financeiros&aba=automovel&tipo=periodo&placa=<?= urlencode($placaBusca) ?>&mes=<?= $mesBusca ?>&ano=<?= $anoBusca ?>"
            class="subaba periodo <?= $tipo === "periodo" ? "ativa" : "" ?>">
            <i class="fa-solid fa-list"></i> Lançamentos do Período
        </a>
    </div>

    <div class="conteudo-aba">

        <?php if ($tipo === "entrada"): ?>
            <div class="entrada-container">
                <div class="entrada-formulario">

                    <form id="form-entrada" action="/ideal/public/index.php?url=financeiros/storeAutomovel" method="POST">

                        <input type="hidden" name="tipo" value="entrada">
                        <input type="hidden" name="idVeiculo"
                            value="<?= $veiculoExiste ? $veiculoBusca->getIdVeiculo() : "" ?>">

                        <input type="hidden" name="placa_hidden" value="<?= htmlspecialchars($placaBusca) ?>">

                        <input type="hidden" name="mes_hidden" value="<?= htmlspecialchars($mesBusca) ?>">
                        <input type="hidden" name="ano_hidden" value="<?= htmlspecialchars($anoBusca) ?>">

                        <div class="grid-entrada">

                            <!-- Categoria -->
                            <div class="form-group">
                                <label>Categoria <span class="obrigatorio">*</span></label>
                                <select name="categoria" required>
                                    <option value="">Selecione a categoria </option>
                                    <?php foreach (SistemaConstantes::CATEGORIAS_FIN_AUTO as $categoria): ?>
                                        <option value="<?= $categoria ?>"> <?= $categoria ?> </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Fornecedor -->
                            <div class="form-group">
                                <label>Fornecedor </label>
                                <input type="text" name="fornecedor" maxlength="100" placeholder="Ex.Posto Shell">
                            </div>
                            <!-- Valor -->
                            <div class="form-group">
                                <label>Valor <span class="obrigatorio">*</span></label>
                                <div class="input-prefixo">
                                    <span class="prefixo">R$</span>
                                    <input type="number" name="valor" step="0.01" min="0" placeholder="0,00" required>
                                </div>
                            </div>

                            <!-- Descrição -->
                            <div class="form-group span-2">
                                <label>Descrição <span class="obrigatorio">*</span></label>
                                <input type="text" name="descricao" maxlength="255" placeholder="Descreva o recebimento"
                                    required>
                            </div>



                            <!-- Data -->
                            <div class="form-group">
                                <label>Data da Movimentação <span class="obrigatorio">*</span></label>
                                <input type="date" name="dataMovimentacao" value="<?= $dataPadrao ?>" required>
                            </div>

                            <!-- Forma de Pagamento -->
                            <div class="form-group">
                                <label>Forma de Pagamento <span class="obrigatorio">*</span></label>
                                <select name="formaPagamento" required>
                                    <option value="">Selecione</option>
                                    <?php foreach (SistemaConstantes::FORMAS_PAGAMENTO as $forma): ?>
                                        <option value="<?= $forma ?>"> <?= $forma ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Documento Fiscal -->
                            <div class="form-group">
                                <label>Documento Fiscal</label>
                                <input type="text" name="documento" maxlength="100"
                                    placeholder="Ex.: NF-e 125487 ou Recibo 4589">
                            </div>

                            <!-- Observaçao -->
                            <div class="form-group span-2">
                                <label>Observação <span class="observacao">*</span></label>
                                <textarea name="observacao" rows="4" maxlength="250"
                                    placeholder="Informações adicionais...."></textarea>
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
                    <div class="info-card entrada recebimentos-auto">
                        <div class="info-header">
                            <div class="info-icon"><i class="fa-solid fa-arrow-up"></i></div>
                            <div>
                                <h3>Recebimentos</h3>
                            </div>
                        </div>
                        <p class="info-descricao"> Registre valores recebidos relacionados ao veículo.</p>

                        <ul>
                            <?php foreach (SistemaConstantes::RECEBIMENTOS_FIN_AUTO as $recebimento): ?>
                                <li><?= htmlspecialchars($recebimento) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </aside>
            </div>

        <?php elseif ($tipo === "saida"): ?>
            <div class="saida-container">
                <div class="saida-formulario">
                    <form id="form-saida" action="/ideal/public/index.php?url=financeiros/storeAutomovel" method="POST">

                        <input type="hidden" name="tipo" value="saida">
                        <input type="hidden" name="idVeiculo"
                            value="<?= $veiculoExiste ? $veiculoBusca->getIdVeiculo() : "" ?>">
                        <input type="hidden" name="placa_hidden" value="<?= htmlspecialchars($placaBusca) ?>">
                        <input type="hidden" name="mes_hidden" value="<?= htmlspecialchars($mesBusca) ?>">
                        <input type="hidden" name="ano_hidden" value="<?= htmlspecialchars($anoBusca) ?>">

                        <div class="grid-saida">

                            <!-- Categoria -->
                            <div class="form-group">
                                <label>Categoria <span class="obrigatorio">*</span></label>
                                <select name="categoria" required>
                                    <option value="">Selecione a categoria</option>

                                    <?php foreach (SistemaConstantes::CATEGORIAS_FIN_AUTO as $categoria): ?>
                                        <option value="<?= $categoria ?>">
                                            <?= $categoria ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Fornecedor -->
                            <div class="form-group">
                                <label>Fornecedor</label>
                                <input type="text" name="fornecedor" maxlength="100" placeholder="Ex.: Posto Shell">
                            </div>

                            <!-- Valor -->
                            <div class="form-group">
                                <label>Valor <span class="obrigatorio">*</span></label>

                                <div class="input-prefixo">
                                    <span class="prefixo">R$</span>

                                    <input type="number" name="valor" step="0.01" min="0" placeholder="0,00" required>
                                </div>
                            </div>

                            <!-- Descrição -->
                            <div class="form-group span-2">
                                <label>Descrição <span class="obrigatorio">*</span></label>
                                <input type="text" name="descricao" maxlength="255" placeholder="Descreva o gasto" required>
                            </div>


                            <!-- Data -->
                            <div class="form-group">
                                <label>Data da Movimentação <span class="obrigatorio">*</span></label>

                                <input type="date" name="dataMovimentacao" value="<?= $dataPadrao ?>" required>
                            </div>

                            <!-- Forma de Pagamento -->
                            <div class="form-group">
                                <label>Forma de Pagamento <span class="obrigatorio">*</span></label>

                                <select name="formaPagamento" required>
                                    <option value="">Selecione</option>

                                    <?php foreach (SistemaConstantes::FORMAS_PAGAMENTO as $forma): ?>
                                        <option value="<?= $forma ?>">
                                            <?= $forma ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Documento -->
                            <div class="form-group">
                                <label>Documento Fiscal</label>

                                <input type="text" name="documento" maxlength="100"
                                    placeholder="Ex.: NF-e 125487 ou Recibo 4589">
                            </div>

                            <!-- Observação -->
                            <div class="form-group span-2">
                                <label>Observação</label>

                                <textarea name="observacao" rows="4" maxlength="250"
                                    placeholder="Informações adicionais sobre o gasto..."></textarea>
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
                <aside class="saida-info ">
                    <div class="info-card saida recebimentos-auto">

                        <div class="info-header">
                            <div class="info-icon"><i class="fa-solid fa-arrow-down"></i></div>
                            <div>
                                <h3>Gastos</h3>
                            </div>
                        </div>

                        <p class="info-descricao">Registre os gastos relacionados ao veículo.</p>

                        <ul>
                            <?php foreach (SistemaConstantes::CATEGORIAS_FIN_AUTO as $categoria): ?>
                                <li><?= htmlspecialchars($categoria) ?></li>
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
                    <div class="icone"><i class="fas fa-arrow-up"></i></div>
                    <div class="conteudo">
                        <span>Total de Recebimentos</span>
                        <strong>R$ <?= number_format(
                            $resumo["entradas"],
                            2,
                            ",",
                            ".",
                        ) ?>
                        </strong>
                    </div>
                </div>
                <div class="card-resumo saida">
                    <div class="icone">
                        < <i class="fas fa-arrow-down"></i>
                    </div>
                    <div class="conteudo">
                        <span>Total de Gastos</span>
                        <strong>R$ <?= number_format(
                            $resumo["saidas"],
                            2,
                            ",",
                            ".",
                        ) ?></strong>
                    </div>
                </div>


                <div class="card-resumo saldo">
                    <div class="icone">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div class="conteudo">
                        <span>Saldo do Período</span>
                        <strong>
                            R$ <?= number_format(
                                $resumo["saldo"],
                                2,
                                ",",
                                "."
                            ) ?>
                        </strong>
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
                                <td colspan="5" class="text-center">Nenhum lançamento encontrado para este período.</td>
                            </tr>

                        <?php else: ?>
                            <?php foreach ($lancamentos as $l): ?>
                                <tr>
                                    <td>
                                        <?= date("d/m/Y", strtotime($l["dataMovimentacao"])) ?>
                                    </td>
                                    <td>
                                        <?php if ($l["tipo"] === "Entrada"): ?>
                                            <span class="badge-entrada">
                                                <i class="fa-solid fa-arrow-up"></i>
                                                <?= htmlspecialchars($l["categoria"]) ?>
                                            </span>

                                        <?php else: ?>
                                            <span class="badge-saida">
                                                <i class="fa-solid fa-arrow-down"></i>
                                                <?= htmlspecialchars($l["categoria"]) ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($l["descricao"]) ?>
                                    </td>

                                    <td class="<?= $l["tipo"] === "Entrada"
                                        ? "valor-positivo"
                                        : "valor-negativo" ?>">
                                        <?= $l["tipo"] === "Entrada" ? "+" : "-" ?>
                                        R$ <?= number_format($l["valor"], 2, ",", ".") ?>
                                    </td>

                                    <td class="acoes text-center">
                                        <a href="/ideal/public/index.php?url=financeiros/deleteAutomovel&id=<?= $l["idFinanceiroAutomovel"] ?>&placa=<?= urlencode($placaBusca) ?>&mes=<?= $mesBusca ?>&ano=<?= $anoBusca ?>"
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
                <strong>Dica:</strong> Use a aba <strong>"Lançamentos do Período"</strong> para visualizar todos os
                <strong>recebimentos</strong> e <strong>gastos</strong> registrados para este veículo no período selecionado.
            </span>
        </div>
    </div>
<?php endif; ?>