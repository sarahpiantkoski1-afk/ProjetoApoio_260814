document.addEventListener('click', (event) => {
    const link = event.target.closest('[data-historico-tipo][data-historico-id]');
    if (!link) return;

    link.classList.add('foi-visitado');
    const dados = new FormData();
    dados.append('tipo', link.dataset.historicoTipo);
    dados.append('item_id', link.dataset.historicoId);

    fetch('../../backend/funcionalidades/registrar_historico.php', {
        method: 'POST',
        body: dados,
        credentials: 'same-origin',
        keepalive: true
    }).catch(() => {});
});
