<style>
/* Basic resets */
body {
    background-color: #f8f9fa;
    min-height: 100vh;
    margin: 0;
    padding: 0;
}

/* <a> */
a,
a.btn.btn-info.btn-sm:focus,
a.btn.btn-info.btn-sm:hover {
    text-decoration: none !important;
    outline: none;
    color: #fff !important;
}

/* Navbar styling */
.navbar-brand {
    font-weight: bold;
}

.navbar {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1000;
    /* Ensures the navbar stays on top */
}

/* Profile pic in navbar */
.profile-pic {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    object-fit: cover;
}

/* SIDEBAR */
.sidebar {
    position: fixed;
    top: 56px;
    /* Place it below the navbar (which is ~56px tall) */
    left: -280px;
    /* Collapsed by default (off-canvas) */
    width: 280px;
    height: calc(100vh - 56px);
    background-color: #6f42c1;
    /* Purple shade */
    color: #fff;
    overflow-y: auto;
    transition: left 0.3s ease-in-out;
    z-index: 1050;
}

/* Sidebar links */
.sidebar .nav-link {
    color: #ddd;
    transition: background 0.3s ease;
}

.sidebar .nav-link.active {
    background-color: rgba(255, 255, 255, 0.2);
    color: #fff;
}

/* Show sidebar when toggled */
.sidebar.show {
    left: 0 !important;
}

/* CONTENT AREA */
.content {
    margin-top: 56px;
    /* Below the navbar */
    padding: 1rem;
    transition: margin-left 0.3s ease-in-out;
}

/* Desktop behavior (≥ 992px): pushing content when sidebar is toggled */
@media (min-width: 992px) {

    /* By default, sidebar is still off-canvas. We only show it when toggled. */
    .content.shifted {
        margin-left: 280px;
    }
}

/* DataTables filters (the second dropdown is hidden by default) */
#valueFilterContainer {
    display: none;
}

/* Dashboard Cards styling (for example content) */
.card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

/* Common styling for the login container */
.login-container {
    background-color: #fff;
    border-radius: 8px;
    padding: 2rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    margin: 2rem auto;
}

/* Desktop styles (min-width: 992px) */
@media (min-width: 992px) {
    .login-container {
        max-width: 400px;
    }
}

/* Mobile styles (max-width: 991px) */
@media (max-width: 991px) {
    .login-container {
        width: 90%;
        padding: 1.5rem;
        border-radius: 4px;
        box-shadow: none;
        margin: 1rem auto;
    }

    .login-container h2 {
        font-size: 1.5rem;
    }
}
</style>