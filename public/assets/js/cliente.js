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
            document.getElementById("nomeCliente").focus(); 
        }

    });

});

document.addEventListener("DOMContentLoaded", function() {
        const inputBusca = document.getElementById('documento');
        const selectTipo = document.getElementById('tipoDocumento');
        const labelDoc = document.getElementById('labelDocumento');
        
        if (inputBusca && inputBusca.value) {
            let val = inputBusca.value.replace(/\D/g, '');
            
            if (val.length > 0) {
                // Se for maior que 11, ajusta a interface para CNPJ e aplica máscara
                if (val.length > 11) {
                    selectTipo.value = 'cnpj';
                    labelDoc.innerText = 'CNPJ';
                    inputBusca.placeholder = '00.000.000/0000-00';
                    inputBusca.maxLength = 18;
                    
                    val = val.replace(/^(\d{2})(\d)/, "$1.$2");
                    val = val.replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3");
                    val = val.replace(/\.(\d{3})(\d)/, ".$1/$2");
                    val = val.replace(/(\d{4})(\d)/, "$1-$2");
                } else {
                    // Caso contrário, ajusta para CPF e aplica máscara
                    selectTipo.value = 'cpf';
                    labelDoc.innerText = 'CPF';
                    inputBusca.placeholder = '000.000.000-00';
                    inputBusca.maxLength = 14;
                    
                    val = val.replace(/(\d{3})(\d)/, "$1.$2");
                    val = val.replace(/(\d{3})(\d)/, "$1.$2");
                    val = val.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
                }
                
                inputBusca.value = val;
            }
        }
    });