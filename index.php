<?php
// Đặt tên miền và địa chỉ API
$apiDomain = "https://www.1secmail.com/api/v1";

// Kiểm tra nếu có tên miền được gửi qua GET
if (isset($_GET['domain']) && isset($_GET['email'])) {
    $domain = htmlspecialchars($_GET['domain']);
    $email = htmlspecialchars($_GET['email']);

    // Tạo URL để lấy danh sách email
    //$url = "$apiDomain/mailbox?login=$email&domain=$domain";
    $url = "$apiDomain/?action=getMessages&login=$email&domain=$domain";

    // Gửi yêu cầu GET tới API
    $response = file_get_contents($url);
    $mails = json_decode($response, true);

    // Kiểm tra và hiển thị email
    if (is_array($mails) && count($mails) > 0) {
        echo "<h2>Danh sách Email:</h2>";
        echo "<ul>";
        foreach ($mails as $mail) {
            echo "<li>";
            echo "Tiêu đề: " . htmlspecialchars($mail['subject']) . "<br>";
            echo "Người gửi: " . htmlspecialchars($mail['from']) . "<br>";
            echo "Thời gian: " . htmlspecialchars($mail['date']) . "<br>";
            echo "<a href='?domain=$domain&email=$email&id=" . $mail['id'] . "'>Xem chi tiết</a>";
            echo "</li>";
        }
        echo "</ul>";
    } else {
        echo "Không có email nào.";
    }
}

// Nếu có id email được gửi qua GET
if (isset($_GET['id'])) {
    $id = htmlspecialchars($_GET['id']);
    $url = "$apiDomain/readMessage?login=$email&domain=$domain&id=$id";
    
    // Gửi yêu cầu GET để đọc email
    $response = file_get_contents($url);
    $message = json_decode($response, true);

    // Hiển thị chi tiết email
    if (isset($message['text'])) {
        echo "<h2>Chi tiết Email:</h2>";
        echo "<p>" . nl2br(htmlspecialchars($message['text'])) . "</p>";
    } else {
        echo "Không tìm thấy email.";
    }
}
?>

<!-- Form nhập thông tin -->
<form method="GET">
    <label for="email">Email:</label>
    <input type="text" id="email" name="email" required>
    <label for="domain">Miền:</label>
    <input type="text" id="domain" name="domain" required>
    <button type="submit">Xem Email</button>
</form>
