const tableBody = document.querySelector("#clubes-table tbody");
const mensagem = document.getElementById("mensagem");
const editModal = document.getElementById("editModal");
const editForm = document.getElementById("editForm");
const closeModal = document.querySelector(".cancelar");
const viewModal = document.getElementById("viewModal");
const closeEditModal = document.getElementById("close-edit");
const closeViewModal = document.getElementById("close-view");

let currentEditingId = null;

document.addEventListener("DOMContentLoaded", async () => {
    try {
        const response = await fetch("http://localhost/api/webservice/clube.php");
        const clubes = await response.json();

        if (response.ok) {
            clubes.forEach(clube => {
                const row = document.createElement("tr");

                row.innerHTML = `
                    <td>${clube.id}</td>
                    <td>${clube.nome}</td>
                    <td>${clube.cidade || "N/A"}</td>
                    <td>${clube.estadio || "N/A"}</td>
                    <td>
                        <button class="edit-button" data-id="${clube.id}" >Editar</button>
                        <button class="view-button" data-id="${clube.id}">Ver Campeonatos do time</button>
                    </td>
                `;

                tableBody.appendChild(row);
            });

            // Adiciona evento aos botões "Editar"
            document.querySelectorAll(".edit-button").forEach(button => {
                button.addEventListener("click", async (event) => {
                    const id = event.target.dataset.id; // Pega o ID do botão
                    currentEditingId = id;

                    try {
                        const response = await fetch(`http://localhost/api/webservice/clube.php?id=${id}`);
                        const clubes = await response.json();

                        
                        console.log(clubes[id])
                        document.getElementById("editNome").value = clubes[id -1].nome;
                        document.getElementById("editCidade").value = clubes[id -1].cidade || "";
                        document.getElementById("editEstadio").value = clubes[id -1].estadio || "";

                        // Abre o modal
                        editModal.style.display = "block";
                    } catch (error) {
                        console.error("Erro ao carregar clube:", error);
                        alert("Erro ao carregar os dados do clube.");
                    }
                });
            });

            document.querySelectorAll(".view-button").forEach(button => {
                button.addEventListener("click", async (event) => {
                    const id = event.target.dataset.id;
        
                    try {
                        const response = await fetch(`http://localhost/api/webservice/timeJogaCampeonato.php?id=${id}`);
                        const campeonatos = await response.json();
        
                        if (response.ok) {
                            const tableBody = document.querySelector("#campeonatos-table tbody");
                            tableBody.innerHTML = ""; // Limpa a tabela antes de preencher
        
                            campeonatos.forEach(campeonato => {
                                const row = document.createElement("tr");
                                row.innerHTML = `
                                    <td>${campeonato.id}</td>
                                    <td>${campeonato.nome}</td>
                                    <td>${campeonato.ano}</td>
                                `;
                                tableBody.appendChild(row);
                            });
        
                            viewModal.style.display = "block"; // Exibe o modal
                        } else {
                            alert("Erro ao carregar os campeonatos.");
                        }
                    } catch (error) {
                        console.error("Erro ao carregar campeonatos:", error);
                        alert("Erro ao conectar com o servidor.");
                    }
                });
            });

        } else {
            mensagem.textContent = clubes.msg || "Erro ao carregar clubes.";
            mensagem.style.color = "red";
        }
    } catch (error) {
        console.error("Erro ao carregar clubes:", error);
        mensagem.textContent = "Erro ao conectar com o servidor.";
        mensagem.style.color = "red";
    }
});

// Submissão do formulário de edição
editForm.addEventListener("submit", async (event) => {
    event.preventDefault();

    const nome = document.getElementById("editNome").value;
    const cidade = document.getElementById("editCidade").value;
    const estadio = document.getElementById("editEstadio").value;

    const data = { nome, cidade, estadio };

    try {
        const response = await fetch(`http://localhost/api/webservice/clube.php?id=${currentEditingId}`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(data),
        });

        const result = await response.json();

        if (response.ok) {
            alert(result.msg);
            editModal.style.display = "none";
            location.reload();
        } else {
            alert(result.msg || "Erro ao atualizar o clube.");
        }
    } catch (error) {
        console.error("Erro na requisição:", error);
        alert("Erro ao conectar com o servidor.");
    }
});

// Fecha o modal de edição ao clicar no "X"
closeEditModal.addEventListener("click", () => {
    editModal.style.display = "none";
});
// Fecha o modal de visualização ao clicar no "X"
closeViewModal.addEventListener("click", () => {
    viewModal.style.display = "none";
});


// Fecha o modal se clicar fora do conteúdo
window.addEventListener("click", (event) => {
    if (event.target === editModal) {
        editModal.style.display = "none";
    }
    if (event.target === viewModal) {
        viewModal.style.display = "none";
    }
});
