<?php
//PHP部分完成
session_start();

// データベースの情報　
$servername = "localhost";
$username = "dbuser";
$password = "ecc";
$dbname = "food";

$conn = new mysqli($servername, $username, $password, $dbname);

//接続のチェック 
if ($conn->connect_error) {
    die("アクセス失敗: " . $conn->connect_error);
}

// フォームから送信されたデータを取得
$email =  $_SESSION['store_email'];
$stmt = $conn->prepare("SELECT store_id FROM store WHERE store_email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

$store_id = null;

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $store_id = $row['store_id'];
} 
// $_SESSION['store_id'] = $store_id;
$stmt->close();

//additem
$message = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['itemInput']) && isset($_POST['quantityInput']) && isset($_POST['dateInput']) && isset($_POST['statusInput'])) {
      $item = $_POST['itemInput'];
      $quantity = $_POST['quantityInput'];
      $date = $_POST['dateInput'];
      $status = $_POST['statusInput'];
      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }
      $stmt = $conn->prepare("INSERT INTO disposal (store_id, item, qty, date, status) VALUES (?, ?, ?, ?, ?)");
      $stmt->bind_param("isiss", $store_id, $item, $quantity, $date, $status);
      if ($stmt->execute()) {
        $conn -> commit();
         $message = '在庫に追加できました！';
      } else {
        $message = '在庫の追加が失敗しました！';
      }
  }
}
//additem

$stmt2 = $conn->prepare("SELECT * FROM disposal WHERE store_id = ?");
$stmt2->bind_param("s", $store_id);
$stmt2->execute();
$disposal_info = $stmt2->get_result();

// 結果を配列に格納
$rows = array();
if ($disposal_info->num_rows > 0) {
    while ($row = $disposal_info->fetch_assoc()) {
        $rows[] = $row;
    }
}
$stmt2->close();

$stmt3 = $conn->prepare("SELECT store_name FROM store WHERE store_id = ?");
$stmt3->bind_param("s", $store_id);
$stmt3->execute();
$store_info = $stmt3->get_result();


if ($store_info->num_rows > 0) {
    $row2 = $store_info->fetch_assoc();
    $store_name = $row2['store_name'];
}

