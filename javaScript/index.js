let formEntrar = document.querySelector(".formEntrar")
let formCad = document.querySelector(".formCad")
let divForms = document.getElementById("forms-insanos")

let inputName = document.getElementById("nome")
let select = document.querySelector("#curso")

let inputRM = document.getElementById("rm")
let passwordCad = document.querySelector(".divPasswordCad")
let emailCad = document.getElementById("emailCad")

let btnSubmit = document.querySelector(".submit")
let button = document.getElementById("button")

let seta = document.getElementById("seta")


let submitCad = document.getElementById("submitCad")
let boxSelect=document.querySelector(".box-select")

const screenWidth = window.screen.width

function inOff(inEmailCad,inSenhaCad,inName,rm,seletor,btSubmit,btContinue, inSeta){
    inputRM.style.display = rm
    boxSelect.style.display = seletor
    passwordCad.style.display = inSenhaCad 
    emailCad.style.display = inEmailCad
    inputName.style.display = inName
    button.style.display = btContinue
    btnSubmit.style.display = btSubmit
    seta.style.display = inSeta
}

function showPassword(indice){
    let imagemSenha = document.querySelectorAll(".eye")
    let inputPassword = document.querySelectorAll(".inputPassword")
    if(inputPassword[indice].type == "password"){
        inputPassword[indice].type = "text"
        imagemSenha[indice].setAttribute("src","../images/noEyePassword.png")
    }else{
        inputPassword[indice].type = "password"
        imagemSenha[indice].setAttribute("src","../images/eyePassword.png")
    }
}

async function verificarDados(){
    let senha = passwordCad.querySelector("input").value
    let valueEmail = emailCad.value
    let valueNome = inputName.value
    
    var regexEmail = /^[a-z]+\.[a-zA-Z]+(\d)?/
    var regexNome = /^[a-zA-Z0-9\s]+$/;

    if(senha == "" || valueEmail=="" || valueNome == ""){
        alert("Preencha todos os campos")
        return false
    }

    if (regexEmail.test(valueEmail)) {
        var dominioValido = /@etec\.sp\.gov\.br$/.test(valueEmail);
        
        if ( ! (dominioValido) ){
            alert("Seu email deve possuir o domínio @etec.sp.gov.br")
            return false;
        }

    }else {
        alert("email inválido")
        return false
    }

    if ( !(regexNome.test(valueNome)) ) {
        alert("Seu nome não deve possuir caracteres especiais")
        return false
    }

    if(senha.length < 8){
        alert("Sua senha deve possuir no mínimo 8 caracteres")
        return false
    }

    const response = await fetch('../classes/phpjs.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json',},
        body: JSON.stringify({ tipo:"verificar", email: emailCad.value, nome: inputName.value }),
    });

    const data = await response.json();
    if('erro' in data){
        alert(data.erro)
        return false
    }

    return true
}


function cadastrar(tipo){
    divForms.style.animation = `transitar 1.5s linear ${tipo == 1 ? "" : "reverse"}`
    setTimeout(()=>{
        inOff("block","flex","block","none","none","none","flex","none")
        formEntrar.style.display = "none"
        formCad.style.display = "flex"
    },750)
    setTimeout(()=>{
        divForms.style.animation = ""
    },1500)
    //nao tem como fazer alterar a classe da animação?
}


function entrar(){
    divForms.style.animation = "transitar 1.5s linear reverse"
    setTimeout(()=>{
        formEntrar.style.display = "flex"
        formCad.style.display = "none"
    },750)
    setTimeout(()=>{
        divForms.style.animation = ""
    },1500)
}


button.addEventListener("click", async()=>{
    if(await verificarDados() == false){return}

    if(button.innerHTML == "Continuar"){
        divForms.style.animation = "transitar 1.5s linear"
        setTimeout(()=>{
            inOff("none","none","none","block","block","block","none","block") 
        },750)
        setTimeout(()=>{
            divForms.style.animation = ""
        },1500)
    }
})

let rm = document.getElementById("rm")

rm.addEventListener("change", ()=>{
    if(rm.value.length > 4 || rm.value.length < 4){
        alert("Seu RM deve possuir exatamente 4 caracteres")
    }
})

var cursoEscolha= document.querySelector(".cursoEscolha");
function getEscolha(){
    var escolha = select.options[select.selectedIndex].text;
    cursoEscolha.value=escolha;
}

document.addEventListener("input", async()=>{
    if(rm.value.length == 4){
        const response = await fetch('../classes/phpjs.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json',},
            body: JSON.stringify({ tipo:"verificarRM", rm: rm.value }),
        });
    
        const data = await response.json();
        if('erro' in data){
            alert(data.erro)
            return false
        }

        if(cursoEscolha.value !== ""){
            submitCad.disabled = false
            submitCad.style.cursor = "default"
        }else{ return false }
    }else{
        submitCad.disabled = true
        submitCad.style.cursor = "not-allowed"
    }
})

//ALTERAR SENHA ->
let modalEsqueceuSenha = document.getElementById("modal-esqueceuSenha");
let div1 = document.getElementById("div1")
let div2 = document.getElementById("div2")
let div3 = document.getElementById("div3")
let btnTrocarSenha = document.querySelector(".esqueceuSenha");

function nextStage(){
    div1.style.display="none"
    div2.style.display="flex"
}

function nextStage2(){
    let codigo = document.getElementById("codigo").value
    
    if(codigo == "123456"){
        div2.style.display="none"
        div3.style.display="flex"
    }
}

btnTrocarSenha.addEventListener("click",(e)=>{
    e.preventDefault();
    modalEsqueceuSenha.style.display="block";
    

    document.addEventListener("click",(e)=>{
        let clique = e.target.matches("#modal-esqueceuSenha")
        let cliqueX = e.target.matches("#sairModal")
        if(clique || cliqueX){
            e.stopPropagation();
            window.location.href = "index.php"
        }
    })
    
})


