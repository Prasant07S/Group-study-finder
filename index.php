<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group Study Finder - Home</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="index.php" class="logo">📚 Group Study Finder</a>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="#features">Features</a></li>
                <li><a href="#about">About</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li><a href="user/dashboard.php">Dashboard</a></li>
                    <li><a href="logout.php" class="btn-logout">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php" class="btn-register">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Find Your Perfect Study Group 📖</h1>
            <p>Connect with fellow students, form study groups, and ace your exams together!</p>
            <?php if(!isset($_SESSION['user_id'])): ?>
                <div class="hero-buttons">
                    <a href="register.php" class="btn btn-primary">Get Started</a>
                    <a href="login.php" class="btn btn-secondary">Login</a>
                </div>
            <?php else: ?>
                <a href="user/find-groups.php" class="btn btn-primary">Find Groups</a>
            <?php endif; ?>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features">
        <h2>Why Group Study Finder?</h2>
        <div class="feature-grid">
            <div class="feature-card">
                <div class="feature-icon">🔍</div>
                <h3>Find Groups</h3>
                <p>Search study groups by subject, semester, or topic.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">➕</div>
                <h3>Create Groups</h3>
                <p>Start your own study group and invite others to join.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📅</div>
                <h3>Schedule Sessions</h3>
                <p>Plan study sessions with date, time, and location.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">👤</div>
                <h3>Profile Management</h3>
                <p>Manage your profile and showcase your interests.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">⭐</div>
                <h3>Reviews & Ratings</h3>
                <p>Rate and review study groups and members.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🌐</div>
                <h3>Online & Offline</h3>
                <p>Support for both physical and online study sessions.</p>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about">
        <h2>About This Project</h2>
        <p>
            Group Study Finder is a web-based application developed as a BCA 4th Semester Project 
            at Lumbini City College, affiliated with Tribhuvan University. The system helps students 
            collaborate effectively by providing a centralized platform for forming and managing 
            study groups.
        </p>
        <p>
            Built using <strong>PHP, MySQL, HTML, CSS, and JavaScript</strong>, this project 
            demonstrates the practical application of web development concepts learned throughout 
            the BCA curriculum.
        </p>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2026 Group Study Finder | BCA Project | Lumbini City College</p>
        <p>Developed by Prasant Kafle & Ritunjay Pandey</p>
    </footer>
</body>
</html>
