<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Login Admin</title>

<style>
    body {
        margin: 0;
        font-family: 'Poppins', sans-serif;
        background: #f5f5f5;
    }

    .container {
        display: flex;
        height: 100vh;
        width: 100%;
    }

    /* BAGIAN KIRI */
    .left-side {
        width: 50%;
        background: url("{{ asset('images/konserAdmin.jpg') }}") no-repeat center center / cover;
        position: relative;
        color: white;
        padding: 40px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .logo-box {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .logo-box img {
        width: 48px;
        height: 48px;
    }

    .left-text {
        margin-top: auto;
        margin-bottom: 80px;
    }

    .left-text h1 {
        font-size: 36px;
        font-weight: 700;
        margin: 0;
    }

    .left-text p {
        margin-top: 10px;
        font-size: 16px;
        max-width: 370px;
        opacity: 0.9;
    }

    /* BAGIAN KANAN (FORM LOGIN) */
    .right-side {
        width: 50%;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px;
    }

    .login-box {
        width: 80%;
        max-width: 380px;
    }

    .login-box h2 {
        color: #1b1c57;
        font-size: 26px;
        margin-bottom: 10px;
    }

    .input-group {
        margin-top: 20px;
    }

    .input-group label {
        display: block;
        margin-bottom: 6px;
        font-weight: 500;
    }

    .input-group input {
        width: 100%;
        padding: 12px;
        border-radius: 8px;
        border: 1px solid #ccc;
        outline: none;
        font-size: 14px;
    }

    .options {
        margin-top: 10px;
        display: flex;
        justify-content: space-between;
        font-size: 14px;
        color: #555;
    }

    .login-btn {
        width: 100%;
        margin-top: 25px;
        background: #000;
        color: white;
        border: none;
        padding: 14px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 600;
    }
</style>
</head>

<body>

<div class="container">

    <!-- BAGIAN KIRI -->
    <div class="left-side">
        <div class="logo-box">
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
            <h3>Admin Panel</h3>
        </div>

        <div class="left-text">
            <h1>Selamat Datang Admin!</h1>
            <p>Kelola data, pantau aktivitas, dan kontrol sistem dengan mudah melalui dashboard admin.</p>
        </div>
    </div>

    <!-- BAGIAN KANAN -->
    <div class="right-side">
        <div class="login-box">
            <h2>Login Admin</h2>

            <div class="input-group">
                <label>Email</label>
                <input type="email" placeholder="Masukkan email admin...">
            </div>

            <div class="input-group">
                <label>Password</label>
                <input type="password" placeholder="Masukkan password...">
            </div>

            <div class="options">
                <label><input type="checkbox"> Ingat saya</label>
            </div>

            <button class="login-btn">Login</button>
        </div>
    </div>

</div>

</body>
</html>
