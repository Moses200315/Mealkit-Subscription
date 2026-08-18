<?php require_once VIEWS_PATH . DS . 'layouts' . DS . 'customer_header.php'; ?>

<div class="row g-4 justify-content-center">
  <!-- Payment Form -->
  <div class="col-md-7">
    <div class="card border-0" style="border-radius:16px;box-shadow:0 4px 20px rgba(0,0,0,.08);">
      <div class="card-body p-4">
        <h4 class="fw-bold mb-1"><i class="bi bi-phone me-2 text-success"></i>Mobile Money Payment</h4>
        <p class="text-muted small mb-4">Complete your subscription using Mobile Money.</p>

        <form method="POST" action="<?= url('subscriptions/processPayment') ?>" id="paymentForm">
          <?= csrf_field() ?>
          <input type="hidden" name="plan_id" value="<?= $plan['id'] ?>">
          <input type="hidden" name="payment_method" value="mobile">

          <!-- Mobile Money Provider -->
          <div class="mb-4">
            <label class="form-label fw-semibold">Mobile Money Provider *</label>
            <div class="row g-2 mb-3">
              <?php foreach($providers as $code=>$name): ?>
              <div class="col-md-4">
                <input type="radio" class="btn-check" name="provider" id="prov_<?= $code ?>" value="<?= $code ?>" required>
                <label class="btn btn-outline-success w-100 py-2" for="prov_<?= $code ?>">
                  <div class="fw-semibold small"><?= e($code) ?></div>
                  <div style="font-size:.68rem;" class="opacity-75"><?= e($name) ?></div>
                </label>
              </div>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- Mobile Money Phone Number -->
          <div class="mb-4">
            <label class="form-label fw-semibold">Mobile Money Phone Number *</label>
            <div class="input-group input-group-lg">
              <span class="input-group-text"><i class="bi bi-phone"></i></span>
              <input type="tel" name="phone_number" class="form-control"
                     value="<?= e($userPhone) ?>"
                     placeholder="+255 700 000 000"
                     required>
            </div>
            <div class="text-muted small mt-1">Enter the number registered for Mobile Money</div>
          </div>

          <div class="border rounded p-3 mb-4" style="background:#f8fffe;border-radius:10px!important;">
            <div class="d-flex justify-content-between mb-1">
              <span class="text-muted">Plan</span>
              <span class="fw-semibold"><?= e($plan['name']) ?></span>
            </div>
            <div class="d-flex justify-content-between mb-1">
              <span class="text-muted">Duration</span>
              <span><?= $plan['duration_days'] ?> days</span>
            </div>
            <div class="d-flex justify-content-between border-top pt-2 mt-2">
              <span class="fw-bold">Total</span>
              <span class="fw-bold fs-5 text-success"><?= format_currency((float)$plan['price']) ?></span>
            </div>
          </div>

          <div class="d-grid gap-2">
            <button type="submit" class="btn btn-success btn-lg fw-bold" id="payBtn">
              <i class="bi bi-lock-fill me-2"></i>Pay <?= format_currency((float)$plan['price']) ?>
            </button>
            <a href="<?= url('subscriptions/index') ?>" class="btn btn-outline-secondary">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Plan Summary -->
  <div class="col-md-4">
    <div class="card border-0" style="background:linear-gradient(135deg,#2d6a4f,#52b788);border-radius:16px;color:#fff;">
      <div class="card-body p-4">
        <h5 class="fw-bold"><?= e($plan['name']) ?> Plan</h5>
        <div class="fs-3 fw-bold"><?= format_currency((float)$plan['price']) ?><small class="fs-6 opacity-75">/month</small></div>
        <hr class="border-light opacity-25">
        <ul class="list-unstyled small">
          <?php foreach($features as $f): ?>
          <li class="mb-2"><i class="bi bi-check-circle me-2 text-warning"></i><?= e($f) ?></li>
          <?php endforeach; ?>
        </ul>
        <hr class="border-light opacity-25">
        <div class="small opacity-75">
          <i class="bi bi-shield-check me-1"></i>Sandbox simulation – safe for testing.
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.getElementById('paymentForm').addEventListener('submit',function(){
  const btn=document.getElementById('payBtn');
  btn.disabled=true;
  btn.innerHTML='<span class="spinner-border spinner-border-sm me-2"></span>Processing…';
});
</script>
<?php require_once VIEWS_PATH . DS . 'layouts' . DS . 'customer_footer.php'; ?>
