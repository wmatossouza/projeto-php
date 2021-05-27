$(document).ready(function () {
    var usuarioLogado = sessionStorage.getItem('usuario');
    if (usuarioLogado != ''){
        adicionaSessao(usuarioLogado);
    }
    carregaRanking()
});

function carregaRanking() {
    $.getJSON("php/index.php?acao=carregaRanking",{'consultaPontuacao':'FALSE'},
        function (retorno) {
            var html = '';
            var contador = 1;
            $.each(retorno, function (indexInArray, valueOfElement) { 
                 html += '<tr>'+
                            '<th scope="row">'+contador+'</th>'+
                            '<td>'+valueOfElement.nome+'</td>'+
                            '<td>'+valueOfElement.descricao+'</td>'+
                            '<td>'+valueOfElement.pontuacao+'</td>'+
                        '</tr>'
                 contador ++;       
            });
            $("#tblRanking").html(html);
        }
    );
}

function cadastrar(){
    $("#loadCadastrar").show();
    var dataPost = {
        nome : $("#nome").val(),
        sobrenome : $("#sobrenome").val(),
        email : $("#email").val(),
        login : $("#login").val(),
        senha : $("#senha").val(),
        confirmarSenha : $("#confirmarSenha").val()
    }

    $.ajax({
        type: "POST",
        url: "/php/index.php?acao=cadastrar",
        data: dataPost,
        dataType: "json",
        success: function (response) {
            if (response == "senhaDiferente"){
                msgNoty('A senha de confirmação é diferente da senha informada!','warning');
            }else if(response == "campoVazio"){
                msgNoty('Existem campos obrigatórios que não foram preenchidos!','warning');
            }else if(response == "OK"){
                msgNoty('Cadastro realizado com Sucesso!','success');
                limparCampos();
            }else if(response == "jaExiste"){
                msgNoty('Atenção, este usuário de login ja existe!','error');
                $("#login").focus();
            }
            $("#loadCadastrar").hide();
        }
    });
}

function alterarSenha(){
    $("#loadAlterarSenha").show();
    var dataPost = {
        senhaAtual : $("#senhaAtual").val(),
        novaSenha : $("#novaSenha").val(),
        confirmarNovaSenha : $("#confirmarNovaSenha").val()
    }

    $.ajax({
        type: "POST",
        url: "/php/index.php?acao=alterarSenha",
        data: dataPost,
        dataType: "json",
        success: function (response) {
            if(response == "OK"){
                $("#loadAlterarSenha").hide();
                msgNoty('Senha alterada com Sucesso!','success');
                limparCampos();
            }else if(response == "senhaDiferente"){
                msgNoty('A senha de confirmação é diferente da nova senha informada!','warning');
            }
        }
    });

}

function login(){
    $("#loadLogin").show();
    if( $("#loginUser").val() == '' || $("#senhaUser").val() == ''){
        msgNoty('Forneça usuário e a senha para logar!','warning');
        $("#loadLogin").hide();
        return;
    }
    var dataPost = {
        email : $("#loginUser").val(),
        senha : $("#senhaUser").val()
    }

    $.ajax({
        type: "POST",
        url: "/php/index.php?acao=login",
        data: dataPost,
        dataType: "json",
        success: function (response) {
            if (response[0] == "ok"){
                msgNoty('Login realizado com sucesso!','success');
                sessionStorage.setItem('usuario', response[1]);
                adicionaSessao(response[1]);
            }else{
                msgNoty('Usuário e senha não encontrados!','error');
                $("#loadLogin").hide();
            }
        }
    });
}

function adicionaSessao(usuario){
    if(usuario){
        $("#liLogin").html(' <div id="divLogin"> '+
                                '<button class="btn btn-info btn-sm" id="usuarioLogado" data-toggle="collapse" data-target="#collapse" aria-expanded="false" aria-controls="collapse">' + usuario + '</button>'+
                            '</div>'+
                            ' <div class="row pull-right" style="margin-top: 5px;">'+
                                '<div class="collapse" id="collapse">'+
                                    '<div class="card card-body" style="background-color:rgb(38, 38, 100)">'+
                                    '<button class="btn btn-warning btn-sm" style="margin-top: 5px;" data-toggle="modal" data-target="#modalAlterarSenha">Alterar Senha</button>'+
                                    '<button class="btn btn-danger btn-sm" style="margin-top: 5px;" onclick="deslogar()">Deslogar</button>'+
                                    '<button class="btn btn-primary btn-sm" style="margin-top: 5px;" onclick="minhaPontuacao()">Minha Pontuação</button>'+
                                    '</div>'+
                                '</div>'+
                            '</div>');
    }                    
}

function deslogar(){
    sessionStorage.setItem('usuario', '');
    $("#liLogin").html('<div class="input-group input-group-sm mb-3">'+
                    '<input type="text" id="loginUser" class="form-control" placeholder="Email"  aria-describedby="basic-addon2">'+
                    '<input type="password" id="senhaUser" class="form-control" placeholder="Senha" aria-describedby="basic-addon2">'+
                    '<div class="input-group-append">'+
                        '<button class="btn btn-danger" onclick="login()">Login</button>'+
                    '</div>'+
                '</div>');
}

function limparCampos(){
    $("#nome").val('');
    $("#sobrenome").val('');
    $("#email").val('');
    $("#login").val('');
    $("#senha").val('');
    $("#confirmarSenha").val('');
    $("#senhaAtual").val('');
    $("#novaSenha").val('');
    $("#confirmarNovaSenha").val('');
}

function minhaPontuacao(){
    $.getJSON("php/index.php?acao=carregaRanking", {'consultaPontuacao':'OK'},
        function (retorno) {
            if(retorno == "nenhum registro"){
                msgNoty("Nenhuma pontuação registrada, comece a jogar para pontuar.", 'info',4000,'topCenter');
            }else{
                $.each(retorno, function (indexInArray, valueOfElement) { 
                    msgNoty("Sua maior pontuação é: " + valueOfElement.pontuacao + ' pontos', 'info',5000, 'topCenter')
                });
               
            }
        }
    );
}

function msgNoty(msg, tipo, tempo, layout){
    if (tempo == undefined){
        tempo = 4000;
    }
    noty({
        text: msg, 
        type: tipo, 
        timeout: tempo, 
        layout: layout
    });	
}