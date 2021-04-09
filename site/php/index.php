<?php
require 'banco.php';

$id = $_REQUEST['acao'];
if($id == 'cadastrar'){
    cadastrar();
}

function cadastrar(){
    $nome = $_POST['nome'];
    $pdo = Banco::conectar();

    // $sql = 'SELECT * FROM phprs.pessoa ORDER BY id DESC';
    // $q = $pdo->query($sql);
    // $data = $q->fetchAll(PDO::FETCH_ASSOC);
    // echo json_encode($data);

    // $sql = 'SELECT * FROM phprs.pessoa ORDER BY id DESC';
    // $q = $pdo->query($sql);
    // $data = $q->fetchAll(PDO::FETCH_ASSOC);
    // echo json_encode($data);

    $sql = "INSERT INTO 3491651_jogobd.usuarios (nome) VALUES(?)";
    $q = $pdo->prepare($sql);
    $q->execute(array($nome));
    
    Banco::desconectar();
}

?>