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

// Check if the store ID parameter is set
if (isset($_GET['id'])) {
    // Retrieve the store ID from the parameter
    $store_id = $_GET['id'];

    // Prepare and execute a SELECT query to retrieve store information
    $stmt = $conn->prepare("SELECT STORE_ID, STORE_NAME, STORE_EMAIL, STORE_TEL, STORE_ADDRESS FROM store WHERE STORE_ID = ?");
    $stmt->bind_param("i", $store_id);
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if a store with the given ID exists
    if ($result->num_rows > 0) {
        // Fetch the store information as an associative array
        $store_info = $result->fetch_assoc();
        ?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <title>Store Information</title>
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

                .store-info {
                    background-color: #fff;
                    border: 1px solid #ccc;
                    padding: 20px;
                    border-radius: 5px;
                }

                .store-info p {
                    margin-bottom: 5px;
                }
            </style>
        </head>

        <body>
            <div class="store-info">
                <h2>ストア情報</h2>
                <p><strong>ストア ID:</strong> <?php echo $store_info['STORE_ID']; ?></p>
                <p><strong>ストア名:</strong> <?php echo $store_info['STORE_NAME']; ?></p>
                <p><strong>ストアメール:</strong> <?php echo $store_info['STORE_EMAIL']; ?></p>
                <p><strong>ストア電話番号:</strong> <?php echo $store_info['STORE_TEL']; ?></p>
                <p><strong>ストア住所:</strong> <?php echo $store_info['STORE_ADDRESS']; ?></p>
                <button onclick="location.href='statusdisposalpage.php'" type="button" class="btn btn-primary">
                戻る
            </button>
            </div>

        </body>

        </html>
        <?php
    } else {
        echo "Store not found.";
    }

    $stmt->close();
} else {
    echo "Invalid store ID.";
}

// Close the database connection
$conn->close();
?>
