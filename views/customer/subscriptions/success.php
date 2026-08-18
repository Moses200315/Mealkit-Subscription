<?php require_once VIEWS_PATH . DS . 'layouts' . DS . 'customer_header.php'; ?>
<div class="text-center py-5">
  <div style="width:80px;height:80px;background:#d8f3dc;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:2.5rem;margin:0 auto 1.5rem;">✅</div>
  <h2 class="fw-bold text-success">Payment Successful!</h2>
  <p class="text-muted mb-4">Your <?= e($payment['plan_name']) ?> subscription is now active.</p>

  <div class="card border-0 mx-auto mb-4" style="max-width:420px;border-radius:14px;box-shadow:0 2px 16px rgba(0,0,0,.07);">
    <div class="card-body p-4 text-start">
      <div class="d-flex justify-content-between mb-2"><span class="text-muted">Plan</span><strong><?= e($payment['plan_name']) ?></strong></div>
      <div class="d-flex justify-content-between mb-2"><span class="text-muted">Amount</span><strong class="text-success"><?= format_currency((float)$payment['amount']) ?></strong></div>
      <div class="d-flex justify-content-between mb-2"><span class="text-muted">Provider</span><strong><?= e($payment['provider']) ?></strong></div>
      <div class="d-flex justify-content-between mb-2"><span class="text-muted">Reference</span><code class="small"><?= e($payment['ref']) ?></code></div>
      <div class="d-flex justify-content-between"><span class="text-muted">Expires</span><strong><?= format_date($payment['ends_at'],'d F Y') ?></strong></div>
    </div>
  </div>

  <div class="d-flex gap-3 justify-content-center flex-wrap">
    <a href="<?= url('recipes/index') ?>" class="btn btn-success btn-lg px-4 fw-semibold">
      <i class="bi bi-journal-richtext me-2"></i>Browse Recipes
    </a>
    <a href="<?= url('customer/dashboard') ?>" class="btn btn-outline-secondary btn-lg px-4">Dashboard</a>
  </div>
</div>
<?php require_once VIEWS_PATH . DS . 'layouts' . DS . 'customer_footer.php'; ?>
