document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('#form-conteudo');
    if (!form) return;

    const checkboxes = form.querySelectorAll('input[name="nichos[]"]');
    checkboxes.forEach((checkbox) => checkbox.addEventListener('change', () => {
        if (form.querySelectorAll('input[name="nichos[]"]:checked').length > 2) {
            checkbox.checked = false;
            alert('Selecione no máximo duas categorias.');
        }
    }));

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const button = form.querySelector('input[type="submit"]');
        button.disabled = true;
        button.value = 'Salvando...';

        try {
            const response = await fetch('../../backend/funcionalidades/criar_conteudo_educativo.php', {
                method: 'POST',
                body: new FormData(form),
                credentials: 'same-origin'
            });
            const result = await response.json();
            if (!response.ok || result.status !== 'ok') throw new Error(result.message || 'Não foi possível salvar o conteúdo.');
            alert(result.message);
            window.location.href = 'conteudo_educativo.php';
        } catch (error) {
            alert(error.message || 'Ocorreu um erro ao salvar. Tente novamente.');
            button.disabled = false;
            button.value = 'Adicionar conteúdo';
        }
    });
});
