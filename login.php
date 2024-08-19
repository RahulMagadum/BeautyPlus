<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get email and password from POST request
    $email = $_POST['email']; // Ensure this matches the name attribute in the HTML form
    $password = $_POST['pass'];

    // Prepare SQL statement to select hashed password
    $stmt = $conn->prepare("SELECT password FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            echo "Login successful!";
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "No account found with that email!";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
