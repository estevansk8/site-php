const form = document.getElementById("form-jogador");
const mensagem = document.getElementById("mensagem");
const timeSelect = document.getElementById("time");


async function carregarTimes() {
    try {
        const response = await fetch("http://localhost/api/webservice/clube.php");
        
        if (!response.ok) {
            throw new Error(`Erro ao buscar times: ${response.statusText}`);
        }
        
        const times = await response.json();
        
        // Certifique-se de limpar o dropdown antes de adicionar os novos valores
        timeSelect.innerHTML = '<option value="">Selecione um time</option>';
        
        times.forEach(time => {
            const option = document.createElement("option");
            option.value = time.id; // O ID do time
            option.textContent = time.nome; // O nome do time
            timeSelect.appendChild(option);
        });
    } catch (error) {
        console.error("Erro ao carregar os times:", error);
        const mensagem = document.getElementById("mensagem");
        mensagem.textContent = "Erro ao carregar os times.";
        mensagem.style.color = "red";
    }
}


// Função para cadastrar jogador
form.addEventListener("submit", async (event) => {
    event.preventDefault();

    const nome = document.getElementById("nome").value;
    const posicao = document.getElementById("posicao").value;
    const time_id = timeSelect.value;

    const jogador = { nome, posicao, time_id };

    try {
        const response = await fetch("http://localhost/api/webservice/jogador.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(jogador)
        });

        const result = await response.json();
        
        if (response.ok) {
            mensagem.textContent = result.msg;
            mensagem.style.color = "green";
            form.reset();
        } else {
            mensagem.textContent = result.msg;
            mensagem.style.color = "red";
        }
    } catch (error) {
        console.error("Erro ao cadastrar jogador:", error);
        mensagem.textContent = "Erro de conexão.";
        mensagem.style.color = "red";
    }
});

// Carregar times quando a página for carregada
carregarTimes();
