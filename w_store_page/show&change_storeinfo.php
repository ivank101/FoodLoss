<?php
session_start();

if (!isset($_SESSION)) {
  session_start();
}

if (!isset($_SESSION['store_email'])) {
  exit;
}

$servername = "localhost";
$username = "dbuser";
$password = "ecc";
$dbname = "food";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Kết nối thất bại: " . $conn->connect_error);
}

$email = $_SESSION['store_email'];
$stmt = $conn->prepare("SELECT * FROM store WHERE store_email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <title>店舗情報</title>
  <meta charset="utf-8">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" />
  <link rel="stylesheet" href="css/footer.css" />
  <link rel="stylesheet" href="css/navbar.css" />
  <link rel="stylesheet" href="../css/color.css" />
  <link rel="stylesheet" href="../css/store_info.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script>
    var updateSuccess = <?php echo isset($_SESSION['update_success']) && $_SESSION['update_success'] ? 'true' : 'false'; ?>;
  </script>

  <script>

  </script>
</head>

<body>
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
      <li><a href="./w_disposal_page/deliveryDisposal.html">Disposal Information</a></li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
      <li>
        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span>
          <span class="glyphicon glyphicon-user">
          </span>
        </a>
        <ul class="dropdown-menu">
          <li><a href="#"></a></li>
          <li><a href="userProfile.php">Your Profile</a></li>
          <li><a href="setting.php">Setting</a></li>
          <li><a href="function/logout.php"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>
        </ul>
      </li>
    </ul>
  </nav>
  <p id="updateMessage"
    data-success="<?php echo isset($_SESSION['update_success']) && $_SESSION['update_success'] ? 'true' : 'false'; ?>">
    <?php
    if (isset($_SESSION['update_error'])) {
      echo $_SESSION['update_error'];
      unset($_SESSION['update_error']); // Xóa session để không hiển thị lại thông báo sau khi refresh trang
    } else {
      if (isset($_SESSION['update_success'])) {
        if ($_SESSION['update_success']) {
          echo "Cập nhật dữ liệu thành công";
        } else {
          echo "Cập nhật dữ liệu thất bại";
        }
        unset($_SESSION['update_success']); // Xóa session để không hiển thị lại thông báo sau khi refresh trang
      }
    }
    ?>
  </p>
  <div class="container" style="margin-top: 70px;">
    <h1>店舗情報</h1>

    <!-- Các thông tin cửa hàng được in ra từ kết quả truy vấn -->
    <div id="storeInfo">
      <?php while ($row = $result->fetch_assoc()): ?>
        <h3>店舗コード:
          <?php echo $row['STORE_ID']; ?>
        </h3>
        <p>店舗名:
          <?php echo $row['STORE_NAME']; ?>
        </p>
        <p>メールアドレス:
          <?php echo $row['STORE_EMAIL']; ?>
        </p>
        <p>電話番号:
          <?php echo $row['STORE_TEL']; ?>
        </p>
        <p>住所:
          <?php echo $row['STORE_ADDRESS']; ?>
        </p>
        <hr>
      <?php endwhile; ?>
    </div>

    <!-- Box nhập thông tin mới -->
    <form id="editForm" style="display: none;">
      <div class="form-group">
        <label for="newStoreName">店舗名:</label>
        <input type="text" id="newStoreName" name="newStoreName" placeholder="New Store Name">
      </div>
      <div class="form-group">
        <label for="newStoreEmail">メール:</label>
        <input type="text" id="newStoreEmail" name="newStoreEmail" placeholder="New Store Email"
          onkeyup="showEmailNote()">
        <br>
        <span style="font-size: small; color: red;">ログイン時のメールアドレスが変更されますので、ご注意ください！</span>
      </div>
      <div class="form-group">
        <label for="newStoreTel">電話番号:</label>
        <input type="text" id="newStoreTel" name="newStoreTel" placeholder="New Store Tel">
      </div>
      <div class="form-group">
        <label for="newStoreAddress">住所:</label>
        <input type="text" id="newStoreAddress" name="newStoreAddress" placeholder="New Store Address">
      </div>
    </form>

    <!-- Các nút điều hướng -->
    <div class="button-container">
      <button id="editButton" onclick="showEditForm()">変更</button>
      <button id="saveButton" style="display: none;" onclick="saveChanges()">保存</button>
      <button id="backButton" onclick="backButtonClicked()">戻る</button>
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
  <script src="../js/store_info.js"></script>
</body>

</html>