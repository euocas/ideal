// Lógica de Funcionários e Tabela
// let indiceFuncionario = <?= isset($indiceFuncionario) ? $indiceFuncionario : 0 ?>;
let indiceFuncionario = indiceFuncionarioInicial;

function adicionarFuncionarioNaTabela() {
  const selectFuncionario = document.getElementById("idFuncionarioSelect");
  const selectFuncao = document.getElementById("funcaoSelect");
  const selectVeiculo = document.getElementById("idVeiculoSelect");
  const selectStatus = document.getElementById("statusFuncionarioSelect");
  const inputInicio = document.getElementById("dataInicioFuncionario");
  const inputSaida = document.getElementById("dataSaidaFuncionario");

  const idFunc = selectFuncionario.value;

  if (!idFunc) {
    alert("Por favor, selecione um funcionário.");
    return;
  }

  const nomeFunc =
    selectFuncionario.options[selectFuncionario.selectedIndex].text;
  const funcao = selectFuncao.value || "—";
  const idVeic = selectVeiculo.value;
  const textoVeiculo = idVeic
    ? selectVeiculo.options[selectVeiculo.selectedIndex].text
    : "—";
  const status = selectStatus.value;

  let modeloVeic = "—";
  let placaVeic = "—";
  if (idVeic) {
    const partes = textoVeiculo.split(" - ");
    modeloVeic = partes[0];
    placaVeic = partes[1] || "—";
  }

  const formataData = (dataStr) =>
    dataStr ? dataStr.split("-").reverse().join("/") : "—";

  const tbody = document.getElementById("tabela-funcionarios-body");
  const tr = document.createElement("tr");

  tr.innerHTML = `
    <td class="responsavel-tabela">
        <input type="radio" name="idResponsavel" value="${idFunc}">
    </td>

    <td>
        ${nomeFunc}
        <input type="hidden" name="funcionariosObra[${indiceFuncionario}][idFuncionario]" value="${idFunc}">
        <input type="hidden" name="funcionariosObra[${indiceFuncionario}][idVeiculo]" value="${idVeic}">
    </td>

    <td>${funcao}</td>
    <td>${modeloVeic}</td>
    <td>${placaVeic}</td>
    <td>${formataData(inputInicio.value)}</td>
    <td>${formataData(inputSaida.value)}</td>
    <td>
        <span class="status ${status.toLowerCase() === "ativo" ? "ativo" : "inativo"}">
            ${status}
        </span>
    </td>

    <td class="acoes-tabela">
        <button type="button" class="btn-excluir"
            onclick="removerFuncionarioDaTabela(this)">
            <i class="fa-solid fa-trash"></i>
        </button>
    </td>
`;

  tbody.appendChild(tr);
  indiceFuncionario++;

  selectFuncionario.value = "";
  selectFuncao.value = "";
  selectVeiculo.value = "";
  selectStatus.value = "Ativo";
  inputInicio.value = "";
  inputSaida.value = "";
}

function removerFuncionarioDaTabela(botao) {
  botao.closest("tr").remove();
}

function mascaraCpfCnpjObra(input) {
  let v = input.value.replace(/\D/g, "");

  if (v.length <= 11) {
    // Máscara de CPF
    v = v.replace(/(\d{3})(\d)/, "$1.$2");
    v = v.replace(/(\d{3})(\d)/, "$1.$2");
    v = v.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
  } else {
    // Máscara de CNPJ
    v = v.replace(/^(\d{2})(\d)/, "$1.$2");
    v = v.replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3");
    v = v.replace(/\.(\d{3})(\d)/, ".$1/$2");
    v = v.replace(/(\d{4})(\d)/, "$1-$2");
  }
  input.value = v;
}

// Função assíncrona para buscar o cliente no banco sem recarregar a página
async function buscarClienteObra() {
  const inputCnpjCpf = document.getElementById("cnpjCliente");
  const docLimpo = inputCnpjCpf.value.replace(/\D/g, "");

  if (docLimpo.length !== 11 && docLimpo.length !== 14) {
    alert(
      "Por favor, digite um CPF (11 números) ou CNPJ (14 números) completo para buscar.",
    );
    return;
  }

  try {
    // Faz a requisição na rota que já existe no seu ClientesController
    const response = await fetch(
      `${BASE_URL}/index.php?url=clientes/buscarPorCnpj&cnpj=${docLimpo}`,
    );
    const data = await response.json();

    if (data.erro) {
      mostrarAlertaIdeal(
        "Cliente não existe no banco de dados. Você será redirecionado para cadastrá-lo primeiro.",
      );

      document.querySelector(".alerta-ideal button").onclick = function () {
        window.location.href = `${BASE_URL}/index.php?url=clientes/create&documento=${docLimpo}&novo=1`;
      };
    } else {
      document.getElementById("idCliente").value = data.idCliente;
      document.getElementById("clienteNome").textContent = data.nomeCliente;

      // Formatar Documento que veio do banco para exibição
      let docBanc = data.cnpj ? data.cnpj : data.cpf;
      let docFormatado = docBanc;
      if (docBanc.length === 11) {
        docFormatado = docBanc.replace(
          /(\d{3})(\d{3})(\d{3})(\d{2})/,
          "$1.$2.$3-$4",
        );
      } else if (docBanc.length === 14) {
        docFormatado = docBanc.replace(
          /(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/,
          "$1.$2.$3/$4-$5",
        );
      }
      document.getElementById("clienteCnpj").textContent = docFormatado;

      // Formatar WhatsApp que veio do banco para exibição
      let whatsBanc = data.whatsapp || "-";
      if (whatsBanc !== "-" && whatsBanc.length >= 10) {
        whatsBanc =
          whatsBanc.length === 11
            ? whatsBanc.replace(/(\d{2})(\d{5})(\d{4})/, "($1) $2-$3")
            : whatsBanc.replace(/(\d{2})(\d{4})(\d{4})/, "($1) $2-$3");
      }
      document.getElementById("clienteWhatsapp").textContent = whatsBanc;
    }
  } catch (error) {
    console.error("Erro na busca:", error);
    alert("Ocorreu um erro ao comunicar com o servidor. Tente novamente.");
  }
}
// criado para a mensagem personalizada
function mostrarAlertaIdeal(mensagem) {
  document.getElementById("mensagemAlertaIdeal").textContent = mensagem;
  document.getElementById("alertaIdeal").classList.add("ativo");
}

function fecharAlertaIdeal() {
  document.getElementById("alertaIdeal").classList.remove("ativo");
}
