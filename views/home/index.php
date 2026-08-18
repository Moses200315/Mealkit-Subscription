<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= e($pageTitle) ?></title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link rel="stylesheet" href="<?= asset('css/style.css') ?>">
<style>
:root{--primary:#2d6a4f;--accent:#f4a261;}
body{font-family:'Segoe UI',system-ui,sans-serif;}
.hero{background:linear-gradient(135deg,#1b4332 0%,#2d6a4f 50%,#52b788 100%);color:#fff;padding:5rem 0 4rem;position:relative;overflow:hidden;}
.hero::before{content:'';position:absolute;inset:0;background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");}
.hero h1{font-size:clamp(2rem,5vw,3.5rem);font-weight:900;line-height:1.1;}
.hero-badge{background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.3);color:#fff;border-radius:50px;padding:.4rem 1rem;font-size:.82rem;display:inline-block;margin-bottom:1.25rem;}
.recipe-card{border:none;border-radius:16px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,.07);transition:transform .2s,box-shadow .2s;}
.recipe-card:hover{transform:translateY(-4px);box-shadow:0 10px 28px rgba(45,106,79,.14);}
.recipe-card img{height:180px;object-fit:cover;}
.plan-card{border:none;border-radius:18px;padding:2rem;box-shadow:0 2px 16px rgba(0,0,0,.07);transition:transform .2s;}
.plan-card:hover{transform:translateY(-4px);}
.plan-card.popular{background:linear-gradient(135deg,#2d6a4f,#52b788);color:#fff;}
.plan-card.popular .plan-price{color:#fff;}
.plan-card.popular .btn-plan{background:#fff;color:#2d6a4f;border-color:#fff;}
.plan-price{font-size:2.5rem;font-weight:900;color:#2d6a4f;}
.plan-price sup{font-size:1rem;font-weight:600;}
.feature-icon{width:56px;height:56px;border-radius:14px;background:#f0faf5;display:flex;align-items:center;justify-content:center;font-size:1.6rem;margin-bottom:1rem;}
.category-chip{background:#fff;border:1px solid #d8ead3;border-radius:50px;padding:.4rem 1rem;font-size:.82rem;color:#2d6a4f;text-decoration:none;transition:all .15s;}
.category-chip:hover{background:#2d6a4f;color:#fff;border-color:#2d6a4f;}
.navbar-home{background:rgba(255,255,255,.95);backdrop-filter:blur(10px);border-bottom:1px solid #e2ece7;}
.navbar-home .navbar-brand{font-weight:800;color:#2d6a4f;font-size:1.2rem;}
</style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-home sticky-top">
  <div class="container">
    <a class="navbar-brand" href="<?= url('') ?>">🍽️ <?= APP_NAME ?></a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navHome">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navHome">
      <ul class="navbar-nav ms-auto gap-2 align-items-center">
        <li class="nav-item"><a class="nav-link fw-medium" href="#plans">Pricing</a></li>
        <li class="nav-item"><a class="nav-link fw-medium" href="#features">Features</a></li>
        <li class="nav-item"><a href="<?= url('auth/login') ?>" class="btn btn-outline-success btn-sm px-3">Sign In</a></li>
        <li class="nav-item"><a href="<?= url('auth/register') ?>" class="btn btn-success btn-sm px-3">Get Started</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- HERO -->
<section class="hero">
  <div class="container text-center position-relative">
    <div class="hero-badge">🌿 Subscription-Based Recipe & Meal Planning</div>
    <h1>Cook Smarter.<br>Eat Better. <span style="color:#f4a261;">Every Day.</span></h1>
    <p class="lead mt-3 mb-4 opacity-75">Discover hundreds of curated recipes, build weekly meal plans, and track your nutrition – all in one place.</p>
    <div class="d-flex gap-3 justify-content-center flex-wrap">
      <a href="<?= url('auth/register') ?>" class="btn btn-warning btn-lg fw-bold px-4" style="border-radius:12px;">
        Start Free Today <i class="bi bi-arrow-right ms-2"></i>
      </a>
      <a href="#featured" class="btn btn-outline-light btn-lg px-4" style="border-radius:12px;">
        Browse Recipes
      </a>
    </div>
    <div class="mt-4 d-flex justify-content-center gap-4 flex-wrap opacity-75 small">
      <span><i class="bi bi-check-circle-fill text-warning me-1"></i>Mobile Money payments</span>
    </div>
  </div>
</section>

<!-- FEATURES -->
<section class="py-5 bg-white" id="features">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold">Everything you need to cook confidently</h2>
      <p class="text-muted">Packed with tools designed for home cooks</p>
    </div>
    <div class="row g-4 text-center">
      <?php foreach([
        ['🍲','Recipe Library','Hundreds of curated recipes from easy to gourmet, with step-by-step instructions.'],
        ['📅','Meal Planning','Build your personal weekly meal plans and always know what to cook next.'],
        ['⚖️','Serving Calculator','Scale any recipe up or down instantly for the exact number of servings you need.'],
        ['💳','Card & Mobile Payments','Subscribe easily with secure card or mobile money payments.'],
        ['📥','PDF Downloads','Download beautifully formatted recipe PDFs to use offline in the kitchen.'],
        ['❤️','Favourites','Save your most-loved recipes and access them instantly anytime.'],
      ] as [$icon,$title,$desc]): ?>
      <div class="col-sm-6 col-md-4">
        <div class="feature-icon mx-auto"><?= $icon ?></div>
        <h5 class="fw-bold"><?= $title ?></h5>
        <p class="text-muted small"><?= $desc ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- CATEGORIES -->
<?php if(!empty($categories)): ?>
<section class="py-4" style="background:#f8fffe;">
  <div class="container">
    <div class="d-flex flex-wrap gap-2 justify-content-center">
      <?php foreach($categories as $cat): ?>
        <a href="<?= url('auth/login') ?>" class="category-chip">
          <?= e($cat['name']) ?>
          <span class="badge bg-success ms-1"><?= (int)$cat['recipe_count'] ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- POPULAR RECIPES -->
<?php if(!empty($featured)): ?>
<section class="py-5" id="featured">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div><h3 class="fw-bold mb-1">Popular Recipes</h3><p class="text-muted mb-0 small">Most viewed by our community</p></div>
      <?php if($hasMoreRecipes ?? false): ?>
      <a href="<?= url('auth/register') ?>" class="btn btn-outline-success btn-sm">Browse All Recipes</a>
      <?php endif; ?>
    </div>
    <div class="row g-4">
      <?php foreach($featured as $r): ?>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="card recipe-card h-100">
          <img src="<?= recipe_img_url($r['image']) ?>" class="card-img-top" alt="<?= e($r['title']) ?>">
          <div class="card-body">
            <?php if($r['is_premium']): ?><span class="premium-badge me-1">⭐ Premium</span><?php endif; ?>
            <h6 class="fw-bold mt-1 mb-1"><?= e($r['title']) ?></h6>
            <p class="text-muted small mb-2"><?= truncate(e($r['description']),80) ?></p>
            <div class="d-flex gap-2 small text-muted flex-wrap">
              <span><i class="bi bi-clock me-1"></i><?= format_duration((int)$r['prep_time']+(int)$r['cook_time']) ?></span>
              <span><i class="bi bi-people me-1"></i><?= e($r['servings']) ?> servings</span>
            </div>
          </div>
          <div class="card-footer bg-white border-0 pt-0 pb-3 px-3">
            <a href="<?= url('auth/register') ?>" class="btn btn-success btn-sm w-100">View Recipe</a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- PRICING -->
<section class="py-5 bg-white" id="plans">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold">Simple, Transparent Pricing</h2>
      <p class="text-muted">Pay with Mobile Money.</p>
    </div>
    <div class="row g-4 justify-content-center">
      <?php foreach($plans as $plan):
        $features = json_decode($plan['features'] ?? '[]', true) ?: [];
        $isPopular = (bool)$plan['is_popular'];
      ?>
      <div class="col-md-4">
        <div class="plan-card h-100 <?= $isPopular ? 'popular' : 'bg-light' ?>">
          <?php if($isPopular): ?><div class="badge bg-warning text-dark mb-2">Most Popular</div><?php endif; ?>
          <h4 class="fw-bold"><?= e($plan['name']) ?></h4>
          <div class="plan-price"><sup><?= CURRENCY_SYMBOL ?></sup><?= number_format((float)$plan['price'], 2) ?><small class="fs-6 fw-normal opacity-75">/month</small></div>
          <p class="small mt-2 mb-3 opacity-75"><?= e($plan['description']) ?></p>
          <ul class="list-unstyled small mb-4">
            <?php foreach($features as $f): ?>
              <li class="mb-1"><i class="bi bi-check-circle-fill me-2 <?= $isPopular ? 'text-warning' : 'text-success' ?>"></i><?= e($f) ?></li>
            <?php endforeach; ?>
          </ul>
          <a href="<?= url('auth/register') ?>" class="btn btn-plan w-100 fw-semibold <?= $isPopular ? 'btn-warning' : 'btn-success' ?>">
            Get Started
          </a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer class="py-4 text-center text-muted small" style="background:#1a1f2e;color:#8a9bb8!important;">
  <div class="container">
    <p class="mb-1" style="color:#8a9bb8;">🍽️ <?= APP_NAME ?> &copy; <?= date('Y') ?> – Subscription-Based Recipe & Meal Planning</p>
    <div class="d-flex gap-3 justify-content-center mt-2">
      <a href="<?= url('auth/login') ?>" style="color:#52b788">Login</a>
      <a href="<?= url('auth/register') ?>" style="color:#52b788">Register</a>
    </div>
  </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body></html>
