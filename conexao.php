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
//conecta ao banco de dados
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
        //Update
        // se a variável $id estiver vazia, será feito um Insert
        // se for diferente de vazio, sera feito um update
        if ($id != "") {
            $stmt = $conexao->prepare("UPDATE contatos SET nome=?, email=?, celular=? WHERE id = ?");
            $stmt->bindParam(4, $id);
        } else {
            $stmt = $conexao->prepare("INSERT INTO contatos (nome, email, celular) VALUES (?, ?, ?)");
        }

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

//recupera os dados
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "upd" && $id != "") {
    try {
        $stmt = $conexao->prepare("SELECT * FROM contatos WHERE id = ?");
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $rs = $stmt->fetch(PDO::FETCH_OBJ);
            $id = $rs->id;
            $nome = $rs->nome;
            $email = $rs->email;
            $celular = $rs->celular;
        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "Erro: " . $erro->getMessage();
    }
}

//delete
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "del" && $id != "") {

    try {
        $stmt = $conexao->prepare("DELETE FROM contatos WHERE id = ?");
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
    
        if ($stmt->execute()) {
            echo "Registro foi excluído com êxito";
            $id = null;
        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "Erro: ".$erro->getMessage();
    }
}

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

            <div>
                <input class="form-control" type="hidden" name="id" <?php
                                                                    // Preenche o id no campo id com um valor "value"
                                                                    if (isset($id) && $id != null || $id != "") {
                                                                        echo "value=\"{$id}\"";
                                                                    }
                                                                    ?> />

            </div>

            <div class="mb-3 row">
                <label for="inputEmail4" class="form-label">Nome</label>
                <input class="form-control" style="width:300px ;" type="text" name="nome" <?php
                                                                                            // Preenche o nome no campo nome com um valor "value"
                                                                                            if (isset($nome) && $nome != null || $nome != "") {
                                                                                                echo "value=\"{$nome}\"";
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

    <table class="table table-striped table-hover" width="70%  ">
        <tr class="table-dark">
            <th>Nome</th>
            <th>E-mail</th>
            <th>Celular</th>
            <th></th>
        </tr>


        <?php
        // Bloco que realiza o papel do Read - recupera os dados e apresenta na tela
        try {
            $stmt = $conexao->prepare("SELECT * FROM contatos");

            if ($stmt->execute()) {
                while ($rs = $stmt->fetch(PDO::FETCH_OBJ)) {
                    echo "<tr>";
                    echo "<td>" . $rs->nome . "</td>
                        <td>" . $rs->email . "</td>
                        <td>" . $rs->celular . "</td>
                        <td><center>
                        <a class='btn btn-primary' href=\"?act=upd&id=" . $rs->id . "\">Alterar</a>"
                        . "&nbsp;"
                        . "<a class='btn btn-primary' href=\"?act=del&id=" . $rs->id . "\">Excluir</a>
                        </center></td>";
                    echo "</tr>";
                }
            } else {
                echo "Erro: Não foi possível recuperar os dados do banco de dados";
            }
        } catch (PDOException $erro) {
            echo "Erro: " . $erro->getMessage();
        }
        ?>



    </table>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>

</html>