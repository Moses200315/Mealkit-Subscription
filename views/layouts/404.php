<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>404 – Not Found | <?= APP_NAME ?></title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<style>
body{background:#f8fffe;display:flex;align-items:center;justify-content:center;min-height:100vh;font-family:'Segoe UI',system-ui,sans-serif;}
.error-box{text-align:center;max-width:480px;padding:2rem;}
.error-code{font-size:7rem;font-weight:900;color:#e9ecef;line-height:1;}
.error-emoji{font-size:3rem;margin:-1rem 0 .5rem;}
</style>
</head>
<body>
<div class="error-box">
  <div class="error-code">404</div>
  <div class="error-emoji">🍽️</div>
  <h2 class="fw-bold text-dark">Page Not Found</h2>
  <p class="text-muted mb-4"><?= e($message ?? "The page you're looking for doesn't exist or has been moved.") ?></p>
  <a href="<?= url('') ?>" class="btn btn-success px-4">
    <i class="bi bi-house me-2"></i>Go Home
  </a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
