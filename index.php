<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap Layout</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom styles */
        @media (min-width: 768px) {
            #sidebar {
                height: 100vh;
                position: sticky;
                top: 0;
            }
            #bottom-nav {
                display: none;
            }
            
            /* Right panel transition */
            #right-panel {
                height: 100vh;
                position: sticky;
                top: 0;
                transition: transform 0.3s ease-in-out;
                transform: translateX(100%);
                position: fixed;
                right: 0;
                background: white;
                box-shadow: -2px 0 5px rgba(0,0,0,0.1);
                z-index: 1000;
            }

            #right-panel.show {
                transform: translateX(0);
            }

            /* Adjust main content when panel is shown */
            main {
                transition: padding-right 0.3s ease-in-out;
            }

            main.panel-visible {
                padding-right: 25%; /* Adjust based on panel width */
            }
        }
        
        @media (max-width: 767.98px) {
            #sidebar {
                display: none;
            }
            #bottom-nav {
                position: fixed;
                bottom: 0;
                width: 100%;
                z-index: 1000;
            }
            body {
                padding-bottom: 60px; /* Space for bottom nav */
            }
            #right-panel {
                position: fixed;
                top: 0;
                right: 0;
                bottom: 60px; /* Account for bottom nav */
                width: 100%;
                transform: translateX(100%);
                transition: transform 0.3s ease-in-out;
                background: white;
                z-index: 999;
            }
            #right-panel.show {
                transform: translateX(0);
            }
        }

        /* Card hover effect */
        #card-1 {
            cursor: pointer;
            transition: transform 0.2s;
        }

        #card-1:hover {
            transform: scale(1.02);
        }

        /* Close button for panel */
        .panel-close {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            padding: 5px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar for desktop -->
            <div id="sidebar" class="col-md-3 col-lg-2 bg-light p-3">
                <h3>Sidebar</h3>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Reports</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Settings</a>
                    </li>
                </ul>
            </div>

            <!-- Main content area -->
            <main class="col-md-9 col-lg-10 p-4">
                <h1>Main Content Area</h1>
                <p>This is your main content. It will take up the middle section of the page.</p>
                <div class="card" id="card-1">
                    <div class="card-body">
                        <h5 class="card-title">Card Title</h5>
                        <p class="card-text">Click this card to see more information in the right panel.</p>
                    </div>
                </div>
                <!-- Add your main content here -->
            </main>

            <!-- Right info panel -->
            <div id="right-panel" class="col-md-3 col-lg-2 p-3">
                <button type="button" class="btn-close panel-close" aria-label="Close"></button>
                <h3>Info Panel</h3>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Information</h5>
                        <p class="card-text">Additional information can be displayed here.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom navigation for mobile -->
    <nav id="bottom-nav" class="navbar navbar-expand navbar-light bg-light">
        <div class="container-fluid justify-content-around">
            <a class="nav-link active" href="#"><i class="bi bi-house"></i><br>Home</a>
            <a class="nav-link" href="#"><i class="bi bi-speedometer2"></i><br>Dashboard</a>
            <a class="nav-link" href="#"><i class="bi bi-file-text"></i><br>Reports</a>
            <a class="nav-link" href="#"><i class="bi bi-gear"></i><br>Settings</a>
        </div>
    </nav>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Bootstrap 5.3 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const card = document.getElementById('card-1');
            const rightPanel = document.getElementById('right-panel');
            const mainContent = document.querySelector('main');
            const closeButton = document.querySelector('.panel-close');

            function togglePanel() {
                rightPanel.classList.toggle('show');
                if (window.innerWidth >= 768) {
                    mainContent.classList.toggle('panel-visible');
                }
            }

            card.addEventListener('click', togglePanel);
            closeButton.addEventListener('click', togglePanel);

            // Close panel when clicking outside (optional)
            document.addEventListener('click', function(event) {
                const isClickInside = rightPanel.contains(event.target) || card.contains(event.target);
                if (!isClickInside && rightPanel.classList.contains('show')) {
                    togglePanel();
                }
            });
        });
    </script>
</body>
</html>
