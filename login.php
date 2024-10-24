<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";    
$dbname = "portfolio_db"; 

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle signup
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); 

    // Insert the user into the database
    $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
    
    if ($conn->query($sql) === TRUE) {
        // Optionally, you can redirect the user after signup
        header("Location: login.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // Retrieve the user from the database
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            // Redirect to a different page after successful login
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with that email.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Signup</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="container">
        <div class="tab-navigation">
            <button class="tab-button active" onclick="openTab('login')">Login</button>
            <button class="tab-button" onclick="openTab('signup')">Signup</button>
        </div>

        <div id="login" class="tab-content active">
            <form id="loginForm" method="POST">
                <h2>Login</h2>
                <label for="loginEmail">Email</label>
                <input type="email" id="loginEmail" name="email" required>

                <label for="loginPassword">Password</label>
                <input type="password" id="loginPassword" name="password" required>

                <button class="btn" type="submit" name="login">Login</button>
            </form>
        </div>

        <div id="signup" class="tab-content">
            <form id="signupForm" method="POST">
                <h2>Signup</h2>
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>

                <label for="signupEmail">Email</label>
                <input type="email" id="signupEmail" name="email" required>

                <label for="signupPassword">Password</label>
                <input type="password" id="signupPassword" name="password" required>

                <button class="btn" type="submit" name="signup">Signup</button>
            </form>
        </div>
    </div>

    <script>
        function openTab(tabName) {
    var i, tabcontent, tabbuttons;
    tabcontent = document.getElementsByClassName("tab-content");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    tabbuttons = document.getElementsByClassName("tab-button");
    for (i = 0; i < tabbuttons.length; i++) {
        tabbuttons[i].classList.remove("active");
    }

    document.getElementById(tabName).style.display = "block";
    document.querySelector(`button[onclick="openTab('${tabName}')"]`).classList.add("active");
}
    </script>
</body>
</html>
