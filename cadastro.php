<?php
session_start();
if (isset($_SESSION['usuario'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro - Sensor de Fadiga</title>
  <style>
    * { box-sizing: border-box; }
    body {
      margin: 0; font-family: Arial, sans-serif;
      background: linear-gradient(135deg, #0f172a, #1e293b);
      color: white; display: flex; justify-content: center; align-items: center; min-height: 100vh;
    }
    .box {
      background: #1e293b; padding: 30px; border-radius: 16px;
      width: 100%; max-width: 360px; box-shadow: 0 10px 30px rgba(0,0,0,0.35);
    }
    h2 { margin-top: 0; text-align: center; }
    input, button { width: 100%; padding: 12px; margin-top: 12px; border-radius: 10px; border: none; font-size: 15px; }
    input { background: #f8fafc; color: #0f172a; }
    button { background: #10b981; color: white; cursor: pointer; font-weight: bold; }
    button:hover { background: #059669; }
    p { text-align: center; }
    a { color: #60a5fa; text-decoration: none; }
    #mensagem { margin-top: 15px; font-size: 14px; color: #fca5a5; }
  </style>
</head>
<body>
  <div class="box">
    <h2>Criar Conta</h2>
    <input type="email" id="email" placeholder="Email">
    <input type="password" id="senha" placeholder="Senha (mín. 6 caracteres)">
    <button onclick="registrar()">Cadastrar</button>
    <p>Já tem conta? <a href="index.php">Faça login</a></p>
    <p id="mensagem"></p>
  </div>

  <script type="module">
    import { firebaseConfig } from './config.js';
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js";
    import { getAuth, createUserWithEmailAndPassword } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-auth.js";

    const app = initializeApp(firebaseConfig);
    const auth = getAuth(app);

    window.registrar = async function () {
      const email = document.getElementById("email").value.trim();
      const senha = document.getElementById("senha").value.trim();
      const msg = document.getElementById("mensagem");
      msg.innerText = "";

      if (!email || !senha) {
        msg.innerText = "Preencha todos os campos.";
        return;
      }

      try {
        const userCredential = await createUserWithEmailAndPassword(auth, email, senha);
        
        const response = await fetch("session.php", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: "uid=" + encodeURIComponent(userCredential.user.uid)
        });

        if (await response.text() === "ok") {
          window.location.href = "home.php";
        }
      } catch (error) {
        console.error(error);
        if (error.code === 'auth/email-already-in-use') {
          msg.innerText = "Este email já está cadastrado.";
        } else if (error.code === 'auth/weak-password') {
          msg.innerText = "A senha deve ter pelo menos 6 caracteres.";
        } else {
          msg.innerText = "Erro ao criar conta: " + error.message;
        }
      }
    }
  </script>
</body>
</html>