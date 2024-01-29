const sidebar = document.getElementById("sidebar");
sidebar.style.display = "none";

function handleScroll() {
    window.scrollTo(0, 0);
}

function ativar() {

    const sideClose = sidebar.style.display === "none";
    sidebar.style.display = "block";

    sidebar.classList.remove(sideClose ? "classSideVoltar" : "classSideIr")
    sidebar.classList.add(sideClose ? "classSideIr" : "classSideVoltar")
    
    if (sideClose) {
        document.addEventListener('scroll', handleScroll);
    } else {
        setTimeout(() => {
            sidebar.style.display = "none";
            document.removeEventListener('scroll', handleScroll);
        }, 700);
    }
    
}





//Funções estrela
document.addEventListener("click", async (e) => {
    if (e.target.matches(".estrela")) {
        const estrela = e.target;
        const isFavorito = estrela.getAttribute("src") === "../images/favorite.png";
        const id = isNaN(parseInt(e.target.id, 10)) ? e.target.id.substring(3) : e.target.id;

        let id2 = e.target.id.substring(3)
        if(id2){
            let estrela2 = document.getElementById(id2)
            estrela2.setAttribute("src", isFavorito ? "../images/noFavorite.png" : "../images/favorite.png")
        }

        estrela.style.animation = "favoritar 1s linear";

        setTimeout(() => {
            estrela.setAttribute("src", isFavorito ? "../images/noFavorite.png" : "../images/favorite.png");
        }, 700);

        setTimeout(() => {
            estrela.style.animation = "";
            document.body.style.pointerEvents = "auto";
        }, 1000);

        document.body.style.pointerEvents = "none";

        const response = await fetch('../classes/phpjs.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ tipo: isFavorito ? "desfavoritar" : "favoritar", id: id }),
        });
    }
});

//Entrando em perfis
document.addEventListener("click", (e)=>{
    let campoUsuario = e.target.closest(".usuario")
    if(!campoUsuario){
        campoUsuario = e.target.closest(".imgPerfil")
    }
    if(campoUsuario){
        let contaUsuario = campoUsuario.id
        if(contaUsuario == ""){ 
            window.location.href= "perfil.php";
        }else{
            carregarConta(contaUsuario)
        }
    }
})

function carregarConta(conta){
    window.location.href= `conta.php?username=${conta}`;
}

//FIM ENTRANDO EM PERFIS



//Modal Denunciar 
let modal
try {
    modal = document.getElementById("modal-denuncia")
    modal.style.display = "none"    
} catch (error) {
    modal = ""
}

function alterarModal(){
    isOn = modal.style.display == "flex"
    modal.style.display = isOn ? "none" : "flex"
}

document.addEventListener("click", (e)=>{
    let denuncia = e.target.matches(".btnDenunciar")
    if(denuncia){
        let id = e.target.id.substring(4)
        let inputIdDen = document.getElementById("idDen")
        inputIdDen.value = id
        alterarModal()
    }
})
//FIM MODAL DENUNCIAR




//Gerar pensamentos selecionado (clicados)

let selecionado = document.getElementById("selecionado"); // DIV COM O PENSAMENTO SELECIONADO
let publicacoes = document.getElementById("publicacoes"); // DIV COM TODOS OS PENSAMENTOS
if(!publicacoes){
    publicacoes = document.getElementById("sections"); //ALTERANDO CASO EM TELAS QUE NÃO POSSUEM A DIV "publicacoes"
}

function gerarPensamento(texto, nome, cor, favoritar, curso, favoritos, id) {
    let favoritarDenunciar = ""
    let nomeCurso = ""
    
    if (favoritar) {
        const isFavorito = favoritos.includes(id)

        favoritarDenunciar = `
            <div class="textoImagem">
                <img src="../images/${isFavorito ? 'favorite.png' : 'noFavorite.png'}" id="fav${id}" class="estrela">
                <p id="textoFavoritar">${isFavorito ? 'Desfavoritar' : 'Favoritar'}</p>
            </div>
            <div class="textoImagem">
                <img src="../images/denuncia.png" id="den-${id}" class="btnDenunciar">
                <p>Denunciar</p>
            </div>`
    }

    if(nome !== undefined){
        nomeCurso = `
            <div id="info-usuario" class="textoImagem">
                <img src="../images/perfil.png" id="${nome}" class="imgPerfil">
                <div id="nomeCurso">
                    <p>${nome}</p>
                    <p>${curso}</p>
                </div>
            </div>`
    }

    selecionado.innerHTML += `
        <div class="pensamentoSelecionado" style="background: ${cor}">
            <p class="texto">${texto}</p>
        </div>
        <div id="informacoesPostagem">
            ${nomeCurso}
            ${favoritarDenunciar}
            <button id="btnVoltar" onclick="voltar()">Voltar</button>
        </div>`
}


//RECONHECIMENTO DE CLIQUE EM PENSAMENTOS E ATIVAÇÃO 
document.addEventListener("click", async (e) => {
    const areaPensamento = e.target.closest(".pensamento")

    if (areaPensamento) {
        document.body.style.pointerEvents = "none"

        const id = areaPensamento.id.substring(14)

        let dados = await fetch('../classes/phpjs.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json',},
            body: JSON.stringify({ tipo:"verificarPensamento", idPensamento: id }),
        })
        dados = await dados.json()

        let pensamento = document.getElementById(areaPensamento.id)
        let textoDoPensamento = pensamento.querySelector('.texto').textContent

        let nome = dados['nome_usuario']
        let cor = pensamento.style.background;

        gerarPensamento(textoDoPensamento, nome, cor, dados['boolean'], dados['curso_usuario'], dados['favoritos'], id)
        exibirPensamento()

        setTimeout(() => {
            document.body.style.pointerEvents = "auto"
        }, 800)
    }
});



var posicao; // POSIÇÃO DO USUÁRIO NA TELA PARA MANTER FLUIDEZ

// ANIMAÇÃO NAS DIVS SELECIONADO E PUBLICACOES
function exibirPensamento() {
    posicao = window.scrollY
    publicacoes.style.animation = "desaparecer .5s linear forwards"

    setTimeout(() => {
        publicacoes.style.display = "none"
        selecionado.style.display = "grid"
        publicacoes.style.animation = "desaparecer .5s linear forwards"
    }, 500);
    setTimeout(() => {
        publicacoes.style.animation = ""
        selecionado.style.animation = ""
    }, 1000);  
}

//REVERSO DA exibirPensamento()

function voltar() {
    selecionado.style.animation = "desaparecer .5s linear forwards"

    setTimeout(() => {
        selecionado.style.display = "none"
        publicacoes.style.display = "grid"
        publicacoes.style.animation = "desaparecer .5s linear reverse forwards"
        window.scrollTo(0, posicao)
    }, 500)

    setTimeout(() => {
        selecionado.innerHTML = ""
        publicacoes.style.animation = ""
        selecionado.style.animation = ""
    }, 1000)
}

//FIM DE EXIBIÇÃO DE PENSAMENTOS SELECIONADOS (CLICADOS)

