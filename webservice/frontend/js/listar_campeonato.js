const tableBody = document.querySelector("#campeonatos-table tbody");
const modal = document.getElementById("vencedorModal");
const closeModal = document.getElementById("close-vencedor");
const vencedorNome = document.getElementById("vencedor-nome");
const vencedorCidade = document.getElementById("vencedor-cidade");
const vencedorEstadio = document.getElementById("vencedor-estadio");

document.addEventListener("DOMContentLoaded", async () => {
    
    // Carrega os campeonatos
    try {
        const response = await fetch("http://localhost/api/webservice/campeonato.php");
        const campeonatos = await response.json();

        if (response.ok) {
            campeonatos.forEach(campeonato => {
                const row = document.createElement("tr");

                row.innerHTML = `
                    <td>${campeonato.id}</td>
                    <td>${campeonato.nome}</td>
                    <td>${campeonato.ano}</td>
                    <td>
                        <button class="vencedor-button" data-id="${campeonato.id}">Ver Vencedor</button>
                    </td>
                `;

                tableBody.appendChild(row);
            });

            // Adiciona eventos aos botões "Ver Vencedor"
            document.querySelectorAll(".vencedor-button").forEach(button => {
                button.addEventListener("click", async (event) => {
                    const id = event.target.dataset.id;

                    try {
                        const response = await fetch(`http://localhost/api/webservice/vencedor.php?id=${id}`);
                        
                        if (response.ok) {
                            const vencedor = await response.json();
                            vencedorNome.textContent = vencedor.nome;
                            vencedorCidade.textContent = vencedor.cidade;
                            vencedorEstadio.textContent = vencedor.estadio;

                            modal.style.display = "block"; // Exibe o modal
                        } else {
                            alert("Campenato sem nenhum vencedor.");
                        }
                    } catch (error) {
                        console.error("Erro ao buscar o vencedor:", error);
                        alert("Erro ao conectar com o servidor.");
                    }
                });
            });
        } else {
            console.error("Erro ao carregar campeonatos:", campeonatos);
            alert("Erro ao carregar os campeonatos.");
        }
    } catch (error) {
        console.error("Erro ao conectar ao servidor:", error);
        alert("Erro ao conectar ao servidor.");
    }
});

// Fecha o modal
closeModal.addEventListener("click", () => {
    modal.style.display = "none";
});

// Fecha o modal ao clicar fora do conteúdo
window.addEventListener("click", (event) => {
    if (event.target === modal) {
        modal.style.display = "none";
    }
});
