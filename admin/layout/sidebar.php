<?php
// Current page determine karne ke liye
$current_page = basename($_SERVER['PHP_SELF']);
?>

<style>
    :root {
        --sidebar-width: 280px;
        --sidebar-collapsed-width: 85px;
        --accent: #00f2fe;
        --bg-dark: #0b0e11;
        --nav-hover: rgba(0, 242, 254, 0.08);
        --transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .admin-sidebar {
        width: var(--sidebar-width);
        height: 100vh;
        position: fixed;
        left: 0; top: 0;
        background: var(--bg-dark);
        border-right: 1px solid rgba(255, 255, 255, 0.05);
        padding: 25px 15px;
        display: flex;
        flex-direction: column;
        z-index: 1050;
        transition: var(--transition);
    }

    .admin-sidebar.collapsed { width: var(--sidebar-collapsed-width); }

    .admin-brand {
        padding: 10px 15px 40px;
        display: flex; align-items: center;
        gap: 15px; min-width: 250px;
    }
    
    .brand-box {
        width: 42px; height: 42px;
        background: linear-gradient(135deg, var(--accent), #4facfe);
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        color: #000; font-weight: 800;
        box-shadow: 0 8px 15px rgba(0, 242, 254, 0.2);
    }

    .brand-name {
        font-size: 1.3rem; font-weight: 800; color: #fff;
        transition: var(--transition);
    }

    .admin-sidebar.collapsed .brand-name,
    .admin-sidebar.collapsed .nav-label,
    .admin-sidebar.collapsed .nav-link-admin span {
        opacity: 0; pointer-events: none; visibility: hidden;
    }

    .nav-menu { list-style: none; padding: 0; margin: 0; flex-grow: 1; }

    .nav-label {
        color: rgba(255,255,255,0.2); font-size: 0.7rem;
        text-transform: uppercase; letter-spacing: 1.5px;
        padding: 20px 18px 10px; transition: 0.3s;
    }

    .nav-link-admin {
        display: flex; align-items: center; gap: 18px;
        padding: 14px 18px; color: rgba(255, 255, 255, 0.5);
        text-decoration: none; border-radius: 16px;
        font-weight: 500; margin-bottom: 5px; transition: var(--transition);
    }

    .nav-link-admin i { font-size: 1.2rem; width: 24px; text-align: center; }

    .nav-link-admin:hover { background: var(--nav-hover); color: var(--accent); }

    .nav-link-admin.active {
        background: linear-gradient(90deg, rgba(0, 242, 254, 0.15) 0%, transparent 100%);
        color: var(--accent);
        box-shadow: inset 4px 0 0 var(--accent);
        border-radius: 0 16px 16px 0;
        margin-left: -15px; padding-left: 33px;
    }

    .sidebar-footer { margin-top: auto; padding-top: 20px; border-top: 1px solid rgba(255, 255, 255, 0.05); }
    .logout-btn { color: #ff4b5c; }
    .logout-btn:hover { background: #ff4b5c; color: #fff; }

    /* Main Content adjustment helper */
    @media (max-width: 992px) { .admin-sidebar { left: -100%; } .admin-sidebar.show { left: 0; } }
</style>

<aside class="admin-sidebar" id="sidebar">
    <div class="admin-brand">
        <div class="brand-box">E</div>
        <div class="brand-name">ELITE<span style="color: var(--accent)">ADMIN</span></div>
    </div>

    <ul class="nav-menu">
        <div class="nav-label">Main Menu</div>
        <li class="nav-item">
            <a href="dashboard.php" class="nav-link-admin <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
                <i class="fas fa-chart-line"></i><span>Analytics</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="manage-bookings.php" class="nav-link-admin <?php echo ($current_page == 'manage-bookings.php') ? 'active' : ''; ?>">
                <i class="fas fa-calendar-check"></i><span>Reservations</span>
            </a>
        </li>

        <div class="nav-label">Inventory</div>
        <li class="nav-item">
            <a href="manage-vehicles.php" class="nav-link-admin <?php echo ($current_page == 'manage-vehicles.php') ? 'active' : ''; ?>">
                <i class="fas fa-car"></i><span>Fleet Manager</span>
            </a>
        </li>

        <div class="nav-label">Support</div>
        <li class="nav-item">
            <a href="manage-contact.php" class="nav-link-admin <?php echo ($current_page == 'manage-contact.php') ? 'active' : ''; ?>">
                <i class="fas fa-envelope-open-text"></i><span>Contact Messages</span>
            </a>
        </li>

        <div class="nav-label">Administration</div>
        <li class="nav-item">
            <a href="manage-users.php" class="nav-link-admin <?php echo ($current_page == 'manage-users.php') ? 'active' : ''; ?>">
                <i class="fas fa-user-shield"></i><span>User Control</span>
            </a>
        </li>
    </ul>

    <div class="sidebar-footer">
        <a href="logout.php" class="nav-link-admin logout-btn">
            <i class="fas fa-sign-out-alt"></i><span>Log Out</span>
        </a>
    </div>
</aside>