$stmt3->close();
$conn->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>OutSeaS管理システム</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../css/footer.css" />
  <link rel="stylesheet" href="../css/navbar.css" />
  <link rel="stylesheet" href="../css/food_disposal.css" />
  <link rel="stylesheet" href="../css/getfood_disposal.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
  <nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand" href="../w_aboutUs/about.html">OutSeaS</a>
      </div>
      <ul class="nav navbar-nav">
        <li><a href="../w_Landing_Page/landing.html">ホーム</a></li>
        <li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">ストア用<span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="../w_Store_Inventory/StoreInvnt.html">ストアフロント</a></li>
            <li><a href="../w_disposal_page/registerDisposal.html">廃棄登録</a></li>
            <li><a href="../w_store_page/storeInfo.html">ストア情報</a></li>
          </ul>
        </li>
        <li><a href="../w_disposal_page/deliveryDisposal.html">廃棄情報</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
      <li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">店舗設定<span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="show&change_storeinfo.php">情報変更</a></li>
          </ul>
        </li>
        <li id="user">
          <a href="../function/logout.php"><span class="glyphicon glyphicon-log-in"></span> ログアウト</a>
        </li>
      </ul>
    </div>
  </nav>
  
  <div class="container" style="margin-top: 70px;">
    <div class="text-center">
      <h1 class="mx-auto"><?php echo $store_name ?></h1>
      <p id="message" style="font-style: italic; color: green;">
        <?php if (isset($message) && $message != null) {
          echo $message;
        } ?>
      </p>
    <script src="../js/getfood_disposal.js"></script>
    </div>
    
    <div class="row">
      <div class="col-sm-2">
        <div id="dashboard">
          <h3>ダッシュボード</h3>
          <div class="btn-group-vertical custom-btn-group">
            <button onclick="hideInventory()" type="button" class="btn btn-lg w-100 dash-btn" id="addBtn">
              アイテム登録
            </button>
            <button type="button" class="btn btn-lg w-100 dash-btn" id="">
              発送問い合わせ
            </button>
          </div>
        </div>
      </div>
      
      <div class="col-sm-10">
        <div id="addItem">
          <div class="row">
            <h3>アイテム追加フォーム</h3>
          </div>
          <div class="row">
            <form id="itemForm" class="text-left" method="post" action="getfood_disposal.php">
              <div class="col-sm-5">
                <div class="form-group">
                  <label for="itemInput">アイテム</label>
                  <input type="text" class="form-control" id="itemInput" name="itemInput" required />
                </div>
                <div class="form-group">
                  <label for="quantityInput">個数</label>
                  <input type="number" class="form-control" id="quantityInput" name="quantityInput" required />
                </div>
              </div>
              <div class="col-sm-5">
                <div class="form-group">
                  <label for="dateInput">日付</label>
                  <input type="date" class="form-control" id="dateInput" name="dateInput" required />
                </div>
                <div class="form-group">
                  <label for="statusInput"></label>
                  <input type="text" class="form-control" id="statusInput" name="statusInput" required />
                </div>
                <button type="submit" class="btn btn-success">追加</button>
                <a href="getfood_disposal.php" class="btn btn-success">戻る</a>
              </div>
            </form>
          </div>
        </div>
        
        <h3 id="h3">廃棄物一覧</h3>
        <table class="table-bordered table-hover" id="inventory">
          <thead>
            <tr>
              <th onclick="sortTable(0)">
                ストアID <span class="glyphicon glyphicon-sort"></span>
              </th>
              <th onclick="sortTable(1)">
                商品ID <span class="glyphicon glyphicon-sort"></span>
              </th>
              <th onclick="sortTable(2)">
                アイテム <span class="glyphicon glyphicon-sort"></span>
              </th>
              <th onclick="sortTable(3)">
                個数 <span class="glyphicon glyphicon-sort"></span>
              </th>
              <th onclick="sortTable(4)">
                日付 <span class="glyphicon glyphicon-sort"></span>
              </th>
              <th onclick="sortTable(5)">
                状況 <span class="glyphicon glyphicon-sort"></span>
              </th>
              <th id="deleteColumn"></th>
            </tr>
          </thead>
          <tbody id="inventoryBody">
            <!-- Table rows will be generated dynamically -->
            <?php foreach ($rows as $row) : ?>
              <tr>
                <td><?php echo $row['STORE_ID']; ?></td>
                <td><?php echo $row['DISPOSAL_ID']; ?></td>
                <td><?php echo $row['ITEM']; ?></td>
                <td><?php echo $row['QTY']; ?></td>
                <td><?php echo $row['DATE']; ?></td>
                <td><?php echo $row['STATUS']; ?></td>
                <td><button class="deleteButton" data-disposal-id="<?= $row['DISPOSAL_ID']; ?>">削除</button></td>
              <?php endforeach; ?>
            </tbody>
          </table>
          <script>
            function hideInventory() {
              var inventoryManagementElement = document.getElementById("h3");
              var inventoryTableElement = document.getElementById("inventory");
              var addButton = document.getElementById("addBtn");

              addButton.disabled = true;
              if (inventoryManagementElement) {
                inventoryManagementElement.style.display = "none";
              }

              if (inventoryTableElement) {
                inventoryTableElement.style.display = "none";
              }
            }
          </script>
        </div>
      </div>
    </div>

    <br />
    
    <footer class="custom-footer">
      <div class="container fixed-bottom">
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
    
    <script src="../js/food_disposal.js"></script>
    <script src ="../js/deleteItemFromDisposal.js"></script>
  </body>
</html>