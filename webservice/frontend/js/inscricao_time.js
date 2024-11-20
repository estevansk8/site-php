const form = document.getElementById("form-time-campeonato");
const mensagem = document.getElementById("mensagem");
const timeSelect = document.getElementById("time");
const campeonatoSelect = document.getElementById("campeonato");

// Função para carregar times
async function carregarTimes() {
    try {
        const response = await fetch("http://localhost/api/webservice/clube.php");
        
        if (!response.ok) throw new Error(`Erro ao buscar times: ${response.statusText}`);
        
        const times = await response.json();
        timeSelect.innerHTML = '<option value="">Selecione um time</option>';
        times.forEach(time => {
            const option = document.createElement("option");
            option.value = time.id;
            option.textContent = time.nome;
            timeSelect.appendChild(option);
        });
    } catch (error) {
        console.error("Erro ao carregar os times:", error);
        mensagem.textContent = "Erro ao carregar os times.";
        mensagem.style.color = "red";
    }
}

// Função para carregar campeonatos
async function carregarCampeonatos() {
    try {
        const response = await fetch("http://localhost/api/webservice/campeonato.php");
        
        if (!response.ok) throw new Error(`Erro ao buscar campeonatos: ${response.statusText}`);
        
        const campeonatos = await response.json();
        campeonatoSelect.innerHTML = '<option value="">Selecione um campeonato</option>';
        campeonatos.forEach(campeonato => {
            const option = document.createElement("option");
            option.value = campeonato.id;
            option.textContent = campeonato.nome;
            campeonatoSelect.appendChild(option);
        });
    } catch (error) {
        console.error("Erro ao carregar os campeonatos:", error);
        mensagem.textContent = "Erro ao carregar os campeonatos.";
        mensagem.style.color = "red";
    }
}

// Função para inscrever time no campeonato
form.addEventListener("submit", async (event) => {
    event.preventDefault();

    const time_id = timeSelect.value;
    const campeonato_id = campeonatoSelect.value;

    const inscricao = { time_id, campeonato_id };

    try {
        const response = await fetch("http://localhost/api/webservice/timecampeonato.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(inscricao)
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
        console.error("Erro ao inscrever o time:", error);
        mensagem.textContent = "Erro de conexão.";
        mensagem.style.color = "red";
    }
});

// Carregar dados ao inicializar
carregarTimes();
carregarCampeonatos();
