<?php require_once VIEWS_PATH . DS . 'layouts' . DS . 'customer_header.php'; ?>

<div class="row g-4">
  <!-- Left: Recipe Main -->
  <div class="col-lg-8">
    <!-- Header -->
    <div class="card border-0 mb-4" style="border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,.08);">
      <img src="<?= recipe_img_url($recipe['image']) ?>" style="height:320px;object-fit:cover;width:100%;" alt="<?= e($recipe['title']) ?>">
      <div class="card-body p-4">
        <div class="d-flex gap-2 mb-2 flex-wrap">
          <span class="badge bg-success"><?= e($recipe['category_name']) ?></span>
          <?= difficulty_badge($recipe['difficulty']) ?>
          <?php if($recipe['is_premium']): ?><span class="premium-badge">⭐ Premium</span><?php endif; ?>
        </div>
        <h2 class="fw-bold mb-2"><?= e($recipe['title']) ?></h2>
        <p class="text-muted"><?= e($recipe['description']) ?></p>

        <!-- Meta row -->
        <div class="row g-3 mt-1 mb-3">
          <?php foreach([
            ['⏱️ Prep',format_duration((int)$recipe['prep_time'])],
            ['🔥 Cook',format_duration((int)$recipe['cook_time'])],
            ['⏰ Total',format_duration((int)$recipe['prep_time']+(int)$recipe['cook_time'])],
            ['👤 Serves','1 person'],
            $recipe['calories'] ? ['🥗 Energy content',$recipe['calories'].' kcal'] : null,
          ] as $meta): if(!$meta) continue; ?>
          <div class="col-auto">
            <div class="text-center bg-light rounded px-3 py-2" style="min-width:90px;">
              <div class="fw-bold small"><?= $meta[1] ?></div>
              <div class="text-muted" style="font-size:.7rem;"><?= $meta[0] ?></div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>

        <!-- Actions -->
        <div class="d-flex gap-2 flex-wrap">
          <!-- Favourite -->
          <form method="POST" action="<?= url('favourites/toggle') ?>" class="fav-form">
            <?= csrf_field() ?>
            <input type="hidden" name="recipe_id" value="<?= $recipe['id'] ?>">
            <button type="submit" class="btn btn-outline-danger fav-btn">
              <i class="bi <?= $isFav?'bi-heart-fill':'bi-heart' ?> me-1"></i>
              <span class="fav-label"><?= $isFav ? 'Saved' : 'Save' ?></span>
              <span class="badge bg-danger ms-1 fav-count"><?= $favCount ?></span>
            </button>
          </form>
          <!-- Download -->
          <?php if($permissions['can_download']): ?>
          <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#downloadModal">
            <i class="bi bi-download me-1"></i>Download PDF
          </button>
          <?php endif; ?>
          <!-- Add to meal plan -->
          <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addToPlanModal">
            <i class="bi bi-calendar-plus me-1"></i>Add to Plan
          </button>
        </div>
      </div>
    </div>

    <!-- Ingredients with Serving Calculator -->
    <div class="card border-0 mb-4" style="border-radius:14px;box-shadow:0 2px 10px rgba(0,0,0,.05);">
      <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="fw-bold mb-0">🛒 Ingredients</h5>
          <!-- Serving Size Calculator -->
          <div class="d-flex align-items-center gap-2">
            <label class="text-muted small mb-0">Servings:</label>
            <button class="btn btn-sm btn-outline-secondary" id="servDecrease">−</button>
            <span class="fw-bold px-2" id="servingCount"
                  data-recipe-id="<?= $recipe['id'] ?>"
                  data-original="<?= (int)$recipe['servings'] ?>">1</span>
            <button class="btn btn-sm btn-outline-secondary" id="servIncrease">+</button>
          </div>
        </div>
        <ul class="list-unstyled mb-0" id="ingredientList">
          <?php foreach($recipe['ingredients'] as $ing): ?>
          <li class="d-flex justify-content-between border-bottom py-2" data-original-qty="<?= e($ing['quantity']) ?>">
            <span><?= e($ing['name']) ?></span>
            <span class="fw-semibold text-success">
              <span class="ing-qty"><?= e($ing['quantity']) ?></span>
              <?php if($ing['unit']): ?> <?= e($ing['unit']) ?><?php endif; ?>
            </span>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>

    <!-- Procedures -->
    <div class="card border-0 mb-4" style="border-radius:14px;box-shadow:0 2px 10px rgba(0,0,0,.05);">
      <div class="card-body p-4">
        <h5 class="fw-bold mb-4">👨‍🍳 Method</h5>
        <?php foreach($recipe['procedures'] as $step): ?>
        <div class="d-flex gap-3 mb-4">
          <div style="width:40px;height:40px;background:#2d6a4f;color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;flex-shrink:0;">
            <?= (int)$step['step_number'] ?>
          </div>
          <div class="flex-grow-1">
            <p class="mb-2"><?= e($step['instruction']) ?></p>
            <?php if($step['tip']): ?>
            <div class="p-2 rounded" style="background:#fff9e6;border-left:3px solid #f4a261;font-size:.85rem;color:#7b4f00;">
              💡 <em><?= e($step['tip']) ?></em>
            </div>
            <?php endif; ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- Right: Sidebar -->
  <div class="col-lg-4">
    <!-- Related -->
    <?php if(!empty($related)): ?>
    <div class="card border-0 mb-3" style="border-radius:14px;box-shadow:0 2px 10px rgba(0,0,0,.05);">
      <div class="card-body p-3">
        <h6 class="fw-bold mb-3">🔗 Related Recipes</h6>
        <?php foreach($related as $r): ?>
        <div class="d-flex gap-2 mb-2 border-bottom pb-2">
          <img src="<?= recipe_img_url($r['image']) ?>" style="width:48px;height:48px;object-fit:cover;border-radius:8px;" alt="">
          <div class="flex-grow-1">
            <a href="<?= url('recipes/view/'.$r['id']) ?>" class="text-dark text-decoration-none fw-semibold small d-block"><?= e(truncate($r['title'],35)) ?></a>
            <div class="text-muted" style="font-size:.72rem;"><?= format_duration((int)$r['prep_time']+(int)$r['cook_time']) ?> · <?= e($r['servings']) ?> servings</div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <!-- Upgrade prompt for non-premium -->
    <?php if(!$permissions['can_download']): ?>
    <div class="card border-0 text-center" style="background:linear-gradient(135deg,#2d6a4f,#52b788);border-radius:14px;color:#fff;">
      <div class="card-body p-4">
        <div style="font-size:2rem;">📥</div>
        <h6 class="fw-bold mt-2">Download Recipes</h6>
        <p class="small opacity-75 mb-3">Upgrade to Basic or Premium to download print-ready PDF recipes.</p>
        <a href="<?= url('subscriptions/index') ?>" class="btn btn-warning btn-sm fw-semibold px-4">Upgrade Plan</a>
      </div>
    </div>
    <?php endif; ?>
  </div>
