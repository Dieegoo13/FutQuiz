let pontuacao = 0;
let tempoRestante = 15;
let perguntaAtual = 0;
let timer;
let perguntasSorteadas = [];

const pontuacaoEl = document.getElementById("pontuacao");
const tempoEl   = document.getElementById("tempo");
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
// 5. FINALIZAR
// =============================
function finalizarQuiz() {
    clearInterval(timer);
    window.location.href = "/resultado";
}

// =============================
// 6. INICIAR JOGO CHAMANDO API
// =============================
carregarPerguntasAPI();
