// Verifica se a variável veio do PHP
if (typeof codigoExpiraEm !== "undefined") {
  const tempoRestante = document.getElementById("tempo-restante");

  function atualizarContador() {
    // Horário atual (em segundos)
    const agora = Math.floor(Date.now() / 1000);

    // Quanto tempo ainda falta
    let restante = codigoExpiraEm - agora;

    // Se já expirou
    if (restante <= 0) {
      tempoRestante.textContent = "00:00";
      return;
    }

    // 👇 Coloque aqui
    if (restante <= 60) {
      tempoRestante.style.color = "var(--danger)";
    } else {
      tempoRestante.style.color = "";
    }

    if (restante <= 0) {
      tempoRestante.textContent = "Código expirado";
      tempoRestante.style.color = "var(--danger)";

      const btn = document.getElementById("btn-validar");

      btn.disabled = true;
      btn.style.opacity = "0.5";
      btn.style.cursor = "not-allowed";

      return;
    }

    // Calcula minutos e segundos
    const minutos = Math.floor(restante / 60);
    const segundos = restante % 60;

    // Formata para 09:05
    tempoRestante.textContent =
      String(minutos).padStart(2, "0") +
      ":" +
      String(segundos).padStart(2, "0");
  }

  // Atualiza imediatamente
  atualizarContador();

  // Atualiza a cada segundo
  setInterval(atualizarContador, 1000);
}
