<?php
session_start();

require_once dirname(__FILE__) . '/function/db_connection.php';

$conn = connection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kiểm tra xem người dùng đã đăng nhập hay chưa
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    // Lấy user ID từ session
    $user_id = $_SESSION['user_id'];

    // Lấy dữ liệu được gửi từ JavaScript và chuyển đổi thành mảng dữ liệu
    $updateData = json_decode(file_get_contents('php://input'), true);

    // Kiểm tra và lấy giá trị từ mảng dữ liệu
    $userName = $updateData['user_name'] ?? null;
    $userEmail = $updateData['user_email'] ?? null;
    $userTel = $updateData['user_tel'] ?? null;
    $userAddress = $updateData['user_address'] ?? null;

    // Chuẩn bị và thực thi truy vấn UPDATE để cập nhật thông tin người dùng
    $updateQuery = "UPDATE user SET USER_NAME = :userName, USER_EMAIL = :userEmail, USER_TEL = :userTel, USER_ADDRESS = :userAddress WHERE USER_ID = :userId";
    $stmt = $conn->prepare($updateQuery);

    // Bind các giá trị vào câu truy vấn
    $stmt->bindParam(':userName', $userName);
    $stmt->bindParam(':userEmail', $userEmail);
    $stmt->bindParam(':userTel', $userTel);
    $stmt->bindParam(':userAddress', $userAddress);
    $stmt->bindParam(':userId', $user_id);

    // Thực thi câu truy vấn
    $success = $stmt->execute();

    // Đóng statement sau khi sử dụng
    $stmt->closeCursor();

    if ($success) {
        // Gửi phản hồi thành công về cho JavaScript
        $response = array('success' => true);
        echo json_encode($response);
    } else {
        // Gửi phản hồi lỗi về cho JavaScript
        $response = array('success' => false);
        echo json_encode($response);
    }
} else {
    // Gửi phản hồi lỗi về cho JavaScript nếu yêu cầu không phải là POST
    $response = array('success' => false);
    echo json_encode($response);
}

// Đóng kết nối cơ sở dữ liệu
$conn = null;
?>
