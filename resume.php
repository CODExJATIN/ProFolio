<?php

$servername = "localhost";
$username = "root";
$password = "";    
$dbname = "portfolio_db"; 

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
}
else{
    $id = 13;
}

$sql = "SELECT * FROM portfolios WHERE id=$id"; 
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc(); 

    $skills = json_decode($data['skills'], true);
    if (is_string($skills)) {
        $skills = json_decode($skills, true); 
    }

    $projects = json_decode($data['projects'], true);
    if (is_string($projects)) {
        $projects = json_decode($projects, true); 
    }

    $education = json_decode($data['education'], true);
    if (is_string($education)) {
        $education = json_decode($education, true); 
    }

    $github = $data['github'];
    $linkedin = $data['linkedin'];
    $email = $data['email'];
    $courses = $education['courses'];

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo 'Error decoding JSON: ' . json_last_error_msg();
        exit;
    }
} else {
    echo "No data found.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ATS Friendly Resume - <?php echo htmlspecialchars($data['name']); ?></title>
    <link rel="stylesheet" href="resume.css">
</head>
<body>
    <div class="resume-wrapper">
        <header>
            <h1><?php echo htmlspecialchars($data['name']); ?></h1>
            <p><?php echo htmlspecialchars($data['title']); ?></p>
            <p>Email: <a href="mailto:<?php echo htmlspecialchars($data['email']); ?>"><?php echo htmlspecialchars($data['email']); ?></a></p>
            <p>GitHub: <a href="<?php echo htmlspecialchars($github); ?>" target="_blank"><?php echo htmlspecialchars($github); ?></a></p>
            <p>LinkedIn: <a href="<?php echo htmlspecialchars($linkedin); ?>" target="_blank"><?php echo htmlspecialchars($linkedin); ?></a></p>
        </header>
        <hr>
        <section class="skills">
            <h2>Skills</h2>
            <ul>
                <?php foreach ($skills as $skill): ?>
                    <li><?php echo htmlspecialchars($skill); ?></li>
                <?php endforeach; ?>
            </ul>
        </section>
        <hr>
        <section class="projects">
            <h2>Projects</h2>
            <?php foreach ($projects as $project): ?>
                <div class="project-item">
                    <h3><?php echo htmlspecialchars($project['title']); ?></h3>
                    <p><?php echo htmlspecialchars($project['description']); ?></p>
                    <p><strong>Technologies:</strong> <?php echo implode(', ', array_map('htmlspecialchars', $project['technologies'])); ?></p>
                </div>
            <?php endforeach; ?>
        </section>
        <hr>
        <section class="education">
            <h2>Education</h2>
            <p><?php echo htmlspecialchars($education['degree']); ?></p>
            <p><?php echo htmlspecialchars($education['institution']) . ', ' . htmlspecialchars($education['year']); ?></p>
            <p><strong>Relevant Coursework:</strong></p>
            <ul>
                <?php foreach ($courses as $course): ?>
                    <li><?php echo htmlspecialchars($course); ?></li>
                <?php endforeach; ?>
            </ul>
        </section>
        <hr>
        
    </div>

    <button onclick="window.print();" class="print-btn">Print PDF</button>

    <script>
        // Handle Print Button
        document.querySelector('.print-btn').addEventListener('click', () => {
            document.querySelector('.print-btn').style.display='none'
            window.print();
        });
    </script>
</body>
</html>
