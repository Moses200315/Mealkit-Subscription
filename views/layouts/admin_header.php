<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($pageTitle ?? 'Admin') ?> | <?= APP_NAME ?></title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link rel="stylesheet" href="<?= asset('css/style.css') ?>">
<style>
:root{--sidebar-width:260px;--sidebar-bg:#1a1f2e;--sidebar-hover:rgba(45,106,79,.18);--primary:#2d6a4f;--accent:#f4a261;}
body{background:#f0f4f8;font-family:'Segoe UI',system-ui,sans-serif;}
#sidebar{width:var(--sidebar-width);min-height:100vh;background:var(--sidebar-bg);position:fixed;top:0;left:0;z-index:1050;transition:width .25s;display:flex;flex-direction:column;}
#sidebar .brand{padding:1.25rem 1.5rem;display:flex;align-items:center;gap:.75rem;border-bottom:1px solid rgba(255,255,255,.07);}
#sidebar .brand-icon{width:38px;height:38px;background:var(--primary);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;}
#sidebar .brand-text{color:#fff;font-weight:700;font-size:1.1rem;line-height:1.1;white-space:nowrap;}
#sidebar .brand-text small{color:#8a9bb8;font-size:.7rem;font-weight:400;display:block;}
#sidebar nav{flex:1;padding:1rem 0;overflow-y:auto;}
#sidebar .nav-label{color:#556070;font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;padding:.5rem 1.5rem .25rem;margin-top:.5rem;}
#sidebar .nav-link{color:#c8cfe4;padding:.6rem 1.5rem;display:flex;align-items:center;gap:.75rem;border-radius:0;font-size:.88rem;border-left:3px solid transparent;transition:all .15s;}
#sidebar .nav-link:hover{background:var(--sidebar-hover);color:#fff;border-left-color:var(--primary);}
#sidebar .nav-link.active{background:var(--sidebar-hover);color:#fff;border-left-color:var(--primary);font-weight:600;}
#sidebar .nav-link i{width:18px;text-align:center;font-size:1rem;opacity:.8;}
#sidebar .sidebar-footer{padding:1rem 1.5rem;border-top:1px solid rgba(255,255,255,.07);}
#sidebar .sidebar-footer .user-info{display:flex;align-items:center;gap:.75rem;}
#sidebar .sidebar-footer img{width:36px;height:36px;border-radius:50%;object-fit:cover;border:2px solid var(--primary);}
#sidebar .sidebar-footer .user-name{color:#fff;font-size:.82rem;font-weight:600;}
#sidebar .sidebar-footer .user-role{color:#8a9bb8;font-size:.7rem;text-transform:capitalize;}
#topbar{background:#fff;border-bottom:1px solid #e8ecf1;padding:.6rem 1.5rem;display:flex;align-items:center;gap:1rem;position:sticky;top:0;z-index:1040;box-shadow:0 1px 4px rgba(0,0,0,.05);}
#topbar .sidebar-toggle{background:none;border:none;font-size:1.25rem;color:#6c757d;padding:.25rem .5rem;border-radius:6px;}
#topbar .sidebar-toggle:hover{background:#f0f4f8;}
.main-content{margin-left:var(--sidebar-width);min-height:100vh;transition:margin .25s;display:flex;flex-direction:column;}
.page-header{padding:1.25rem 1.5rem .25rem;}
.page-header h4{font-weight:700;color:#1a1f2e;margin:0;}
.page-body{padding:1rem 1.5rem 2rem;flex:1;}
.stat-card{border:none;border-radius:14px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,.06);}
.stat-card .card-body{padding:1.25rem;}
.stat-card .stat-icon{width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.4rem;}
.table th{font-size:.78rem;text-transform:uppercase;letter-spacing:.05em;color:#6c757d;border-bottom:2px solid #e8ecf1;background:#fafbfc;}
.badge-pill{border-radius:50px;padding:.35em .75em;}
@media(max-width:768px){#sidebar{transform:translateX(-100%);}.main-content{margin-left:0;}.sidebar-open #sidebar{transform:none;}}
</style>
</head>
<body>
<?php $currentUrl = $_SERVER['REQUEST_URI'] ?? ''; ?>
<div id="wrapper" class="<?= Session::has('_sidebar_open') ? 'sidebar-open' : '' ?>">

<!-- ── SIDEBAR ──────────────────────────────────────────────────────────── -->
<nav id="sidebar">
  <div class="brand">
    <div class="brand-icon">🍽️</div>
    <div class="brand-text"><?= APP_NAME ?><small>Admin Panel</small></div>
  </div>

  <nav>
    <span class="nav-label">Main</span>
    <a class="nav-link <?= active_class('admin/dashboard') ?>" href="<?= url('admin/dashboard') ?>">
      <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <span class="nav-label">Content</span>
    <a class="nav-link <?= active_class('recipes/adminIndex') ?>" href="<?= url('recipes/adminIndex') ?>">
      <i class="bi bi-journal-richtext"></i> Recipes
    </a>
    <a class="nav-link <?= active_class('categories') ?>" href="<?= url('categories/index') ?>">
      <i class="bi bi-tags"></i> Categories
    </a>

    <span class="nav-label">Business</span>
    <a class="nav-link <?= active_class('admin/users') ?>" href="<?= url('admin/users') ?>">
      <i class="bi bi-people"></i> Customers
    </a>
    <a class="nav-link <?= active_class('subscriptions/adminIndex') ?>" href="<?= url('subscriptions/adminIndex') ?>">
      <i class="bi bi-calendar-check"></i> Subscriptions
    </a>
    <a class="nav-link <?= active_class('payments/adminIndex') ?>" href="<?= url('payments/adminIndex') ?>">
      <i class="bi bi-credit-card"></i> Payments
    </a>
    <a class="nav-link <?= active_class('reports') ?>" href="<?= url('reports/index') ?>">
      <i class="bi bi-bar-chart"></i> Reports
    </a>

    <span class="nav-label">Account</span>
    <a class="nav-link <?= active_class('profile') ?>" href="<?= url('profile/index') ?>">
      <i class="bi bi-person-circle"></i> My Profile
    </a>
    <a class="nav-link text-danger" href="<?= url('auth/logout') ?>"
       onclick="return confirm('Log out?')"><?php /* quick GET logout for admin */ ?>
      <i class="bi bi-box-arrow-right"></i> Logout
    </a>
  </nav>

  <div class="sidebar-footer">
    <div class="user-info">
      <img src="<?= avatar_url($currentUser['avatar'] ?? null) ?>" alt="avatar">
      <div>
        <div class="user-name"><?= e(Session::userName()) ?></div>
        <div class="user-role"><?= e(str_replace('_', ' ', $currentUser['role'] ?? 'admin')) ?></div>
      </div>
    </div>
  </div>
</nav>

<!-- ── MAIN ──────────────────────────────────────────────────────────────── -->
<div class="main-content">
  <div id="topbar">
    <button class="sidebar-toggle" onclick="document.getElementById('wrapper').classList.toggle('sidebar-open')">
      <i class="bi bi-list"></i>
    </button>
    <span class="text-muted small fw-semibold"><?= e($pageTitle ?? '') ?></span>
    <div class="ms-auto d-flex align-items-center gap-2">
      <a href="<?= url('') ?>" class="btn btn-sm btn-outline-secondary" target="_blank">
        <i class="bi bi-globe"></i> View Site
      </a>
      <div class="dropdown">
        <img src="<?= avatar_url($currentUser['avatar'] ?? null) ?>"
             class="rounded-circle" style="width:34px;height:34px;object-fit:cover;cursor:pointer"
             data-bs-toggle="dropdown" alt="avatar">
        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
          <li><a class="dropdown-item" href="<?= url('profile/index') ?>"><i class="bi bi-person me-2"></i>Profile</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item text-danger" href="<?= url('auth/logout') ?>"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
        </ul>
      </div>
    </div>
  </div>

  <div class="page-body">
    <?= render_flash() ?>
