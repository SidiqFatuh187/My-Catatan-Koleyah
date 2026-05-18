document.addEventListener('DOMContentLoaded', function() {
    window.toggleSidebar = function() {
        const sidebar = document.getElementById('sidebar')
        sidebar.classList.toggle('-translate-x-full')
    }
})