<?php
// データベースの情報
$servername = "localhost";
$username = "dbuser";
$password = "ecc";
$dbname = "food";

$conn = new mysqli($servername, $username, $password, $dbname);

// 接続のチェック 
if ($conn->connect_error) {
    die("アクセス失敗: " . $conn->connect_error);
}

if (isset($_POST['statusChange'])) {
    $disposalId = $_POST['disposalId'];
    $status = $_POST['status'];
    print_r($status);
    print_r($disposalId);

    $stmt = $conn->prepare("UPDATE disposal SET STATUS = ? WHERE DISPOSAL_ID = ?");
    $stmt->bind_param("si", $status, $disposalId);

    if ($stmt->execute()) {
        // Status update successful
        $conn->commit();
        echo "Status updated successfully.";
    
        // Redirect back to the previous page
        header("Location: statusdisposalpage.php");
        exit();
    } else {
        // Error occurred while updating status
        echo "Status update failed: " . $stmt->error;
    }
    

    $stmt->close();
}

$conn->close();
?>
