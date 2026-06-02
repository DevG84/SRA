const themeToggle = document.getElementById('themeToggle');
    const html = document.documentElement;

    themeToggle.addEventListener('click', () => {
        const currentTheme = html.getAttribute('data-bs-theme');
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';
        html.setAttribute('data-bs-theme', newTheme);
        
        // Cambiar icono
        themeToggle.innerHTML = newTheme === 'light' 
            ? '<i class="bi bi-moon-stars"></i> Cambiar Modo' 
            : '<i class="bi bi-sun"></i> Cambiar Modo';
    });