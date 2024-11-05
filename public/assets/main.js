const toggleButton = document.getElementById('theme-toggle');

const savedTheme = localStorage.getItem('theme');
if (savedTheme) {
    document.documentElement.setAttribute('data-bs-theme', savedTheme);
}

function updateButtonText() {
    const currentTheme = document.documentElement.getAttribute('data-bs-theme');
    toggleButton.textContent = currentTheme === 'light' ? 'Dark Mode' : 'Light Mode';
}
updateButtonText();

toggleButton.addEventListener('click', () => {
    const currentTheme = document.documentElement.getAttribute('data-bs-theme');
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
    document.documentElement.setAttribute('data-bs-theme', newTheme);
    localStorage.setItem('theme', newTheme);
    updateButtonText();
});
