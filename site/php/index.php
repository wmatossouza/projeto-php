<?php
require 'banco.php';

$req = $_REQUEST['acao'];
if($req == 'cadastrar'){
    cadastrar();
}else if($req == 'login'){
    login();
}else if ($req == 'alterarSenha'){
    alterarSenha();
}

function login(){
    $pdo = Banco::conectar();

    $login = $_POST['login'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM $dbNome".".usuarios where login = " . "'".$login."'" . "and senha = " . "'".$senha."'"; //where login = ? and senha = ?';
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
    $senhaAtual = $_POST['senhaAtual'];
    $novaSenha = $_POST['novaSenha'];
    $confirmarNovaSenha = $_POST['confirmarNovaSenha'];
    

    if ($novaSenha != $confirmarNovaSenha){
        echo json_encode('senhaDiferente');
        return;
    }else{
        session_start();
        $pdo = Banco::conectar();
        
        $sql = "SELECT * FROM $dbNome".".usuarios where login = " . "'".$_SESSION["usuario"]."'" . "and senha = " . "'".$senhaAtual."'";
        $q = $pdo->query($sql);
        $data = $q->fetchAll(PDO::FETCH_ASSOC);

        if ($data){
            $sql = "UPDATE $dbNome".".usuarios set senha = ? WHERE login = ?";
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
    $senha = $_POST['senha'];
    $confirmarSenha = $_POST['confirmarSenha'];

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