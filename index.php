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
  min-height: 100vh;
  background:
    radial-gradient(circle at top left, rgba(56, 189, 248, 0.18), transparent 35%),
    linear-gradient(135deg, #020617, #0f172a 55%, #1e3a8a);
  color: white;
  display: flex;
  justify-content: center;
  align-items: center;
}

.box {
  background: rgba(15, 23, 42, 0.92);
  padding: 34px;
  border-radius: 22px;
  width: 100%;
  max-width: 390px;
  box-shadow: 0 20px 50px rgba(0,0,0,0.45);
  border: 1px solid rgba(148, 163, 184, 0.25);
}

.logo {
  text-align: center;
  margin-bottom: 22px;
}

.logo .icon {
  font-size: 42px;
  margin-bottom: 8px;
}

.logo h1 {
  margin: 0;
  font-size: 28px;
}

.logo p {
  margin: 8px 0 0;
  color: #cbd5e1;
  font-size: 14px;
}

h2 {
  margin-top: 0;
  text-align: center;
  font-size: 20px;
  color: #e0f2fe;
}

input, button {
  width: 100%;
  padding: 13px;
  margin-top: 12px;
  border-radius: 12px;
  border: none;
  font-size: 15px;
}

input {
  background: #f8fafc;
  color: #0f172a;
  outline: none;
}

input:focus {
  box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.35);
}

button {
  background: linear-gradient(135deg, #2563eb, #38bdf8);
  color: white;
  cursor: pointer;
  font-weight: bold;
  margin-top: 16px;
}

button:hover {
  filter: brightness(1.08);
}

p {
  text-align: center;
  color: #cbd5e1;
}

a {
  color: #38bdf8;
  text-decoration: none;
  font-weight: bold;
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
    <div class="logo">
  <div class="icon">🌙</div>
  <h1>SonoSafe</h1>
  <p>Monitoramento inteligente de fadiga</p>
</div>
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