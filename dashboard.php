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
  background: linear-gradient(135deg, #020617, #0f172a, #1e3a8a);
  color: white;
  padding: 18px 30px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 4px 18px rgba(0,0,0,0.22);
}

.topo h1 {
  margin: 0;
  font-size: 24px;
}

.menu a {
  color: #e2e8f0;
  text-decoration: none;
  margin-left: 18px;
  font-weight: bold;
}

.menu a:hover {
  color: #38bdf8;
}

.container,
.conteudo {
  padding: 28px;
}

.box,
.card,
.tabela-box {
  background: white;
  border-radius: 18px;
  padding: 22px;
  box-shadow: 0 6px 22px rgba(15, 23, 42, 0.10);
}

.card {
  border-left: 5px solid #38bdf8;
  transition: 0.2s;
}

.card:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 28px rgba(15, 23, 42, 0.16);
}

.card h3 {
  margin: 0 0 10px 0;
  color: #64748b;
  font-size: 15px;
}

.card p {
  margin: 0;
  font-size: 24px;
  font-weight: bold;
}

button {
  background: linear-gradient(135deg, #2563eb, #38bdf8);
  color: white;
  border: none;
  font-weight: bold;
  cursor: pointer;
}

button:hover {
  filter: brightness(1.08);
}

input,
select,
button {
  padding: 12px;
  border-radius: 12px;
  border: 1px solid #cbd5e1;
  font-size: 15px;
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 18px;
}

th,
td {
  padding: 13px;
  border-bottom: 1px solid #e2e8f0;
  text-align: left;
}

th {
  background: #f8fafc;
  color: #334155;
}

tr:hover td {
  background: #f8fafc;
}

.badge {
  display: inline-block;
  padding: 5px 11px;
  border-radius: 999px;
  font-size: 12px;
  font-weight: bold;
  color: white;
}

.badge-ativo,
.badge-normal {
  background: #16a34a;
}

.badge-inativo,
.badge-offline {
  background: #64748b;
}

.badge-atencao {
  background: #ca8a04;
}

.badge-fadiga {
  background: #dc2626;
}

.status-normal {
  color: #16a34a !important;
}

.status-atencao {
  color: #ca8a04 !important;
}

.status-fadiga {
  color: #dc2626 !important;
}

.status-offline {
  color: #64748b !important;
}
.layout {
  display: flex;
  min-height: calc(100vh - 72px);
}

.sidebar {
  width: 330px;
  background: #0f172a;
  color: white;
  padding: 22px;
  overflow-y: auto;
}

.sidebar h2 {
  margin-top: 0;
  font-size: 21px;
}

.sidebar h3 {
  color: #cbd5e1;
  border-bottom: 1px solid #334155;
  padding-bottom: 7px;
  margin-top: 26px;
  font-size: 16px;
}

.motorista-item {
  background: #1e293b;
  border-radius: 14px;
  padding: 15px;
  margin-bottom: 12px;
  cursor: pointer;
  border: 2px solid transparent;
  transition: 0.2s;
  box-shadow: 0 4px 14px rgba(0,0,0,0.18);
}

.motorista-item:hover {
  background: #334155;
  transform: translateY(-2px);
}

.motorista-item.ativo {
  border-color: #60a5fa;
  background: #1d4ed8;
}

.motorista-item strong {
  display: block;
  margin-bottom: 6px;
  font-size: 16px;
}

.motorista-status {
  font-size: 13px;
  margin-top: 7px;
  font-weight: bold;
}

.conteudo {
  flex: 1;
  padding: 28px;
}

.titulo-painel {
  margin: 0 0 6px 0;
  font-size: 28px;
}

.subinfo {
  color: #475569;
  margin-bottom: 22px;
  font-size: 14px;
}

.cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 20px;
  margin-bottom: 24px;
}

.status-card {
  border-left-color: #2563eb;
}

.risco-card {
  border-left-color: #f59e0b;
}

