<?php
// Assuming you have already established a database connection
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

// Check if the user ID parameter is set
if (isset($_GET['id'])) {
    // Retrieve the user ID from the parameter
    $user_id = $_GET['id'];

    // Prepare and execute a SELECT query to retrieve user information
    $stmt = $conn->prepare("SELECT USER_ID, USER_NAME, USER_EMAIL, USER_TEL, USER_ADDRESS FROM user WHERE USER_ID = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if a user with the given ID exists
    if ($result->num_rows > 0) {
        // Fetch the user information as an associative array
        $user_info = $result->fetch_assoc();
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../css/footer.css" />
    <link rel="stylesheet" href="../css/navbar.css" />
    <link rel="stylesheet" href="../css/storeInvnt.css" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

        <head>
            <title>User Information</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f8f8f8;
                    margin: 20px;
                }

                h2 {
                    color: #333;
                }

                p {
                    margin: 10px 0;
                }

                .user-info {
                    background-color: #fff;
                    border: 1px solid #ccc;
                    padding: 20px;
                    border-radius: 5px;
                }

                .user-info p {
                    margin-bottom: 5px;
                }
            </style>
        </head>

        <body>
            <div class="user-info">
                <h2>ユーザー情報</h2>
                <p><strong>ユーザー ID:</strong> <?php echo $user_info['USER_ID']; ?></p>
                <p><strong>ユーザー名:</strong> <?php echo $user_info['USER_NAME']; ?></p>
                <p><strong>ユーザーメール:</strong> <?php echo $user_info['USER_EMAIL']; ?></p>
                <p><strong>ユーザー電話番号:</strong> <?php echo $user_info['USER_TEL']; ?></p>
                <p><strong>ユーザー住所:</strong> <?php echo $user_info['USER_ADDRESS']; ?></p>
                <button onclick="location.href='orderstatus.php'" type="button" class="btn btn-primary">
                    戻る
                </button>
            </div>

        </body>

        </html>
        <?php
    } else {
        echo "User not found.";
    }

    $stmt->close();
} else {
    echo "Invalid user ID.";
}

// Close the database connection
$conn->close();
?>
