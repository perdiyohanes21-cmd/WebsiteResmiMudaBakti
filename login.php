<?php
session_start();
include "config.php";

$error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // gunakan prepared statement
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username=? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // verifikasi password hash
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin'] = $row['username'];
            header("Location: admin.php?notif=Login berhasil");
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login Admin - Muda Bakti RT 8 RW 11</title>
    <style>
        body {
            font-family: 'Orbitron', sans-serif;
            background: #0d0d0d;
            color: #e0e0e0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-box {
            background: #1a1a1a;
            padding: 30px;
            border-radius: 12px;
            border: 1px solid #00f0ff;
            box-shadow: 0 0 20px rgba(0, 240, 255, .5);
            width: 320px;
            text-align: center;
        }

        .login-box h2 {
            margin-bottom: 20px;
            color: #00f0ff;
            text-shadow: 0 0 10px #00f0ff;
        }

        .login-box input,
        .login-box button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 6px;
            border: none;
            outline: none;
        }

        .login-box button {
            background: #00f0ff;
            font-weight: bold;
            cursor: pointer;
            transition: .3s;
        }

        .login-box button:hover {
            background: #ff00ff;
            color: #fff;
        }

        .close-btn {
            display: inline-block;
            margin-top: 15px;
            padding: 8px 15px;
            border-radius: 6px;
            border: 1px solid #ff00ff;
            color: #ff00ff;
            text-decoration: none;
            font-weight: bold;
            transition: .3s;
            box-shadow: 0 0 10px #ff00ff;
        }

        .close-btn:hover {
            background: #ff00ff;
            color: #0d0d0d;
            box-shadow: 0 0 20px #00f0ff;
        }


        .error {
            background: #ff4d4d;
            color: #fff;
            padding: 8px;
            border-radius: 6px;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="login-box">
        <h2>Login Admin</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
            <a href="index.php" class="close-btn">Close</a>

            <?php if ($error)
                echo "<div id='notif' class='error'>" . htmlspecialchars($error) . "</div>"; ?>
        </form>
    </div>
    <script>
        const notif = document.getElementById("notif");
        if (notif) {
            setTimeout(() => {
                notif.style.transition = "opacity 0.5s ease";
                notif.style.opacity = "0";
                setTimeout(() => notif.remove(), 500);
            }, 2000);
        }
    </script>
</body>

</html>