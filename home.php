<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel Inicial</title>
  <style>
    * { box-sizing: border-box; }

    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: linear-gradient(135deg, #0f172a, #1e293b);
      color: white;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .topo {
      background: #0f172a;
      padding: 18px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .topo h1 {
      margin: 0;
      font-size: 24px;
    }

    .menu a {
      color: white;
      text-decoration: none;
      font-weight: bold;
    }

    .container {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 30px;
      padding: 40px;
      flex-wrap: wrap;
    }

    .card {
      width: 320px;
      background: #1e293b;
      border-radius: 18px;
      padding: 30px;
      text-align: center;
      box-shadow: 0 10px 30px rgba(0,0,0,0.35);
    }

    .card h2 {
      margin-top: 0;
      margin-bottom: 10px;
    }

    .card p {
      color: #cbd5e1;
      min-height: 48px;
    }

    .card a {
      display: inline-block;
      margin-top: 15px;
      padding: 12px 18px;
      background: #2563eb;
      color: white;
      text-decoration: none;
      border-radius: 10px;
      font-weight: bold;
    }

    .card a:hover {
      background: #1d4ed8;
    }
  </style>
</head>
<body>
  <div class="topo">
    <h1>Central da Empresa</h1>
    <div class="menu">
      <a href="logout.php">Sair</a>
    </div>
  </div>

  <div class="container">
    <div class="card">
      <h2>Verificar Corridas</h2>
      <p>Acompanhe em tempo real os motoristas em operação e os alertas de fadiga.</p>
      <a href="dashboard.php">Acessar Dashboard</a>
    </div>

    <div class="card">
      <h2>Verificar Motoristas</h2>
      <p>Cadastre, visualize, edite e remova os motoristas do sistema.</p>
      <a href="motoristas.php">Gerenciar Motoristas</a>
    </div>
  </div>
</body>
</html>