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
  <title>Central de Monitoramento</title>
  <style>
    * { box-sizing: border-box; }

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

    .layout {
      display: flex;
      min-height: calc(100vh - 72px);
    }

    .sidebar {
      width: 320px;
      background: #0f172a;
      color: white;
      padding: 20px;
      overflow-y: auto;
    }

    .sidebar h2 {
      margin-top: 0;
      font-size: 20px;
    }

    .motorista-item {
      background: #1e293b;
      border-radius: 12px;
      padding: 14px;
      margin-bottom: 12px;
      cursor: pointer;
      border: 2px solid transparent;
      transition: 0.2s;
    }

    .motorista-item:hover {
      background: #334155;
    }

    .motorista-item.ativo {
      border-color: #60a5fa;
    }

    .motorista-item strong {
      display: block;
      margin-bottom: 6px;
    }

    .motorista-status {
      font-size: 13px;
      margin-top: 6px;
      font-weight: bold;
    }

    .status-normal {
      color: #16a34a;
    }

    .status-atencao {
      color: #ca8a04;
    }

    .status-fadiga {
      color: #dc2626;
    }

    .status-sem {
      color: #cbd5e1;
    }

    .conteudo {
      flex: 1;
      padding: 25px;
    }

    .titulo-painel {
      margin-top: 0;
      margin-bottom: 10px;
    }

    .subinfo {
      color: #475569;
      margin-bottom: 20px;
      font-size: 14px;
    }

    .cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
      margin-bottom: 24px;
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

    .vazio {
      color: #475569;
      font-size: 18px;
      padding: 20px;
      background: white;
      border-radius: 16px;
      box-shadow: 0 4px 14px rgba(0,0,0,0.08);
    }

    .tabela-box {
      background: white;
      border-radius: 16px;
      padding: 20px;
      box-shadow: 0 4px 14px rgba(0,0,0,0.08);
    }

    .tabela-box h3 {
      margin-top: 0;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th, td {
      padding: 12px;
      border-bottom: 1px solid #e2e8f0;
      text-align: left;
      font-size: 14px;
    }

    th {
      color: #334155;
      background: #f8fafc;
    }

    .badge {
      display: inline-block;
      padding: 4px 10px;
      border-radius: 999px;
      font-size: 12px;
      font-weight: bold;
      color: white;
    }

    .badge-normal { background: #16a34a; }
    .badge-atencao { background: #ca8a04; }
    .badge-fadiga { background: #dc2626; }
    .badge-sem { background: #64748b; }
  </style>
</head>
<body>
  <div class="topo">
    <h1>Central de Monitoramento</h1>
    <div class="menu">
      <a href="logout.php">Sair</a>
    </div>
  </div>

  <div class="layout">
    <aside class="sidebar">
      <h2>Motoristas em corrida</h2>
      <div id="listaMotoristas"></div>
    </aside>

    <main class="conteudo">
      <h2 class="titulo-painel">Monitoramento em tempo real</h2>
      <div class="subinfo" id="ultimaAtualizacaoPainel">Aguardando dados...</div>

      <div id="painelVazio" class="vazio">
        Selecione um motorista à esquerda para visualizar os dados em tempo real.
      </div>

      <div id="painelDetalhes" style="display:none;">
        <div class="cards">
          <div class="card">
            <h3>Motorista</h3>
            <p id="nomeMotorista">--</p>
          </div>

          <div class="card">
            <h3>Placa</h3>
            <p id="placaMotorista">--</p>
          </div>

          <div class="card">
            <h3>Empresa</h3>
            <p id="empresaMotorista">--</p>
          </div>

          <div class="card">
            <h3>Status atual</h3>
            <p id="statusAtual">--</p>
          </div>

          <div class="card">
            <h3>Nível de risco</h3>
            <p id="nivelRisco">--</p>
          </div>

          <div class="card">
            <h3>Nível de alerta</h3>
            <p id="nivelAlerta">--</p>
          </div>

          <div class="card">
            <h3>Episódios recentes</h3>
            <p id="episodiosRecentes">--</p>
          </div>

          <div class="card">
            <h3>EAR</h3>
            <p id="ear">--</p>
          </div>

          <div class="card">
            <h3>PERCLOS</h3>
            <p id="perclos">--</p>
          </div>

          <div class="card">
            <h3>Piscadas por minuto</h3>
            <p id="bpm">--</p>
          </div>

          <div class="card">
            <h3>Duração média da piscada</h3>
            <p id="duracaoMedia">--</p>
          </div>
        </div>

        <div class="tabela-box">
          <h3>Eventos recentes</h3>
          <table>
            <thead>
              <tr>
                <th>Tipo</th>
                <th>Status</th>
                <th>Nível de alerta</th>
                <th>Horário</th>
              </tr>
            </thead>
            <tbody id="tabelaEventos">
              <tr><td colspan="4">Nenhum evento encontrado.</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>

  <script type="module">
    import { firebaseConfig } from './config.js';
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js";
    import {
      getFirestore,
      collection,
      query,
      where,
      onSnapshot,
      doc,
      orderBy,
      limit
    } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-firestore.js";

    const app = initializeApp(firebaseConfig);
    const db = getFirestore(app);

    const listaMotoristas = document.getElementById("listaMotoristas");
    const painelVazio = document.getElementById("painelVazio");
    const painelDetalhes = document.getElementById("painelDetalhes");
    const tabelaEventos = document.getElementById("tabelaEventos");
    const ultimaAtualizacaoPainel = document.getElementById("ultimaAtualizacaoPainel");

    let unsubscribeMotoristaSelecionado = null;
    let unsubscribeEventos = null;
    let motoristaSelecionadoId = null;

    function textoStatus(status) {
      if (status === "ATENCAO") return "Atenção";
      if (status === "FADIGA") return "Fadiga";
      if (status === "NORMAL") return "Normal";
      if (status === "SEM ROSTO") return "Sem rosto";
      return status || "--";
    }

    function classeStatus(status) {
      if (status === "NORMAL") return "status-normal";
      if (status === "ATENCAO") return "status-atencao";
      if (status === "FADIGA") return "status-fadiga";
      return "status-sem";
    }

    function classeBadge(status) {
      if (status === "NORMAL") return "badge badge-normal";
      if (status === "ATENCAO") return "badge badge-atencao";
      if (status === "FADIGA") return "badge badge-fadiga";
      return "badge badge-sem";
    }

    function definirNivelRisco(nivelAlerta, status) {
      if (status === "FADIGA" || nivelAlerta >= 2) return "Alto";
      if (status === "ATENCAO" || nivelAlerta === 1) return "Atenção";
      return "Normal";
    }

    function formatarHorario(timestamp) {
      if (!timestamp) return "--";
      if (timestamp.toDate) {
        return timestamp.toDate().toLocaleString("pt-BR");
      }
      return "--";
    }

    function renderizarPainel(data) {
      painelVazio.style.display = "none";
      painelDetalhes.style.display = "block";

      document.getElementById("nomeMotorista").innerText = data.nome ?? "--";
      document.getElementById("placaMotorista").innerText = data.placa ?? "--";
      document.getElementById("empresaMotorista").innerText = data.empresa ?? "--";

      const statusAtual = document.getElementById("statusAtual");
      statusAtual.innerText = textoStatus(data.status);
      statusAtual.className = classeStatus(data.status);

      const nivelRisco = definirNivelRisco(data.nivelAlerta ?? 0, data.status ?? "");
      const riscoEl = document.getElementById("nivelRisco");
      riscoEl.innerText = nivelRisco;
      riscoEl.className = "";
      if (nivelRisco === "Normal") riscoEl.classList.add("status-normal");
      if (nivelRisco === "Atenção") riscoEl.classList.add("status-atencao");
      if (nivelRisco === "Alto") riscoEl.classList.add("status-fadiga");

      document.getElementById("nivelAlerta").innerText = data.nivelAlerta ?? "--";
      document.getElementById("episodiosRecentes").innerText = data.episodiosRecentes ?? "--";
      document.getElementById("ear").innerText = data.ear ?? "--";
      document.getElementById("perclos").innerText = data.perclos ?? "--";
      document.getElementById("bpm").innerText = data.bpm ?? "--";
      document.getElementById("duracaoMedia").innerText = data.duracaoMediaPiscada ?? "--";

      ultimaAtualizacaoPainel.innerText = `Última atualização: ${formatarHorario(data.atualizadoEm)}`;
    }

    function carregarEventos(motoristaId) {
      if (unsubscribeEventos) unsubscribeEventos();

      const qEventos = query(
        collection(db, "eventos"),
        where("motoristaId", "==", motoristaId),
        orderBy("criadoEm", "desc"),
        limit(10)
      );

      unsubscribeEventos = onSnapshot(qEventos, (snapshot) => {
        tabelaEventos.innerHTML = "";

        if (snapshot.empty) {
          tabelaEventos.innerHTML = "<tr><td colspan='4'>Nenhum evento encontrado.</td></tr>";
          return;
        }

        snapshot.forEach((docSnap) => {
          const data = docSnap.data();

          const tr = document.createElement("tr");
          tr.innerHTML = `
            <td>${data.tipo ?? "--"}</td>
            <td><span class="${classeBadge(data.status)}">${textoStatus(data.status)}</span></td>
            <td>${data.nivelAlerta ?? "--"}</td>
            <td>${formatarHorario(data.criadoEm)}</td>
          `;
          tabelaEventos.appendChild(tr);
        });
      });
    }

    function selecionarMotorista(motoristaId) {
      motoristaSelecionadoId = motoristaId;

      document.querySelectorAll(".motorista-item").forEach(item => {
        item.classList.remove("ativo");
      });

      const card = document.getElementById("motorista-" + motoristaId);
      if (card) card.classList.add("ativo");

      if (unsubscribeMotoristaSelecionado) {
        unsubscribeMotoristaSelecionado();
      }

      const ref = doc(db, "monitoramento_atual", motoristaId);

      unsubscribeMotoristaSelecionado = onSnapshot(ref, (snapshot) => {
        if (!snapshot.exists()) return;
        renderizarPainel(snapshot.data());
      });

      carregarEventos(motoristaId);
    }

    const q = query(
      collection(db, "monitoramento_atual"),
      where("emCorrida", "==", true)
    );

    onSnapshot(q, (snapshot) => {
      listaMotoristas.innerHTML = "";

      if (snapshot.empty) {
        listaMotoristas.innerHTML = "<p>Nenhum motorista ativo no momento.</p>";
        return;
      }

      snapshot.forEach((docSnap) => {
        const data = docSnap.data();
        const motoristaId = docSnap.id;

        const div = document.createElement("div");
        div.className = "motorista-item";
        div.id = "motorista-" + motoristaId;

        div.innerHTML = `
          <strong>${data.nome ?? motoristaId}</strong>
          <div>Placa: ${data.placa ?? "--"}</div>
          <div class="motorista-status ${classeStatus(data.status)}">
            Status: ${textoStatus(data.status)}
          </div>
        `;

        div.addEventListener("click", () => selecionarMotorista(motoristaId));

        listaMotoristas.appendChild(div);
      });

      if (!motoristaSelecionadoId && !snapshot.empty) {
        selecionarMotorista(snapshot.docs[0].id);
      }
    });
  </script>
</body>
</html>