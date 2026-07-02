document.addEventListener("DOMContentLoaded", () => {
    const header = document.querySelector("header");
    const nav = document.querySelector("#options-header");

    if (!header || !nav || header.querySelector(".menu-toggle")) {
        return;
    }

    const button = document.createElement("button");
    button.type = "button";
    button.className = "menu-toggle";
    button.setAttribute("aria-label", "Abrir menu");
    button.setAttribute("aria-expanded", "false");
    button.setAttribute("aria-controls", "options-header");
    button.innerHTML = "<span></span><span></span><span></span>";

    header.insertBefore(button, nav);

    const closeMenu = () => {
        header.classList.remove("menu-open");
        button.setAttribute("aria-expanded", "false");
        button.setAttribute("aria-label", "Abrir menu");
    };

    button.addEventListener("click", () => {
        const isOpen = header.classList.toggle("menu-open");
        button.setAttribute("aria-expanded", String(isOpen));
        button.setAttribute("aria-label", isOpen ? "Fechar menu" : "Abrir menu");
    });

    nav.addEventListener("click", (event) => {
        if (event.target.closest("a")) {
            closeMenu();
        }
    });

    document.addEventListener("click", (event) => {
        if (!header.contains(event.target)) {
            closeMenu();
        }
    });

    window.addEventListener("resize", () => {
        if (window.innerWidth > 768) {
            closeMenu();
        }
    });
});
