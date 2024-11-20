const form = document.getElementById("form-campeonato");
const mensagem = document.getElementById("mensagem");

// Adiciona um evento de envio ao formulário
form.addEventListener("submit", async (event) => {
    event.preventDefault(); // Evita o recarregamento da página

    // Coleta os dados do formulário
    const nome = document.getElementById("nome").value;
    const ano = document.getElementById("ano").value;

    // Monta o corpo da requisição
    const data = { nome, ano };

    try {
        const response = await fetch("http://localhost/api/webservice/campeonato.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(data),
        });

        const result = await response.json();

        if (response.ok) {
            mensagem.textContent = result.msg;
            mensagem.style.color = "green";
            form.reset(); 
        } else {
            mensagem.textContent = result.msg || "Erro ao cadastrar o campeonato.";
            mensagem.style.color = "red";
        }
    } catch (error) {
        console.error("Erro na requisição:", error);
        mensagem.textContent = "Erro ao conectar com o servidor.";
        mensagem.style.color = "red";
    }
});
