<?php require_once VIEWS_PATH . DS . 'layouts' . DS . 'customer_header.php'; ?>

<div class="text-center mb-5">
  <h2 class="fw-bold">Choose Your Plan</h2>
  <p class="text-muted">Pay with Mobile Money.</p>
</div>

<?php if($activeSub): ?>
<div class="alert alert-success d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
  <div><i class="bi bi-check-circle me-2"></i>You're on the <strong><?= e($activeSub['plan_name']) ?></strong> plan.
    Expires <?= format_date($activeSub['ends_at'],'d F Y') ?>.</div>
  <a href="<?= url('subscriptions/history') ?>" class="btn btn-sm btn-success">Manage</a>
</div>
<?php endif; ?>

<div class="row g-4 justify-content-center">
  <?php foreach($plans as $plan):
    $features = json_decode($plan['features'] ?? '[]', true) ?: [];
    $isPopular = (bool)$plan['is_popular'];
    $isCurrent = $activeSub && $activeSub['plan_id'] == $plan['id'];
  ?>
  <div class="col-md-4">
    <div class="card h-100 border-<?= $isPopular?'success':'0' ?>" style="border-radius:20px;box-shadow:0 4px 20px rgba(0,0,0,.08);<?= $isPopular?'border-width:2px!important;':'' ?>">
      <?php if($isPopular): ?><div class="card-header text-center bg-success text-white fw-semibold" style="border-radius:18px 18px 0 0;">🌟 Most Popular</div><?php endif; ?>
      <div class="card-body p-4">
        <h4 class="fw-bold"><?= e($plan['name']) ?></h4>
        <div class="my-3">
          <span style="font-size:2.5rem;font-weight:900;color:#2d6a4f;"><?= CURRENCY_SYMBOL ?><?= number_format((float)$plan['price'],2) ?></span>
          <span class="text-muted">/month</span>
        </div>
        <p class="text-muted small mb-4"><?= e($plan['description']) ?></p>

        <ul class="list-unstyled mb-4">
          <?php foreach($features as $f): ?>
          <li class="mb-2 d-flex align-items-start gap-2">
            <i class="bi bi-check-circle-fill text-success mt-1 flex-shrink-0"></i>
            <span class="small"><?= e($f) ?></span>
          </li>
          <?php endforeach; ?>
        </ul>

        <?php if($isCurrent): ?>
        <div class="btn btn-secondary w-100 disabled">Current Plan</div>
        <?php elseif((float)$plan['price'] == 0): ?>
        <a href="<?= url('subscriptions/checkout/'.$plan['id']) ?>" class="btn btn-outline-success w-100 fw-semibold">Get Free</a>
        <?php else: ?>
        <a href="<?= url('subscriptions/checkout/'.$plan['id']) ?>"
           class="btn w-100 fw-semibold <?= $isPopular?'btn-success':'btn-outline-success' ?>">
          <i class="bi bi-credit-card me-1"></i>Subscribe
        </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<div class="text-center mt-4">
  <p class="text-muted small">
    <i class="bi bi-shield-check me-1 text-success"></i>Secure payments.
  </p>
  <a href="<?= url('subscriptions/history') ?>" class="text-muted small text-decoration-none">View subscription history →</a>
</div>
<?php require_once VIEWS_PATH . DS . 'layouts' . DS . 'customer_footer.php'; ?>
