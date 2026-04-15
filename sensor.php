<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

$usuarioId = $_SESSION['usuario'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastrar Sensor</title>
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #f1f5f9;
      color: #0f172a;
    }

    .topo {
      background: #0f172a;
      color: white;
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
      margin-left: 16px;
      font-weight: bold;
    }

    .container {
      max-width: 600px;
      margin: 40px auto;
      background: white;
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 4px 14px rgba(0,0,0,0.08);
    }

    input, button {
      width: 100%;
      padding: 12px;
      margin-top: 12px;
      border-radius: 10px;
      border: 1px solid #cbd5e1;
      font-size: 15px;
    }

    button {
      background: #2563eb;
      color: white;
      border: none;
      font-weight: bold;
      cursor: pointer;
    }

    button:hover {
      background: #1d4ed8;
    }

    #mensagem {
      margin-top: 15px;
      font-weight: bold;
      color: #15803d;
    }
  </style>
</head>
<body>
  <div class="topo">
    <h1>Cadastro do Sensor</h1>
    <div class="menu">
      <a href="dashboard.php">Dashboard</a>
      <a href="logout.php">Sair</a>
    </div>
  </div>

  <div class="container">
    <h2>Vincular sensor ao usuário</h2>

    <input type="text" id="nomeSensor" placeholder="Nome do sensor">
    <input type="text" id="sensorId" placeholder="ID do sensor">
    <input type="text" id="veiculo" placeholder="Veículo">
    <input type="text" id="placa" placeholder="Placa do veículo">

    <button onclick="salvarSensor()">Salvar Sensor</button>

    <p id="mensagem"></p>
  </div>

  <script type="module">
    import { firebaseConfig } from './config.js';
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js";
    import { getFirestore, addDoc, collection } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-firestore.js";

    const app = initializeApp(firebaseConfig);
    const db = getFirestore(app);

    const usuarioId = "<?php echo $usuarioId; ?>";

    window.salvarSensor = async function () {
      const nomeSensor = document.getElementById("nomeSensor").value.trim();
      const sensorId = document.getElementById("sensorId").value.trim();
      const veiculo = document.getElementById("veiculo").value.trim();
      const placa = document.getElementById("placa").value.trim();
      const msg = document.getElementById("mensagem");

      msg.innerText = "";

      if (!nomeSensor || !sensorId || !veiculo || !placa) {
        msg.innerText = "Preencha todos os campos.";
        return;
      }

      try {
        await addDoc(collection(db, "sensores"), {
          usuarioId: usuarioId,
          nomeSensor: nomeSensor,
          sensorId: sensorId,
          veiculo: veiculo,
          placa: placa,
          status: "ativo",
          criadoEm: new Date().toISOString()
        });

        msg.innerText = "Sensor cadastrado com sucesso.";

        document.getElementById("nomeSensor").value = "";
        document.getElementById("sensorId").value = "";
        document.getElementById("veiculo").value = "";
        document.getElementById("placa").value = "";

      } catch (error) {
        msg.innerText = "Erro ao cadastrar sensor.";
        console.error(error);
      }
    }
  </script>
</body>
</html>