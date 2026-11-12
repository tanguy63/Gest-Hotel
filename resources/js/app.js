import './bootstrap';
import '@fontsource/poppins/400.css';
import '@fontsource/poppins/500.css';
import '@fontsource/poppins/600.css';
import '@fontsource/poppins/700.css';


/* Gestion du thème clair / sombre */
const html = document.documentElement;
const themeToggle = document.getElementById('theme-toggle');

const savedTheme = localStorage.getItem('gest-hostel-theme');

if (savedTheme === 'dark') {
    html.classList.add('dark');
} else if (savedTheme === 'light') {
    html.classList.remove('dark');
} else if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
    html.classList.add('dark');
}

themeToggle?.addEventListener('click', () => {
    const isDark = html.classList.toggle('dark');

    localStorage.setItem(
        'gest-hostel-theme',
        isDark ? 'dark' : 'light'
    );
});

/* Menu mobile */
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('sidebar-overlay');
const openSidebar = document.getElementById('open-sidebar');
const closeSidebar = document.getElementById('close-sidebar');

function showSidebar() {
    sidebar?.classList.remove('-translate-x-full');
    overlay?.classList.remove('hidden');
}

function hideSidebar() {
    sidebar?.classList.add('-translate-x-full');
    overlay?.classList.add('hidden');
}

openSidebar?.addEventListener('click', showSidebar);
closeSidebar?.addEventListener('click', hideSidebar);
overlay?.addEventListener('click', hideSidebar);
