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
  <title>Dashboard - Sensor de Fadiga</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #e2e8f0;
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
      padding: 25px;
    }

    .cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
      margin-bottom: 25px;
    }

    .card {
      background: white;
      border-radius: 16px;
      padding: 20px;
      box-shadow: 0 4px 14px rgba(0,0,0,0.08);
    }

    .card h3 {
      margin-top: 0;
      font-size: 18px;
      color: #334155;
    }

    .card p {
      font-size: 22px;
      font-weight: bold;
      margin-bottom: 0;
    }

    .grafico {
      background: white;
      border-radius: 16px;
      padding: 20px;
      box-shadow: 0 4px 14px rgba(0,0,0,0.08);
    }

    .grafico h3 {
      margin-top: 0;
    }

    .status-normal {
      color: #16a34a;
    }

    .status-atencao {
      color: #ca8a04;
    }

    .status-alto {
      color: #dc2626;
    }
  </style>
</head>
<body>
  <div class="topo">
    <h1>Dashboard do Sensor de Fadiga</h1>
    <div class="menu">
      <a href="sensor.php">Cadastrar Sensor</a>
      <a href="logout.php">Sair</a>
    </div>
  </div>

  <div class="container">
    <div class="cards">
      <div class="card">
        <h3>Nível atual de fadiga</h3>
        <p id="fadigaAtual">--</p>
      </div>

      <div class="card">
        <h3>Status do sensor</h3>
        <p id="statusSensor">Conectado</p>
      </div>

      <div class="card">
        <h3>Último alerta</h3>
        <p id="ultimoAlerta">Nenhum</p>
      </div>

      <div class="card">
        <h3>Nível de risco</h3>
        <p id="nivelRisco">--</p>
      </div>
    </div>

    <div class="grafico">
      <h3>Picos de sono durante o trajeto</h3>
      <canvas id="graficoFadiga"></canvas>
    </div>
  </div>

  <script type="module">
    import { firebaseConfig } from './config.js';
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js";
    import { getFirestore, collection, query, where, getDocs, orderBy } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-firestore.js";

    const app = initializeApp(firebaseConfig);
    const db = getFirestore(app);

    const usuarioId = "<?php echo $usuarioId; ?>";
    let graficoInstancia = null;

    async function carregarDados() {
      try {
        const q = query(
          collection(db, "leituras"),
          where("usuarioId", "==", usuarioId),
          orderBy("timestamp", "asc")
        );

        const querySnapshot = await getDocs(q);

        let labels = [];
        let dados = [];
        let fadigaAtual = "--";
        let ultimoAlerta = "Nenhum";
        let nivelRisco = "--";
        let ultimaLeitura = null;

        querySnapshot.forEach((doc) => {
          const leitura = doc.data();

          labels.push(leitura.timestamp || "");
          dados.push(Number(leitura.nivelFadiga) || 0);

          ultimaLeitura = leitura;
        });

        if (ultimaLeitura) {
          fadigaAtual = ultimaLeitura.nivelFadiga ?? "--";

          const valor = Number(ultimaLeitura.nivelFadiga) || 0;

          if (valor < 40) {
            nivelRisco = "Normal";
          } else if (valor < 70) {
            nivelRisco = "Atenção";
          } else {
            nivelRisco = "Alto";
            ultimoAlerta = "Possível pico de sono detectado";
          }
        }

        document.getElementById("fadigaAtual").innerText = fadigaAtual;
        document.getElementById("ultimoAlerta").innerText = ultimoAlerta;

        const riscoEl = document.getElementById("nivelRisco");
        riscoEl.innerText = nivelRisco;
        riscoEl.className = "";

        if (nivelRisco === "Normal") riscoEl.classList.add("status-normal");
        if (nivelRisco === "Atenção") riscoEl.classList.add("status-atencao");
        if (nivelRisco === "Alto") riscoEl.classList.add("status-alto");

        const ctx = document.getElementById("graficoFadiga").getContext("2d");

        if (graficoInstancia) {
          graficoInstancia.destroy();
        }

        graficoInstancia = new Chart(ctx, {
          type: "line",
          data: {
            labels: labels,
            datasets: [{
              label: "Nível de Fadiga",
              data: dados,
              borderWidth: 2,
              tension: 0.3,
              fill: false
            }]
          },
          options: {
            responsive: true,
            scales: {
              y: {
                beginAtZero: true,
                max: 100
              }
            }
          }
        });

      } catch (error) {
        console.error("Erro ao carregar dados:", error);
      }
    }

    carregarDados();
  </script>
</body>
</html>