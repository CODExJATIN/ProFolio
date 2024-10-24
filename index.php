<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php"); 
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Portfolio Builder</title>
  <link rel="stylesheet" href="style.css">
  <script>

function addEducationData() { 

    
    const educationData = createEducationObject();

    
    document.getElementById('education-hidden').value = JSON.stringify(educationData);

    console.log(document.getElementById('education-hidden').value);
    this.submit();
}

  function getProjects() {
    const projectList = document.getElementById('projects-list').children;
    return Array.from(projectList).map(project => ({
        title: project.querySelector('.card-title').textContent,
        description: project.querySelector('p').textContent,
        technologies: Array.from(project.querySelectorAll('.flex span')).map(tech => tech.textContent),
        link: project.querySelector('a').textContent
    }));
}

function getListItems(listId) {
    const list = document.getElementById(listId);
    return Array.from(list.children).map(item => item.querySelector('span.skill-box').textContent); // Fetch only the skill text
}

function addSkill() {
    const skillInput = document.getElementById('new-skill');
    const newSkill = skillInput.value.trim();
    if (newSkill) {
        addListItem('skills-list', newSkill, removeSkill);
        
        let skills = document.getElementById('skills-hidden').value;
        let skillsArray = skills ? JSON.parse(skills) : [];
        skillsArray.push(newSkill);
        document.getElementById('skills-hidden').value = JSON.stringify(skillsArray);

        console.log(document.getElementById('skills-hidden').value)

        skillInput.value = '';
    }
}

function removeSkill(e) {
    const item = e.target.closest('div');
    const text = item.querySelector('span.skill-box').textContent;

    
    let skills = document.getElementById('skills-hidden').value;
    let skillsArray = skills ? JSON.parse(skills) : [];
    skillsArray = skillsArray.filter(skill => skill !== text);
    document.getElementById('skills-hidden').value = JSON.stringify(skillsArray);

    
    item.remove();
}

function addProject() {
    const title = document.getElementById('project-title').value.trim();
    const description = document.getElementById('project-description').value.trim();
    const technologies = document.getElementById('project-technologies').value.split(',').map(tech => tech.trim());
    const link = document.getElementById('project-link').value.trim() || '';
    
    if (title && description && technologies.length) {
        const projectItem = document.createElement('div');
        projectItem.classList.add('card');
        projectItem.innerHTML = `
            <div class="card-header">
                <h3 class="card-title">${title}</h3>
                <button type="button" class="button small" onclick="this.parentElement.parentElement.remove()">Remove</button>
            </div>
            <div class="card-content">
                <p>${description}</p>
                <div class="flex gap-2">
                    ${technologies.map(tech => `<span class="bg-pink-100 text-pink-800 px-2 py-1 rounded">${tech}</span>`).join('')}
                </div>

                <a>${link}</a>
            </div>`;
        document.getElementById('projects-list').appendChild(projectItem);

        const project = { title, description, technologies,link};
        let projects = document.getElementById('projects-hidden').value;
        let projectsArray = projects ? JSON.parse(projects) : [];
        projectsArray.push(project);
        document.getElementById('projects-hidden').value = JSON.stringify(projectsArray);

        console.log(document.getElementById('projects-hidden'));

        
        document.getElementById('project-title').value = '';
        document.getElementById('project-description').value = '';
        document.getElementById('project-technologies').value = '';
        document.getElementById('project-link').value = '';
    }
}

function createEducationObject() {
    const degree = document.getElementById('degree').value.trim();
    const institution = document.getElementById('institution').value.trim();
    const year = document.getElementById('year').value.trim();
    const courses = [];

   
    const coursesList = document.getElementById('courses-list').children;
    Array.from(coursesList).forEach(courseItem => {
        courses.push(courseItem.querySelector('span').textContent);
    });

    return {
        degree,
        institution,
        year,
        courses
    };
}

