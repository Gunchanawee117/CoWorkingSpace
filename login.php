<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link href="./assets/css/loginstyle.css" rel="stylesheet">
    <link href="./assets/css/style.css" rel="stylesheet">
    
</head>

<body>
    <div class="signin">
        <div class="back-img">
            <div class="layer"></div>
            <div class="sign-in-text">
                <button class="h2L" id="loginBtn">Login</button>
                <button class="h2R" id="registerBtn">Register</button>
            </div>
        </div>
        <div class="form-section" id="loginForm">
            <form action="process_login.php" method="POST">
                <label for="username">Username:</label>
                <input type="text" name="user_id" required><br>

                <label for="password">Password:</label>
                <input type="password" name="password" required><br>

                <button class="sign-in-btn" type="submit">Login</button>
                <center>
                <a href="index.php" class="btn btn-danger1">ย้อนกลับ</a>
                </center>
                
            </form>
        </div>
        <!-- Registration Form -->
<div class="form-section" id="registerForm" style="display: none;">
    <form action="process_register.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" name="user_id" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br>

        <label for="repassword">Re-enter Password:</label>
        <input type="password" name="repassword" required><br>

        <label for="username">Firstname:</label>
        <input type="text" name="Fname" required><br>

        <label for="username">Lastname:</label>
        <input type="text" name="Lname" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required><br>

        <label for="phone">Phone:</label>
        <input type="number" name="phone" required><br>

        <button class="sign-in-btn" type="submit">Register</button>
        <center>
            <a href="index.php" class="btn btn-danger1">ย้อนกลับ</a>
        </center>
    </form>
</div>

    </div>

    <script>
        const loginBtn = document.getElementById('loginBtn');
        const registerBtn = document.getElementById('registerBtn');
        const loginForm = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');

        loginBtn.addEventListener('click', () => {
            loginForm.style.display = 'block';
            registerForm.style.display = 'none';
        });

        registerBtn.addEventListener('click', () => {
            loginForm.style.display = 'none';
            registerForm.style.display = 'block';
        });
    </script>

</body>

</html>

<?php
if (isset($_GET['message'])) {
    $message = $_GET['message'];
    echo "<script>alert('$message');</script>";
}
?>