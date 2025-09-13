<?php
session_start(); 
if(!isset($_SESSION['user_id'])){
  header('Location: ./login.php'); 
  exit; 
}
$pageTitle='Home'; 

require_once __DIR__ . '/model/car_model.php'; 
include __DIR__ . '/includes/header.php'; ?>
  <div class="box home-wrap">
    <div class="toolbar"> 
      <a class="pill active" href="#">All</a>
      <a class="pill" href="./myrent.php">My Rides</a>
      <a class="pill" href="./rent.php">Quick Rent</a> 
    </div>
    

    <?php $cars = cars_all_public(); if(!empty($cars)): ?>
      <hr>
      <div class="cars-title">Available Cars</div>
      <div class="cars-grid" id="carsList">
      <?php foreach($cars as $c): ?>
        <div class="car-card car-item" data-name="<?php echo htmlspecialchars(strtolower($c['model'])); ?>">
          <?php if(!empty($c['image'])): ?>
            <img class="car-img" src="<?php echo htmlspecialchars($c['image']); ?>" alt="car">
          <?php endif; ?>
          <div class="car-name"><a href="./car.php?id=<?php echo (int)$c['id']; ?>" style="text-decoration:none;color:inherit;"><?php echo htmlspecialchars($c['model']); ?></a></div>
          <div class="car-owner">Owner: <?php echo htmlspecialchars($c['owner']); ?></div>
          <div style="display:flex;gap:8px;margin-top:8px;">
            <a class="login-btn car-rent-btn" style="width:auto;" href="./rent.php?car_id=<?php echo (int)$c['id']; ?>">Rent</a>
            <a class="login-btn car-rent-btn" style="width:auto;background:#111827;" href="./car.php?id=<?php echo (int)$c['id']; ?>">View</a>
          </div>
        </div>
      <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
  <script>
    (function(){
      var s=document.getElementById('search');
      if(!s) return;
      var items=document.querySelectorAll('.car-item');
      s.addEventListener('input',function(){
        var q=this.value.toLowerCase();
        items.forEach(function(el){
          var name=el.getAttribute('data-name')||'';
          el.style.display = name.indexOf(q)>-1 ? '' : 'none';
        });
      });
    })();
  </script>
<?php include __DIR__ . '/includes/footer.php'; ?>

