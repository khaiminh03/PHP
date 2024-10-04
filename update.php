<?php
include_once("connect.php");

// Kiểm tra xem dữ liệu có được gửi hay không
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $ma = $_POST['txtMa'];
    $ten = $_POST['txtTen'];
    $oldMaLop = $_POST['oldMaLop']; // Lấy mã lớp cũ

    // Kiểm tra dữ liệu đầu vào
    if (empty($ma) || empty($ten)) {
        echo "Mã lớp và tên lớp không được để trống.";
        exit;
    }

    // Kiểm tra mã lớp mới có bị trùng lặp không
    $checkQuery = "SELECT * FROM lophoc WHERE maLop = ? AND maLop != ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ss", $ma, $oldMaLop);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Mã lớp đã tồn tại. Vui lòng chọn mã lớp khác.";
        exit;
    }

    // Cập nhật dữ liệu
    $updateQuery = "UPDATE lophoc SET maLop = ?, tenLop = ? WHERE maLop = ?";
    $stmt = $conn->prepare($updateQuery);
    if ($stmt === false) {
        echo "Lỗi trong quá trình chuẩn bị câu lệnh: " . $conn->error;
        exit;
    }

    // Thực hiện cập nhật
    $stmt->bind_param("sss", $ma, $ten, $oldMaLop);
    if ($stmt->execute()) {
        header("Location: lophoc.php"); // Điều hướng về trang danh sách lớp học
        exit();
    } else {
        echo "Error: " . $stmt->error; // In ra lỗi nếu có
    }

    $stmt->close();
} else {
    echo "Yêu cầu không hợp lệ.";
}

$conn->close();
