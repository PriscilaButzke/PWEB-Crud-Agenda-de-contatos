<?php

// Verificar se foi enviando dados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (isset($_POST["id"]) && $_POST["id"] != null) ? $_POST["id"] : "";
    $nome = (isset($_POST["nome"]) && $_POST["nome"] != null) ? $_POST["nome"] : "";
    $email = (isset($_POST["email"]) && $_POST["email"] != null) ? $_POST["email"] : "";
    $celular = (isset($_POST["celular"]) && $_POST["celular"] != null) ? $_POST["celular"] : NULL;
} else if (!isset($id)) {
    // Se não se não foi setado nenhum valor para variável $id
    $id = (isset($_GET["id"]) && $_GET["id"] != null) ? $_GET["id"] : "";
    $nome = NULL;
    $email = NULL;
    $celular = NULL;
}
try {
    $conexao = new PDO("mysql:host=localhost; dbname=crudsimples", "root", "");

    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexao->exec("set names utf8");
} catch (PDOException $erro) {
    echo "Erro na conexão:" . $erro->getMessage();
}

//create
//ação “act” para salvar “save”, se sim, então entra em um novo bloco try e catch.
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "save" && $nome != "") {
    try {
        $stmt = $conexao->prepare("INSERT INTO contatos (nome, email, celular) VALUES (?, ?, ?)");
        $stmt->bindParam(1, $nome);
        $stmt->bindParam(2, $email);
        $stmt->bindParam(3, $celular);

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo "Dados cadastrados com sucesso!";
                $id = null;
                $nome = null;
                $email = null;
                $celular = null;
            } else {
                echo "Erro ao tentar efetivar cadastro";
            }
        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "Erro: " . $erro->getMessage();
    }
}
//read

?>

<html>

<head>
    <meta charset="UTF-8">
    <title>Agenda de contatos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
</head>

<body>
    <form class="row g-0" action="?act=save" method="POST" name="form1">


        <h1 class="d-flex justify-content-center">Agenda de contatos</h1>
        <hr>
        <div class="d-flex justify-content-start ms-5">


            <div  class="mb-3 row">
                <label for="inputEmail4" class="form-label">Nome</label>
                <input class="form-control" style="width:300px ;" type="text" name="id" <?php
                                                                                        // Preenche o id no campo id com um valor "value"
                                                                                        if (isset($id) && $id != null || $id != "") {
                                                                                            echo "value=\"{$id}\"";
                                                                                        }
                                                                                        ?> />
            </div>

            <div class="mb-3 row">
                <label for="inputEmail4" class="form-label">Email</label>
                <input class="form-control" style="width:300px ;" type="text" name="email" <?php
                                                                                            // Preenche o email no campo email com um valor "value"
                                                                                            if (isset($email) && $email != null || $email != "") {
                                                                                                echo "value=\"{$email}\"";
                                                                                            }
                                                                                            ?> />

            </div>
            <div class="mb-3 row">
                <label for="inputEmail4" class="form-label">Telefone</label>
                <input class="form-control" style="width:300px ;" type="text" name="celular" <?php
                                                                                                // Preenche o celular no campo celular com um valor "value"
                                                                                                if (isset($celular) && $celular != null || $celular != "") {
                                                                                                    echo "value=\"{$celular}\"";
                                                                                                }
                                                                                                ?> />


            </div>
            <div class="mt-4">
            <input type="submit" value="salvar" class="btn btn-primary" />
            <input type="reset" value="Novo" class="btn btn-primary" />
        </div>
        </div>
        
        <hr>
    </form>

    <table class="table" width="100%  ">
        <tr class="table-primary">
            <th>Nome</th>
            <th>E-mail</th>
            <th>Celular</th>
            <th>Ações</th>
        </tr>
    </table>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>

</html>