<?php
session_start();

require_once dirname(__FILE__) . '/function/db_connection.php';
// Truy vấn để lấy thông tin từ bảng tạm
$conn = connection();
// Query user information based on user_id
$user_id = $_SESSION['user_id'];

$userQuery = "SELECT USER_ID, USER_NAME FROM user WHERE USER_ID = ?";
$userStmt = $conn->prepare($userQuery);
$userStmt->bindParam(1, $user_id);
$userStmt->execute();
$userResult = $userStmt->fetch(PDO::FETCH_ASSOC);

$selectQuery = "SELECT * FROM orders";
$selectStmt = $conn->prepare($selectQuery);
$selectStmt->execute();
$orders = $selectStmt->fetchAll(PDO::FETCH_ASSOC);

if ($userResult) {
    $user_id = $userResult['USER_ID'];
    $user_name = $userResult['USER_NAME'];
} else {
    // Handle case when user information is not found
    // Redirect or display an error message
    header("Location: error.php");
    exit();
}
$conn = null;
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management</title>
    <link rel="stylesheet" type="text/css" href="css/user.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/footer.css" />
    <link rel="stylesheet" href="css/navbar.css" />
    <link rel="stylesheet" href="css/storeInvnt.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body style="height: 1000px">
    <nav class="navbar navbar-inverse fixed-top">
        <div class="navbar-header">
            <a class="navbar-brand" href="w_Landing_Page/landing.html">
                <span class="logo"></span>
            </a>
        </div>
        <div>
            <a class="navbar-brand">OpenSeaS</a>
        </div>
        <ul class="nav navbar-nav">
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Store<span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="#">Storefront</a></li>
                    <li><a href="./w_disposal_page/registerDisposal.html">Disposal Registration</a></li>
                    <li><a href="./w_store_page/storeInfo.html">Store Information</a></li>
                </ul>
            </li>
            <li><a href="./w_disposal_page/deliveryDisposal">Disposal Information</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li>
                <a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span>
                    <span class="glyphicon glyphicon-user">
                        <?php echo $user_name; ?>
                    </span>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="#"></a></li>
                    <li><a href="userProfile.php">Your Profile</a></li>
                    <li><a href="orderHistory.php">Order History</a></li>
                    <li><a href="setting.php">Setting</a></li>
                    <li><a href="function/logout.php"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <!-- Thêm phần tử HTML để hiển thị thông tin từ bảng tạm -->
    <div id="orderInfo">
        <h3>注文詳細:</h3>
        <table>
            <tr>
                <th>STORE NAME</th>
                <th>商品名</th>
                <th>数量</th>
            </tr>
            <?php
            foreach ($orders as $order) {
                echo "<tr>";
                echo "<td>" . $order['STORE_NAME'] . "</td>";
                echo "<td>" . $order['ITEM'] . "</td>";
                echo "<td>" . $order['QTY'] . "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
    <footer class="custom-footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>About Us</h5>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                </div>
                <div class="col-md-6">
                    <h5>Contact</h5>
                    <ul class="list-unstyled">
                        <li>Phone: 123-356-7890</li>
                        <li>Email: info@example.com</li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <script src="js/bootstrap.js"></script>
    <script src="js/userScript.js"></script>
</body>

</html>