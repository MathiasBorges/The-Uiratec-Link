<?php
session_start();
if($_SESSION['token'] == ""){
    echo "<script>
        window.location.href='index.php'
        </script>";
}

$nome = $_SESSION['nome'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/anonim.css">
    <link rel="stylesheet" href="../css/default.css">
    <link rel="icon" href="../images/brainLogo.png">
    <script src="../javaScript/default.js" defer></script>
    <script src="../javaScript/anonim.js" defer></script>
    <title>Anônimo</title>
</head>
<body>
<header>
        <img src="../images/brainLogo.png" id="logo">
        <div id="title-header">
            <h1>The Uiratec</h1>
            <p id="TipoPensamentos">Anônimo</p>
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

    <div id="publicacoes">
        <?php
            include '../classes/Usuario.php';
            include '../classes/Pensamento.php';
            $pensamento=new Pensamento();
            $pensamento->carregarPensamentosAnonimos($_SESSION['user']);
        ?>
    </div>
    <div id="selecionado"></div>


    <div id="modal-denuncia">
        <div id="content-modal">
            <h1>Descreva brevemente o motivo de sua denúncia</h1>

            <form method="post">
                <textarea name="text_den" id="motivoDenuncia" cols="20" rows="7" maxlength="100" wrap="soft"></textarea>
                <input type="text" style="display: none;" id="idDen" name="idDen">
                <div class="buttons">
                    <input type="submit" value="Denunciar" class="button">
                    <div class="button" id="cancelarDenuncia" onclick="alterarModal()">Cancelar</div>
                </div>
            </form>
        </div>
    </div>
    
</body>
</html>

<?php
    $id = $_SESSION['user'];
    if( isset($_POST['text_den']) && isset($_POST['idDen']) ){
        $idPensamentoDenunciado = $_POST['idDen'];
        $textoMotivo = $_POST['text_den'];

        $id = $_SESSION['user'];
        $pensamento=new Pensamento();

        if($textoMotivo == "" || $textoMotivo == null){
            echo "<script>setTimeout(() => { alert('Não envie denúncias vazias') },1);
                setTimeout(() => { window.location.href = window.location.href; }, 1000);
            </script>";
            
        }else{
            $pensamento->denunciarPensamento($idPensamentoDenunciado, $id, $textoMotivo);
            echo "<script>; 
                setTimeout(() => { alert('Denúncia enviada')},1)
                setTimeout(() => { window.location.href = window.location.href; }, 2000);
            </script>";
        }
    }
?>