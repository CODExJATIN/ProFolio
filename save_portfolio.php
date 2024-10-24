<?php

$servername = "localhost";
$username = "root"; 
$password = "";     
$dbname = "portfolio_db"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Collecting data from the form
$name = $_POST['name'];
$title = $_POST['title'];
$about = $_POST['about'];
$email = $_POST['email'];
$github = !empty($_POST['github']) ? $_POST['github'] : null;
$linkedin = !empty($_POST['linkedin']) ? $_POST['linkedin'] : null;

$skills = json_encode($_POST['skills']); 
$projects = json_encode($_POST['projects']); 
$education = json_encode($_POST['education']); 

// SQL to insert a new portfolio
$sql = "INSERT INTO portfolios (name, title, about, email, github, linkedin, skills, projects, education) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssssss", $name, $title, $about, $email, $github, $linkedin, $skills, $projects, $education);

if ($stmt->execute()) {
    $portfolio_id = $conn->insert_id; // Get the last inserted ID
} else {
    echo "Error creating portfolio: " . $stmt->error;
}

$stmt->close();

session_start(); 
$userId = $_SESSION['user_id']; 

$checkSql = "SELECT p1, p2, p3 FROM users WHERE id = ?";
$checkStmt = $conn->prepare($checkSql);
$checkStmt->bind_param("i", $userId);
$checkStmt->execute();
$checkStmt->bind_result($p1, $p2, $p3);
$checkStmt->fetch();
$checkStmt->close();

$updateSql = "UPDATE users SET ";
$fields = [];
$updateValue = null;


if (is_null($p1)) {
    $fields[] = "p1 = ?";
    $updateValue = $portfolio_id;
} elseif (is_null($p2)) {
    $fields[] = "p2 = ?";
    $updateValue = $portfolio_id;
} elseif (is_null($p3)) {
    $fields[] = "p3 = ?";
    $updateValue = $portfolio_id;
} else {

    echo "Maximum number of portfolios reached.";
    exit;
}


if (!empty($fields)) {
    $updateSql .= implode(", ", $fields) . " WHERE id = ?";
    $updateStmt = $conn->prepare($updateSql);

 
    if (count($fields) === 1) {
        $updateStmt->bind_param("ii", $updateValue, $userId);
    } else {
        $updateStmt->bind_param("ii", $updateValue, $userId);
    }

    if ($updateStmt->execute()) {
        $portfolio_link = "http://localhost:8080/portfolio-builder/portfolio.php?id=" . $portfolio_id;
    } else {
        echo "Error updating user: " . $updateStmt->error;
    }

    $updateStmt->close(); 
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio Created</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            width:60%;

            display:flex;
            align-items:center;
            justify-content:center;
            flex-direction:column;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        .portfolio-link {
            display: block;
            margin: 10px 0;
            padding: 10px;
            background-color: #000000;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            width: 150px;
            text-align:center;
        }
        .portfolio-link:hover {
            background-color:  #474747;
            
        }
        .copy-button {
            margin-top: 10px;
            padding: 10px;
            background-color:#1e3a8a;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .copy-button:hover {
            background-color: #041952;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Portfolio Created!</h1>
    <a href="<?php echo htmlspecialchars($portfolio_link); ?>" class="portfolio-link" target="_blank">View Portfolio</a>
    <button class="copy-button" onclick="copyLink()">Copy Portfolio Link</button>
    <p id="copyMessage" style="color: green; display: none;">Link copied to clipboard!</p>
</div>

<script>
function copyLink() {
    const portfolioLink = "<?php echo htmlspecialchars($portfolio_link); ?>";
    navigator.clipboard.writeText(portfolioLink).then(() => {
        const copyMessage = document.getElementById("copyMessage");
        copyMessage.style.display = "block"; // Show the message
        setTimeout(() => {
            copyMessage.style.display = "none"; // Hide after 2 seconds
        }, 2000);
    }).catch(err => {
        console.error('Could not copy text: ', err);
    });
}
</script>

</body>
</html>
