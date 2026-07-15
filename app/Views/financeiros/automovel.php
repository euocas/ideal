<?php
/** @var \App\Models\FinanceiroAutomovel|null $financeiroAutomovel */
$isEditAutomovel =
    isset($financeiroAutomovel) && is_object($financeiroAutomovel);
$actionAutomovel = $isEditAutomovel
    ? "/ideal/public/index.php?url=financeiros/updateAutomovel&id={$financeiroAutomovel->getIdFinanceiroAutomovel()}"
    : "/ideal/public/index.php?url=financeiros/storeAutomovel";
?>



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
                <input type="number" name="idVeiculo" value="<?= htmlspecialchars(
                    $isEditAutomovel
                    ? $financeiroAutomovel->getIdVeiculo()
                    : "",
                ) ?>" placeholder="Ex: 1" required min="1">
            </div>
            <div class="form-group">
                <label><i class="fa-solid fa-gas-pump"></i> Combustível</label>
                <div class="input-prefixo">
                    <span class="prefixo">R$</span>
                    <input type="number" name="combustivel" step="0.01" min="0" value="<?= htmlspecialchars(
                        $isEditAutomovel
                        ? $financeiroAutomovel->getCombustivel()
                        : "",
                    ) ?>" placeholder="0,00">
                </div>
            </div>
            <div class="form-group">
                <label><i class="fa-solid fa-screwdriver-wrench"></i> Manutenção</label>
                <div class="input-prefixo">
                    <span class="prefixo">R$</span>
                    <input type="number" name="manutencao" step="0.01" min="0" value="<?= htmlspecialchars(
                        $isEditAutomovel
                        ? $financeiroAutomovel->getManutencao()
                        : "",
                    ) ?>" placeholder="0,00">
                </div>
            </div>
            <div class="form-group">
                <label><i class="fa-solid fa-file-invoice-dollar"></i> IPVA</label>
                <div class="input-prefixo">
                    <span class="prefixo">R$</span>
                    <input type="number" name="ipva" step="0.01" min="0" value="<?= htmlspecialchars(
                        $isEditAutomovel
                        ? $financeiroAutomovel->getIpva()
                        : "",
                    ) ?>" placeholder="0,00">
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