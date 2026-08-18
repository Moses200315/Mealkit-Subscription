<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($pageTitle ?? 'Dashboard') ?> | <?= APP_NAME ?></title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link rel="stylesheet" href="<?= asset('css/style.css') ?>">
<style>
:root{--primary:#2d6a4f;--primary-light:#52b788;--accent:#f4a261;--bg:#f8fffe;}
body{background:var(--bg);font-family:'Segoe UI',system-ui,sans-serif;}
.navbar-custom{background:#fff;border-bottom:1px solid #e2ece7;box-shadow:0 1px 6px rgba(45,106,79,.08);}
.navbar-custom .navbar-brand{font-weight:800;color:var(--primary)!important;font-size:1.25rem;}
.navbar-custom .nav-link{color:#4a5568;font-size:.88rem;font-weight:500;}
.navbar-custom .nav-link:hover,.navbar-custom .nav-link.active{color:var(--primary);}
.notif-badge{position:absolute;top:-4px;right:-6px;font-size:.6rem;min-width:16px;height:16px;border-radius:50%;display:flex;align-items:center;justify-content:center;}
.sidebar-customer{background:#fff;border-right:1px solid #e2ece7;min-height:calc(100vh - 58px);padding:1.25rem 0;}
.sidebar-customer .nav-link{color:#4a5568;padding:.55rem 1.25rem;font-size:.875rem;border-left:3px solid transparent;border-radius:0;display:flex;align-items:center;gap:.65rem;}
.sidebar-customer .nav-link:hover{background:#f0faf5;color:var(--primary);border-left-color:var(--primary-light);}
.sidebar-customer .nav-link.active{background:#f0faf5;color:var(--primary);border-left-color:var(--primary);font-weight:600;}
.sidebar-customer .nav-label{font-size:.65rem;font-weight:700;text-transform:uppercase;color:#a0aec0;padding:.75rem 1.25rem .25rem;letter-spacing:.08em;}
.main-area{padding:1.5rem;}
.card-recipe{border:none;border-radius:14px;box-shadow:0 2px 10px rgba(0,0,0,.06);transition:transform .2s,box-shadow .2s;overflow:hidden;}
.card-recipe:hover{transform:translateY(-3px);box-shadow:0 8px 24px rgba(45,106,79,.12);}
.card-recipe .card-img-top{height:180px;object-fit:cover;}
.badge-difficulty{font-size:.7rem;border-radius:50px;}
.premium-badge{background:linear-gradient(135deg,#f6d365,#fda085);color:#7b3f00;font-size:.7rem;border-radius:50px;padding:.25em .65em;}
.btn-primary-custom{background:var(--primary);border-color:var(--primary);color:#fff;}
.btn-primary-custom:hover{background:#245a40;border-color:#245a40;color:#fff;}
.subscription-alert{background:linear-gradient(135deg,var(--primary),var(--primary-light));color:#fff;border-radius:14px;padding:1rem 1.25rem;}
</style>
</head>
<body>

<!-- ── TOP NAVBAR ────────────────────────────────────────────────────────── -->
<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
  <div class="container-fluid px-4">
    <a class="navbar-brand" href="<?= url('customer/dashboard') ?>">🍽️ <?= APP_NAME ?></a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMain">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-1">
        <li class="nav-item">
          <a class="nav-link <?= active_class('home') ?>" href="<?= url('home') ?>">
            <i class="bi bi-house-door me-1"></i>Home
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= active_class('recipes/index') ?>" href="<?= url('recipes/index') ?>">
            <i class="bi bi-journal-richtext me-1"></i>Recipes
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= active_class('mealplans') ?>" href="<?= url('mealplans/index') ?>">
            <i class="bi bi-calendar3 me-1"></i>Meal Plans
          </a>
        </li>
        <!-- Notifications -->
        <li class="nav-item">
          <a class="nav-link position-relative" href="<?= url('notifications/index') ?>">
            <i class="bi bi-bell fs-5"></i>
            <?php if(($unreadNotifCount ?? 0) > 0): ?>
              <span class="notif-badge badge bg-danger"><?= (int)$unreadNotifCount ?></span>
            <?php endif; ?>
          </a>
        </li>
        <!-- User Dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" data-bs-toggle="dropdown">
            <img src="<?= avatar_url($currentUser['avatar'] ?? null) ?>"
                 class="rounded-circle" style="width:30px;height:30px;object-fit:cover;" alt="avatar">
            <span class="d-none d-lg-inline"><?= e($currentUser['first_name'] ?? 'Me') ?></span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end shadow-sm">
            <li><h6 class="dropdown-header"><?= e(Session::userName()) ?></h6></li>
            <li><a class="dropdown-item" href="<?= url('customer/dashboard') ?>"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
            <li><a class="dropdown-item" href="<?= url('profile/index') ?>"><i class="bi bi-person me-2"></i>Profile</a></li>
            <li><a class="dropdown-item" href="<?= url('subscriptions/history') ?>"><i class="bi bi-calendar-check me-2"></i>Subscription</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <form method="POST" action="<?= url('auth/logout') ?>">
                <?= csrf_field() ?>
                <button type="submit" class="dropdown-item text-danger">
                  <i class="bi bi-box-arrow-right me-2"></i>Logout
                </button>
              </form>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- ── BODY LAYOUT ───────────────────────────────────────────────────────── -->
<div class="container-fluid px-0">
  <div class="row g-0">
    <!-- Sidebar -->
    <div class="col-lg-2 d-none d-lg-block sidebar-customer">
      <nav class="nav flex-column">
        <span class="nav-label">Menu</span>
        <a class="nav-link <?= active_class('home') ?>" href="<?= url('home') ?>">
          <i class="bi bi-house-door"></i> Home
        </a>
        <a class="nav-link <?= active_class('customer/dashboard') ?>" href="<?= url('customer/dashboard') ?>">
          <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a class="nav-link <?= active_class('recipes') ?>" href="<?= url('recipes/index') ?>">
          <i class="bi bi-journal-richtext"></i> Browse Recipes
        </a>
        <a class="nav-link <?= active_class('mealplans') ?>" href="<?= url('mealplans/index') ?>">
          <i class="bi bi-calendar3"></i> Meal Plans
        </a>
        <a class="nav-link <?= active_class('favourites') ?>" href="<?= url('favourites/index') ?>">
          <i class="bi bi-heart"></i> Favourites
        </a>
        <span class="nav-label">Account</span>
        <a class="nav-link <?= active_class('subscriptions') ?>" href="<?= url('subscriptions/index') ?>">
          <i class="bi bi-gem"></i> Subscription
        </a>
        <a class="nav-link <?= active_class('payments') ?>" href="<?= url('payments/index') ?>">
          <i class="bi bi-receipt"></i> Payments
        </a>
        <a class="nav-link <?= active_class('notifications') ?>" href="<?= url('notifications/index') ?>">
          <i class="bi bi-bell"></i> Notifications
          <?php if(($unreadNotifCount ?? 0) > 0): ?>
            <span class="badge bg-danger ms-auto"><?= $unreadNotifCount ?></span>
          <?php endif; ?>
        </a>
        <a class="nav-link <?= active_class('profile') ?>" href="<?= url('profile/index') ?>">
          <i class="bi bi-person-circle"></i> Profile
        </a>
      </nav>
    </div>
    <!-- Main content -->
    <div class="col-lg-10">
      <div class="main-area">
        <?= render_flash() ?>
