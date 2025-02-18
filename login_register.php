<?php
session_start();
require_once 'config.php';


// LOGIN USER
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Secure query using prepared statements
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];

            // Only allow the predefined admin email to be an admin
            if ($user['role'] === 'admin' && $user['email'] === ADMIN_EMAIL) {
                $_SESSION['role'] = 'admin';
                header("Location: admin_page.php");
            } else {
                $_SESSION['role'] = 'user';
                header("Location: user_page.php");
            }
            exit();
        }
    }

    $_SESSION['login_error'] = "Incorrect email or password";
    $_SESSION['active_form'] = 'login';

    header("Location: index.php");
    exit();
}
?>