document.addEventListener('DOMContentLoaded', function () {

    const btnLimpar = document.getElementById('btnLimpar');
    const form = document.getElementById('form-dados');
    const placaBusca = document.getElementById('placaBusca');

    // Limpar
    if (btnLimpar) {

        btnLimpar.addEventListener('click', function () {

            if (form) {

                form.querySelectorAll('input, select, textarea').forEach(campo => {

                    // Mantém RENAVAM, PLACA e CHASSI
                    if (
                        campo.name === 'renavam' ||
                        campo.name === 'placa' ||
                        campo.name === 'chassi'
                    ) {
                        return;
                    }

                    if (campo.tagName === 'SELECT') {
                        campo.selectedIndex = 0;
                    } else {
                        campo.value = '';
                    }

                });
            }

            // Limpa a placa da busca
            if (placaBusca) {
                placaBusca.value = '';
                document.getElementById("marca").focus(); 
            }

        });

    }

});

// Foco na busca somente na tela inicial
window.addEventListener('pageshow', function () {

    const placaBusca = document.getElementById('placaBusca');

    if (
        placaBusca &&
        placaBusca.dataset.telaInicial === 'true'
    ) {
        placaBusca.focus();
    }

});