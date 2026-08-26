// ==========================================
// LÓGICA DE FUNCIONÁRIOS E TABELA
// ==========================================

// Índice inicial vindo da view
let indiceFuncionario = indiceFuncionarioInicial;

function adicionarFuncionarioNaTabela() {

  // Campos do formulário
  const selectFuncionario = document.getElementById("idFuncionarioSelect");
  const selectFuncao = document.getElementById("funcaoSelect");
  const selectVeiculo = document.getElementById("idVeiculoSelect");
  const selectStatus = document.getElementById("statusFuncionarioSelect");

  const inputInicio = document.getElementById("dataInicioFuncionario");
  const inputSaida = document.getElementById("dataSaidaFuncionario");
  const inputObservacao = document.getElementById("observacaoFuncionario");

  const idFunc = selectFuncionario.value;

  // Validação do funcionário
  if (!idFunc) {
    alert("Por favor, selecione um funcionário.");
    return;
  }

  // Dados do funcionário
  const nomeFunc =
    selectFuncionario.options[
      selectFuncionario.selectedIndex
    ].text;

  const funcao = selectFuncao.value || "—";

  // Dados do veículo
  const idVeic = selectVeiculo.value;

  const textoVeiculo = idVeic
    ? selectVeiculo.options[
        selectVeiculo.selectedIndex
      ].text
    : "—";

  // Status
  const status = selectStatus.value;

  // Observação
  const observacao = inputObservacao.value.trim();

  // Separa modelo e placa
  let modeloVeic = "—";
  let placaVeic = "—";

  if (idVeic) {
    const partes = textoVeiculo.split(" - ");

    modeloVeic = partes[0];
    placaVeic = partes[1] || "—";
  }

  // Formata a data para dd/mm/yyyy
  const formataData = (dataStr) =>
    dataStr
      ? dataStr.split("-").reverse().join("/")
      : "—";

  // Tabela
  const tbody = document.getElementById(
    "tabela-funcionarios-body"
  );

  const tr = document.createElement("tr");

  // Cria a nova linha
  tr.innerHTML = `

    <td class="responsavel-tabela">
        <input
            type="radio"
            name="idResponsavel"
            value="${idFunc}">
    </td>

    <td>
        ${nomeFunc}

        <input
            type="hidden"
            name="funcionariosObra[${indiceFuncionario}][idFuncionario]"
            value="${idFunc}">

        <input
            type="hidden"
            name="funcionariosObra[${indiceFuncionario}][idVeiculo]"
            value="${idVeic}">
    </td>

    <td>
        ${funcao}
    </td>

    <td>
        ${modeloVeic}
    </td>

    <td>
        ${placaVeic}
    </td>

    <td>
        ${formataData(inputInicio.value)}
    </td>

    <td>
        ${formataData(inputSaida.value)}
    </td>

    <td>
        <span class="status ${
          status.toLowerCase() === "ativo"
            ? "ativo"
            : "inativo"
        }">
            ${status}
        </span>
    </td>

    <td class="observacao-tabela">
        ${observacao || "—"}

        <input
            type="hidden"
            name="funcionariosObra[${indiceFuncionario}][observacao]"
            value="${observacao}">
    </td>

    <td class="acoes-tabela">

        <button
            type="button"
            class="btn-excluir"
            onclick="removerFuncionarioDaTabela(this)">

            <i class="fa-solid fa-trash"></i>

        </button>

    </td>
  `;

  // Adiciona a linha na tabela
  tbody.appendChild(tr);

  // Incrementa o índice
  indiceFuncionario++;

  // ==========================================
  // LIMPA OS CAMPOS
  // ==========================================

  selectFuncionario.value = "";
  selectFuncao.value = "";
  selectVeiculo.value = "";
  selectStatus.value = "Ativo";

  inputInicio.value = "";
  inputSaida.value = "";
  inputObservacao.value = "";
}


// ==========================================
// REMOVER FUNCIONÁRIO
// ==========================================

function removerFuncionarioDaTabela(botao) {

  const linha = botao.closest("tr");

  if (linha) {
    linha.remove();
  }
}


// ==========================================
// MÁSCARA CPF / CNPJ
// ==========================================

function mascaraCpfCnpjObra(input) {

  let v = input.value.replace(/\D/g, "");

  if (v.length <= 11) {

    // CPF
    v = v.replace(/(\d{3})(\d)/, "$1.$2");
    v = v.replace(/(\d{3})(\d)/, "$1.$2");
    v = v.replace(/(\d{3})(\d{1,2})$/, "$1-$2");

  } else {

    // CNPJ
    v = v.replace(/^(\d{2})(\d)/, "$1.$2");
    v = v.replace(
      /^(\d{2})\.(\d{3})(\d)/,
      "$1.$2.$3"
    );
    v = v.replace(/\.(\d{3})(\d)/, ".$1/$2");
    v = v.replace(/(\d{4})(\d)/, "$1-$2");
  }

  input.value = v;
}


// ==========================================
// BUSCAR CLIENTE
// ==========================================

