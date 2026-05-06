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
    * {
  box-sizing: border-box;
}

body {
  margin: 0;
  font-family: Arial, sans-serif;
  background:
    radial-gradient(circle at top left, rgba(56, 189, 248, 0.16), transparent 32%),
    linear-gradient(135deg, #020617, #0f172a 60%, #1e3a8a);
  color: white;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

.topo {
  background: rgba(2, 6, 23, 0.75);
  backdrop-filter: blur(10px);
  padding: 18px 30px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid rgba(148, 163, 184, 0.18);
}

.topo h1 {
  margin: 0;
  font-size: 24px;
}

.menu a {
  color: #e2e8f0;
  text-decoration: none;
  font-weight: bold;
}

.menu a:hover {
  color: #38bdf8;
}

.hero {
  text-align: center;
  padding: 48px 20px 12px;
}

.hero .icon {
  font-size: 46px;
}

.hero h2 {
  font-size: 34px;
  margin: 14px 0 8px;
}

.hero p {
  color: #cbd5e1;
  margin: 0;
}

.container {
  flex: 1;
  display: flex;
  justify-content: center;
  align-items: flex-start;
  gap: 30px;
  padding: 40px;
  flex-wrap: wrap;
}

.card {
  width: 340px;
  background: rgba(30, 41, 59, 0.92);
  border: 1px solid rgba(148, 163, 184, 0.22);
  border-radius: 22px;
  padding: 32px;
  text-align: center;
  box-shadow: 0 20px 50px rgba(0,0,0,0.32);
  transition: 0.2s;
}

.card:hover {
  transform: translateY(-5px);
  border-color: rgba(56, 189, 248, 0.5);
}

.card .card-icon {
  font-size: 40px;
  margin-bottom: 12px;
}

.card h2 {
  margin-top: 0;
  margin-bottom: 10px;
}

.card p {
  color: #cbd5e1;
  min-height: 55px;
}

.card a {
  display: inline-block;
  margin-top: 16px;
  padding: 13px 18px;
  background: linear-gradient(135deg, #2563eb, #38bdf8);
  color: white;
  text-decoration: none;
  border-radius: 12px;
  font-weight: bold;
}

.card a:hover {
  filter: brightness(1.08);
}
  </style>
</head>
<body>
  <div class="topo">
  <h1>SonoSafe</h1>
  <div class="menu">
    <a href="logout.php">Sair</a>
  </div>
</div>

<div class="hero">
  <div class="icon">🌙</div>
  <h2>Central de Monitoramento</h2>
  <p>Controle de fadiga e acompanhamento de motoristas em tempo real</p>
</div>

<div class="container">
  <div class="card">
    <div class="card-icon">🚚</div>
    <h2>Verificar Corridas</h2>
    <p>Acompanhe motoristas em operação, níveis de alerta e eventos de fadiga.</p>
    <a href="dashboard.php">Acessar Dashboard</a>
  </div>

  <div class="card">
    <div class="card-icon">👤</div>
    <h2>Verificar Motoristas</h2>
    <p>Cadastre, visualize, edite e remova motoristas vinculados à empresa.</p>
    <a href="motoristas.php">Gerenciar Motoristas</a>
  </div>
</div>
</body>
</html>