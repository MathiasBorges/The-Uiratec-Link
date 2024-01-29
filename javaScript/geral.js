// mudando página e categoria dos pensamentos
document.addEventListener("click", (e) => {
    let categoria = e.target.closest(".categorias")

    if (categoria) {
        ativar()
        let id = categoria.id
        setTimeout(() => {
            if (id === "anonim") {
                window.location.href = "../php/anonimos.php"
            }

            const categoriasMap = {
                "geral": { tipo: 1, texto: "Público" },
                "adm": { tipo: 3, texto: "Administração" },
                "ds": { tipo: 4, texto: "Desenvolvimento de Sistema" },
                "nutri": { tipo: 5, texto: "Nutrição" },
            };

            let tipo = categoriasMap[id].tipo
            let textHeader = categoriasMap[id].texto

            window.location.href = `../php/geral.php?tipo=${tipo}&titulo=${textHeader}`
        },700)
    }
});