/*document.getElementById('portfolio-form').addEventListener('submit', function (e) {
    e.preventDefault(); // Prevent the form's default submission behavior
    
    const data = {
      name: document.getElementById('name').value,
      title: document.getElementById('title').value,
      about: document.getElementById('about').value,
      skills: getListItems('skills-list'),
      projects: getProjects(),
      education: {
        degree: document.getElementById('degree').value,
        institution: document.getElementById('institution').value,
        year: document.getElementById('year').value,
        courses: getListItems('courses-list')
      },
      email: document.getElementById('email').value,
      github: document.getElementById('github').value || null,  // Add null if empty
      linkedin: document.getElementById('linkedin').value || null // Add null if empty
    };

    fetch('save_portfolio.php', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/json'
      },
      body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
      if (result.success) {
          alert("Portfolio saved successfully!");
      } else {
          alert("Error saving portfolio: " + result.error); // Improved error message
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert("An error occurred while saving the portfolio. Please try again.");
    });
});*/

function getProjects() {
    const projectList = document.getElementById('projects-list').children;
    return Array.from(projectList).map(project => ({
        title: project.querySelector('.card-title').textContent,
        description: project.querySelector('p').textContent,
        technologies: Array.from(project.querySelectorAll('.flex span')).map(tech => tech.textContent)
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
            </div>`;
        document.getElementById('projects-list').appendChild(projectItem);

        const project = { title, description, technologies};
        let projects = document.getElementById('projects-hidden').value;
        let projectsArray = projects ? JSON.parse(projects) : [];
        projectsArray.push(project);
        document.getElementById('projects-hidden').value = JSON.stringify(projectsArray);

        console.log(document.getElementById('projects-hidden'));

        
        document.getElementById('project-title').value = '';
        document.getElementById('project-description').value = '';
        document.getElementById('project-technologies').value = '';
    }
}

function addCourse() {
    const courseInput = document.getElementById('new-course');
    const newCourse = courseInput.value.trim()
    if (newCourse) {
        addListItem('courses-list', newCourse, removeSkill);
        
        let courses = document.getElementById('courses-hidden').value;
        let coursesArray = courses ? JSON.parse(courses) : [];
        coursesArray.push(newCourse);
        document.getElementById('courses-hidden').value = JSON.stringify(coursesArray);

        console.log(document.getElementById('courses-hidden').value);


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
