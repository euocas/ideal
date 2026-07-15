<?php
/** @var \App\Models\FinanceiroObra|null $financeiroObra */

$isEditObra = isset($financeiroObra) && is_object($financeiroObra);
$actionObra = $isEditObra
    ? "/ideal/public/index.php?url=financeiro-obra/update&id={$financeiroObra->getIdFinanceiroObra()}"
    : "/ideal/public/index.php?url=financeiro-obra/store";
?>
                
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