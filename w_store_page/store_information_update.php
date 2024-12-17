<?php
session_start();

if (!isset($_SESSION['store_email'])) {
    exit;
}

$email = $_SESSION['store_email'];

$servername = "localhost";
$username = "dbuser";
$password = "ecc";
$dbname = "food";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
$conn->autocommit(true);
$newStoreName = $_POST['newStoreName'];
$newStoreTel = $_POST['newStoreTel'];
$newStoreAddress = $_POST['newStoreAddress'];
$newStoreEmail = $_POST['newStoreEmail'];

try {
    $sql = "UPDATE STORE SET STORE_NAME = ?, STORE_EMAIL = ?, STORE_TEL = ?, STORE_ADDRESS = ? WHERE STORE_EMAIL = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $newStoreName, $newStoreEmail, $newStoreTel, $newStoreAddress, $email);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $_SESSION['update_success'] = true;
        $_SESSION['store_email']= $newStoreEmail;
    } else {
        $_SESSION['update_success'] = false;
    }

    $stmt->close();
    $conn->close();

    header("Location: show&change_storeinfo.php");
    exit();
} catch (mysqli_sql_exception $e) {
    $conn->rollback();
    $_SESSION['update_success'] = false;

    $errorCode = $e->getCode();
    $errorMessage = $e->getMessage();

    if ($errorCode === 1062) {
        if (strpos($errorMessage, "STORE_EMAIL") !== false) {
            $_SESSION['update_error'] = "入力されたメールがすでに登録されています！";
        } else if (strpos($errorMessage, "STORE_TEL") !== false) {
            $_SESSION['update_error'] = "入力された電話番号がすでに登録されています！";
        } else {
            $_SESSION['update_error'] = "入力されたメールと電話番号がすでに登録されています！";
        }
    } else {
        $_SESSION['update_error'] = "Oops! 変更が正常に行われませんでした！もう一度お試しくだい ";
    }
    header("Location: show&change_storeinfo.php"); 
    exit();
}
?>
