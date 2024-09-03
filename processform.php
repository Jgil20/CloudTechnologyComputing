<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $fname = filter_var(trim($_POST["fname"]), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $lname = filter_var(trim($_POST["lname"]), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $company = filter_var(trim($_POST["company"]), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $phone = filter_var(trim($_POST["phone"]), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $message = filter_var(trim($_POST["message"]), FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Basic validation
    if (empty($fname) || empty($lname) || empty($company) || empty($email) || empty($phone) || empty($message)) {
        http_response_code(400);
        echo htmlspecialchars("Please fill out all required fields.");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo htmlspecialchars("Invalid email format.");
        exit;
    }

    // Prepare the email
    $to = "Admin@cloudtechnologycomputing.com"; // Replace with your email address
    $subject = "New Contact Request from $fname $lname";
    $body = "
    First Name: $fname\n
    Last Name: $lname\n
    Company: $company\n
    Email: $email\n
    Phone: $phone\n
    Message: $message
    ";
    $headers = "From: $email\r\nReply-To: $email";

    // Send the email
    if (mail($to, $subject, $body, $headers)) {
        http_response_code(200);
        header("Location: index.php");
        exit();
    } else {
        http_response_code(500);
        echo htmlspecialchars("There was a problem sending your message. Please try again later.");
    }
} else {
    http_response_code(403);
    echo htmlspecialchars("Invalid request method.");
}
?>
