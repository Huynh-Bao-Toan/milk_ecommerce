<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <!-- Link to your CSS file for styling -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,500;0,700;1,400;1,500;1,900&display=swap" rel="stylesheet">


    <link rel="stylesheet" href="../css/dashboard.css">


</head>
<body>

<?php
// Kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "asm_3_huynh_bao_toan_2051120173";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kết nối đến cơ sở dữ liệu thất bại: " . $conn->connect_error);
}

// Lấy danh sách sản phẩm từ cơ sở dữ liệu
$product_query = "SELECT * FROM products";
$product_result = $conn->query($product_query);

// Xử lý dữ liệu được chỉnh sửa từ form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['addProductName']) && isset($_POST['addProductPrice'])) {
        $addProductName = $_POST['addProductName'];
        $addProductPrice = $_POST['addProductPrice'];

        $insert_query = "INSERT INTO products (product_name, price) VALUES ('$addProductName', '$addProductPrice')";

        if ($conn->query($insert_query) === TRUE) {
            // Thực hiện cập nhật thành công, sau đó tải lại trang
            echo '<script>window.location.href = window.location.href;</script>';
        } else {
            echo "Lỗi khi thêm sản phẩm: " . $conn->error;
        }
    }
    else if (isset($_POST['editProductName']) && isset($_POST['editProductPrice'])) {
        $editedProductName = $_POST['editProductName'];
        $editedProductPrice = $_POST['editProductPrice'];
        $productId = $_POST['productId']; 

        $update_query = "UPDATE products SET product_name='$editedProductName', price='$editedProductPrice' WHERE id='$productId'";

        if ($conn->query($update_query) === TRUE) {
            // Thực hiện cập nhật thành công, sau đó tải lại trang
            echo '<script>window.location.href = window.location.href;</script>';
        } else {
            echo "Lỗi khi cập nhật sản phẩm: " . $conn->error;
        }
    } else if  (isset($_POST['deleteProductId'])) {
        $deleteProductId = $_POST['deleteProductId'];

        // Thực hiện xóa sản phẩm với ID tương ứng trong cơ sở dữ liệu
        $delete_query = "DELETE FROM products WHERE id='$deleteProductId'";

        if ($conn->query($delete_query) === TRUE) {
            // Xóa thành công, sau đó tải lại trang
            echo '<script>window.location.href = window.location.href;</script>';
        } else {
            echo "Lỗi khi xóa sản phẩm: " . $conn->error;
        }
    }
}
?>

<div class="admin-dashboard">
    <h2>Danh sách sản phẩm</h2>

    <div style="display: flex; justify-content: space-between">

        <!-- Search bar for products -->
        <input type="text" id="search" placeholder="Tìm kiếm sản phẩm">
        
        <!-- Button to add a new product -->
        <button id="addProductButton" class="add_btn">Thêm sản phẩm</button>
</div>

    <table>
        <thead>
            <tr>
                <th>Tên sản phẩm</th>
                <th>Giá sản phẩm</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($product_result->num_rows > 0) {
                while ($row = $product_result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['product_name'] . "</td>";
                    echo "<td>" . $row['price'] . "</td>";
                    echo "<td>";
                    echo "<button class='edit_btn' onclick='editProduct(" . $row['id'] . ", \"" . $row['product_name'] . "\", " . $row['price'] . ")'>Sửa</button>";
                    echo "<button class='delete_btn' onclick='deleteProduct(" . $row['id'] . ")'>Xóa</button>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>Không có sản phẩm nào.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Modal thêm sản phẩm -->
<div id="addProductModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('addProductModal')">&times;</span>
        <h2>Thêm sản phẩm</h2>
        <form id="addProductForm" method="post" >
            <div class="form-group">
                <label for="addProductName">Tên sản phẩm:</label>
                <input type="text" id="addProductName" name="addProductName" required>
            </div>
            <div class="form-group">
                <label for="addProductPrice">Giá sản phẩm:</label>
                <input type="text" id="addProductPrice" name="addProductPrice" required>
            </div>
            <button type="submit">Thêm</button>
        </form>
    </div>
</div>

<!-- Modal sửa sản phẩm -->
<div id="editProductModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('editProductModal')">&times;</span>
        <h2>Sửa sản phẩm</h2>
        <form id="editProductForm" method="post" >
            <!-- Thêm trường ẩn để lưu ID của sản phẩm -->
            <input type="hidden" id="productId" name="productId">
            <div class="form-group">
                <label for="editProductName">Tên sản phẩm:</label>
                <input type="text" id="editProductName" name="editProductName" required>
            </div>
            <div class="form-group">
                <label for="editProductPrice">Giá sản phẩm:</label>
                <input type="text" id="editProductPrice" name="editProductPrice" required>
            </div>
            <button type="submit">Lưu</button>
        </form>
    </div>
</div>

<!-- Modal xác nhận xóa sản phẩm -->
<div id="deleteProductModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('deleteProductModal')">&times;</span>
        <h2>Xác nhận xóa sản phẩm</h2>
        <p style="margin-bottom: 10px">Bạn có chắc chắn muốn xóa sản phẩm này?</p>
        <div style="display: flex">
            <form id="deleteForm" method="post">
                <!-- Thêm trường ẩn để lưu ID của sản phẩm -->
                <input type="hidden" id="deleteProductId" name="deleteProductId">
                <button type="submit">Xóa</button>
            </form>
            <button onclick="closeModal('deleteProductModal')" style="margin-left: 10px">Hủy</button>
        </div>
    </div>
</div>

<!-- Script for searching, editing, and deleting products -->
<script>
    // Script để tìm kiếm sản phẩm
    document.getElementById("search").addEventListener("keyup", function() {
        let input = this.value.toLowerCase();
        let rows = document.querySelectorAll("table tbody tr");

        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            if (text.indexOf(input) !== -1) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });

    // Hiển thị modal
    function openModal(modalId) {
        document.getElementById(modalId).style.display = "block";
    }

    // Ẩn modal
    function closeModal(modalId) {
        document.getElementById(modalId).style.display = "none";
    }

    // Sự kiện click vào nút Thêm sản phẩm
    document.getElementById("addProductButton").addEventListener("click", function() {
        openModal("addProductModal");
    });


    // Function để sửa sản phẩm
    function editProduct(productId, productName, productPrice) {
        openModal("editProductModal");

        // Hiển thị thông tin sản phẩm trong modal sửa sản phẩm
        document.getElementById("editProductName").value = productName;
        document.getElementById("editProductPrice").value = productPrice;

        // Cập nhật giá trị productId trong trường ẩn
        document.getElementById("productId").value = productId;
    }

    // Biến để lưu ID sản phẩm được chọn để xóa
    var selectedProductId = null;
    
    // Function để xóa sản phẩm
    function deleteProduct(productId) {
        openModal("deleteProductModal");

        // Lưu id của sản phẩm để xóa
        document.getElementById("deleteProductId").value = productId;
    }
</script>

</body>
</html>
