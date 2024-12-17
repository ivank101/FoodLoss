<?php
session_start();

require_once dirname(__FILE__) . '/function/db_connection.php';

$conn = connection();

$message = '';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$store_id = isset($_SESSION['store_id']) ? $_SESSION['store_id'] : null;

// Query user information based on user_id
$user_id = $_SESSION['user_id'];

$userQuery = "SELECT USER_ID, USER_NAME FROM user WHERE USER_ID = ?";
$userStmt = $conn->prepare($userQuery);
$userStmt->bindParam(1, $user_id);
$userStmt->execute();
$userResult = $userStmt->fetch(PDO::FETCH_ASSOC);

// Query store and disposal information
$storeQuery = "SELECT s.STORE_ID, s.STORE_NAME, s.STORE_EMAIL, s.STORE_TEL, s.STORE_ADDRESS, d.DISPOSAL_ID, d.ITEM, d.QTY, d.DATE
                FROM store s
                LEFT JOIN disposal d ON s.STORE_ID = d.STORE_ID";
$storeStmt = $conn->prepare($storeQuery);
$storeStmt->execute();
$storeResult = $storeStmt->fetchAll(PDO::FETCH_ASSOC);

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="css/footer.css" />
    <link rel="stylesheet" href="css/navbar.css" />
    <link rel="stylesheet" href="css/storeInvnt.css" />
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
    <div class="bottom-right-section text-right">
        <a>
            <button class="order-btn" onclick="openConfirmationPopup()"><i class="fa fa-shopping-cart"></i>カート</button>
        </a>
        <!-- Rest of the code for the pop-up -->
    </div>


    </div>
    <div class="center-section">
        <?php
        $currentStoreID = null;
        foreach ($storeResult as $store) {
            if ($store['STORE_ID'] != $currentStoreID) {
                ?>
                <table class="table-bordered table-hover" id="inventory">
                    <h3>
                        Store Name:&ensp;
                        <?php echo $store['STORE_NAME']; ?>&ensp;

                        <!-- Detail button functionality starts here -->
                        <!-- Button -->
                        <button class="small-button" data-storeName="<?php echo $store['STORE_NAME']; ?>"
                            data-storeEmail="<?php echo $store['STORE_EMAIL']; ?>"
                            data-storeTel="<?php echo $store['STORE_TEL']; ?>"
                            data-storeAddress="<?php echo $store['STORE_ADDRESS']; ?>" onclick="openPopup()">詳細</button>



                    </h3>
                    <thead>
                        <tr>
                            <th onclick="sortTable(0)">商品名 <span class="glyphicon glyphicon-sort"></span></th>
                            <th onclick="sortTable(1)">賞味期限 <span class="glyphicon glyphicon-sort"></span></th>
                            <th onclick="sortTable(2)">数量 <span class="glyphicon glyphicon-sort"></span></th>
                            <th onclick="sortTable(3)"> 要求 <span class="glyphicon glyphicon-sort"></span></th>
                        </tr>
                    </thead>
                    <tbody id="inventoryBody">
                        <!-- Insert code here -->
                    </tbody>
                    <?php
                    $currentStoreID = $store['STORE_ID'];
            }

            if (!empty($store['ITEM'])) {
                ?>
                    <tr>
                        <td>
                            <?php echo $store['ITEM']; ?>
                        </td>
                        <td>
                            <?php echo $store['DATE']; ?>
                        </td>
                        <td id="qty_<?php echo $store['DISPOSAL_ID']; ?>"><?php echo $store['QTY']; ?></td>

                        <td>
                            <button class="request-button" data-disposalId="<?php echo $store['DISPOSAL_ID']; ?>"
                                data-item="<?php echo $store['ITEM']; ?>" data-store="<?php echo $store['STORE_NAME']; ?>"
                                onclick="openModal(<?php echo $store['DISPOSAL_ID']; ?>, '<?php echo $store['ITEM']; ?>','<?php echo $store['STORE_NAME']; ?>')">要求</button>
                        </td>


                    </tr>
                    <?php
            } else {
                ?>
                    <tr>
                        <td colspan="4">現在、廃棄物がないです!</td>
                    </tr>
                    <?php
            }
        }
        ?>
            <!-- Request Modal -->
            <div id="request-modal" class="modal">
                <div class="request-modal-content">
                    <span class="close" onclick="closeModal()">&times;</span>
                    <h2>Request Quantity</h2>
                    <input type="text" id="quantityInput" placeholder="Enter quantity">
                    <button id="submitRequestBtn" onclick="submitRequest()">要求</button>
                </div>
            </div>
            <!-- Order Confirmation Modal -->
            <div id="confirmation-modal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeConfirmationPopup()">&times;</span>
                    <h2>注文確認</h2>
                    <div id="requestedStores"></div>
                    <button id="confirmOrderBtn" onclick="confirmOrder()">確認</button>
                </div>
            </div>


            <!-- Info Modal -->
            <div id="info-Modal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closePopup()">&times;</span>
                    <h2 class="popup-title">Store Information</h2>
                    <h2>
                        Store Name :
                        <span id="storeName"></span>
                    </h2>
                    <p>
                        Store Email :
                        <span id="storeEmail"></span>
                    </p>
                    <p>
                        Store Telephone :
                        <span id="storeTel"></span>
                    </p>
                    <p>
                        Store Address :
                        <span id="storeAddress"></span>
                    </p>
                </div>
            </div>
            <!-- Logout -->
            <div id="logoutModal" class="modal logout-modal">
                <div class="logmodal-content">
                    <span class="close" onclick="closeLogoutModal()">&times;</span>
                    <h2>ログアウトを確認</h2>
                    <p>アカウントからログアウトしてもよろしいですか？</p>
                    <div class="button-container">
                        <a class="confLog" onclick="confirmLogout()">同意する</a>
                        <hr class="vertical-line">
                        <a class="Cancel" onclick="closeLogoutModal()">キャンセル</a>
                    </div>
                </div>


                <!-- Detail button functionality ends here -->
        </table>
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