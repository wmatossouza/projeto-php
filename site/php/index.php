<?php
require 'banco.php';

$id = $_REQUEST['acao'];
if($id == 'cadastrar'){
    cadastrar();
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

        // $sql = 'SELECT * FROM phprs.pessoa ORDER BY id DESC';
        // $q = $pdo->query($sql);
        // $data = $q->fetchAll(PDO::FETCH_ASSOC);
        // echo json_encode($data);

        // $sql = 'SELECT * FROM phprs.pessoa ORDER BY id DESC';
        // $q = $pdo->query($sql);
        // $data = $q->fetchAll(PDO::FETCH_ASSOC);
        // echo json_encode($data);

        $sql = "INSERT INTO 3491651_jogobd.usuarios (nome, sobrenome, email, login, senha) VALUES(?,?,?,?,?)";
        $q = $pdo->prepare($sql);
        $q->execute(array($nome,$sobrenome,$email,$login, $senha));
        echo json_encode('OK');
        Banco::desconectar();
    }    
}

?>