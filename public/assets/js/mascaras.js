function mascaraCPF(input) {
    let valor = input.value.replace(/\D/g, "");

    valor = valor.replace(/(\d{3})(\d)/, "$1.$2");
    valor = valor.replace(/(\d{3})(\d)/, "$1.$2");
    valor = valor.replace(/(\d{3})(\d{1,2})$/, "$1-$2");

    input.value = valor;
}

function mascaraCNPJ(input) {
    let valor = input.value.replace(/\D/g, "");

    // Limita a 14 dígitos
    valor = valor.substring(0, 14);

    valor = valor.replace(/^(\d{2})(\d)/, "$1.$2");
    valor = valor.replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3");
    valor = valor.replace(/\.(\d{3})(\d)/, ".$1/$2");
    valor = valor.replace(/(\d{4})(\d)/, "$1-$2");

    input.value = valor;
}

function mascaraCEP(input) {
    let valor = input.value.replace(/\D/g, "");

    // Limita a 8 dígitos
    valor = valor.substring(0, 8);

    valor = valor.replace(/(\d{5})(\d)/, "$1-$2");

    input.value = valor;
}

function mascaraTelefone(input) {
    let valor = input.value.replace(/\D/g, "");

    // Limita a 11 dígitos
    valor = valor.substring(0, 11);

    if (valor.length > 10) {
        // Celular: (11) 91234-5678
        valor = valor.replace(/^(\d{2})(\d{5})(\d{0,4}).*/, "($1) $2-$3");
    } else if (valor.length > 6) {
        // Telefone: (11) 3234-5678
        valor = valor.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, "($1) $2-$3");
    } else if (valor.length > 2) {
        valor = valor.replace(/^(\d{2})(\d+)/, "($1) $2");
    }

    input.value = valor;
}

function mascaraDocumento(input) {
    const tipo = document.getElementById("tipoDocumento").value;

    if (tipo === "cpf") {
        mascaraCPF(input);
    } else {
        mascaraCNPJ(input);
    }
}

function alterarMascaraDocumento() {
    const tipo = document.getElementById("tipoDocumento").value;
    const input = document.getElementById("documento");
    const label = document.getElementById("labelDocumento");

    input.value = "";

    if (tipo === "cpf") {
        label.innerText = "CPF";
        input.placeholder = "000.000.000-00";
        input.maxLength = 14;
    } else {
        label.innerText = "CNPJ";
        input.placeholder = "00.000.000/0000-00";
        input.maxLength = 18;
    }
}

function mascaraMoeda(input) {
    let valor = input.value;

    // Remove tudo que não for número
    valor = valor.replace(/\D/g, "");

    if (!valor) {
        input.value = "";
        return;
    }

    // O valor digitado representa reais
    valor = parseInt(valor, 10);

    input.value = valor.toLocaleString("pt-BR", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}
function iniciarEdicaoMoeda(input) {
    // Converte o valor que veio formatado para o valor bruto em reais.
    // Exemplo: 50.000,00 -> 50000
    const numeros = input.value.replace(/\D/g, "");

    if (numeros) {
        input.dataset.valorMoeda = String(parseInt(numeros, 10) / 100);
    } else {
        input.dataset.valorMoeda = "";
    }

    // Seleciona todo o valor ao entrar no campo
    input.select();
}


function editarMoeda(event, input) {

    // Permite atalhos do Mac/Windows
    if (event.metaKey || event.ctrlKey) {
        return;
    }

    // Permite navegação
    if (
        event.key === "Tab" ||
        event.key === "ArrowLeft" ||
        event.key === "ArrowRight" ||
        event.key === "Home" ||
        event.key === "End"
    ) {
        return;
    }

    // =========================
    // DIGITANDO UM NÚMERO
    // =========================
    if (/^\d$/.test(event.key)) {

        event.preventDefault();

        // Se o conteúdo estiver selecionado, começa um novo valor
        if (input.selectionStart !== input.selectionEnd) {
            input.dataset.valorMoeda = event.key;
        } else {
            input.dataset.valorMoeda =
                (input.dataset.valorMoeda || "") + event.key;
        }

        const valor = Number(input.dataset.valorMoeda);

        input.value = valor.toLocaleString("pt-BR", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        input.setSelectionRange(input.value.length, input.value.length);

        return;
    }

    // =========================
    // BACKSPACE
    // =========================
    if (event.key === "Backspace") {

        event.preventDefault();

        if (input.selectionStart !== input.selectionEnd) {
            input.dataset.valorMoeda = "";
            input.value = "";
            return;
        }

        let valor = input.dataset.valorMoeda || "";

        valor = valor.slice(0, -1);

        input.dataset.valorMoeda = valor;

        if (!valor) {
            input.value = "";
            return;
        }

        input.value = Number(valor).toLocaleString("pt-BR", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        input.setSelectionRange(input.value.length, input.value.length);

        return;
    }

    // =========================
    // DELETE
    // =========================
    if (event.key === "Delete") {
        event.preventDefault();
        return;
    }

    // Bloqueia letras e caracteres não permitidos
    event.preventDefault();
}