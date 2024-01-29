document.addEventListener("DOMContentLoaded",()=>{

    let post = document.getElementById("btnPost")
post.style.background = "rgb(211, 211, 211)"

document.addEventListener("click", (e) => {
    let id = e.target.id
    let postsSection = document.getElementById("posts-section")
    let favoriteSection = document.getElementById("favorite-section")

    if (e.target.matches(".opcao")) {
        document.querySelectorAll(".opcao").forEach(bloco => bloco.style.background = "white")
        document.getElementById(id).style.background = "rgb(211, 211, 211)"
    }else{
        return;
    }

    postsSection.style.display = (id === "btnPost") ? "grid" : "none"
    favoriteSection.style.display = (id === "btnFavoritos") ? "grid" : "none"
    id === "btnPost" ? window.location.href = `perfil.php?dsajwidqu=${window.scrollY}` : ""
});

let criarPost = document.getElementById("modal")
criarPost.style.display = "none"
let body = document.querySelector("body")

document.querySelector("#criarPost").addEventListener("click",()=>{
    if (criarPost.style.display == "none"){
        criarPost.style.display = "flex"
        body.style.overflow = "hidden"
    }
    console.log("Botão clicado")
})

document.querySelector("#voltar").addEventListener("click",()=>{
        criarPost.style.display = "none"
        body.style.overflow = "auto"
    })

document.addEventListener("click", (e) => {
    let inputcor = document.getElementById("corHexa");
    let textArea = document.querySelector("textarea");
    let caixaModal = document.getElementById("caixaCriarPost");
    let id = e.target.id;

    const cores = {
        red: { background: "#FF8181", modalBackground: "#910303" },
        green: { background: "#7dd76f", modalBackground: "#35C61D" },
        black: { background: "#91939C", modalBackground: "#555866" },
        blue: { background: "#B7C1ED", modalBackground: "#92A1E3" },
    };

    if (e.target.matches(".cor") && cores[id]) {
        textArea.style.background = cores[id].background;
        caixaModal.style.background = cores[id].modalBackground;
        inputcor.value = cores[id].background;
    }
});

document.addEventListener("click", (e) =>{
    let opcaoTipo = e.target.closest(".buttonOpcao")
    if( opcaoTipo) {
        let inputTipo = document.getElementById("tipoPubli")
        let tipos = document.querySelectorAll(".buttonOpcao")
        tipos.forEach(tipo => tipo.style.boxShadow = "")
        let elemento = e.target
        elemento.style.boxShadow = "0 0 15px 2px black"
        inputTipo.value = opcaoTipo.id
    }
})

document.querySelector("#btnSair").addEventListener ("click",async ()=>{
    const response = await fetch('../classes/phpjs.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json',},
        body: JSON.stringify({ tipo: "sair", sair : true }),
    });

    window.location.href='index.php'
})

function trocarModal(modal){
    if(modal.style.display == "none"){
        modal.style.display = "flex"
    }else{
        modal.style.display = "none"
    }

}

let modalSair = document.getElementById("modalSair")
modalSair.style.display = "none"
document.querySelector("#sair").addEventListener("click",()=>{
    trocarModal(modalSair)
})

let modalApagar = document.getElementById("modalApagar")
modalApagar.style.display = "none"

 document.querySelector("#fecharModalApagar").addEventListener("click",()=>{
    trocarModal(modalApagar)
})

document.addEventListener("click", (e)=>{
    if(e.target.matches(".apaga")){
        trocarModal(modalApagar)
        let id = e.target.id.substring(6)
        let btnConfirmar = document.getElementById("btnConfirmar")

        btnConfirmar.addEventListener("click", async() => {
            
            await fetch('../classes/phpjs.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json',},
                body: JSON.stringify({ tipo: "apagar", id : id }),        
            })
            
            let posicao = window.scrollY
            window.location.href=`perfil.php?dsajwidqu=${posicao}`
        })
    }

})

})