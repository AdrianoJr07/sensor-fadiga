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
  <title>Login - Sensor de Fadiga</title>
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: linear-gradient(135deg, #0f172a, #1e293b);
      color: white;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    .box {
      background: #1e293b;
      padding: 30px;
      border-radius: 16px;
      width: 100%;
      max-width: 360px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.35);
    }

    h2 {
      margin-top: 0;
      text-align: center;
    }

    input, button {
      width: 100%;
      padding: 12px;
      margin-top: 12px;
      border-radius: 10px;
      border: none;
      font-size: 15px;
    }

    input {
      background: #f8fafc;
      color: #0f172a;
    }

    button {
      background: #2563eb;
      color: white;
      cursor: pointer;
      font-weight: bold;
    }

    button:hover {
      background: #1d4ed8;
    }

    p {
      text-align: center;
    }

    a {
      color: #60a5fa;
      text-decoration: none;
    }

    #mensagem {
      margin-top: 15px;
      font-size: 14px;
      color: #fca5a5;
    }
  </style>
</head>
<body>
  <div class="box">
    <h2>Login</h2>

    <input type="email" id="email" placeholder="Digite seu email">
    <input type="password" id="senha" placeholder="Digite sua senha">

    <button onclick="login()">Entrar</button>

    <p>Não tem conta? <a href="cadastro.php">Cadastre-se</a></p>

    <p id="mensagem"></p>
  </div>

  <script type="module">
    import { firebaseConfig } from './config.js';
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js";
    import { getAuth, signInWithEmailAndPassword } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-auth.js";

    const app = initializeApp(firebaseConfig);
    const auth = getAuth(app);

    window.login = async function () {
      const email = document.getElementById("email").value.trim();
      const senha = document.getElementById("senha").value.trim();
      const msg = document.getElementById("mensagem");

      msg.innerText = "";

      if (!email || !senha) {
        msg.innerText = "Preencha email e senha.";
        return;
      }

      try {
        const userCredential = await signInWithEmailAndPassword(auth, email, senha);
        const user = userCredential.user;

        const response = await fetch("session.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded"
          },
          body: "uid=" + encodeURIComponent(user.uid)
        });

        const result = await response.text();

        if (result === "ok") {
          window.location.href = "home.php";
        } else {
          msg.innerText = "Erro ao iniciar sessão no PHP.";
        }

      } catch (error) {
        console.error("Erro detalhado:", error);
        if (error.code === 'auth/invalid-credential') {
          msg.innerText = "Email ou senha incorretos.";
        } else if (error.code === 'auth/user-not-found') {
          msg.innerText = "Usuário não encontrado.";
        } else {
          msg.innerText = "Erro ao fazer login. Verifique o console (F12).";
        }
      }
    }
  </script>
</body>
</html>