document.getElementById("btnCadastrar").addEventListener("click", async function(event) {
    event.preventDefault(); // Evita o comportamento padrão do botão

    // Captura os valores dos campos
    const nome = document.getElementById("nome").value;
    const email = document.getElementById("email").value;
    const senha = document.getElementById("senha").value;
    const confirmeSenha = document.getElementById("confirmeSenha").value;

    // Validação simples
    if (!nome || !email || !senha || !confirmeSenha) {
        alert("Por favor, preencha todos os campos!");
        return;
    }

    if (senha !== confirmeSenha) {
        alert("As senhas não conferem!");
        return;
    }

    // Dados a serem enviados para o back-end
    const dados = {
        nome,
        email,
        senha
    };

    try {
        // Chamada para a API
        const response = await fetch("http://localhost/api/webservice/user.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(dados)
        });

        // Trata a resposta do servidor
        const resultado = await response.json();
        if (response.ok) {
            alert("Usuário cadastrado com sucesso!");
            window.location.href = "index.html"; // Redireciona para a página de login
        } else {
            alert(resultado.msg || "Erro ao cadastrar!");
        }
    } catch (error) {
        console.error("Erro:", error);
        alert("Erro ao conectar com o servidor!");
    }
});