</div>

<!-- Add to Plan Modal -->
<div class="modal fade" id="addToPlanModal" tabindex="-1">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title fw-bold">Add to Meal Plan</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
      <p class="text-muted small">Go to your <a href="<?= url('mealplans/index') ?>">Meal Plans</a> to add this recipe to a specific day and meal slot.</p>
      <a href="<?= url('mealplans/index') ?>" class="btn btn-success w-100">Open Meal Plans</a>
    </div>
  </div></div>
</div>

<!-- Download Modal -->
<div class="modal fade" id="downloadModal" tabindex="-1">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title fw-bold">Download Recipe PDF</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
      <p class="text-muted small mb-3">How many servings would you like in the downloaded recipe?</p>
      <div class="d-flex align-items-center gap-3 mb-3">
        <button class="btn btn-outline-secondary" id="dlServDecrease">−</button>
        <span class="fw-bold fs-5" id="dlServingCount">1</span>
        <button class="btn btn-outline-secondary" id="dlServIncrease">+</button>
      </div>
      <p class="text-muted small">Original recipe serves: <strong><?= (int)$recipe['servings'] ?></strong></p>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      <a href="<?= url('recipes/download/'.$recipe['id']) ?>" class="btn btn-success" id="dlConfirmBtn">
        <i class="bi bi-download me-1"></i>Download
      </a>
    </div>
  </div></div>
</div>

<?php $extraScripts = '<script>
const origServings='.((int)$recipe['servings']).';
const recipeId='.$recipe['id'].';
const csrfToken="'.csrf_token().'";
let currentServings=1;

