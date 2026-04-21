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
  <title>Motoristas</title>
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

    .container {
      max-width: 1100px;
      margin: 30px auto;
      padding: 0 20px;
    }

    .box {
      background: white;
      border-radius: 16px;
      padding: 24px;
      box-shadow: 0 4px 14px rgba(0,0,0,0.08);
      margin-bottom: 24px;
    }

    h2 {
      margin-top: 0;
    }

    .acoes-topo {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 18px;
      gap: 12px;
      flex-wrap: wrap;
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 12px;
    }

    input, select, button {
      width: 100%;
      padding: 12px;
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

    .btn-secundario {
      background: #475569;
    }

    .btn-secundario:hover {
      background: #334155;
    }

    .btn-editar {
      background: #ca8a04;
    }

    .btn-editar:hover {
      background: #a16207;
    }

    .btn-excluir {
      background: #dc2626;
    }

    .btn-excluir:hover {
      background: #b91c1c;
    }

    .msg {
      margin-top: 12px;
      font-weight: bold;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 18px;
    }

    th, td {
      padding: 12px;
      border-bottom: 1px solid #e2e8f0;
      text-align: left;
    }

    th {
      background: #f8fafc;
    }

    .acoes {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
    }

    .acoes button {
      width: auto;
      min-width: 90px;
    }

    #formBox {
      display: none;
    }

    .badge {
      display: inline-block;
      padding: 4px 10px;
      border-radius: 999px;
      font-size: 12px;
      font-weight: bold;
      color: white;
    }

    .badge-ativo {
      background: #16a34a;
    }

    .badge-inativo {
      background: #64748b;
    }
  </style>
