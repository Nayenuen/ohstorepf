<?php
include 'db.php'; // Include the database connection file

header('Content-Type: application/json'); // Set the content type to JSON

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["error" => "Invalid email format"]);
        exit();
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT * FROM suscripciones WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(["error" => "Email already subscribed"]);
    } else {
        // Insert email into the database
        $stmt = $conn->prepare("INSERT INTO suscripciones (email) VALUES (?)");
        $stmt->bind_param("s", $email);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Subscription successful"]);
        } else {
            echo json_encode(["error" => "Error subscribing: " . $conn->error]);
        }
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "Invalid request method"]);
}

$conn->close();
?>