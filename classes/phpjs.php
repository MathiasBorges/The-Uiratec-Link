<?php
include '../classes/Usuario.php';
include '../classes/Pensamento.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$data = json_decode(file_get_contents("php://input"),true);
    $usuario = new Usuario();
    $pensamento = new Pensamento();
    session_start();

    if(isset($_SESSION['token'])){
        $idUser = $_SESSION['user']; 
        $nomeUser = $_SESSION['nome']; 
        $cursoUser = $_SESSION['curso']; 
    }

    // agora substitui certo nas funções e apaga as repetidas
	if ($data['tipo'] == "verificar") {
		$email = $data['email'];
		$nome = $data['nome'];

		$result = $usuario->conferirUsuario($email,$nome);

		echo json_encode($result);

	} elseif($data['tipo'] == "verificarRM"){
        $rm = $data['rm'];
		$result = $usuario->verificarRM($rm);

		echo json_encode($result);

    }elseif($data['tipo'] == "sair"){
        $_SESSION['nome'] = ""; // isso aqui, deixa ok // sim é pra marcar o fim da sessão
        $_SESSION['user'] = "";
        $_SESSION['token'] = "";
        $result = true;
        echo json_encode(['sucesso' => '']);

    } elseif($data['tipo'] == "dadosSession"){
        $result = [ 'nome' => $nomeUser ];
        echo json_encode($result);

    } elseif( $data['tipo'] == "verificarPensamento" ){
        $idPensamento = intval($data['idPensamento']);

        $result1 = $pensamento->verificarPensamento($idUser,$idPensamento);
        $result = $pensamento->cursoNomePensamento($idPensamento);

        if($result1 == true){
            $result['boolean'] = false;
        }else{
            $result['boolean'] = true;
        }
        $result['favoritos'] = $pensamento->consultarFavoritos($idUser);
    
        echo json_encode($result);

    } elseif($data['tipo'] == "favoritar"){
        $idFavoritar = $data['id'];
        $verificacaoPensamento = $pensamento->verificarPensamento($idUser,$idFavoritar);
        
        if( $verificacaoPensamento == false ){
            $pensamento->favoritar($idUser,$idFavoritar);
        }
        
    } elseif($data['tipo'] == "desfavoritar"){
        $idFavoritar = $data['id'];
        $pensamento->desfavoritar($idUser,$idFavoritar);
    }

    elseif($data['tipo'] == "apagar"){
        $idPensamento = $data['id'];
        $verificacao = $pensamento->verificarPensamento($idUser,$idPensamento);
        if($verificacao == false) {return;}
        $pensamento->apagarPostagem($idPensamento);
    }
}
?>