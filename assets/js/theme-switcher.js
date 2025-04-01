// Theme switcher functionality
document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('theme-toggle');
    const body = document.body;
    
    // Check for saved theme preference
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
        document.documentElement.setAttribute('data-theme', savedTheme);
        themeToggle.checked = savedTheme === 'dark';
        body.classList.toggle('dark-mode', savedTheme === 'dark');
    }

    // Theme toggle event listener
    themeToggle.addEventListener('change', function() {
        const newTheme = this.checked ? 'dark' : 'light';
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        body.classList.toggle('dark-mode', this.checked);
        
        // Update navbar style
        const navbar = document.querySelector('.navbar');
        if (navbar) {
            navbar.style.backgroundColor = this.checked ? 'var(--nav-bg)' : '#f8f9fa';
        }
    });
});