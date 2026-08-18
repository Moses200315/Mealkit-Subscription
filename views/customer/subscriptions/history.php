<?php require_once VIEWS_PATH . DS . 'layouts' . DS . 'customer_header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="fw-bold mb-0">Subscription History</h4>
  <a href="<?= url('subscriptions/index') ?>" class="btn btn-success btn-sm"><i class="bi bi-gem me-1"></i>View Plans</a>
</div>

<?php if($activeSub): ?>
<div class="card border-0 mb-4" style="background:linear-gradient(135deg,#2d6a4f,#52b788);border-radius:14px;color:#fff;">
  <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
      <div class="small opacity-75">Active Plan</div>
      <h4 class="fw-bold mb-0"><?= e($activeSub['plan_name']) ?></h4>
      <div class="small opacity-75">Expires <?= format_date($activeSub['ends_at'],'d F Y') ?> · <?= $permissions['days_remaining'] ?> days remaining</div>
    </div>
    <div class="d-flex gap-2">
      <a href="<?= url('subscriptions/index') ?>" class="btn btn-warning btn-sm fw-semibold">Upgrade</a>
    </div>
  </div>
</div>
<?php endif; ?>

<div class="card border-0" style="border-radius:14px;box-shadow:0 2px 12px rgba(0,0,0,.06);">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead><tr><th>Plan</th><th>Status</th><th>Start</th><th>Expiry</th><th>Auto-renew</th></tr></thead>
        <tbody>
          <?php foreach($history as $s): ?>
          <tr>
            <td><span class="badge bg-success"><?= e($s['plan_name']) ?></span><div class="text-muted small"><?= format_currency((float)$s['plan_price']) ?>/month</div></td>
            <td><?= status_badge($s['status']) ?></td>
            <td class="small"><?= $s['starts_at'] ? format_date($s['starts_at'],'d M Y') : '—' ?></td>
            <td class="small"><?= $s['ends_at'] ? format_date($s['ends_at'],'d M Y') : '—' ?></td>
            <td><?= $s['auto_renew'] ? '<span class="badge bg-info">Yes</span>' : '<span class="badge bg-secondary">No</span>' ?></td>
          </tr>
          <?php endforeach; ?>
          <?php if(empty($history)): ?><tr><td colspan="5" class="text-center py-4 text-muted">No subscription history.</td></tr><?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php require_once VIEWS_PATH . DS . 'layouts' . DS . 'customer_footer.php'; ?>
