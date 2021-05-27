<?php
require 'banco.php';

$req = $_REQUEST['acao'];
if($req == 'cadastrar'){
    cadastrar();
}else if($req == 'login'){
    login();
}else if ($req == 'alterarSenha'){
    alterarSenha();
}else if($req == 'carregaRanking'){
    if($_GET['consultaPontuacao'] == 'OK'){
        session_start();
        carregaRanking($_SESSION["usuario"]);
    }else{
        carregaRanking("");
    }
}

function carregaRanking($login){
    $where = ' where 1 = 1 ';
    $order = ' order by r.pontuacao desc LIMIT 5 ';
    if($login){
        if($login == 'jogo'){
            $order = ' order by r.pontuacao desc LIMIT 30 ';
        }else{
            $where = "$where and login = '".$login."'";
        }    
    }
    $pdo = Banco::conectar();

    $sql = "select u.id, u.nome, r.pontuacao from ranking r LEFT JOIN usuarios u on u.id = r.id_usuario";
    $sql = "$sql $where $order";
   
    $q = $pdo->query($sql);
    $data = $q->fetchAll(PDO::FETCH_ASSOC);
    
    if ($data){
        echo json_encode($data);
    }else{
        echo json_encode("nenhum registro");
    }

    Banco::desconectar();
}

function login(){
    $pdo = Banco::conectar();

    $email = $_POST['email'];
    $senha = md5($_POST['senha']);

    $sql = "SELECT * FROM $dbNome".".usuarios where email = " . "'".$email."'" . "and senha = " . "'".$senha."'"; //where login = ? and senha = ?';
    $q = $pdo->query($sql);
    $data = $q->fetchAll(PDO::FETCH_ASSOC);
    
    if ($data){
        session_start();
        $_SESSION["usuario"] = $data[0]['login'];
        $_SESSION["nome"] = $data[0]['nome'] . " " . $data[0]['sobrenome'];
        $array=['ok',$_SESSION["nome"]];
        echo json_encode($array, JSON_FORCE_OBJECT);
    }else{
        echo json_encode("semAcesso");
    }

    Banco::desconectar();
}

function alterarSenha(){
    $senhaAtual = md5($_POST['senhaAtual']);
    $novaSenha = md5($_POST['novaSenha']);
    $confirmarNovaSenha = md5($_POST['confirmarNovaSenha']);
    

    if ($novaSenha != $confirmarNovaSenha){
        echo json_encode('senhaDiferente');
        return;
    }else{
        session_start();
        $pdo = Banco::conectar();
        
        $sql = "SELECT * FROM usuarios where login = " . "'".$_SESSION["usuario"]."'" . "and senha = " . "'".$senhaAtual."'";
        $q = $pdo->query($sql);
        $data = $q->fetchAll(PDO::FETCH_ASSOC);

        if ($data){
            $sql = "UPDATE usuarios set senha = ? WHERE login = ?";
            $q = $pdo->prepare($sql);
            $q->execute(array($novaSenha, $_SESSION["usuario"]));
            echo json_encode("OK");
            Banco::desconectar();
        }else{
            Banco::desconectar();
        }
    } 
}

function cadastrar(){
    $nome = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];
    $email = $_POST['email'];
    $login = $_POST['login'];
    $senha = md5($_POST['senha']);
    $confirmarSenha = md5($_POST['confirmarSenha']);

    if ($senha != $confirmarSenha){
        echo json_encode('senhaDiferente');
        return;
    }else if($nome == '' && $email == '' && $login == ""){
        echo json_encode('campoVazio');
        return;   
    }else{

        $pdo = Banco::conectar();

        $sql = "SELECT * FROM $dbNome".".usuarios where login = " . "'".$login."'";
        $q = $pdo->query($sql);
        $data = $q->fetchAll(PDO::FETCH_ASSOC);
        
        if ($data){
            echo json_encode('jaExiste');
            Banco::desconectar();
        }else{

            // $sql = 'SELECT * FROM phprs.pessoa ORDER BY id DESC';
            // $q = $pdo->query($sql);
            // $data = $q->fetchAll(PDO::FETCH_ASSOC);
            // echo json_encode($data);

            $sql = "INSERT INTO $dbNome".".usuarios (nome, sobrenome, email, login, senha) VALUES(?,?,?,?,?)";
            $q = $pdo->prepare($sql);
            $q->execute(array($nome,$sobrenome,$email,$login, $senha));
            echo json_encode('OK');
            Banco::desconectar();
        } 
    }    
}

?>