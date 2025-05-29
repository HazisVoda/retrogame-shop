<div class="sidebar">
        <div class ="top-bar">
            <div class="profile-link">
                <a href="../profile.php" class="profile-img">
                    <img src="../../<?= $adminImage?>" alt="Profile Picture">
                </a>
                <div class="profile-details">
                    <h4><?php echo htmlspecialchars($adminUsername); ?></h4>
                    <a href="../profile.php" class="profile-edit-link">
                        <h4>Edit</h4> <i class="ri-edit-box-fill"></i>
                    </a>
                </div>
            </div>
        </div>
        <ul class="sidebar-menu">
            <li>
                <a href="admin_home.php" class="sidebar-link active">
                    <i class="ri-dashboard-fill"></i> <h4>Dashboard</h4>
                </a>
            </li>
            <li>
                <a href="admin_users.php" class="sidebar-link">
                    <i class="ri-group-fill"></i> <h4>Users</h4>
                </a>
            </li>
            <li>
                <a href="admin_games.php" class="sidebar-link">
                    <i class="ri-store-2-line"></i> <h4>Games</h4>
                </a>
            </li>
            <li>
                <a href="admin_analytics.php" class="sidebar-link">
                    <i class="ri-bar-chart-box-line"></i> <h4>Analytics</h4>
                </a>
            </li>
            <li>
                <a href="../../logout.php" class="sidebar-link">
                    <i class="ri-logout-box-line"></i> <h4>Logout</h4>
                </a>
            </li>
        </ul>
    </div>