<?php
    session_start(); 
    if($_SESSION['token'] == ""){
        echo "<script>
        window.location.href='index.php'
        </script>";
    }
    $id = $_SESSION['user'];
    $nome = $_SESSION['nome'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/perfil.css">
    <link rel="icon" href="../images/brainLogo.png">
    <script src="../javaScript/default.js" defer></script>
    <script src="../javaScript/perfil.js" defer></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito">
    <title>
        <?php echo $nome ?>
    </title>
    <link rel="stylesheet" href="../css/default.css">
</head>
<body>

    <header>
        <img src="../images/brainLogo.png" id="logo">
        <div id="title-header">
            <h1>The Uiratec</h1>
        </div>
        <div id="hamburguer" onclick="ativar()">
            <div class="linhas" id="linha1"></div>
            <div class="linhas" id="linha2"></div>
            <div class="linhas" id="linha3"></div>
        </div>
    </header>
    <div id="sidebar">
        <a href="perfil.php" style="color:white;text-decoration:none;">
            <div id="perfil">
                <img src="../images/perfil.png">
                <p class="nomeUsuario"> <?php echo $nome ?> </p>
            </div>
        </a>
        <div>
            <a href="geral.php?tipo=1&titulo=Público" style="color:white;text-decoration:none"><div class="categorias" id="geral"> <img src="../images/brain.png" id="brain"> <p>Público</p></div></a>
            <a href="geral.php?tipo=5&titulo=Nutrição" style="color:white;text-decoration:none"><div class="categorias" id="nutri"> <img src="../images/cobra.png"> <p>Nutrição</p> </div></a>
            <a href="geral.php?tipo=3&titulo=Administração" style="color:white;text-decoration:none"><div class="categorias" id="adm"> <img src="../images/excel.png"> <p>Administração</p> </div></a>
            <a href="geral.php?tipo=4&titulo=Desenvolvimento%20de%20Sistemas" style="color:white;text-decoration:none"><div class="categorias" id="ds"> <img src="../images/computador.png"> <p>Desenvolvimento de Sistemas</p> </div></a>
            <a href="anonimos.php" style="color:white;text-decoration:none"><div class="categorias" id="anonim"> <img src="../images/anonimo.png"> <p>Anônimo</p> </div></a>
        </div>
    </div>

    <div id="conteudo">
        <div id="perfil-section">
            <img src="../images/perfil.png">
            <div id="infos">
                <p class="nome"> <?php echo $nome ?> </p>
                <p id="cursoUser"> <?php echo $_SESSION['curso']?> </p>
            </div>
            <button id="criarPost">Criar post</button>
            <a href="#" id="sair">Sair</a>
        </div>

        <div id="opcoes">
            <div class="opcao" id="btnPost">Posts</div>
            <div class="opcao" id="btnFavoritos">Favoritos</div>
        </div>

        <div id="sections">
            <div id="posts-section">
                <?php
                include '../classes/Usuario.php';
                include '../classes/Pensamento.php';
                $pensamento=new Pensamento();
                $pensamento->carregarPostagensPerfil($id);
                ?>

            </div>
            <div id="favorite-section">
                <?php
                    $pensamento=new Pensamento();
                    $pensamento->carregarFavoritos();
                ?>
            </div>
        </div>

    </div>

    <div id="modal">
        <form id="caixaCriarPost" method="POST">
            <div id="corVoltar">
                <div id="voltar">X</div>
                <div id="escolherCor">
                    <fieldset id="opcoesCores">
                        <div class="cor" id="blue">Azul</div>
                        <div class="cor" id="red">Vermelho</div>
                        <div class="cor" id="green">Verde</div>
                        <div class="cor" id="black">Preto</div>
                    </fieldset>
                    <input type="text" style="display:none;" value="#B7C1ED" id="corHexa" name="hexadecimal">
                </div>
            </div>
            <textarea name="userpensamento" cols="50" rows="10" maxlength="500" wrap="soft"></textarea>
            <div id="opcoesPostagem">
                <div class="buttonOpcao" id="2">Anônimo</div>
                <div class="buttonOpcao" id="1">Público</div>
                <div class="buttonOpcao" id="curso">Curso</div>

                <input type="text" name="tPubli" id='tipoPubli' value="1" style="display:none;">
                <input type="submit" id="submitButton" value="Enviar">
            </div>
        </form>
    </div>

    <div id="modalSair" class="modalOpcao">
        <div class="conteudo-modal">
            <p class="texto-modal">Deseja sair de sua conta?</p>
            <div class="buttons">
                <button id="btnSair">Sim</button>
                <button onclick="trocalModalSair()">Não</button>
            </div>
        </div>
    </div>

    <div id="modalApagar" class="modalOpcao">
        <div class="conteudo-modal">
            <p>Tem certeza que deseja apagar seu post?</p>
            <div class="buttons">
                <button id="btnConfirmar">Sim</button>
                <button id="fecharModalApagar">Não</button>
            </div>
        </div>
    </div>

    <div id="selecionado"></div>
    
</body>
</html>

<?php

    if( isset($_GET['dsajwidqu']) ){
        $posicao = $_GET['dsajwidqu'];
        echo "<script>window.scrollTo(0, ".$posicao.")</script>";
    }
    
    if(isset($_POST['userpensamento']) && isset($_POST['hexadecimal']) && isset($_POST['tPubli']) ){
        $texto = $_POST['userpensamento'];

        if($texto == "" || is_null($texto)){
            echo "<script>alert('Vai se fuder seu macaco, faz as coisas direito')</script>";
            return;
        }

        $texto = nl2br($texto);
        $cor = $_POST['hexadecimal'];

        $tipo =  $_POST['tPubli'];
        if($tipo == "curso"){
            $curso = $_SESSION['curso'];
            if($curso == "Administração"){$tipo = 3;}
            else if ($curso == "Desenvolvimento de Sistemas"){$tipo = 4;}
            else if ($curso == "Nutrição"){$tipo = 5;}
        }else{
            $tipo =  intval($_POST['tPubli']);
        }
        $pensamento->adicionarPostagem($texto,$cor,$id,$tipo);
        echo "<script>
        window.location.href='perfil.php'
        </script>";
    }
?>