      </div><!-- /main-area -->
    </div><!-- /col -->
  </div><!-- /row -->
</div><!-- /container-fluid -->

<script>
  const CSRF_TOKEN = "<?= csrf_token() ?>";
  const APP_URL    = "<?= APP_URL ?>";
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script src="<?= asset('js/app.js') ?>"></script>
<?php if(isset($extraScripts)) echo $extraScripts; ?>
</body>
</html>
