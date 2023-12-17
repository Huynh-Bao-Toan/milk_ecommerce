<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="../css/login.css"> <!-- Đường dẫn đến file CSS -->
</head>
<body>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Kết nối đến cơ sở dữ liệu
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "asm_3_huynh_bao_toan_2051120173";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Kết nối đến cơ sở dữ liệu thất bại: " . $conn->connect_error);
    }

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Truy vấn kiểm tra thông tin đăng nhập
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Đăng nhập thành công
        session_start();
        $_SESSION['username'] = $username;
        header("Location: home.php"); // Chuyển hướng đến trang chính sau khi đăng nhập thành công
    } else {
        // Đăng nhập thất bại
        echo "Đăng nhập thất bại. Vui lòng kiểm tra lại thông tin đăng nhập.";
    }

    $conn->close();
}
?>

<div class="login-container">
    <h2>Đăng nhập</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="form-group">
            <label for="username">Tên đăng nhập:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Mật khẩu:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button class="form-group" type="submit">Đăng nhập</button>
    </form>
    <div >Bạn chưa có tài khoản? <a href="register.php" style="color: #176B87; ">Đăng ký ngay</a></div>
</div>

</body>
</html>