</head>
<body>
  <div class="topo">
    <h1>Gerenciamento de Motoristas</h1>
    <div class="menu">
      <a href="home.php">Início</a>
      <a href="dashboard.php">Corridas</a>
      <a href="logout.php">Sair</a>
    </div>
  </div>

  <div class="container">
    <div class="box">
      <div class="acoes-topo">
        <h2 style="margin:0;">Motoristas cadastrados</h2>
        <button onclick="abrirFormularioNovo()" style="max-width:220px;">Novo motorista</button>
      </div>

      <div id="formBox">
        <h3 id="tituloFormulario">Cadastrar motorista</h3>

        <div class="grid">
          <input type="hidden" id="docId">
          <input type="hidden" id="idMotoristaNumero">

          <input type="text" id="nome" placeholder="Nome do motorista">
          <input type="text" id="empresa" placeholder="Empresa">
          <input type="text" id="placa" placeholder="Placa">
          <select id="ativo">
            <option value="true">Ativo</option>
            <option value="false">Inativo</option>
          </select>
        </div>

        <div class="acoes-topo" style="margin-top:12px;">
          <button onclick="salvarMotorista()" style="max-width:220px;">Salvar motorista</button>
          <button class="btn-secundario" onclick="cancelarFormulario()" style="max-width:220px;">Cancelar</button>
        </div>
      </div>

      <p class="msg" id="mensagem"></p>

      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Empresa</th>
            <th>Placa</th>
            <th>Ativo</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody id="tabelaMotoristas">
          <tr><td colspan="6">Carregando...</td></tr>
        </tbody>
      </table>
    </div>
  </div>

  <script type="module">
    import { firebaseConfig } from './config.js';
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js";
    import {
      getFirestore,
      collection,
      onSnapshot,
      doc,
      setDoc,
      updateDoc,
      deleteDoc,
      getDoc,
      runTransaction,
      serverTimestamp
    } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-firestore.js";

    const app = initializeApp(firebaseConfig);
    const db = getFirestore(app);

    const tabelaMotoristas = document.getElementById("tabelaMotoristas");
    const mensagem = document.getElementById("mensagem");
    const formBox = document.getElementById("formBox");
    const tituloFormulario = document.getElementById("tituloFormulario");

    function limparFormulario() {
      document.getElementById("docId").value = "";
      document.getElementById("idMotoristaNumero").value = "";
      document.getElementById("nome").value = "";
      document.getElementById("empresa").value = "";
      document.getElementById("placa").value = "";
      document.getElementById("ativo").value = "true";
    }

    window.abrirFormularioNovo = function () {
      limparFormulario();
      tituloFormulario.innerText = "Cadastrar motorista";
      formBox.style.display = "block";
      window.scrollTo({ top: 0, behavior: "smooth" });
    }

    window.cancelarFormulario = function () {
      limparFormulario();
      formBox.style.display = "none";
    }

    async function gerarProximoIdMotorista() {
      const contadorRef = doc(db, "configuracoes", "contador_motoristas");

      const novoId = await runTransaction(db, async (transaction) => {
        const contadorSnap = await transaction.get(contadorRef);

        let proximoId = 1;

        if (contadorSnap.exists()) {
          proximoId = contadorSnap.data().proximoId || 1;
        }

        transaction.set(contadorRef, {
          proximoId: proximoId + 1
        }, { merge: true });

        return proximoId;
      });

      return novoId;
    }

    window.salvarMotorista = async function () {
      const docId = document.getElementById("docId").value;
      const idMotoristaNumero = document.getElementById("idMotoristaNumero").value;
      const nome = document.getElementById("nome").value.trim();
      const empresa = document.getElementById("empresa").value.trim();
      const placa = document.getElementById("placa").value.trim();
      const ativo = document.getElementById("ativo").value === "true";

      mensagem.innerText = "";

      if (!nome || !empresa || !placa) {
        mensagem.innerText = "Preencha todos os campos.";
        return;
      }

      try {
        let numeroId = idMotoristaNumero;
        let motoristaId = "";

        if (!docId) {
          numeroId = await gerarProximoIdMotorista();
        }

        motoristaId = "motorista_" + numeroId;

        const dados = {
          idMotorista: Number(numeroId),
          motoristaId,
          nome,
          empresa,
          placa,
          ativo,
          emCorrida: false,
          status: "OFFLINE",
          atualizadoEm: serverTimestamp()
        };

        await setDoc(doc(db, "motoristas", motoristaId), dados, { merge: true });

        await setDoc(doc(db, "monitoramento_atual", motoristaId), {
          motoristaId,
          nome,
          empresa,
          placa,
          status: "OFFLINE",
          ear: 0,
          perclos: 0,
          bpm: 0,
          duracaoMediaPiscada: 0,
          episodiosRecentes: 0,
          nivelAlerta: 0,
          emCorrida: false,
          atualizadoEm: serverTimestamp()
        }, { merge: true });

        mensagem.innerText = docId
          ? "Motorista atualizado com sucesso."
          : "Motorista cadastrado com sucesso.";

        limparFormulario();
        formBox.style.display = "none";
      } catch (error) {
        console.error(error);
        mensagem.innerText = "Erro ao salvar motorista.";
      }
    }

    window.editarMotorista = function (motoristaId, idMotorista, nome, empresa, placa, ativo) {
      document.getElementById("docId").value = motoristaId;
      document.getElementById("idMotoristaNumero").value = idMotorista;
      document.getElementById("nome").value = nome;
      document.getElementById("empresa").value = empresa;
      document.getElementById("placa").value = placa;
      document.getElementById("ativo").value = ativo ? "true" : "false";

      tituloFormulario.innerText = "Editar motorista";
      formBox.style.display = "block";
      window.scrollTo({ top: 0, behavior: "smooth" });
    }

    window.excluirMotorista = async function (motoristaId) {
      const confirmar = confirm("Deseja realmente excluir este motorista?");
      if (!confirmar) return;

      try {
        await deleteDoc(doc(db, "motoristas", motoristaId));
        await deleteDoc(doc(db, "monitoramento_atual", motoristaId));

        mensagem.innerText = "Motorista excluído com sucesso.";
      } catch (error) {
        console.error(error);
        mensagem.innerText = "Erro ao excluir motorista.";
      }
    }

    onSnapshot(collection(db, "motoristas"), (snapshot) => {
      tabelaMotoristas.innerHTML = "";

      if (snapshot.empty) {
        tabelaMotoristas.innerHTML = "<tr><td colspan='6'>Nenhum motorista cadastrado.</td></tr>";
        return;
      }

      const docsOrdenados = [];
      snapshot.forEach((docSnap) => docsOrdenados.push({ id: docSnap.id, ...docSnap.data() }));
      docsOrdenados.sort((a, b) => (a.idMotorista || 0) - (b.idMotorista || 0));

      docsOrdenados.forEach((data) => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
          <td>${data.idMotorista ?? "--"}</td>
          <td>${data.nome ?? "--"}</td>
          <td>${data.empresa ?? "--"}</td>
          <td>${data.placa ?? "--"}</td>
          <td>
            <span class="badge ${data.ativo ? 'badge-ativo' : 'badge-inativo'}">
              ${data.ativo ? "Sim" : "Não"}
            </span>
          </td>
          <td>
            <div class="acoes">
              <button class="btn-editar" onclick='editarMotorista(
                "${data.motoristaId}",
                "${data.idMotorista ?? ""}",
                "${data.nome ?? ""}",
                "${data.empresa ?? ""}",
                "${data.placa ?? ""}",
                ${data.ativo ? "true" : "false"}
              )'>Editar</button>
              <button class="btn-excluir" onclick='excluirMotorista("${data.motoristaId}")'>Excluir</button>
            </div>
          </td>
        `;
        tabelaMotoristas.appendChild(tr);
      });
    });
  </script>
</body>
</html>