const campeonatoSelect = document.getElementById("campeonato");
const timeCasaSelect = document.getElementById("timeCasa");
const timeVisitanteSelect = document.getElementById("timeVisitante");
const form = document.getElementById("form-partida");
const mensagemDiv = document.getElementById("mensagem");

document.addEventListener("DOMContentLoaded", () => {
    
    // Função para carregar campeonatos
    async function carregarCampeonatos() {
        try {
            const response = await fetch("http://localhost/api/webservice/campeonato.php");
            const campeonatos = await response.json();

            campeonatos.forEach(campeonato => {
                const option = document.createElement("option");
                option.value = campeonato.id;
                option.textContent = `${campeonato.nome} (${campeonato.ano})`;
                campeonatoSelect.appendChild(option);
            });
        } catch (error) {
            console.error("Erro ao carregar campeonatos:", error);
        }
    }

    // Função para carregar times
    async function carregarTimes() {
        try {
            const response = await fetch("http://localhost/api/webservice/clube.php");
            const times = await response.json();

            times.forEach(time => {
                const optionCasa = document.createElement("option");
                optionCasa.value = time.id;
                optionCasa.textContent = time.nome;

                const optionVisitante = optionCasa.cloneNode(true);

                timeCasaSelect.appendChild(optionCasa);
                timeVisitanteSelect.appendChild(optionVisitante);
            });
        } catch (error) {
            console.error("Erro ao carregar times:", error);
        }
    }

    // Enviar dados do formulário
    form.addEventListener("submit", async (event) => {
        event.preventDefault();

        const dados = {
            data: document.getElementById("data").value,
            local: document.getElementById("local").value,
            campeonato_id: campeonatoSelect.value,
            time_home_id: timeCasaSelect.value,
            time_away_id: timeVisitanteSelect.value,
            time_home_gols: document.getElementById("golsCasa").value,
            time_away_gols: document.getElementById("golsVisitante").value
        };

        if (dados.time_home_id === dados.time_away_id) {
            mensagemDiv.textContent = "Os times da casa e visitante não podem ser iguais.";
            mensagemDiv.style.color = "red";
            return;
        }

        try {
            const response = await fetch("http://localhost/api/webservice/partida.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(dados)
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
            mensagemDiv.textContent = "Erro ao cadastrar a partida.";
            mensagemDiv.style.color = "red";
            console.error(error);
        }
    });

    carregarCampeonatos();
    carregarTimes();
});
