<?php
include_once("connect.php");

// Kiểm tra xem maLop có được gửi qua GET hay không
if (isset($_GET['ma'])) {
    $maLop = $_GET['ma'];

    // Sử dụng prepared statement để tránh SQL Injection
    $sql = "DELETE FROM lophoc WHERE maLop = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $maLop); // Giả sử maLop là chuỗi

    if ($stmt->execute()) {
        echo "<h1>Xóa thành công</h1>";
    } else {
        echo "Lỗi xóa bản ghi: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "<h1>Không có mã lớp để xóa</h1>";
}

mysqli_close($conn);
header("Location:lophoc.php");
