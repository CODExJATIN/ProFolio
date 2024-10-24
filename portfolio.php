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
    $id = 30;
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
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo htmlspecialchars($data['name']); ?>'s Portfolio</title>
    <link rel="stylesheet" href="portfolio.css">
    <style>
        @media (max-width: 768px) {
            .projects-grid {
      grid-template-columns: 1fr;
    }
        }
    </style>
</head>
<body>
<div class="page-wrapper">
    <!-- Header Section -->
    <header class="header">
        <div class="container container-head">
            
          <div class="header-left">
            
      
          <a href="/" class="site-title"><?php echo htmlspecialchars($data['name']); ?></a>
            <nav class="main-nav" id="nav-menu">
              <a href="#about" class="nav-link">About</a>
              <a href="#skills" class="nav-link">Skills</a>
              <a href="#projects" class="nav-link">Projects</a>
              <a href="#education" class="nav-link">Education</a>
              <a href="#contact" class="nav-link">Contact</a>
            </nav>
          </div>
          <div class="header-right">
          <button class="download-btn" onclick="window.location.href='resume.php?id=<?php echo $id; ?>'">Download CV</button>
            <nav class="social-links">
                <?php if (!empty($github)) { ?>
                    <a href="<?php echo htmlspecialchars($github); ?>" class="social-link" target="_blank">
                        <img src="https://res.cloudinary.com/ddamnzrvc/image/upload/v1727969362/hdgqjxfv5efkglfgjevy.png" alt="GitHub" class="social-icon">
                    </a>
                <?php } ?>

                <?php if (!empty($linkedin)) { ?>
                    <a href="<?php echo htmlspecialchars($linkedin); ?>" class="social-link" target="_blank">
                        <img src="https://res.cloudinary.com/ddamnzrvc/image/upload/v1727969362/zsy1sbxcgbrht3hdblwa.png" alt="LinkedIn" class="social-icon">
                    </a>
                <?php } ?>

                <?php if (!empty($email)) { ?>
                    <a href="mailto:<?php echo htmlspecialchars($email); ?>" class="social-link">
                        <img src="https://res.cloudinary.com/ddamnzrvc/image/upload/v1727969363/iok3xmw7oewiinuq4oqv.png" alt="Email" class="social-icon">
                    </a>
                <?php } ?>
            </nav>
          </div>

          <div class="mobile-header">
            
          <a href="/" class="site-title"><?php echo htmlspecialchars($data['name']); ?></a>
            <nav class="social-links">
                <?php if (!empty($github)) { ?>
                    <a href="<?php echo htmlspecialchars($github); ?>" class="social-link" target="_blank">
                        <img src="https://res.cloudinary.com/ddamnzrvc/image/upload/v1727969362/hdgqjxfv5efkglfgjevy.png" alt="GitHub" class="social-icon">
                    </a>
                <?php } ?>

                <?php if (!empty($linkedin)) { ?>
                    <a href="<?php echo htmlspecialchars($linkedin); ?>" class="social-link" target="_blank">
                        <img src="https://res.cloudinary.com/ddamnzrvc/image/upload/v1727969362/zsy1sbxcgbrht3hdblwa.png" alt="LinkedIn" class="social-icon">
                    </a>
                <?php } ?>

                <?php if (!empty($email)) { ?>
                    <a href="mailto:<?php echo htmlspecialchars($email); ?>" class="social-link">
                        <img src="https://res.cloudinary.com/ddamnzrvc/image/upload/v1727969363/iok3xmw7oewiinuq4oqv.png" alt="Email" class="social-icon">
                    </a>
                <?php } ?>
            </nav>
            <!-- Hamburger Icon for Mobile -->
            <div class="hamburger" id="hamburger">
              <span></span>
              <span></span>
              <span></span>
            </div>
          </div>
      
          <!-- Slider Menu for Mobile -->
          <div class="mobile-menu" id="mobile-menu">
            <nav class="mobile-nav">
                <button class="close-menu" id="close-menu">x</button>
              <a href="#about" class="nav-link">About</a>
              <a href="#skills" class="nav-link">Skills</a>
              <a href="#projects" class="nav-link">Projects</a>
              <a href="#education" class="nav-link">Education</a>
              <a href="#contact" class="nav-link">Contact</a>
              <button class="btn download-btn">Download CV</button>
            </nav>
          </div>
        </div>
      </header>
    

    <main class="main-content">
        <!-- About Section -->
        <section id="about" class="section about-section">
            <div class="container">
                <h1 class="section-title"><?php echo htmlspecialchars($data['name']); ?></h1>
                <h2 class="section-subtitle"><?php echo htmlspecialchars($data['title']); ?></h2>
                <p class="section-description"><?php echo htmlspecialchars($data['about']); ?></p>
                <button class="btn contact-btn">Get in Touch</button>
            </div>
        </section>

        <!-- Skills Section -->
        <section id="skills" class="section skills-section">
            <div class="container">
                <h2 class="section-title">Skills</h2>
                <div class="skills-grid">
                    <?php foreach ($skills as $skill): ?>
                        <div class="skill-card"><?php echo htmlspecialchars($skill); ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- Projects Section -->
        <section id="projects" class="section projects-section">
            <div class="container">
                <h2 class="section-title">Featured Projects</h2>
                <div class="projects-grid">
                    <?php foreach ($projects as $project): ?>
                        <div class="project-card">
                            <h3 class="project-title"><?php echo htmlspecialchars($project['title']); ?></h3>
                            <p class="project-description"><?php echo htmlspecialchars($project['description']); ?></p>
                            <div class="project-technologies">
                                <?php foreach ($project['technologies'] as $tech): ?>
                                    <span class="project-tech"><?php echo htmlspecialchars($tech); ?></span>
                                <?php endforeach; ?>
                            </div>
                            <button onclick="window.open('<?php echo htmlspecialchars($project['link']); ?>', '_blank');" class="btn project-btn">View Project</button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- Education Section -->
        <section id="education" class="section education-section">
            <div class="container">
                <h2 class="section-title">Education</h2>
    
                    <div class="education-card">
                        <h3 class="education-degree"><?php echo htmlspecialchars($education['degree']); ?></h3>
                        <p class="education-school"><?php echo htmlspecialchars($education['institution']) . ', ' . htmlspecialchars($education['year']); ?></p>
                        <p class="education-description">Graduated with honors. Relevant coursework included:</p>
                        <ul class="education-list">
                            <?php foreach ($courses as $course): ?>
                                <li><?php echo htmlspecialchars($course); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
             
            </div>
        </section>

        <!-- Contact Section -->
        <section id="contact" class="section contact-section">
        <div class="container-form">
          <h2 class="section-title">Get in Touch</h2>
          <form class="contact-form" action="send_mail.php?to_email=jjatinparmar54@gmail.com" method="POST">
            <h2>Contact Me</h2>
            <p>Fill out the form below to send me a message. I'll get back to you as soon as possible</p>
            <br><br>
            <div class="form-group">
              <label for="first-name">First Name</label>
              <input type="text" id="first-name" name="first_name" class="form-input" placeholder="John">
            </div>
            <div class="form-group">
              <label for="last-name">Last Name</label>
              <input type="text" id="last-name" name="last_name" class="form-input" placeholder="Doe">
            </div>
            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" id="email" name="email" class="form-input" placeholder="johndoe@example.com">
            </div>
            <div class="form-group">
              <label for="message">Message</label>
              <textarea id="message" name="message" class="form-textarea" placeholder="Your message here..."></textarea>
            </div>
            <button type="submit" class="btn submit-btn">Send Message</button>
          </form>
        </div>
      </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 <?php echo htmlspecialchars($data['name']); ?>. All Rights Reserved.</p>
        </div>
    </footer>
</div>

<script src="portfolio.js"></script>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
