<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kiểm tra xem người dùng đã đăng nhập hay chưa
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    // Thư mục lưu trữ hình ảnh đại diện
    $avatarUploadDir = 'avatars/';
    $avatarFileName = $_FILES['avatar']['name'];
    $avatarTmpName = $_FILES['avatar']['tmp_name'];

    // Kiểm tra xem tệp có phải là hình ảnh
    $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
    $fileExtension = strtolower(pathinfo($avatarFileName, PATHINFO_EXTENSION));
    if (!in_array($fileExtension, $allowedExtensions)) {
        echo 'Chỉ chấp nhận tệp hình ảnh có định dạng JPG, JPEG, PNG hoặc GIF.';
        exit();
    }

    // Định tên mới cho tệp hình đại diện (có thể sử dụng user ID để đảm bảo tên duy nhất)
    $user_id = $_SESSION['user_id'];
    $newAvatarFileName = 'avatar_' . $user_id . '.' . $fileExtension;

    // Di chuyển tệp hình đại diện vào thư mục lưu trữ
    if (move_uploaded_file($avatarTmpName, $avatarUploadDir . $newAvatarFileName)) {
        // Cập nhật tên hình đại diện mới vào cơ sở dữ liệu
        require_once dirname(__FILE__) . '/function/db_connection.php';
        $conn = connection();

        $updateQuery = "UPDATE user SET AVATAR = :avatarFileName WHERE USER_ID = :user_id";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bindParam(':avatarFileName', $newAvatarFileName);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        $conn = null;

        echo 'Tải lên hình đại diện thành công.';
    } else {
        echo 'Đã xảy ra lỗi khi tải lên hình đại diện.';
    }
} else {
    header("Location: userProfile.php"); // Chuyển hướng về trang userProfile nếu không phải là phương thức POST
    exit();
}
?>