// Store the base quantities for 1 serving from data-original-qty attribute
const baseQuantities=[];
document.querySelectorAll(".ing-qty").forEach((el,i)=>{
  const qtyStr=el.closest("li").dataset.originalQty;
  baseQuantities.push(parseQuantity(qtyStr));
});

function parseQuantity(qtyStr){
  qtyStr=qtyStr.trim();
  // Handle fractions like "1/2"
  const fracMatch=qtyStr.match(/^(\d+)\s*\/\s*(\d+)$/);
  if(fracMatch)return parseFloat(fracMatch[1])/parseFloat(fracMatch[2]);
  
  // Handle mixed numbers like "1 1/2"
  const mixedMatch=qtyStr.match(/^(\d+)\s+(\d+)\s*\/\s*(\d+)$/);
  if(mixedMatch)return parseFloat(mixedMatch[1])+parseFloat(mixedMatch[2])/parseFloat(mixedMatch[3]);
  
  // Handle plain numbers
  const num=parseFloat(qtyStr);
  return isNaN(num)?0:num;
}

function changeServings(delta){
  currentServings=Math.max(1,Math.min(50,currentServings+delta));
  document.getElementById("servingCount").textContent=currentServings;
  
  // Calculate scaling factor from 1 serving to current servings (direct proportion)
  const factor=currentServings/1;
  
  // Scale each ingredient proportionally from the 1-serving base
  document.querySelectorAll(".ing-qty").forEach((el,i)=>{
    if(baseQuantities[i]!==undefined&&!isNaN(baseQuantities[i])){
      const newQty=baseQuantities[i]*factor;
      el.textContent=formatQuantityForDisplay(newQty);
    }
  });
}

// Attach event listeners to serving buttons
document.getElementById("servDecrease")?.addEventListener("click",()=>changeServings(-1));
document.getElementById("servIncrease")?.addEventListener("click",()=>changeServings(1));

// Download modal serving adjustment
let dlServings=1;
document.getElementById("dlServDecrease")?.addEventListener("click",()=>{
  dlServings=Math.max(1,dlServings-1);
  document.getElementById("dlServingCount").textContent=dlServings;
});
document.getElementById("dlServIncrease")?.addEventListener("click",()=>{
  dlServings=Math.min(50,dlServings+1);
  document.getElementById("dlServingCount").textContent=dlServings;
});
document.getElementById("dlConfirmBtn")?.addEventListener("click",function(e){
  e.preventDefault();
  const url=new URL(this.href);
  url.searchParams.set("servings",dlServings);
  window.open(url.toString(),"_blank");
});

// Helper to format quantity for display (similar to PHP format_quantity)
function formatQuantityForDisplay(value){
  const fractions={
    0.125:"1/8",0.25:"1/4",0.333:"1/3",
    0.375:"3/8",0.5:"1/2",0.625:"5/8",
    0.667:"2/3",0.75:"3/4",0.875:"7/8"
  };
  const whole=Math.floor(value);
  const decimal=Math.round(value-whole,3);
  
  // Find closest fraction
  let fracStr="";
  for(const frac in fractions){
    if(Math.abs(decimal-parseFloat(frac))<0.01){
      fracStr=fractions[frac];
      break;
    }
  }
  
  if(whole>0&&fracStr!=="")return whole+" "+fracStr;
  if(whole===0&&fracStr!=="")return fracStr;
  if(decimal===0.0||Math.abs(decimal)<0.01)return String(whole);
  return parseFloat(value.toFixed(2)).toString();
}

// AJAX favourite toggle
document.querySelectorAll(".fav-form").forEach(form=>{
  form.addEventListener("submit",function(e){
    e.preventDefault();
    const fd=new FormData(this);
    fetch(this.action,{method:"POST",body:fd})
      .then(r=>r.json())
      .then(d=>{
        if(d.success){
          const btn=this.querySelector(".fav-btn");
          const icon=btn.querySelector("i");
          const label=btn.querySelector(".fav-label");
          const count=btn.querySelector(".fav-count");
          if(d.added){icon.className="bi bi-heart-fill text-danger";if(label)label.textContent="Saved";}
          else{icon.className="bi bi-heart";if(label)label.textContent="Save";}
          if(count&&d.count!==undefined)count.textContent=d.count;
        }
      }).catch(()=>this.submit());
  });
});
</script>';
?>
<?php require_once VIEWS_PATH . DS . 'layouts' . DS . 'customer_footer.php'; ?>
