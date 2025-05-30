import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Sidebar toggle for header hamburger
window.toggleSidebar = function() {
    // For Bootstrap offcanvas (mobile)
    var offcanvas = document.getElementById('sidebarOffcanvas');
    if (window.innerWidth < 992) {
        if (offcanvas) {
            var bsOffcanvas = bootstrap.Offcanvas.getOrCreateInstance(offcanvas);
            bsOffcanvas.toggle();
        }
        return;
    }
    // For desktop: toggle sidebar width and main-content margin
    if (offcanvas) {
        if (offcanvas.style.width === '72px') {
            offcanvas.style.width = '250px';
            document.querySelector('.main-content').style.marginLeft = '250px';
        } else {
            offcanvas.style.width = '72px';
            document.querySelector('.main-content').style.marginLeft = '72px';
        }
    }
};
