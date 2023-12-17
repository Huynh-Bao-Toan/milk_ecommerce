<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập Admin</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>

<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root"; // Thay thế bằng thông tin cơ sở dữ liệu của bạn
    $password = "";
    $dbname = "asm_3_huynh_bao_toan_2051120173";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Kết nối đến cơ sở dữ liệu thất bại: " . $conn->connect_error);
    }

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admins WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $_SESSION['admin_username'] = $username;
        header("Location: dashboard.php"); // Chuyển hướng đến trang dashboard của admin
    } else {
        echo "Đăng nhập thất bại. Vui lòng kiểm tra lại thông tin đăng nhập.";
    }

    $conn->close();
}
?>

<div class="login-container">
    <h2>Đăng nhập Admin</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="form-group">
            <label for="username">Tên đăng nhập:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Mật khẩu:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Đăng nhập</button>
    </form>
</div>

</body>
</html>
