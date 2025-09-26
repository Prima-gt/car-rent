<?php
session_start(); 

if(!isset($_SESSION['user_id'])){
  header('Location: ./login.php'); 
  exit; 
}
$pageTitle='Home'; 

require_once 'model/car_model.php'; 
require_once 'model/user_model.php';
$user = user_find_by_id($_SESSION['user_id']);

include __DIR__ . '/includes/header.php';
 ?>
  <!-- User Welcome Section -->
  <div class="box" style="max-width: 600px; margin-bottom: 20px;">
    <div style="display: flex; align-items: center; gap: 15px;">
      <div style="flex-shrink: 0;">
        <?php if(isset($user['profile_image']) && $user['profile_image']): ?>
          <img src="<?php echo htmlspecialchars($user['profile_image']); ?>" alt="Profile" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 2px solid #e5e7eb;">
        <?php else: ?>
          <div style="width: 60px; height: 60px; border-radius: 50%; background: #f3f4f6; border: 2px solid #e5e7eb; display: flex; align-items: center; justify-content: center; color: #6b7280; font-weight: bold; font-size: 20px;">
            <?php echo strtoupper(substr($user['name'] ?? 'U', 0, 1)); ?>
          </div>
        <?php endif; ?>
      </div>
      <div>
        <h2 style="margin: 0; color: #1f2937; font-size: 24px;">Welcome back, <?php echo htmlspecialchars($user['name'] ?? 'User'); ?>!</h2>
        <p style="margin: 5px 0 0 0; color: #6b7280; font-size: 14px;">Ready to find your perfect ride?</p>
      </div>
    </div>
  </div>

  <div class="box home-wrap">
    <div class="toolbar"> 
      <a class="pill active" href="#">All</a>
      <a class="pill" href="./myrent.php">My Rides</a>
      <a class="pill" href="./rent.php">Quick Rent</a> 
    </div>
    

    <?php $cars = cars_all_public();
     if(!empty($cars)): ?>
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
 
<?php include __DIR__ . '/includes/footer.php'; ?>

