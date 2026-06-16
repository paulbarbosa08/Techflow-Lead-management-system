<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TECHFLOW Lead Management System</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
        }

        :root {
            --primary-dark: #2d3250;
            --secondary-dark: #2d3250;
            --primary-yellow: #ffe176;
            --yellow-hover: #FFC947;
            --cream-bg: #F2E7D5;
            --cream-light: #FAF3E8;
            --text-dark: #1A1A1A;
            --text-light: #F8F9FA;
            --text-muted: #8A8D93;
            --border-color: rgba(45, 45, 45, 0.1);
            --success: #10B981;
            --warning: #F59E0B;
            --danger: #EF4444;
            --info: #3B82F6;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.12);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1);
            --transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            background-color: var(--primary-dark);
            color: var(--text-light);
        }

        /* ========== AUTH PAGES ========== */

.auth-logo {
    text-align: center;
    margin-bottom: 8px;
}

.auth-logo img {
    width: 140px;
    height: 140px;
    object-fit: contain;
}

.auth-page {
    min-height: 100vh;
    display: flex;
    overflow: hidden;
}

.auth-page::before {
    display: none;
}

.auth-left {
    width: 50%;
    min-height: 100vh;
    background: var(--primary-dark);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 48px;
}

.auth-right {
    width: 50%;
    min-height: 100vh;
    background-image: url('/images/auth-bg.jpg');
    background-size: cover;
    background-position: center;
}

.auth-container {
    background: transparent;
    padding: 0;
    width: 100%;
    max-width: 420px;
}

.auth-container:hover {
    box-shadow: none;
}

.auth-title {
    color: var(--text-light) !important;
}

.auth-subtitle {
    color: var(--text-muted) !important;
}

.form-group label {
    color: var(--text-light) !important;
}

.form-control {
    background: var(--secondary-dark) !important;
    border-color: rgba(255,255,255,0.1) !important;
    color: var(--text-light) !important;
}

.forgot-password-link a,
.auth-link a {
    color: var(--text-muted) !important;
}

.forgot-password-link a:hover,
.auth-link a:hover {
    color: var(--primary-yellow) !important;
}

@media (max-width: 768px) {
    .auth-left { width: 100%; padding: 32px 24px; }
    .auth-right { display: none; }
}

        /* Auth title on one line */
        .auth-title {
    color: var(--text-light) !important;
    font-family: 'Orbitron', sans-serif;
    letter-spacing: 2px;
    text-align: center;
    font-size: 34px;
}

