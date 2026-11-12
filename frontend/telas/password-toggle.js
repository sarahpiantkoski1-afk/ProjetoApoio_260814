document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.alternar-senha').forEach((botao) => {
        const campoSenha = document.getElementById(botao.dataset.passwordTarget);

        if (!campoSenha) {
            return;
        }

        botao.addEventListener('click', () => {
            const mostrarSenha = campoSenha.type === 'password';
            campoSenha.type = mostrarSenha ? 'text' : 'password';
            botao.setAttribute('aria-pressed', String(mostrarSenha));
            botao.setAttribute('aria-label', mostrarSenha ? 'Ocultar senha' : 'Mostrar senha');
            botao.setAttribute('title', mostrarSenha ? 'Ocultar senha' : 'Mostrar senha');
        });
    });
});
