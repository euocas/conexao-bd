<?php

require '../conexao.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id === false || $id === null) {
    header('Location: listar.php');
    exit;
}

try {
    $sql = 'DELETE FROM usuarios WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
} catch (PDOException $e) {
    // Ex.: usuário vinculado a aluguéis (FK)
}

header('Location: listar.php');
exit;
