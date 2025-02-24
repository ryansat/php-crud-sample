<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Database connection
function getDatabaseConnection() {
    $host = $_ENV['DB_HOST'];
    $dbname = $_ENV['DB_NAME'];
    $username = $_ENV['DB_USER'];
    $password = $_ENV['DB_PASS'];

    try {
        $connection = new PDO(
            "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
            $username,
            $password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
        return $connection;
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

// Fetch user data or other necessary data
function fetchUserData($userId) {
    $db = getDatabaseConnection();
    $stmt = $db->prepare("SELECT email FROM Users WHERE id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetch();
}

$userData = fetchUserData($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - PHP CRUD Sample</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #f5f5f5;
            display: grid;
            grid-template-columns: 250px 1fr;
            grid-template-rows: 60px 1fr;
            grid-template-areas:
                "sidebar header"
                "sidebar main";
            height: 100vh;
        }

        .sidebar {
            grid-area: sidebar;
            background-color: #2c3e50;
            color: white;
            padding: 1rem;
        }

        .sidebar-header {
            padding: 1rem 0;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 1rem;
        }

        .nav-menu {
            list-style: none;
        }

        .nav-item {
            margin-bottom: 0.5rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.2s;
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .nav-link i {
            margin-right: 0.75rem;
            width: 20px;
        }

        .header {
            grid-area: header;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: #007bff;
            color: white;
            display: grid;
            place-items: center;
            font-weight: bold;
        }

        .main-content {
            grid-area: main;
            padding: 2rem;
        }

        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .card-title {
            font-size: 1.1rem;
            color: #333;
            font-weight: 500;
        }

        .logout-btn {
            padding: 0.5rem 1rem;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .logout-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>PHP CRUD Sample</h2>
        </div>
        <nav>
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link">
                        <i class="fas fa-home"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="contacts.php" class="nav-link">
                        <i class="fas fa-address-book"></i>
                        Contacts
                    </a>
                </li>
                <li class="nav-item">
                    <a href="profile.php" class="nav-link">
                        <i class="fas fa-user"></i>
                        Profile
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <header class="header">
        <h1>Dashboard</h1>
        <div class="user-info">
            <div class="user-avatar">
                <?php echo strtoupper(substr($userData['email'] ?? 'U', 0, 1)); ?>
            </div>
            <span><?php echo htmlspecialchars($userData['email'] ?? ''); ?></span>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </header>

    <main class="main-content">
        <div class="dashboard-cards">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Total Contacts</h3>
                    <i class="fas fa-address-book"></i>
                </div>
                <p>0</p>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recent Activities</h3>
                    <i class="fas fa-clock"></i>
                </div>
                <p>No recent activities</p>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Quick Actions</h3>
                    <i class="fas fa-bolt"></i>
                </div>
                <p>Add new contact</p>
            </div>
        </div>
    </main>
</body>
</html>