document.addEventListener("DOMContentLoaded", function () {
    // DOM Elements
    const sidebarToggler = document.getElementById("sidebarToggler");
    const sidebar = document.getElementById("sidebar");
    const sidebarOverlay = document.getElementById("sidebarOverlay");
    const mobileSearchBtn = document.querySelector(".mobile-search-btn");
    const closeSearchBtn = document.querySelector(".close-search");
    const mobileSearchForm = document.querySelector(".mobile-search-form");
    const sidebarLinks = document.querySelectorAll(".sidebar a");
    const mainSidebarToggler = document.querySelector(".sidebar-toggler");

    // Sidebar Toggle Functionality
    function toggleSidebar() {
        sidebar.classList.toggle("active");
        sidebarOverlay.classList.toggle("active");
    }

    // Close Sidebar Function
    function closeSidebar() {
        sidebar.classList.remove("active");
        sidebarOverlay.classList.remove("active");
    }

    // Mobile Search Toggle
    function toggleMobileSearch(show) {
        mobileSearchForm.style.display = show ? "block" : "none";
    }

    // Event Listeners
    sidebarToggler.addEventListener("click", toggleSidebar);
    sidebarOverlay.addEventListener("click", closeSidebar);

    mobileSearchBtn.addEventListener("click", function () {
        toggleMobileSearch(true);
    });

    closeSearchBtn.addEventListener("click", function () {
        toggleMobileSearch(false);
    });

    // Close sidebar when clicking on sidebar links (mobile only)
    sidebarLinks.forEach((link) => {
        link.addEventListener("click", function () {
            if (window.innerWidth <= 768) {
                closeSidebar();
            }
        });
    });

    // Main sidebar toggler (for demonstration)
    mainSidebarToggler.addEventListener("click", function () {
        console.log("Sidebar toggled");
        // Implement your actual sidebar toggle logic here
    });
});