.alerta-card {
  border-left-color: #dc2626;
}

.metric-card {
  border-left-color: #38bdf8;
}

.status-box {
  display: inline-block;
  padding: 8px 14px;
  border-radius: 999px;
  font-size: 18px;
  font-weight: bold;
}

.status-box.status-normal {
  background: #dcfce7;
  color: #166534 !important;
}

.status-box.status-atencao {
  background: #fef9c3;
  color: #92400e !important;
}

.status-box.status-fadiga {
  background: #fee2e2;
  color: #991b1b !important;
}

.status-box.status-offline {
  background: #e2e8f0;
  color: #334155 !important;
}

.vazio {
  color: #475569;
  font-size: 18px;
  padding: 22px;
  background: white;
  border-radius: 18px;
  box-shadow: 0 6px 22px rgba(15, 23, 42, 0.10);
}

.tabela-box h3 {
  margin-top: 0;
  font-size: 20px;
}

.badge-sem {
  background: #94a3b8;
}

.status-sem {
  color: #94a3b8 !important;
}

@media (max-width: 900px) {
  .layout {
    flex-direction: column;
  }

  .sidebar {
    width: 100%;
  }
}
  </style>
</head>
<body>
  <div class="topo">
    <h1>🌙 SonoSafe | Central de Monitoramento</h1>
    <div class="menu">
      <a href="home.php">Início</a>
      <a href="motoristas.php">Motoristas</a>
      <a href="logout.php">Sair</a>
    </div>
  </div>

  <div class="layout">
    <aside class="sidebar">
      <h2>Central de motoristas</h2>

      <h3>Em corrida</h3>
      <div id="listaMotoristasAtivos"></div>

      <h3>Offline</h3>
      <div id="listaMotoristasOffline"></div>
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

          <div class="card status-card">
            <h3>Status atual</h3>
            <p><span id="statusAtual" class="status-box">--</span></p>
          </div>

          <div class="card risco-card">
            <h3>Nível de risco</h3>
            <p id="nivelRisco">--</p>
          </div>

          <div class="card alerta-card">
            <h3>Nível de alerta</h3>
            <p id="nivelAlerta">--</p>
          </div>

          <div class="card alerta-card">
            <h3>Episódios recentes</h3>
            <p id="episodiosRecentes">--</p>
          </div>

          <div class="card metric-card">
            <h3>Abertura dos olhos</h3>
            <p id="ear">--</p>
          </div>

          <div class="card metric-card">
            <h3>Tempo com olhos fechados</h3>
            <p id="perclos">--</p>
          </div>

          <div class="card metric-card">
            <h3>Piscadas por minuto</h3>
            <p id="bpm">--</p>
          </div>

          <div class="card metric-card">
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
      onSnapshot,
      doc,
      where,
      orderBy,
      limit
    } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-firestore.js";

    const app = initializeApp(firebaseConfig);
    const db = getFirestore(app);

    const listaMotoristasAtivos = document.getElementById("listaMotoristasAtivos");
    const listaMotoristasOffline = document.getElementById("listaMotoristasOffline");
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
      if (status === "OFFLINE") return "Offline";
      return status || "--";
    }

    function textoEvento(tipo) {
      if (tipo === "EPISODIO_FADIGA_CONFIRMADO") return "Fadiga confirmada";
      if (tipo === "MUDANCA_STATUS") return "Mudança de status";
      if (tipo === "MUDANCA_NIVEL_ALERTA") return "Mudança de alerta";
      return tipo || "--";
    }

    function classeStatus(status) {
      if (status === "NORMAL") return "status-normal";
      if (status === "ATENCAO") return "status-atencao";
      if (status === "FADIGA") return "status-fadiga";
      if (status === "OFFLINE") return "status-offline";
      return "status-sem";
    }

    function classeBadge(status) {
      if (status === "NORMAL") return "badge badge-normal";
      if (status === "ATENCAO") return "badge badge-atencao";
      if (status === "FADIGA") return "badge badge-fadiga";
      if (status === "OFFLINE") return "badge badge-offline";
      return "badge badge-sem";
    }

    function definirNivelRisco(nivelAlerta, status) {
      if (status === "OFFLINE") return "Offline";
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
      statusAtual.className = `status-box ${classeStatus(data.status)}`;

      const nivelRisco = definirNivelRisco(data.nivelAlerta ?? 0, data.status ?? "");
      const riscoEl = document.getElementById("nivelRisco");
      riscoEl.innerText = nivelRisco;
      riscoEl.className = "";

      if (nivelRisco === "Normal") riscoEl.classList.add("status-normal");
      if (nivelRisco === "Atenção") riscoEl.classList.add("status-atencao");
      if (nivelRisco === "Alto") riscoEl.classList.add("status-fadiga");
      if (nivelRisco === "Offline") riscoEl.classList.add("status-offline");

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
            <td>${textoEvento(data.tipo)}</td>
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
        if (!snapshot.exists()) {
          renderizarPainel({
            nome: "--",
            placa: "--",
            empresa: "--",
            status: "OFFLINE",
            nivelAlerta: 0,
            episodiosRecentes: 0,
            ear: 0,
            perclos: 0,
            bpm: 0,
            duracaoMediaPiscada: 0,
            atualizadoEm: null
          });
          return;
        }

        renderizarPainel(snapshot.data());
      });

      carregarEventos(motoristaId);
    }

    const q = query(collection(db, "motoristas"));

    onSnapshot(q, (snapshot) => {
      listaMotoristasAtivos.innerHTML = "";
      listaMotoristasOffline.innerHTML = "";

      if (snapshot.empty) {
        listaMotoristasAtivos.innerHTML = "<p>Nenhum motorista cadastrado.</p>";
        listaMotoristasOffline.innerHTML = "<p>Nenhum motorista cadastrado.</p>";
        return;
      }

      let primeiroMotoristaDisponivel = null;
      let qtdAtivos = 0;
      let qtdOffline = 0;

      const docsOrdenados = [];
      snapshot.forEach((docSnap) => docsOrdenados.push({ id: docSnap.id, ...docSnap.data() }));
      docsOrdenados.sort((a, b) => (a.idMotorista || 0) - (b.idMotorista || 0));

      docsOrdenados.forEach((data) => {
        const motoristaId = data.motoristaId || data.id;

        if (!primeiroMotoristaDisponivel) {
          primeiroMotoristaDisponivel = motoristaId;
        }

        const div = document.createElement("div");
        div.className = "motorista-item";
        div.id = "motorista-" + motoristaId;

        const statusExibido = data.emCorrida ? "NORMAL" : "OFFLINE";

        div.innerHTML = `
          <strong>${data.nome ?? motoristaId}</strong>
          <div>Placa: ${data.placa ?? "--"}</div>
          <div class="motorista-status ${classeStatus(statusExibido)}">
            Situação: ${data.emCorrida ? "Em corrida" : "Offline"}
          </div>
        `;

        div.addEventListener("click", () => selecionarMotorista(motoristaId));

        if (data.emCorrida === true) {
          listaMotoristasAtivos.appendChild(div);
          qtdAtivos++;
        } else {
          listaMotoristasOffline.appendChild(div);
          qtdOffline++;
        }
      });

      if (qtdAtivos === 0) {
        listaMotoristasAtivos.innerHTML = "<p>Nenhum motorista em corrida.</p>";
      }

      if (qtdOffline === 0) {
        listaMotoristasOffline.innerHTML = "<p>Nenhum motorista offline.</p>";
      }

      if (!motoristaSelecionadoId && primeiroMotoristaDisponivel) {
        selecionarMotorista(primeiroMotoristaDisponivel);
      }
    });
  </script>
</body>
</html>