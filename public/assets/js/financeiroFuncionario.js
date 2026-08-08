document.addEventListener('DOMContentLoaded', function () {

    const formaPagamento = document.getElementById('formaPagamentoFuncionario');
    const contaPagamento = document.getElementById('contaPagamentoFuncionario');
    const reciboContainer = document.getElementById('reciboContainerFuncionario');

    if (!formaPagamento || !contaPagamento || !reciboContainer) {
        return;
    }

    function atualizarCamposPagamento() {

        if (formaPagamento.value === 'Dinheiro') {

            // Desabilita a conta de pagamento
            contaPagamento.disabled = true;
            contaPagamento.value = '';

            // Mostra o botão de recibo
            reciboContainer.style.display = 'block';

        } else {

            // Habilita a conta de pagamento
            contaPagamento.disabled = false;

            // Esconde o botão de recibo
            reciboContainer.style.display = 'none';

        }

    }

    formaPagamento.addEventListener('change', atualizarCamposPagamento);

    atualizarCamposPagamento();

});