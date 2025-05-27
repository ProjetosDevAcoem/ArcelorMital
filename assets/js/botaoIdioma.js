const translations = {
    "pt": {
        "title": "Monitoramento da Qualidade do Ar",
        "titleLink":"Guia Técnico da Qualidade do Ar",
        "botaoBuscar":"Buscar"
    },
    "en": {
        "title": "Air Quality Monitoring",
        "titleLink":"Technical Guide to Air Quality",
        "botaoBuscar":"Search"
    },
    "es": {
        "title": "Monitoreo de la Calidad del Aire",
        "titleLink":"Guía Técnica de la Calidad del Aire",
        "botaoBuscar":"Consultar"
    }
};

// Links do Google Drive para cada idioma
const driveLinks = {
    pt: "https://drive.google.com/file/d/1NfGmHHtDDLUMNHVCVh4EO51qnOti2Lvi/view?usp=sharing",
};

function toggleDropdown() {
    var menu = document.getElementById("dropdownMenu");
    menu.classList.toggle("show");
}

// Fecha o menu se o usuário clicar fora dele
window.onclick = function(event) {
    // Verifica se o clique não foi no botão ou no menu
    if (!event.target.matches('.dropdown') && !event.target.matches('.dropdown-menu') && !event.target.closest('.dropdown-menu')) {
        var dropdowns = document.getElementsByClassName("dropdown-menu");
        for (var i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            openDropdown.classList.remove("active");
        }
    }
}

function changeLanguage(lang) {
    document.documentElement.lang = lang; // Define o idioma no HTML

    // Atualiza os textos usando getElementById
    document.getElementById('title').textContent = translations[lang]["title"];
}
// Adiciona evento de clique nas bandeiras para mudar o idioma
document.getElementById("usFlag").addEventListener("click", () => changeLanguage("en"));
document.getElementById("ptFlag").addEventListener("click", () => changeLanguage("pt"));
document.getElementById("esFlag").addEventListener("click", () => changeLanguage("es"));