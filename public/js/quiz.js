let pontuacao = 0;
let totAcertos = 0;
let totErros = 0;
let tempoRestante = 15;
let perguntaAtual = 0;
let timer;
let perguntasSorteadas = [];

const pontuacaoEl = document.getElementById("pontuacao");
const tempoEl = document.getElementById("tempo");
const contadorEl = document.getElementById("contador");
const textoPerguntaEl = document.getElementById("texto-pergunta");
const botoes = document.querySelectorAll(".opcao");

// =============================
// 1. BUSCAR PERGUNTAS VIA FETCH
// =============================
async function carregarPerguntasAPI() {
  try {
    const resposta = await fetch("/api/perguntas");

    if (!resposta.ok) {
      throw new Error("Erro ao carregar perguntas!");
    }

    const dados = await resposta.json();

    perguntasSorteadas = dados;
    carregarPergunta();
  } catch (erro) {
    console.error("Erro no fetch:", erro);
  }
}

// =============================
// 2. CARREGAR PERGUNTA
// =============================
function carregarPergunta() {
  clearInterval(timer);
  tempoRestante = 15;
  tempoEl.textContent = tempoRestante;

  const q = perguntasSorteadas[perguntaAtual];

  contadorEl.textContent = `${perguntaAtual + 1}/10`;
  textoPerguntaEl.textContent = q.enunciado;

  botoes[0].textContent = `A - ${q.alternativa_a}`;
  botoes[1].textContent = `B - ${q.alternativa_b}`;
  botoes[2].textContent = `C - ${q.alternativa_c}`;
  botoes[3].textContent = `D - ${q.alternativa_d}`;

  iniciarTimer();
}

// =============================
// 3. TIMER
// =============================
function iniciarTimer() {
  timer = setInterval(() => {
    tempoRestante--;
    tempoEl.textContent = tempoRestante;

    if (tempoRestante <= 0) {
      clearInterval(timer);
      proximaPergunta();
    }
  }, 1000);
}

// =============================
// 4. PRÓXIMA PERGUNTA
// =============================
function proximaPergunta() {
  perguntaAtual++;

  if (perguntaAtual >= perguntasSorteadas.length) {
    finalizarQuiz();
  } else {
    carregarPergunta();
  }
}

// =============================
// 6. INICIAR JOGO CHAMANDO API
// =============================
carregarPerguntasAPI();

// =============================
// 7. LOGICA PARA ACERTOS E ERROS VISUAL
// =============================

botoes.forEach((botao) => {
  botao.addEventListener("click", function () {
    clearInterval(timer);

    const alternativaEscolhida = this.dataset.op.toLowerCase();
    const pergunta = perguntasSorteadas[perguntaAtual];
    const correta = pergunta.resposta_correta.toLowerCase();

    let acertou = alternativaEscolhida === correta;

    if (acertou) {
      totAcertos++;
      pontuacao += 5; 
      this.classList.add("correto");
    } else {
      totErros++;
      pontuacao -= 3; 
      this.classList.add("errado");


      botoes.forEach((btn) => {
        if (btn.dataset.op.toLowerCase() === correta) {
          btn.classList.add("correto");
        }
      });
    }

    pontuacaoEl.textContent = pontuacao;


    botoes.forEach((btn) => (btn.disabled = true));


    setTimeout(() => {
      botoes.forEach((btn) => {
        btn.classList.remove("correto", "errado");
        btn.disabled = false;
      });
      proximaPergunta();
    }, 1000);
  });
});

// =============================
// FINALIZAR QUIZ
// =============================
function finalizarQuiz() {
  clearInterval(timer);

  const acertos = totAcertos;
  const pontosGanhos = pontuacao;

  document.body.innerHTML = `
        <div style="text-align:center; margin-top: 50px;">
            <h1>Calculando sua pontuação...</h1>
            <p>Aguarde alguns segundos...</p>
        </div>
    `;


  fetch("/resultado", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      acertos,
      pontosGanhos,
    }),
  }).then(() => {

    setTimeout(() => {
      window.location.href = "/resultado";
    }, 2000);
  });
}
