<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
//GET recebe/pega informaçõe
//POST envia informações
//PUT edita informações "update"
//DELETE deleta informações
//OPTIONS  é a  relação de methodos disponiveis para uso
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

include 'conexao.php';


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    //aqui eu crio o comando de select para consultar o banco
    $stmt = $conn->prepare("SELECT * FROM filmes");
    //aqui eu executo o select
    $stmt->execute();
    //aqui eu recebo os dados do banco por meio do PDO
    $filmes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //transformo os dados da variave $filmes em um JSON valido
    echo json_encode($filmes);
}

//Rota para criar filmes
//Rota para inserir filmes
//Utilizando o POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $diretor = $_POST['diretor'];
    $ano_lancamento = $_POST['ano_lancamento'];
    $genero = $_POST['genero'];
    //inserir outros campos caso necessario ....

    $stmt = $conn->prepare("INSERT INTO filmes (titulo, diretor, ano_lancamento, genero) VALUES (:titulo, :diretor, :ano_lancamento, :genero)");
    $stmt->bindParam(':titulo', $titulo);
    $stmt->bindParam(':diretor', $diretor);
    $stmt->bindParam(':ano_lancamento', $ano_lancamento);
    $stmt->bindParam(':genero', $genero);
    //Outros bindParams ....

    if ($stmt->execute()) {
        echo "Filme criado com sucesso!!";
    } else {
        echo "Erro ao criar filme";
    }
}
// Rota para excluir um filme

if($_SERVER['REQUEST_METHOD']==='DELETE' && isset($_GET['id'])){
    $id   = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM filmes WHERE id = :id");
    $stmt-> bindParam(':id', $id);

    if($stmt->execute()){
        echo "filme excluido com sucesso!!!";
    } else {
        echo "Erro ao excluir filme!";
    }
}

//Rota para atualizar um filme existente
if ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_GET['id'])) {
    parse_str(file_get_contents("php://input"), $_PUT);
    $id = $_GET['id'];
    $novoTitulo = $_PUT['titulo'];
    $novoDiretor = $_PUT['diretor'];
    $novoAno = $_PUT['ano_lancamento'];
    $novoGenero = $_PUT['genero'];
    //add novos campos caso necessario
    $stmt = $conn->prepare("UPDATE filmes SET titulo = :titulo, diretor = :diretor, ano_lancamento = :ano_lancamento, genero = :genero WHERE id = :id");
    $stmt->bindParam(':titulo', $novoTitulo);
    $stmt->bindParam(':diretor', $novoDiretor);
    $stmt->bindParam(':ano_lancamento', $novoAno);
    $stmt->bindParam(':genero', $novoGenero);
    $stmt->bindParam(':id', $id);
    //add novos campos caso necessario
    if ($stmt->execute()) {
        echo "Filme atualizado!!";
    } else {
        echo "erro ao atualizar filme :(";
    }
}

