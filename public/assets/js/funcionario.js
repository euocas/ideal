document.addEventListener('DOMContentLoaded', function () {

    const btnLimpar = document.getElementById('btnLimpar');
    const form = document.getElementById('form-dados');

    if (!btnLimpar || !form) {
        return;
    }

    btnLimpar.addEventListener('click', function () {

        form.querySelectorAll('input, select, textarea').forEach(campo => {

            // Mantém o CPF do funcionário
            if (campo.name === 'cpf') {
                return;
            }

            // Limpa os selects
            if (campo.tagName === 'SELECT') {
                campo.selectedIndex = 0;
                return;
            }

            // Limpa os demais campos
            campo.value = '';
        });

        // Limpa o CPF da busca
        const cpfBusca = document.querySelector('.form-busca input[name="cpf"]');

        if (cpfBusca) {
            cpfBusca.value = '';
            cpfBusca.focus();
        }

    });

});