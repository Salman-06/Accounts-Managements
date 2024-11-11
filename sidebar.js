// sidebar.js
document.addEventListener('DOMContentLoaded', function() {
    // Load the sidebar
    fetch('sidebar.html')
        .then(response => response.text())
        .then(data => {
            document.body.insertAdjacentHTML('afterbegin', data);
            initializeSidebar();
        });
});

function initializeSidebar() {
    // Toggle submenu
    document.querySelectorAll('[data-submenu]').forEach(item => {
        item.addEventListener('click', event => {
            const submenu = document.getElementById(event.currentTarget.dataset.submenu);
            submenu.classList.toggle('hidden');
            event.currentTarget.querySelector('.arrow').textContent = submenu.classList.contains('hidden') ? '▼' : '▲';
        });
    });

    // Toggle sidebar
    let sidebarOpen = true;
    document.getElementById('toggleSidebar').addEventListener('click', () => {
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('content');
        sidebarOpen = !sidebarOpen;
        
        if (sidebarOpen) {
            sidebar.classList.remove('w-16');
            sidebar.classList.add('w-64');
            content.classList.remove('ml-16');
            content.classList.add('ml-64');
            document.querySelectorAll('.sidebar-text').forEach(el => el.classList.remove('hidden'));
        } else {
            sidebar.classList.remove('w-64');
            sidebar.classList.add('w-16');
            content.classList.remove('ml-64');
            content.classList.add('ml-16');
            document.querySelectorAll('.sidebar-text').forEach(el => el.classList.add('hidden'));
        }
    });

    // Highlight current page
    const currentPage = window.location.pathname.split("/").pop();
    document.querySelectorAll('#sidebar a').forEach(link => {
        if (link.getAttribute('href') === currentPage) {
            link.parentElement.classList.add('bg-gray-700');
        }
    });
}