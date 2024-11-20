const tableBody = document.querySelector("#jogadores-table tbody");
const mensagem = document.getElementById("mensagem");
const timeSelect = document.getElementById("editTimeId");
const editModal = document.getElementById("editModal");
const editForm = document.getElementById("editForm");
const closeEditModal = document.getElementById("close-edit");
const cancelButton = document.querySelector(".cancelar");

let currentEditingId = null;

document.addEventListener("DOMContentLoaded", async () => {
    try {
        // Carrega os jogadores
        const response = await fetch("http://localhost/api/webservice/jogador.php");
        const jogadores = await response.json();

        for (const jogador of jogadores) {
            const timeResponse = await fetch(`http://localhost/api/webservice/clubeId.php?id=${jogador.time_id}`);
            const time = await timeResponse.json();

            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${jogador.id}</td>
                <td>${jogador.nome}</td>
                <td>${jogador.posicao}</td>
                <td>${time.nome}</td>
                <td>
                    <button class="edit-button" data-id="${jogador.id}">Editar</button>
                    <button class="delete-button" data-id="${jogador.id}">Deletar</button>
                </td>
            `;
            tableBody.appendChild(row);
        }

        // Carrega os times para o dropdown
        const carregarTimes = async () => {
            try {
                const response = await fetch("http://localhost/api/webservice/clube.php");
                const times = await response.json();

                times.forEach(time => {
                    const option = document.createElement("option");
                    option.value = time.id;
                    option.textContent = time.nome;
                    timeSelect.appendChild(option);
                });
            } catch (error) {
                console.error("Erro ao carregar times:", error);
            }
        };

        await carregarTimes();

        // Botões de editar
        document.querySelectorAll(".edit-button").forEach(button => {
            button.addEventListener("click", async (event) => {
                const id = event.target.dataset.id;
                currentEditingId = id;

                const jogador = jogadores.find(j => j.id == id);
                document.getElementById("editNome").value = jogador.nome;
                document.getElementById("editPosicao").value = jogador.posicao;

                // Define o time selecionado no dropdown
                timeSelect.value = jogador.time_id;

                editModal.style.display = "block";
            });
        });

        // Botões de deletar
        document.querySelectorAll(".delete-button").forEach(button => {
            button.addEventListener("click", async (event) => {
                const id = event.target.dataset.id;
                if (confirm("Deseja realmente deletar este jogador?")) {
                    const response = await fetch(`http://localhost/api/webservice/jogador.php?id=${id}`, {
                        method: "DELETE"
                    });
                    const result = await response.json();
                    alert(result.msg);
                    location.reload();
                }
            });
        });
    } catch (error) {
        console.error("Erro ao carregar jogadores:", error);
        mensagem.textContent = "Erro ao conectar com o servidor.";
    }
});

// Submissão do formulário de edição
editForm.addEventListener("submit", async (event) => {
    event.preventDefault();

    const nome = document.getElementById("editNome").value;
    const posicao = document.getElementById("editPosicao").value;
    const time_id = timeSelect.value;

    const data = { nome, posicao, time_id };

    try {
        const response = await fetch(`http://localhost/api/webservice/jogador.php?id=${currentEditingId}`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(data),
        });

        const result = await response.json();
        alert(result.msg);
        if (response.ok) {
            editModal.style.display = "none";
            location.reload();
        }
    } catch (error) {
        console.error("Erro ao editar jogador:", error);
    }
});

// Fecha o modal
closeEditModal.addEventListener("click", () => (editModal.style.display = "none"));
cancelButton.addEventListener("click", () => (editModal.style.display = "none"));