function addCourse() {
    const courseInput = document.getElementById('new-course');
    const newCourse = courseInput.value.trim()
    if (newCourse) {
        addListItem('courses-list', newCourse, removeSkill);
        
        let educationData = JSON.parse(document.getElementById('education-hidden').value);
        educationData.courses.push(newCourse);
        document.getElementById('education-hidden').value = JSON.stringify(educationData);


        //console.log(document.getElementById('courses-hidden').value);


        courseInput.value = '';

    }
}

function addListItem(listId, text, removeHandler) {
    const list = document.getElementById(listId);
    const item = document.createElement('div');
    item.classList.add('flex', 'items-center', 'bg-purple-100', 'text-purple-800', 'px-2', 'py-1', 'rounded');
    item.innerHTML = `<span class='skill-box'>${text}</span> <button type="button" class="remove-btn" onclick="this.parentElement.remove()">X</button>`;
    list.appendChild(item);
}



  </script>
</head>
<body>

  <form id="portfolio-form"  action="save_portfolio.php" method="POST"  class="space-y-8">
    <!-- Personal Information -->
    <div class="card">
      <div class="card-header">
        <h2 class="card-title">Personal Information</h2>
      </div>
      <div class="card-content">
        
          <input type="text" id="name" name="name" required class="input" placeholder="Name" />
        

          <input type="text" id="title" name="title" required class="input" placeholder="Title" />
        
          <textarea id="about" name="about" required class="textarea" placeholder="About"></textarea>
        </div>
        </div>
      </div>
    </div>

    <!-- Skills -->
    <div class="card">
      <div class="card-header">
        <h2 class="card-title">Skills</h2>
      </div>
      <div class="card-content">
        <div class="flex space-x-2 mb-4">
          <input type="text" id="new-skill" placeholder="Add a skill" class="input" />
          <button type="button" onclick="addSkill()" class="button"><i class="plus-icon">+</i></button>
        </div>
        <div id="skills-list" class="flex flex-wrap gap-2"></div>
      </div>
    </div>

    <!-- Projects -->
    <div class="card">
      <div class="card-header">
        <h2 class="card-title">Projects</h2>
      </div>
      <div class="card-content space-y-4">
        <input type="text" id="project-title" placeholder="Project Title" class="input" />
        <textarea id="project-description" placeholder="Project Description" class="textarea"></textarea>
        <input type="text" id="project-technologies" placeholder="Technologies (comma-separated)" class="input" />
        <input type="text" id="project-link" placeholder="GitHub/Live URL" class="input" />
        <button type="button" onclick="addProject()" class="button">Add Project</button>

        <div id="projects-list"></div>
      </div>
    </div>

    <!-- Education -->
    <div class="card">
      <div class="card-header">
        <h2 class="card-title">Education</h2>
      </div>
      <div class="card-content space-y-4">
        <input type="text" id="degree" placeholder="Degree" class="input" />
        <input type="text" id="institution" placeholder="Institution" class="input" />
        <input type="text" id="year" placeholder="Year" class="input" />
        <div class="flex space-x-2">
          <input type="text" id="new-course" placeholder="Add a course" class="input" />
          <button type="button" onclick="addCourse()" class="button"><i class="plus-icon">+</i></button>
        </div>
        <div id="courses-list" class="flex flex-wrap gap-2"></div>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <h2 class="card-title">Social Links:</h2>
      </div>
      <div class="card-content space-y-4">

          <input type="email" id="email" name="email" required class="input" placeholder="Email" />
        
         
          <input type="url" id="github" name="github" class="input" placeholder="GitHub URL" />
        
          
          <input type="url" id="linkedin" name="linkedin" class="input" placeholder="LinkedIn URL" />
       
      </div>
    </div>

    <input type="hidden" id="skills-hidden" name="skills" value="[]" />
    <input type="hidden" id="projects-hidden" name="projects" value="[]" />
    <input type="hidden" id="education-hidden" name="education" value='{"degree": "", "institution": "", "year": "", "courses": []}'>

    <!-- Submit Button -->
    <button type="submit" onclick="addEducationData()" class="button w-full">Create Portfolio</button>
  </form>

</body>
</html>
