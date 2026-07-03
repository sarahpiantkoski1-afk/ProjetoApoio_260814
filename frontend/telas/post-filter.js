document.addEventListener("DOMContentLoaded", () => {
    const filtro = document.querySelector(".filtro-postagens");
    const botaoFiltro = document.querySelector(".botao-filtro");
    const painelFiltro = document.querySelector(".painel-filtro");
    const chips = document.querySelectorAll(".filtro-chip");
    const cards = document.querySelectorAll(".post[data-categorias], .educativo-card[data-categorias]");
    const semResultados = document.querySelector(".sem-resultados");

    if (!filtro || !botaoFiltro || !painelFiltro || cards.length === 0) {
        return;
    }

    const alternarPainel = () => {
        const vaiAbrir = painelFiltro.hidden;
        painelFiltro.hidden = !vaiAbrir;
        botaoFiltro.setAttribute("aria-expanded", String(vaiAbrir));
    };

    const aplicarFiltro = (categoriaAtiva) => {
        let visiveis = 0;

        cards.forEach((card) => {
            const categorias = card.dataset.categorias
                .split(",")
                .map((categoria) => categoria.trim())
                .filter(Boolean);
            const mostrar = !categoriaAtiva || categorias.includes(categoriaAtiva);

            card.hidden = !mostrar;

            if (mostrar) {
                visiveis++;
            }
        });

        if (semResultados) {
            semResultados.hidden = visiveis !== 0;
        }
    };

    botaoFiltro.addEventListener("click", alternarPainel);

    chips.forEach((chip) => {
        chip.addEventListener("click", () => {
            const jaAtivo = chip.classList.contains("ativo");

            chips.forEach((item) => item.classList.remove("ativo"));

            if (jaAtivo) {
                aplicarFiltro("");
                return;
            }

            chip.classList.add("ativo");
            aplicarFiltro(chip.dataset.categoria);
        });
    });

    document.addEventListener("click", (event) => {
        if (!filtro.contains(event.target)) {
            painelFiltro.hidden = true;
            botaoFiltro.setAttribute("aria-expanded", "false");
        }
    });

    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape") {
            painelFiltro.hidden = true;
            botaoFiltro.setAttribute("aria-expanded", "false");
        }
    });
});
