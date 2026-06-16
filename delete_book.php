<?php
session_start();
require_once 'config.php';

// Check admin access
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

// Get image name to delete it from folder
$result = $conn->query("SELECT image FROM book WHERE id=$id");
$book = $result->fetch_assoc();

// Delete the image file
if($book && file_exists("uploads/" . $book['image'])){
    unlink("uploads/" . $book['image']);
}

// Delete the record
$conn->query("DELETE FROM book WHERE id=$id");

header("Location: admin_page.php");
exit();
?>
