<?php
session_start();
require_once dirname(__FILE__) . '/function/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kiểm tra xem người dùng đã đăng nhập hay chưa
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    // Lấy thông tin từ yêu cầu AJAX
    $selectedStoreRequests = json_decode(file_get_contents('php://input'), true);

    // Cập nhật số lượng trong cơ sở dữ liệu cho từng yêu cầu của cửa hàng
    $conn = connection();

    // Thêm thông tin yêu cầu vào bảng orders
    $insertQuery = "INSERT INTO orders (USER_ID, STORE_NAME, ITEM, QTY) VALUES (:userId, :storeName, :item, :quantity)";
    $insertStmt = $conn->prepare($insertQuery);

    foreach ($selectedStoreRequests as $request) {
        $userId = $_SESSION['user_id'];
        $storeName = $request['store_name'];
        $item = $request['item'];
        $quantity = $request['qty'];
        //$date = date('Y-m-d');

        $insertStmt->bindParam(':userId', $userId);
        $insertStmt->bindParam(':storeName', $storeName);
        $insertStmt->bindParam(':item', $item);
        $insertStmt->bindParam(':quantity', $quantity);
        //$insertStmt->bindParam(':date', $date);
        $insertStmt->execute();
    }
    $conn->commit();

    // Đóng kết nối và gửi phản hồi về cho máy khách (client)
    $conn = null;
}
?>

