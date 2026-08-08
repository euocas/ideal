document.addEventListener('DOMContentLoaded', function () {

    const formaPagamento = document.getElementById('formaPagamentoFuncionario');
    const contaPagamento = document.getElementById('contaPagamentoFuncionario');
    const reciboContainer = document.getElementById('reciboContainerFuncionario');
    const btnGerarRecibo = document.getElementById('btnGerarReciboFuncionario');

    if (!formaPagamento || !contaPagamento || !reciboContainer || !btnGerarRecibo) {
        return;
    }

    // Verifica se existe um recibo recém-gerado após salvar o lançamento
    const parametros = new URLSearchParams(window.location.search);
    const idRecibo = parametros.get('recibo');

    function atualizarCamposPagamento() {

        // Lançamento já foi salvo
        if (idRecibo) {

            contaPagamento.disabled = true;

            reciboContainer.style.display = 'flex';

            btnGerarRecibo.disabled = false;

            return;
        }

        // Novo lançamento
        if (formaPagamento.value === 'Dinheiro') {

            // Desabilita a conta de pagamento
            contaPagamento.disabled = true;
            contaPagamento.value = '';

            // Mostra o botão desabilitado
            reciboContainer.style.display = 'flex';

            btnGerarRecibo.disabled = true;

        } else {

            // Habilita a conta de pagamento
            contaPagamento.disabled = false;

            // Esconde o botão de recibo
            reciboContainer.style.display = 'none';

            btnGerarRecibo.disabled = true;
        }
    }

    formaPagamento.addEventListener('change', atualizarCamposPagamento);

    atualizarCamposPagamento();


    // Geração do recibo
    btnGerarRecibo.addEventListener('click', function () {

        const url = this.dataset.url;

        if (!url) {
            return;
        }

       window.open(url, '_blank');

    });


    btnGerarRecibo.addEventListener('click', function () {

    const idRecibo = this.dataset.id;

    console.log('BOTÃO CLICADO');
    console.log('ID DO RECIBO:', idRecibo);

});

});