async function buscarClienteObra() {

  const inputCnpjCpf =
    document.getElementById("cnpjCliente");

  const docLimpo =
    inputCnpjCpf.value.replace(/\D/g, "");

  // Validação
  if (
    docLimpo.length !== 11 &&
    docLimpo.length !== 14
  ) {

    alert(
      "Por favor, digite um CPF (11 números) ou CNPJ (14 números) completo para buscar."
    );

    return;
  }

  try {

    // Busca o cliente
    const response = await fetch(
      `${BASE_URL}/index.php?url=clientes/buscarPorCnpj&cnpj=${docLimpo}`
    );

    const data = await response.json();

    // Cliente não encontrado
    if (data.erro) {

      mostrarAlertaIdeal(
        "Cliente não existe no banco de dados. Você será redirecionado para cadastrá-lo primeiro."
      );

      const botaoAlerta =
        document.querySelector(".alerta-ideal button");

      if (botaoAlerta) {

        botaoAlerta.onclick = function () {

          window.location.href =
            `${BASE_URL}/index.php?url=clientes/create&documento=${docLimpo}&novo=1`;
        };
      }

      return;
    }


    // ==========================================
    // CLIENTE ENCONTRADO
    // ==========================================

    document.getElementById("idCliente").value =
      data.idCliente;

    document.getElementById("clienteNome").textContent =
      data.nomeCliente;


    // Documento
    let docBanc = data.cnpj
      ? data.cnpj
      : data.cpf;

    let docFormatado = docBanc;

    if (docBanc.length === 11) {

      docFormatado = docBanc.replace(
        /(\d{3})(\d{3})(\d{3})(\d{2})/,
        "$1.$2.$3-$4"
      );

    } else if (docBanc.length === 14) {

      docFormatado = docBanc.replace(
        /(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/,
        "$1.$2.$3/$4-$5"
      );
    }

    document.getElementById("clienteCnpj").textContent =
      docFormatado;


    // WhatsApp
    let whatsBanc =
      data.whatsapp || "-";

    if (
      whatsBanc !== "-" &&
      whatsBanc.length >= 10
    ) {

      whatsBanc =
        whatsBanc.length === 11

          ? whatsBanc.replace(
              /(\d{2})(\d{5})(\d{4})/,
              "($1) $2-$3"
            )

          : whatsBanc.replace(
              /(\d{2})(\d{4})(\d{4})/,
              "($1) $2-$3"
            );
    }

    document.getElementById(
      "clienteWhatsapp"
    ).textContent = whatsBanc;


  } catch (error) {

    console.error(
      "Erro na busca:",
      error
    );

    alert(
      "Ocorreu um erro ao comunicar com o servidor. Tente novamente."
    );
  }
}


// ==========================================
// ALERTA PERSONALIZADO
// ==========================================

function mostrarAlertaIdeal(mensagem) {

  document.getElementById(
    "mensagemAlertaIdeal"
  ).textContent = mensagem;

  document.getElementById(
    "alertaIdeal"
  ).classList.add("ativo");
}


function fecharAlertaIdeal() {

  document.getElementById(
    "alertaIdeal"
  ).classList.remove("ativo");
}


// ==========================================
// BOTÃO LIMPAR
// ==========================================

document.addEventListener(
  "DOMContentLoaded",
  function () {

    const btnLimpar =
      document.getElementById("btnLimpar");

    const form =
      document.getElementById("form-dados");

    const fieldset =
      document.getElementById("fieldsetObra");


    if (
      !btnLimpar ||
      !form ||
      !fieldset
    ) {
      return;
    }


    btnLimpar.addEventListener(
      "click",
      function () {

        // Limpa inputs, selects e textareas
        form
          .querySelectorAll(
            "input, select, textarea"
          )
          .forEach(campo => {

            // Mantém o contrato da busca
            if (
              campo.id === "contratoBusca"
            ) {
              return;
            }

            if (
              campo.tagName === "SELECT"
            ) {

              campo.selectedIndex = 0;

            } else {

              campo.value = "";
            }
          });


        // ==========================================
        // LIMPA DADOS DO CLIENTE
        // ==========================================

        const clienteNome =
          document.getElementById(
            "clienteNome"
          );

        const clienteCnpj =
          document.getElementById(
            "clienteCnpj"
          );

        const clienteWhatsapp =
          document.getElementById(
            "clienteWhatsapp"
          );

        const idCliente =
          document.getElementById(
            "idCliente"
          );


        if (clienteNome) {
          clienteNome.textContent = "-";
        }

        if (clienteCnpj) {
          clienteCnpj.textContent = "-";
        }

        if (clienteWhatsapp) {
          clienteWhatsapp.textContent = "-";
        }

        if (idCliente) {
          idCliente.value = "";
        }


        // ==========================================
        // LIMPA TABELA DE FUNCIONÁRIOS
        // ==========================================

        const tabelaFuncionarios =
          document.getElementById(
            "tabela-funcionarios-body"
          );

        if (tabelaFuncionarios) {
          tabelaFuncionarios.innerHTML = "";
        }


        // ==========================================
        // FOCO
        // ==========================================

        const contrato =
          document.getElementById("contrato");

        if (contrato) {
          contrato.focus();
        }

      }
    );

  }
);

// ==========================================
// FOCO NA BUSCA DA OBRA
// ==========================================

window.addEventListener(
  "pageshow",
  function () {

    const contratoBusca =
      document.getElementById("contratoBusca");

    if (
      contratoBusca &&
      contratoBusca.dataset.telaInicial === "true"
    ) {

      contratoBusca.focus();
    }

  }
);