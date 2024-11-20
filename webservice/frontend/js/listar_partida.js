document.addEventListener("DOMContentLoaded", async () => {
    const tableBody = document.querySelector("#partidas-table tbody");
    const mensagem = document.getElementById("mensagem");

    try {
        const response = await fetch("http://localhost/api/webservice/partida.php");
        const partidas = await response.json();

        for (const partida of partidas) {
            const [timeHome, timeAway, campeonato] = await Promise.all([
                fetch(`http://localhost/api/webservice/clubeId.php?id=${partida.time_home_id}`).then(res => res.json()),
                fetch(`http://localhost/api/webservice/clubeId.php?id=${partida.time_away_id}`).then(res => res.json()),
                fetch(`http://localhost/api/webservice/campeonatoId.php?id=${partida.campeonato_id}`).then(res => res.json())
            ]);

            const vencedorNome = partida.vencedor
                ? (partida.vencedor === partida.time_home_id ? timeHome.nome : timeAway.nome)
                : "Empate";

            const row = `
                <tr>
                    <td>${partida.id}</td>
                    <td>${partida.data}</td>
                    <td>${partida.local}</td>
                    <td>${campeonato.nome}</td>
                    <td>${timeHome.nome}</td>
                    <td>${partida.time_home_gols}</td>
                    <td>${partida.time_away_gols}</td>
                    <td>${timeAway.nome}</td>
                    <td>${vencedorNome}</td>
                    <td>
                        <button class="delete-button" data-id="${partida.id}">Deletar</button>
                    </td>
                </tr>
            `;
            tableBody.innerHTML += row;
        }

        document.querySelectorAll(".delete-button").forEach(button => {
            button.addEventListener("click", async (event) => {
                const id = event.target.dataset.id;
                if (confirm("Tem certeza que deseja deletar essa partida?")) {
                    try {
                        const response = await fetch(`http://localhost/api/webservice/partida.php?id=${id}`, {
                            method: "DELETE"
                        });
                        const result = await response.json();
                        alert(result.msg || "Partida deletada com sucesso!");
                        location.reload();
                    } catch (error) {
                        console.error(error);
                        alert("Erro ao deletar partida.");
                    }
                }
            });
        });

        
    } catch (error) {
        console.error("Erro ao carregar partidas:", error);
        mensagem.textContent = "Erro ao carregar as partidas.";
        mensagem.style.color = "red";
    }
});
