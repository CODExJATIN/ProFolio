document.querySelector('.contact-form').addEventListener('submit', function(event) {
    event.preventDefault();
    alert('Message sent! We will get back to you soon.');
  });
  
  document.getElementById('hamburger').addEventListener('click', function() {
    const mobileMenu = document.getElementById('mobile-menu');
    mobileMenu.classList.toggle('active');
  });
  
  document.getElementById('close-menu').addEventListener('click', function() {
    const mobileMenu = document.getElementById('mobile-menu');
    mobileMenu.classList.remove('active');
  });