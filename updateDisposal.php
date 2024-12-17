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
    $updateData = json_decode(file_get_contents('php://input'), true);

    // Cập nhật số lượng trong bảng disposal
    $conn = connection();

    $updateDisposalQuery = "UPDATE disposal SET qty = :updatedQty WHERE disposal_id = :disposalId";
    $updateDisposalStmt = $conn->prepare($updateDisposalQuery);

    // Lặp qua mảng updateData để cập nhật thông tin số lượng vào bảng disposal
    foreach ($updateData as $updateItem) {
        $updatedQty = $updateItem['updtQuantity'];
        $disposalId = $updateItem['disposalId'];

        $updateDisposalStmt->bindParam(':updatedQty', $updatedQty);
        $updateDisposalStmt->bindParam(':disposalId', $disposalId);
        $updateDisposalStmt->execute();
    }
    $conn->commit();

    // Đóng kết nối và gửi phản hồi về cho máy khách (client)
    $conn = null;
}
?>
