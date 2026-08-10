document.addEventListener("DOMContentLoaded", function () {
  const formRelatorio = document.getElementById("formRelatorio");

  if (!formRelatorio) {
    return;
  }

  formRelatorio.addEventListener("submit", function (event) {
    const botao = event.submitter;

    // Executa apenas para o botão de gerar PDF
    if (!botao || !botao.classList.contains("btn-pdf")) {
      return;
    }

    event.preventDefault();

    // Abre a nova aba imediatamente
    const novaAba = window.open("", "_blank");

    if (!novaAba) {
      alert(
        "Não foi possível abrir uma nova aba. Verifique se o navegador está bloqueando pop-ups.",
      );
      return;
    }

    // Mostra o loading na nova aba
    novaAba.document.write(`
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
    <meta charset="UTF-8">
    <title>Gerando relatório...</title>

    <link rel="stylesheet"
          href="${document.querySelector('link[href*="variables.css"]').href}">

    <link rel="stylesheet"
          href="${document.querySelector('link[href*="relatorio-loading.css"]').href}">
    </head>

    <body>
        <div class="relatorio-loading">

            <div class="relatorio-loading__spinner"></div>

            <h2>Gerando relatório...</h2>

            <p>Aguarde enquanto o PDF está sendo preparado.</p>

        </div>
    </body>
    </html>
`);

    novaAba.document.close();

    // Cria um nome exclusivo para a nova aba
    const nomeAba = "relatorioPdf_" + Date.now();

    novaAba.name = nomeAba;

    // Guarda as configurações originais do formulário
    const targetOriginal = formRelatorio.getAttribute("target");
    const actionOriginal = formRelatorio.getAttribute("action");

    // Define a nova aba como destino do formulário
    formRelatorio.setAttribute("target", nomeAba);

    // Utiliza a URL definida no formaction do botão
    if (botao.formAction) {
      formRelatorio.setAttribute("action", botao.formAction);
    }

    // Envia o formulário mantendo o método POST
    HTMLFormElement.prototype.submit.call(formRelatorio);

    // Restaura as configurações originais do formulário
    if (targetOriginal !== null) {
      formRelatorio.setAttribute("target", targetOriginal);
    } else {
      formRelatorio.removeAttribute("target");
    }

    if (actionOriginal !== null) {
      formRelatorio.setAttribute("action", actionOriginal);
    } else {
      formRelatorio.removeAttribute("action");
    }
  });
});
