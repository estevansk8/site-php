const form = document.getElementById("form-jogador");
const mensagem = document.getElementById("mensagem");
const timeSelect = document.getElementById("time");

// Carregar times disponíveis no select
async function carregarTimes() {
    try {
        const response = await fetch("http://localhost/api/times");
        const times = await response.json();
        times.forEach(time => {
            const option = document.createElement("option");
            option.value = time.id;
            option.textContent = time.nome;
            timeSelect.appendChild(option);
        });
    } catch (error) {
        console.error("Erro ao carregar os times:", error);
    }
}

// Função para cadastrar jogador
async function cadastrarJogador(event) {
    event.preventDefault();

    const nome = document.getElementById("nome").value;
    const posicao = document.getElementById("posicao").value;
    const time = timeSelect.value;

    const jogador = { nome, posicao, time };

    try {
        const response = await fetch("http://localhost/api/jogadores", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(jogador)
        });
        
        if (response.ok) {
            mensagem.textContent = "Jogador cadastrado com sucesso!";
            mensagem.style.color = "green";
            form.reset();
        } else {
            mensagem.textContent = "Erro ao cadastrar jogador.";
            mensagem.style.color = "red";
        }
    } catch (error) {
        console.error("Erro ao cadastrar jogador:", error);
        mensagem.textContent = "Erro de conexão.";
        mensagem.style.color = "red";
    }
}

// Inicializar o formulário
form.addEventListener("submit", cadastrarJogador);

// Carregar times quando a página for carregada
carregarTimes();
