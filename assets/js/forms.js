async function enviarLogin(event) {
  event.preventDefault();

  const form = document.getElementById('form-login');
  const formData = new FormData(form);

  try {
    const response = await fetch('loginauth.php', {
      method: 'POST',
      body: formData
    });

    const result = await response.json();

    if (result.status === 'ok') {
      window.location.href = 'index.php';
    } else {
      alert(result.mensagem); // ou exibe numa <div id="erro-login"> por exemplo
    }
  } catch (error) {
    console.error('Erro na requisição:', error);
    alert('Erro ao tentar fazer login.');
  }
}

function toggleSenha(idInput, element) {
  const input = document.getElementById(idInput);
  const iconContainer = element;

  if (input.type === "password") {
    input.type = "text";
    // olho fechado
    iconContainer.innerHTML = `
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
        stroke="currentColor" stroke-width="2" stroke-linecap="round"
        stroke-linejoin="round" viewBox="0 0 24 24">
        <path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a21.37
          21.37 0 0 1 5-5.94"/>
        <line x1="1" y1="1" x2="23" y2="23"/>
        <path d="M10.59 10.59a3 3 0 0 0 4.24 4.24"/>
      </svg>
    `;
  } else {
    input.type = "password";
    // olho aberto
    iconContainer.innerHTML = `
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
        stroke="currentColor" stroke-width="2" stroke-linecap="round"
        stroke-linejoin="round" viewBox="0 0 24 24">
        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
        <circle cx="12" cy="12" r="3"/>
      </svg>
    `;
  }
}
