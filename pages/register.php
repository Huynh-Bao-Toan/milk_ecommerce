<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký</title>
    <link rel="stylesheet" href="../css/register.css"> <!-- Đường dẫn đến file CSS -->
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
    $email = $_POST['email'];

    // Kiểm tra xem username đã tồn tại trong cơ sở dữ liệu chưa
    $check_query = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($check_query);

    // Kiểm tra xem email đã tồn tại trong cơ sở dữ liệu chưa
    $check_email_query = "SELECT * FROM users WHERE email = '$email'";
    $result_email = $conn->query($check_email_query);

    if ($result_email->num_rows > 0) {
        echo '<script>showToast("Email đã được sử dụng. Vui lòng chọn email khác.");</script>';
    } else {
        // Thêm người dùng mới vào cơ sở dữ liệu
        $insert_query = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";
        if ($conn->query($insert_query) === TRUE) {
            header("Location: login.php");
        } else {
            echo "Lỗi: " . $insert_query . "<br>" . $conn->error;
        }
    }
    

    $conn->close();
}
?>

<div class="register-container">
    <h2>Đăng ký</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="form-group">
            <label for="username">Tên đăng nhập:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Mật khẩu:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <button type="submit">Đăng ký</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const emailInput = document.getElementById('email');
        emailInput.addEventListener('blur', function() {
            const email = this.value;
            // Kiểm tra email đã tồn tại trong cơ sở dữ liệu bằng Ajax hoặc fetch API
            // Trong trường hợp này, chúng ta chỉ cần giả lập kiểm tra email
            if (email === 'toanhuynh@2382002.com') {
                showToast('Email đã được sử dụng. Vui lòng chọn email khác.');
            }
        });
    });

    function showToast(message) {
        const toast = document.createElement('div');
        toast.classList.add('toast');
        toast.innerText = message;
        document.body.appendChild(toast);
        setTimeout(function() {
            toast.remove();
        }, 3000); // Remove the toast after 3 seconds (adjust as needed)
    }
</script>

</body>
</html>
