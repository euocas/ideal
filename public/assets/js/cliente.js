document.addEventListener('DOMContentLoaded', function () {

    const btnLimpar = document.getElementById('btnLimpar');
    const form = document.getElementById('form-dados');

    if (!btnLimpar || !form) {
        return;
    }

    btnLimpar.addEventListener('click', function () {

        form.querySelectorAll('input, select, textarea').forEach(campo => {

            // Mantém CPF e CNPJ
            if (campo.name === 'cpf' || campo.name === 'cnpj') {
                return;
            }

            if (campo.tagName === 'SELECT') {
                campo.selectedIndex = 0;
            } else {
                campo.value = '';
            }
        });

        // Limpa também o documento da busca
        const documentoBusca = document.getElementById('documento');

        if (documentoBusca) {
            documentoBusca.value = '';
            documentoBusca.focus();
        }

    });

});