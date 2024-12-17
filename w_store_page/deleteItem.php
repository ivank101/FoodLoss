<?php

// Hoàn thành

// POST request để lấy disposalId
$disposalId = $_POST['disposalId'];

// Thông tin kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "dbuser";
$password = "ecc";
$dbname = "food";

// Kết nối đến cơ sở dữ liệu
$db = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);

// Chuẩn bị câu truy vấn SQL để xóa dữ liệu
$sql = 'DELETE FROM disposal WHERE DISPOSAL_ID = :disposalId';
$stmt = $db->prepare($sql);
$stmt->bindParam(':disposalId', $disposalId, PDO::PARAM_INT); // Sử dụng ràng buộc biến và chỉ định kiểu dữ liệu

// Thực thi câu truy vấn SQL
if ($stmt->execute()) {
    $db -> commit();
    // Nếu xóa thành công, trả về mã trạng thái 200 (OK)
    http_response_code(200);
} else {
    // Nếu xóa thất bại, trả về mã trạng thái 500 (Internal Server Error) và in thông báo lỗi
    http_response_code(500);
    echo 'Lỗi SQL: ' . $stmt->errorInfo()[2];
}
$db -> commit();

?>
