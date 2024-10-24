<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";    
$dbname = "portfolio_db"; 

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); 
    exit();
}

// Get the logged-in user's ID
$userId = $_SESSION['user_id'];

$sql = "SELECT p1, p2, p3 FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($p1, $p2, $p3);
$stmt->fetch();
$stmt->close(); 


$portfolios = [];


function getPortfolioDetails($conn, $portfolioId) {

    if (is_null($portfolioId)) {
        return null;
    }

    $sql = "SELECT id, name, title FROM portfolios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $portfolioId);
    $stmt->execute();
    $stmt->bind_result($id, $name, $title);
    
    $portfolio = null; 
    if ($stmt->fetch()) {
        $portfolio = [
            'id' => $id,
            'name' => $name,
            'title' => $title,
        ];
    }
    $stmt->close(); 
    return $portfolio; 
}


if (!is_null($p1)) {
    $portfolios['p1'] = getPortfolioDetails($conn, $p1);
}
if (!is_null($p2)) {
    $portfolios['p2'] = getPortfolioDetails($conn, $p2);
}
if (!is_null($p3)) {
    $portfolios['p3'] = getPortfolioDetails($conn, $p3);
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="container">
        <h1>Your Portfolios</h1>
        <div class="portfolio-list">
            <?php foreach (['p1', 'p2', 'p3'] as $key): ?>
                <?php if (isset($portfolios[$key])): ?>
                    <?php $portfolio = $portfolios[$key]; ?>
                    <div class="portfolio-item">
                        <h2><?php echo htmlspecialchars($portfolio['name']); ?></h2>
                        <p><?php echo htmlspecialchars($portfolio['title']); ?></p>
                        <button class="btn" onclick="window.location.href='portfolio.php?id=<?php echo $portfolio['id']; ?>'">View Portfolio</button>
                    </div>
                <?php else: ?>
                    <div class="portfolio-item">
                        <h2>Portfolio <?php echo strtoupper($key); ?></h2>
                        <p>No portfolio created yet.</p>
                        <button class="btn" onclick="window.location.href='/portfolio-builder/'">Create Portfolio</button>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <div class="logout-section">
            <form action="logout.php" method="post">
                <button type="submit" class="btn logout-btn">Logout</button>
            </form>
    </div>
    </div>

    
</body>
</html>
