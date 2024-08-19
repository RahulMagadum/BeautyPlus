<?php
$servername = "localhost";
$username = "root";
$password = ""; // Replace with your database password
$dbname = "fullstack";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Registration
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['fname']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirm-password'])) {
    $fname = $_POST['fname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];

    // Check if passwords match
    if ($password === $confirm_password) {
        // Hash the password before storing it
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if the user already exists
        $sql_check = "SELECT * FROM user WHERE email='$email'";
        $result_check = $conn->query($sql_check);

        if ($result_check->num_rows == 0) {
            // Insert user into database
            $sql = "INSERT INTO user (fname, email, password) VALUES ('$fname', '$email', '$hashed_password')";

            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Registration successful! You can now log in.');</script>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "<script>alert('Email already exists! Please use a different email.');</script>";
        }
    } else {
        echo "<script>alert('Passwords do not match. Please try again.');</script>";
    }
}

// Login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email']) && isset($_POST['pass'])) {
    $email = $_POST['email'];
    $password = $_POST['pass'];

    // Retrieve user from database
    $sql = "SELECT * FROM user WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Redirect to another page after successful login
            header("Location: index.html");
        } else {
            echo "<script>alert('Incorrect password. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('Email not found. Please register first.');</script>";
    }
}

$conn->close();
?>
