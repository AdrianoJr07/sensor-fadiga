<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uid = $_POST['uid'] ?? '';

    if (!empty($uid)) {
        $_SESSION['usuario'] = $uid;
        echo "ok";
    } else {
        echo "erro";
    }
    exit();
}

echo "acesso_invalido";
?>