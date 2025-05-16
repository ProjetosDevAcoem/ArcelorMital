// Seleciona o botão e o conteúdo do dropdown
const botao = document.getElementById("saibamaisBotao");
const conteudo = document.querySelector(".saibamaisConteudo");

// Adiciona o evento de clique para alternar a visibilidade
botao.addEventListener("click", function() {
    // Alterna o estado de exibição do conteúdo
    conteudo.style.display = conteudo.style.display === "block" ? "none" : "block";
});