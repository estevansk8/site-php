const form = document.getElementById("form-time");
const mensagem = document.getElementById("mensagem");

form.addEventListener("submit", async (event) => {
    event.preventDefault();

    const nome = document.getElementById("nome").value;
    const cidade = document.getElementById("cidade").value;
    const estadio = document.getElementById("estadio").value;

    const data = { nome, cidade, estadio };

    try {
        const response = await fetch("http://localhost/api/webservice/clube.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(data),
        });

        const result = await response.json();

        if (response.ok) {
            mensagem.textContent = "Time cadastrado com sucesso!";
            mensagem.style.color = "green";
            form.reset();
        } else {
            mensagem.textContent = result.msg || "Erro ao cadastrar o time.";
            mensagem.style.color = "red";
        }
    } catch (error) {
        console.error("Erro na requisição:", error);
        mensagem.textContent = "Erro ao conectar com o servidor.";
        mensagem.style.color = "red";
    }
});
