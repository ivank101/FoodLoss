<?php
session_start();

require_once dirname(__FILE__) . '/function/db_connection.php';

$conn = connection();


// Check if the user ID parameter is set
$user_id = $_SESSION['user_id'];

$userQuery = "SELECT USER_ID, USER_NAME, USER_EMAIL, USER_TEL, USER_ADDRESS FROM user WHERE USER_ID = ?";
$userStmt = $conn->prepare($userQuery);
$userStmt->bindParam(1, $user_id);
$userStmt->execute();
$userResult = $userStmt->fetch(PDO::FETCH_ASSOC);

if ($userResult) {
    $user_id = $userResult['USER_ID'];
    $user_name = $userResult['USER_NAME'];
    $user_email = $userResult['USER_EMAIL'];
    $user_tel = $userResult['USER_TEL'];
    $user_address = $userResult['USER_ADDRESS'];
} else {
    // Handle case when user information is not found
    // Redirect or display an error message
    header("Location: error.php");
    exit();
}

// Đóng kết nối cơ sở dữ liệu
$conn = null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/navbar.css" />
    <link rel="stylesheet" href="css/footer.css" />
    <link rel="stylesheet" href="css/userInfo.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
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
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <span class="caret"></span>
                    <span>
                        <img src="./img/shiba.png" alt="Avatar" class="user-avatar-navbar">
                        <?php echo $user_name; ?>
                    </span>
                </a>

                <ul class="dropdown-menu">
                    <li><a href="#"></a></li>
                    <li><a href="userProfile.php">Your Profile</a></li>
                    <li><a href="orderHistory.php">Order History</a></li>
                    <li><a href="setting.php">Setting</a></li>
                    <li>
                        <a href="#" onclick="openLogoutModal()">
                            <span class="glyphicon glyphicon-log-in"></span> Logout
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
    <div class="container">
        <!-- Hình đại diện của người dùng và form để tải lên hình mới -->
        <img src="./img/shiba.png" alt="Avatar" class="user-avatar" id="userAvatar">

        <form id="uploadForm" action="upload_avatar.php" method="post" enctype="multipart/form-data">
            <input type="file" name="avatar" id="avatarInput">
            <button type="submit">新しい写真をアップロードする</button>
        </form>

        <div class="user-info">
            <h2>ユーザー情報</h2>
            <p><strong>ユーザー ID:</strong>
                <?php echo $user_id; ?>
            </p>
            <p><strong>ユーザー名:</strong>
                <?php echo $user_name; ?>
            </p>
            <p><strong>ユーザーメール:</strong>
                <?php echo $user_email; ?>
            </p>
            <p><strong>ユーザー電話番号:</strong>
                <?php echo $user_tel; ?>
            </p>
            <p><strong>ユーザー住所:</strong>
                <?php echo $user_address; ?>
            </p>
            <button onclick="openInfoChangePopup()" type="button" class="btn btn-primary btn-back">
                変更
            </button>
            <button onclick="location.href='user.php'" type="button" class="btn btn-primary btn-back">
                戻る
            </button>
        </div>
    </div>
    <!-- Info Change Modal -->
    <div id="infoChangeModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeInfoChangePopup()">&times;</span>
            <h2>個人情報を変更する</h2>
            <label for="newUserName">新しいユーザー名:</label>
            <input type="text" id="newUserName" value="<?php echo $user_name; ?>">
            <label for="newUserEmail">新しいメール:</label>
            <input type="text" id="newUserEmail" value="<?php echo $user_email; ?>">
            <label for="newUserTel">新しい電話番号:</label>
            <input type="text" id="newUserTel" value="<?php echo $user_tel; ?>">
            <label for="newUserAddress">新しい住所:</label>
            <input type="text" id="newUserAddress" value="<?php echo $user_address; ?>">
            <button onclick="updateUserInfo()">変更</button>
        </div>
    </div>
    <!-- Logout Modal -->
    <div id="logoutModal" class="modal logout-modal">
        <div class="modal-content">
            <span class="close" onclick="closeLogoutModal()">&times;</span>
            <h2>ログアウトを確認</h2>
            <p>アカウントからログアウトしてもよろしいですか？</p>
            <div class="button-container">
                <a class="confLog" onclick="confirmLogout()">同意する</a>
                <hr class="vertical-line">
                <a class="Cancel" onclick="closeLogoutModal()">キャンセル</a>
            </div>
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