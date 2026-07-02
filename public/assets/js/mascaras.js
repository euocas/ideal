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