.auth-subtitle {
    color: var(--text-muted) !important;
    font-family: 'Inter', sans-serif;
    letter-spacing: 1px;
    text-align: center;
    margin-bottom: 32px;
    font-size: 13px;
}

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-dark);
            font-weight: 500;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 16px;
            transition: var(--transition);
            background: var(--cream-light);
            color: var(--text-dark);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-yellow);
            box-shadow: 0 0 0 3px rgba(249, 185, 51, 0.1);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 14px 28px;
            background: var(--primary-yellow);
            color: var(--text-dark);
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            text-align: center;
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }

        .btn::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 5px;
            height: 5px;
            background: rgba(255, 255, 255, 0.5);
            opacity: 0;
            border-radius: 100%;
            transform: scale(1, 1) translate(-50%);
            transform-origin: 50% 50%;
        }

        .btn:focus:not(:active)::after {
            animation: ripple 1s ease-out;
        }

        @keyframes ripple {
            0% {
                transform: scale(0, 0);
                opacity: 0.5;
            }
            100% {
                transform: scale(20, 20);
                opacity: 0;
            }
        }

        .btn:hover {
            background: var(--yellow-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(249, 185, 51, 0.2);
        }

        .btn:active {
            transform: translateY(0);
        }

        .btn-block {
            width: 100%;
        }

        .role-selector {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-bottom: 24px;
        }

        .role-option {
            text-align: center;
            padding: 20px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            cursor: pointer;
            transition: var(--transition);
            font-weight: 500;
            background: var(--cream-light);
            color: var(--text-dark);
        }

        .role-option:hover {
            border-color: var(--primary-yellow);
            transform: translateY(-2px);
        }

        .role-option.active {
            border-color: var(--primary-yellow);
            background: var(--primary-yellow);
            color: var(--text-dark);
            box-shadow: 0 4px 6px rgba(249, 185, 51, 0.2);
        }

        .forgot-password-link {
            text-align: center;
            margin: 20px 0;
            color: var(--text-dark);
            font-size: 14px;
        }

        .forgot-password-link a {
            color: var(--text-dark);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
            position: relative;
            display: inline-block;
            padding-bottom: 2px;
        }

        .forgot-password-link a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 1px;
            background: var(--primary-yellow);
            transition: var(--transition);
        }

        .forgot-password-link a:hover {
            color: var(--primary-yellow);
        }

        .forgot-password-link a:hover::after {
            width: 100%;
        }

        .auth-link {
            text-align: center;
            margin-top: 32px;
            color: var(--text-dark);
            font-size: 14px;
        }

        .auth-link a {
            color: var(--text-dark);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
            position: relative;
        }

        .auth-link a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary-yellow);
            transition: var(--transition);
        }

        .auth-link a:hover {
            color: var(--primary-yellow);
        }

        .auth-link a:hover::after {
            width: 100%;
        }

        /* ========== ICON STYLES ========== */
        .icon {
            width: 20px;
            height: 20px;
            stroke-width: 2;
            display: inline-block;
            vertical-align: middle;
        }

        .icon-sm {
            width: 16px;
            height: 16px;
        }

        .icon-lg {
            width: 24px;
            height: 24px;
        }

        .icon-xl {
            width: 32px;
            height: 32px;
        }

        /* ========== MAIN APPLICATION LAYOUT ========== */
        .app-layout {
            min-height: 100vh;
            background: var(--primary-dark);
        }

        /* Cleaner Navbar */
        .navbar {
    background: var(--secondary-dark);
    padding: 0 32px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: var(--shadow-md);
    border-bottom: 1px solid rgba(249, 185, 51, 0.1);
    position: sticky;
    top: 0;
    z-index: 100;
}

        .navbar::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--primary-yellow), transparent);
        }

        .navbar-brand {
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.5px;
            color: var(--primary-yellow);
            display: flex;
            align-items: center;
            gap: 12px;
            white-space: nowrap;
        }

        .navbar-brand::before {
            content: '✦';
            font-size: 20px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 16px;
            background: rgba(45, 45, 45, 0.5);
            border-radius: 50px;
            transition: var(--transition);
        }

        .user-profile:hover {
            background: rgba(45, 45, 45, 0.8);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            background: var(--primary-yellow);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: var(--text-dark);
            font-size: 14px;
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            color: var(--text-light);
            font-size: 14px;
        }

        .user-role {
            background: linear-gradient(135deg, var(--primary-yellow), #FFD166);
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-dark);
            box-shadow: 0 2px 4px rgba(249, 185, 51, 0.2);
        }

        /* Professional Sidebar */
        .sidebar {
            width: 280px;
            background: var(--secondary-dark);
            position: fixed;
            left: 0;
            top: 80px;
            bottom: 0;
            display: flex;
            flex-direction: column;
            border-right: 1px solid rgba(249, 185, 51, 0.1);
            overflow: hidden;
            transition: var(--transition);
        }

        .sidebar-menu {
            list-style: none;
            flex: 1;
            padding: 24px 0;
            overflow-y: auto;
        }

        .sidebar-menu::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-menu::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-menu::-webkit-scrollbar-thumb {
            background: var(--primary-yellow);
            border-radius: 2px;
        }

        .sidebar-menu li {
            padding: 0 20px;
            margin-bottom: 8px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 16px 24px;
            color: var(--text-light);
            text-decoration: none;
            transition: var(--transition);
            font-weight: 500;
            border-radius: 12px;
            position: relative;
            overflow: hidden;
            gap: 16px;
        }

        .sidebar-menu a::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 0;
            background: var(--primary-yellow);
            border-radius: 0 2px 2px 0;
            transition: var(--transition);
        }

        .sidebar-menu a:hover {
            background: rgba(249, 185, 51, 0.1);
            padding-left: 32px;
        }

        .sidebar-menu a:hover::before {
            height: 40%;
        }

        .sidebar-menu a.active {
            background: var(--primary-yellow);
            color: var(--text-dark);
            box-shadow: 0 4px 12px rgba(249, 185, 51, 0.2);
        }

        .sidebar-menu a.active::before {
            height: 100%;
            width: 6px;
            background: var(--text-dark);
        }

        /* Sidebar bottom section */
        .sidebar-bottom {
            border-top: 1px solid rgba(249, 185, 51, 0.1);
            padding: 20px;
            background: rgba(45, 45, 45, 0.5);
        }

        .logout-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 16px;
            color: var(--text-light);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
            background: rgba(239, 68, 68, 0.1);
            border-radius: 12px;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.1);
        }

        .logout-btn:active {
            transform: translateY(0);
        }

        .main-content {
            margin-left: 280px;
            padding: 40px;
            min-height: calc(100vh - 80px);
            background: var(--primary-dark);
            transition: var(--transition);
        }

        /* ========== IMPROVED DASHBOARD CARDS ========== */
        .page-header {
            margin-bottom: 40px;
        }

        .page-header h1 {
            font-size: 32px;
            color: var(--text-light);
            margin-bottom: 8px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .page-header p {
            color: var(--text-muted);
            font-size: 16px;
            max-width: 600px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: linear-gradient(135deg, var(--secondary-dark), #252525);
            border-radius: 16px;
            padding: 32px;
            box-shadow: var(--shadow-md);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(249, 185, 51, 0.1);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(to bottom, var(--primary-yellow), transparent);
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
            border-color: rgba(249, 185, 51, 0.3);
        }

        .stat-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            background: rgba(249, 185, 51, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: var(--primary-yellow);
        }

        .stat-card h3 {
            color: var(--text-muted);
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .stat-number {
            font-size: 48px;
            font-weight: 700;
            color: var(--text-light);
            line-height: 1;
            margin-bottom: 12px;
            background: linear-gradient(135deg, var(--text-light), #B0B8C0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-trend {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 500;
        }

        .trend-up { color: var(--success); }
        .trend-down { color: var(--danger); }

        /* ========== IMPROVED TABLE STYLES ========== */
        .card {
    background: var(--secondary-dark);
    border-radius: 16px;
    padding: 32px;
    box-shadow: var(--shadow-md);
    margin-bottom: 40px;
    border: 1px solid rgba(249, 185, 51, 0.1);
    transition: var(--transition);
    overflow: hidden;
}

        .card:hover { border-color: rgba(249, 185, 51, 0.3); }

        .card-header {
            margin-bottom: 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }

        .card-header h2 {
            font-size: 24px;
            color: var(--text-light);
            margin-bottom: 4px;
            font-weight: 600;
        }

        .card-header p {
            color: var(--text-muted);
            font-size: 14px;
        }

        .card-actions { display: flex; gap: 12px; }

        .table-wrapper {
            overflow-x: auto;
            border-radius: 12px;
            background: rgba(45, 45, 45, 0.5);
            border: 1px solid rgba(249, 185, 51, 0.1);
        }

        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            min-width: 800px;
        }

        .table thead {
            background: linear-gradient(135deg, rgba(249, 185, 51, 0.1), transparent);
        }

        .table th {
            padding: 20px 24px;
            text-align: left;
            font-weight: 600;
            color: var(--text-light);
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid rgba(249, 185, 51, 0.1);
            white-space: nowrap;
        }

        .table th:first-child { border-top-left-radius: 12px; }
        .table th:last-child { border-top-right-radius: 12px; }

        .table td {
            padding: 20px 24px;
            border-bottom: 1px solid rgba(249, 185, 51, 0.05);
            color: var(--text-light);
            font-size: 14px;
            transition: var(--transition);
        }

        .table tbody tr { transition: var(--transition); }

        .table tbody tr:hover { background: rgba(249, 185, 51, 0.05); }

        .table tbody tr:last-child td { border-bottom: none; }
        .table tbody tr:last-child td:first-child { border-bottom-left-radius: 12px; }
        .table tbody tr:last-child td:last-child { border-bottom-right-radius: 12px; }

        /* ========== IMPROVED BUTTONS ========== */
        .btn-primary { background: var(--primary-yellow); color: var(--text-dark); }

        .btn-secondary {
            background: rgba(249, 185, 51, 0.1);
            color: var(--primary-yellow);
            border: 1px solid rgba(249, 185, 51, 0.2);
        }

        .btn-secondary:hover {
            background: rgba(249, 185, 51, 0.2);
            border-color: rgba(249, 185, 51, 0.3);
        }

        .btn-sm { padding: 10px 20px; font-size: 14px; }
        .btn-group { display: flex; gap: 8px; }

        /* ========== IMPROVED STATUS BADGES ========== */
        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .status-badge::before {
            content: '';
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
        }

        .status-new {
            background: rgba(59, 130, 246, 0.1);
            color: #60A5FA;
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .status-new::before { background: #60A5FA; }

        .status-contacted {
            background: rgba(245, 158, 11, 0.1);
            color: #FBBF24;
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .status-contacted::before { background: #FBBF24; }

        /* KEEP OLD CLASS (if any pages still call it) */
        .status-qualified {
            background: rgba(16, 185, 129, 0.1);
            color: #34D399;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .status-qualified::before { background: #34D399; }

        /* Accepted */
        .status-accepted {
            background: rgba(16, 185, 129, 0.1);
            color: #34D399;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }
        .status-accepted::before { background: #34D399; }

        /* Denied */
        .status-denied {
            background: rgba(239, 68, 68, 0.1);
            color: #F87171;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }
        .status-denied::before { background: #F87171; }

        /* ========== SETTINGS PAGE ========== */
        .settings-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 32px;
        }

        .settings-section {
            background: var(--secondary-dark);
            border-radius: 16px;
            padding: 32px;
            box-shadow: var(--shadow-md);
            border: 1px solid rgba(249, 185, 51, 0.1);
            transition: var(--transition);
        }

        .settings-section:hover {
            border-color: rgba(249, 185, 51, 0.3);
        }

        .settings-section h2 {
            color: var(--primary-yellow);
            font-size: 24px;
            margin-bottom: 32px;
            padding-bottom: 16px;
            border-bottom: 2px solid rgba(249, 185, 51, 0.2);
        }

        .settings-section h3 {
            font-size: 20px;
            color: var(--text-light);
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid rgba(249, 185, 51, 0.1);
            font-weight: 600;
        }

        .settings-section { color: var(--text-light); }
        .settings-section h3 { color: var(--primary-yellow); }

        .settings-section label {
            color: var(--text-muted);
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .settings-section input[type="text"],
        .settings-section input[type="email"],
        .settings-section input[type="password"] {
            background: var(--secondary-dark);
            color: var(--text-light);
            border: 1px solid rgba(249, 185, 51, 0.2);
            padding: 12px 16px;
            border-radius: 8px;
            width: 100%;
            margin-bottom: 20px;
            transition: var(--transition);
            font-size: 16px;
        }

        .settings-section input[type="text"]:focus,
        .settings-section input[type="email"]:focus,
        .settings-section input[type="password"]:focus {
            outline: none;
            border-color: var(--primary-yellow);
            box-shadow: 0 0 0 3px rgba(249, 185, 51, 0.1);
        }

        .setting-item { margin-bottom: 28px; }

        .setting-item label {
            color: var(--text-muted);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 6px;
            display: block;
            font-weight: 600;
        }

        .setting-value {
            color: var(--text-light);
            font-size: 16px;
            font-weight: 500;
            padding: 10px 0;
            border-bottom: 1px solid rgba(249, 185, 51, 0.1);
            background: rgba(45, 45, 45, 0.3);
            padding: 12px 16px;
            border-radius: 8px;
            border: 1px solid rgba(249, 185, 51, 0.1);
        }

        .settings-btn {
            background: var(--primary-yellow);
            color: var(--text-dark);
            border: none;
            padding: 14px 28px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 16px;
            font-size: 16px;
            width: 100%;
        }

        .settings-btn:hover {
            background: var(--yellow-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(249, 185, 51, 0.2);
        }

        .settings-btn:active {
            transform: translateY(0);
        }

        .profile-info {
            background: rgba(45, 45, 45, 0.5);
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            border: 1px solid rgba(249, 185, 51, 0.1);
        }

        .profile-info p {
            color: var(--text-light);
            margin-bottom: 12px;
            font-size: 16px;
        }

        .profile-info strong {
            color: var(--primary-yellow);
            font-weight: 600;
            display: inline-block;
            min-width: 150px;
        }

        hr {
            border: none;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(249, 185, 51, 0.3), transparent);
            margin: 32px 0;
        }

        .logout-link {
            color: var(--danger);
            text-decoration: none;
            font-weight: 600;
            padding: 14px 20px;
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: var(--transition);
            background: rgba(239, 68, 68, 0.1);
            justify-content: center;
            width: 100%;
            margin-top: 16px;
        }

        .logout-link:hover {
            background: rgba(239, 68, 68, 0.2);
            border-color: rgba(239, 68, 68, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.1);
        }

        .logout-link:active {
            transform: translateY(0);
        }

        /* ========== FORM ELEMENTS ========== */
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            padding: 16px;
            background: rgba(45, 45, 45, 0.5);
            border-radius: 12px;
            border: 1px solid var(--border-color);
            transition: var(--transition);
        }

        .checkbox-group:hover {
            border-color: rgba(249, 185, 51, 0.3);
            background: rgba(45, 45, 45, 0.8);
        }

        .checkbox-group input[type="checkbox"] {
            width: 20px;
            height: 20px;
            accent-color: var(--primary-yellow);
            cursor: pointer;
        }

        .checkbox-group label {
            margin: 0;
            color: var(--text-light);
            font-weight: 500;
            cursor: pointer;
            flex: 1;
        }

        /* ========== ALERTS & MESSAGES ========== */
        .alert {
            padding: 20px 24px;
            border-radius: 12px;
            margin-bottom: 24px;
            border: 1px solid;
            display: flex;
            align-items: center;
            gap: 16px;
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            color: #34D399;
            border-color: rgba(16, 185, 129, 0.2);
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            color: #F87171;
            border-color: rgba(239, 68, 68, 0.2);
        }

        .error {
            color: #F87171;
            font-size: 13px;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* ========== RESPONSIVE DESIGN ========== */
        @media (max-width: 1200px) {
            .sidebar { width: 240px; }
            .main-content { margin-left: 240px; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
                z-index: 1000;
                box-shadow: 20px 0 40px rgba(0,0,0,0.2);
            }
            .sidebar.active { transform: translateX(0); }

            .main-content { margin-left: 0; padding: 32px 24px; }
            .navbar { padding: 0 24px; }

            .menu-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 40px;
                height: 40px;
                background: rgba(249, 185, 51, 0.1);
                border-radius: 8px;
                cursor: pointer;
                color: var(--primary-yellow);
                font-size: 20px;
            }
        }

        @media (max-width: 768px) {
            .stats-grid { grid-template-columns: 1fr; }
            .settings-grid { grid-template-columns: 1fr; }
            .auth-container { padding: 32px 24px; }
            .user-profile { padding: 8px 12px; }
            .user-name { display: none; }
            .card-header { flex-direction: column; align-items: flex-start; }
            .card-actions { width: 100%; justify-content: flex-start; }
            .settings-section { padding: 24px; }
        }

        @media (max-width: 480px) {
            .navbar { padding: 0 16px; height: 70px; }
            .main-content { padding: 24px 16px; }
            .navbar-brand { font-size: 18px; }
            .stat-card { padding: 24px; }
            .stat-number { font-size: 36px; }
            .page-header h1 { font-size: 24px; }
            .setting-value { padding: 10px 12px; font-size: 14px; }
            .settings-btn { padding: 12px 20px; font-size: 14px; }

            .auth-title {
                font-size: 28px;
                white-space: normal;
                line-height: 1.3;
            }

            .login-role-display {
                flex-direction: column;
                gap: 10px;
                align-items: center;
            }
        }

        @media (max-width: 360px) {
            .auth-title { font-size: 24px; }
            .auth-container { padding: 24px 16px; }
            .role-selector { grid-template-columns: 1fr; }
            .settings-section { padding: 20px 16px; }
        }

        /* ========== MICRO-INTERACTIONS ========== */
        .clickable {
            cursor: pointer;
            user-select: none;
            transition: var(--transition);
        }

        .clickable:active { transform: scale(0.98); }

        .smooth-transition { transition: var(--transition); }
        .hover-lift:hover { transform: translateY(-2px); }
        .focus-glow:focus { box-shadow: 0 0 0 3px rgba(249, 185, 51, 0.2); }

        /* ========== UTILITY CLASSES ========== */
        .text-primary { color: var(--primary-yellow); }
        .bg-primary { background: var(--primary-yellow); }
        .border-primary { border-color: var(--primary-yellow); }

        .glass-effect {
            background: rgba(45, 45, 45, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(249, 185, 51, 0.1);
        }

        .shadow-primary { box-shadow: 0 4px 20px rgba(249, 185, 51, 0.15); }

    
        .brand-name{
            font-weight: 800;
            letter-spacing: 2px;
            color: var(--primary-yellow);
        }
        .brand-divider{
            opacity: 0.65;
            margin: 0 6px;
            color: var(--text-light);
        }
        .brand-subtitle{
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: rgba(248, 249, 250, 0.75);
        }

    
        .table-fit-wrapper{
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-radius: 12px;
        }

        .table.table-fit{
            width: 100%;
            min-width: 0 !important;
            table-layout: fixed;
        }

        .table.table-fit th,
        .table.table-fit td{
            padding: 12px 14px;
            font-size: 13px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .assign-select-compact{
            width: 120px !important;
            padding: 8px 10px;
            font-size: 13px;
        }

               
        .navbar-brand{
            display: flex;
            align-items: baseline;
            gap: 10px;
            white-space: nowrap;
        }

        .brand-name{
            font-weight: 800;
            letter-spacing: 2px;
            color: var(--primary-yellow);
        }

        .brand-subtitle{
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: rgba(248, 249, 250, 0.75);
        }

        .search-filter-bar {
    display: flex;
    gap: 12px;
    align-items: center;
    padding: 16px 24px;
    flex-wrap: wrap;
}

.priority-badge {
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.priority-low {
    background: rgba(59, 130, 246, 0.15);
    color: #3B82F6;
}

.priority-medium {
    background: rgba(245, 158, 11, 0.15);
    color: #F59E0B;
}

.priority-high {
    background: rgba(239, 68, 68, 0.15);
    color: #EF4444;
}

    </style>
</head>
<body>
    @if(Request::is('/') || Request::is('login') || Request::is('register') || Request::is('forgot-password') || Request::is('reset-password*'))
        @yield('auth-content')
    @else
        <div class="app-layout">
            <nav class="navbar">
                <!-- ✅ TECHFLOW BRAND (SAFE) -->
                <div class="navbar-brand">
                    <span class="brand-name">TECHFLOW</span>
                    <span class="brand-subtitle">Lead Management System</span>
                </div>

                <div class="user-info">
                    <div class="user-profile clickable">
                        <div class="user-avatar">
                            {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->last_name, 0, 1)) }}
                        </div>
                        <div class="user-details">
                            <span class="user-name">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                        </div>
                        <span class="user-role">{{ strtoupper(Auth::user()->role) }}</span>
                    </div>
                </div>
            </nav>

            <div class="sidebar" id="sidebar">
                <ul class="sidebar-menu">
                    <li>
                        <a href="{{ route('dashboard') }}" class="{{ Request::is('dashboard') ? 'active' : '' }}">
                            <i data-lucide="layout-dashboard" class="icon"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <!-- ADMIN MENU ITEMS -->
                    @if(Auth::user()->role == 'admin')
                    <li>
                        <a href="{{ route('leads.index') }}" class="{{ Request::is('leads*') && !Request::is('leads/update*') ? 'active' : '' }}">
                            <i data-lucide="users" class="icon"></i>
                            <span>Leads (All)</span>
                        </a>
                    </li>
                    @endif

                    @if(auth()->user()->role === 'admin')
<a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}">
    <i data-lucide="user-cog" class="icon"></i>
    Manage Staff
</a>
@endif

                    <!-- STAFF MENU ITEMS -->
                    @if(Auth::user()->role == 'staff')
                    <li>
                        <a href="{{ route('leads.myleads') }}" class="{{ Request::is('leads/my-leads*') ? 'active' : '' }}">
                            <i data-lucide="user-check" class="icon"></i>
                            <span>My Leads</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('leads.update.index') }}" class="{{ Request::is('leads/update*') ? 'active' : '' }}">
                            <i data-lucide="edit" class="icon"></i>
                            <span>Update Leads</span>
                        </a>
                    </li>
                    @endif

                    <li>
                        <a href="{{ route('settings.index') }}" class="{{ Request::is('settings*') ? 'active' : '' }}">
                            <i data-lucide="settings" class="icon"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                </ul>

                <div class="sidebar-bottom">
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="logout-btn clickable">
                        <i data-lucide="log-out" class="icon"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>

            <div class="main-content">
                @if(session('success'))
                    <div class="alert alert-success">
                        <i data-lucide="check-circle" class="icon"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-error">
                        <i data-lucide="alert-circle" class="icon"></i>
                        <div>
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    @endif

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Role selector for registration
        function selectRole(role) {
            document.querySelectorAll('.role-option').forEach(el => el.classList.remove('active'));
            event.currentTarget.classList.add('active');
            document.querySelector('input[name="role"]').value = role;
        }

        // Update lead status with micro-interaction
        function updateLeadStatus(leadId, status) {
            const button = event.currentTarget;
            const originalText = button.innerHTML;

            button.innerHTML = '<i data-lucide="loader-2" class="icon animate-spin"></i><span>Updating...</span>';
            lucide.createIcons();
            button.disabled = true;

            fetch(`/leads/${leadId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    button.classList.add('bg-primary', 'text-dark');
                    button.innerHTML = '<i data-lucide="check" class="icon"></i><span>Updated</span>';
                    lucide.createIcons();

                    setTimeout(() => {
                        location.reload();
                    }, 800);
                } else {
                    button.innerHTML = originalText;
                    lucide.createIcons();
                    button.disabled = false;
                    alert('Failed to update status');
                }
            })
            .catch(error => {
                button.innerHTML = originalText;
                lucide.createIcons();
                button.disabled = false;
                console.error('Error:', error);
            });
        }

        // Mobile menu toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }

        // Add ripple effect to buttons
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.btn, .settings-btn');
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;

                    ripple.style.cssText = `
                        position: absolute;
                        border-radius: 50%;
                        background: rgba(255, 255, 255, 0.7);
                        transform: scale(0);
                        animation: ripple-animation 0.6s linear;
                        width: ${size}px;
                        height: ${size}px;
                        top: ${y}px;
                        left: ${x}px;
                    `;

                    this.appendChild(ripple);

                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });

            const tableRows = document.querySelectorAll('.table tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateX(4px)';
                });
                row.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateX(0)';
                });
            });

            const style = document.createElement('style');
            style.textContent = `
                @keyframes ripple-animation {
                    to { transform: scale(4); opacity: 0; }
                }
                .animate-spin { animation: spin 1s linear infinite; }
                @keyframes spin {
                    from { transform: rotate(0deg); }
                    to { transform: rotate(360deg); }
                }
            `;
            document.head.appendChild(style);
        });
    </script>
</body>
</html>
