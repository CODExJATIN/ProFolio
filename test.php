<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Sample JSON data (similar to what you're fetching from the database as LONGTEXT)
$sample_projects = "[{\"title\":\"BEAST HUNTERS\",\"description\":\"Beast Hunters is an engaging role-playing game (RPG) that immerses players in a world filled with mystical creatures and epic battles. Face off against slimes, fanged beasts, and a formidable dragon to save a town held captive by a draconic threat.\",\"technologies\":[\"HTML\",\"CSS\",\"JS\"]},{\"title\":\"SparkUp\",\"description\":\"SparkUp is a comprehensive platform designed to bridge the gap between startups and investors, fostering meaningful connections and facilitating seamless fundraising processes. Our platform offers innovative features like intro video reels, integrated meeting systems, and direct business proposals, all aimed at revolutionizing the startup funding ecosystem.\",\"technologies\":[\"React\",\"Tailwind\",\"Material UI\",\"Firebase\"]}]";

// Simulate fetching data (in this case, we already have the JSON string above)
echo "<h3>Original JSON Data:</h3>";
echo "<pre>" . $sample_projects . "</pre>"; // Show the JSON string

// Decode JSON into a PHP array
$projects = json_decode($sample_projects, true);

// Check for any errors during decoding
if (json_last_error() !== JSON_ERROR_NONE) {
    echo "JSON decode error: " . json_last_error_msg();
} else {
    // Successfully decoded, now output the array
    echo "<h3>Decoded PHP Array:</h3>";
    echo "<pre>";
    print_r($projects); // Display the decoded array
    echo "</pre>";

    // Example: Loop through the decoded projects and display them
    echo "<h3>Formatted Output:</h3>";
    foreach ($projects as $project) {
        echo "<strong>Title:</strong> " . htmlspecialchars($project['title']) . "<br>";
        echo "<strong>Description:</strong> " . htmlspecialchars($project['description']) . "<br>";
        echo "<strong>Technologies:</strong> " . implode(', ', $project['technologies']) . "<br><br>";
    }
}



$servername = "localhost";
$username = "root"; // adjust with your DB username
$password = "";     // adjust with your DB password
$dbname = "portfolio_db"; // adjust with your DB name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch data
$sql = "SELECT * FROM portfolios WHERE id=10"; 
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch associative array from the result
    $data = $result->fetch_assoc(); 

    // Decode the JSON fields
    $skills = json_decode($data['skills'], true);
    $projects = json_decode($data['projects'], true);
    $education = json_decode($data['education'], true);

    if (is_string($skills)) {
        $skills = json_decode($skills, true); // Second decode if needed
    }


    // Check for JSON errors
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo 'Error decoding JSON: ' . json_last_error_msg();
        exit;
    }

    echo "<pre>";
    var_dump($skills); // Check the structure of $skills
    echo "</pre>";

    // Ensure $skills is an array before looping
    if (is_array($skills)) {
        // Display Skills in <div class="skill-card">
        echo "<h3>Skills:</h3><div class='skills-container'>";
        foreach ($skills as $skill) {
            echo "<div class='skill-card'>" . htmlspecialchars($skill) . "</div>";
        }
        echo "</div>";
    } else {
        echo "Skills data is not in the correct format.";
        echo "<pre>";
        var_dump($skills); // Check the structure of $skills
        echo "</pre>";
    }


    // Display fetched and decoded data
    echo "<h3>Skills:</h3><pre>";
    print_r($skills);
    echo "</pre>";

    echo "<h3>Skills:</h3><div class='skills-container'>";
    foreach ($skills as $skill) {
        echo "<div class='skill-card'>" . htmlspecialchars($skill) . "</div>";
    }
    echo "</div>";


    

    echo "<h3>Projects:</h3><pre>";
    print_r($projects);
    echo "</pre>";

    echo "<h3>Education:</h3><pre>";
    print_r($education);
    echo "</pre>";
} else {
    echo "No data found.";
}

$conn->close(); // Close connection

?>
