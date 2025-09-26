<?php 
session_start();
if(!isset($_SESSION['user_id'])){
  header('Location: ./login.php'); 
  exit; 
}

 require_once 'model/ride_model.php';
       $uid = isset($_SESSION['user_id'])? $_SESSION['user_id'] : 0; 
       $rides = $uid? ride_all_by_user_detailed($uid) : []; 
       

$pageTitle='Ride History'; 

include __DIR__ . '/includes/header.php';
 ?>
  <div class="box home-wrap">
    <div class="header">Ride History</div>
    <div id="rides" class="cars-grid">
      <?php 
     
       if(empty($rides)): ?>
        <p>No rides yet.</p>
      <?php else: foreach($rides as $r): ?>
        <div class="car-card">
          <div class="car-name">Ride #<?php echo (int)$r['id']; ?> | <?php echo htmlspecialchars($r['pickup']); ?> → <?php echo htmlspecialchars($r['dropoff']); ?></div>
          <div class="car-owner">Status: <?php echo htmlspecialchars($r['status']); ?> | Total: $<?php echo htmlspecialchars($r['total_cost']); ?></div>
          <?php if(!empty($r['car_model'])): ?>
            <div class="car-owner">Car: <?php echo htmlspecialchars($r['car_model']); ?></div>
          <?php endif; ?>
          <?php if(!empty($r['driver_name'])): ?>
            <div class="car-owner">Driver: <?php echo htmlspecialchars($r['driver_name']); ?></div>
          <?php endif; ?>
          <div style="margin-top:8px;display:flex;gap:8px;flex-wrap:wrap;">
            <a class="login-btn car-rent-btn" href="./chat.php?ride_id=<?php echo (int)$r['id']; ?>">Message</a>
            <?php if($r['status']==='completed'):
              require_once 'model/rating_model.php'; $given=rating_find_by_ride((int)$r['id']);
              if($given): ?>
                <div class="car-owner">Your rating: <?php echo (int)$given['stars']; ?>/5 - <?php echo htmlspecialchars($given['review']); ?></div>
              <?php else: ?>
              <form method="POST" action="./controller/rating_controller.php" style="display:inline;">
                <input type="hidden" name="ride_id" value="<?php echo (int)$r['id']; ?>">
                <input type="number" name="stars" min="1" max="5" placeholder="Stars" style="width:60px;">
                <input type="text" name="review" placeholder="Review" style="width:160px;">
                <input type="hidden" name="driver_id" value="<?php echo (int)($r['driver_id'] ?? 0); ?>">
                <button class="login-btn" style="width:auto;padding:4px 8px;">Rate</button>
              </form>
              <?php endif; endif; ?>
          </div>
        </div>
      <?php endforeach; endif; ?>
    </div>
    <a href="./home.php" class="link">Back to Home</a>
  </div>
<?php include __DIR__ . '/includes/footer.php'; ?>

