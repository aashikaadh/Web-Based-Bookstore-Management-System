<?php
session_start();
include 'config.php';

// ------------- REGISTER ----------------
if (isset($_POST['register'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Email check
    $checkEmail = $conn->query("SELECT email FROM userss WHERE email='$email'");
    if ($checkEmail->num_rows > 0){
        $_SESSION['register_error'] = 'Email is already registered!';
        $_SESSION['active_form'] = 'register';
        header("Location: login.php");
        exit();
    }

    // Insert new user
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO userss (name, email, password, role) VALUES ('$name','$email','$hashedPassword','$role')";
    if ($conn->query($sql)){
        $_SESSION['register_success'] = 'Registration successful! You can now login.';
        $_SESSION['active_form'] = 'login';  
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['register_error'] = 'Registration failed. Try again!';
        $_SESSION['active_form'] = 'register';
        header("Location: login.php");
        exit();
    }
}

// ------------- LOGIN ----------------
if (isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM userss WHERE email='$email'");
    if ($result->num_rows > 0){
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])){
               $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            if ($user['role']==='admin'){
                header("Location: admin_page.php");
            } else {
                header("Location: user_page.php");
            }
            exit();
        }
    }

    $_SESSION['login_error'] = 'Incorrect email or password';
    $_SESSION['active_form'] = 'login';
    header("Location: login.php");
    exit();
}
?>
