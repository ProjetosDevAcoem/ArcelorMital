function toggleSenha(idInput, element) {
  const input = document.getElementById(idInput);
  const svg = element.querySelector('svg');

  if (input.type === "password") {
    input.type = "text";
    // Altera para olho fechado
    svg.innerHTML = `
      <path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a21.37 21.37 0 0 1 5-5.94"/>
      <line x1="1" y1="1" x2="23" y2="23"/>
      <path d="M10.59 10.59a3 3 0 0 0 4.24 4.24"/>
    `;
  } else {
    input.type = "password";
    // Volta para olho aberto
    svg.innerHTML = `
      <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
      <circle cx="12" cy="12" r="3"/>
    `;
  }
}