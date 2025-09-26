<?php 
$pageTitle='Driver Panel'; 
session_start();
 if(!isset($_SESSION['role']) || $_SESSION['role']!=='driver'){ 
  header('Location: ./login.php'); 
  exit;
 }
require_once 'model/ride_model.php'; 
include __DIR__ . '/includes/header.php'; ?>
  <div class="box home-wrap">
    <div class="toolbar">
      <div class="title">Driver Panel</div>
      <div class="pill" style="cursor:default;">Earnings: <?php echo '$'.number_format(earnings_for_driver($_SESSION['user_id'] ?? 0),2); ?></div>
    </div>

    <div class="cars-title">My Rides</div>
    <div class="cars-grid">
      <?php $mine=isset($_SESSION['user_id'])? rides_for_driver($_SESSION['user_id']) : []; if(empty($mine)){ echo '<div class="car-owner">No rides.</div>'; } else { foreach($mine as $r){ ?>
        <div class="car-card">
          <div class="car-name">#<?php echo (int)$r['id']; ?> | <?php echo htmlspecialchars($r['pickup']); ?> → <?php echo htmlspecialchars($r['dropoff']); ?></div>
          <div class="car-owner">Status: <?php echo htmlspecialchars($r['status']); ?> | $<?php echo htmlspecialchars($r['total_cost']); ?></div>
          <?php if($r['status']!=='completed'): ?>
            <form method="POST" action="./controller/driver_controller.php" style="margin-top:8px;display:flex;gap:8px;">
              <input type="hidden" name="ride_id" value="<?php echo (int)$r['id']; ?>">
              <button class="login-btn" name="action" value="complete" style="width:auto;">Mark Completed</button>
            </form>
          <?php endif; ?>
        </div>
      <?php } } ?>
    </div>

    <div class="cars-title" style="margin-top:16px;">Available Rides (for your cars)</div>
    <div class="cars-grid">
      <?php $pending=rides_pending_for_driver($_SESSION['user_id'] ?? 0)
      ; if(empty($pending)){ echo '<div class="car-owner">No pending rides.</div>'; } else { foreach($pending as $r){ ?>
        <div class="car-card">
          <div class="car-name">#<?php echo (int)$r['id']; ?> | <?php echo htmlspecialchars($r['pickup']); ?> → <?php echo htmlspecialchars($r['dropoff']); ?></div>
          <div class="car-owner">$<?php echo htmlspecialchars($r['total_cost']); ?></div>
          <form method="POST" action="./controller/driver_controller.php" style="display:flex; gap:8px; margin-top:8px;">
            <input type="hidden" name="ride_id" value="<?php echo (int)$r['id']; ?>">
            <button class="login-btn" name="action" value="accept" style="width:auto;">Accept</button>
            <button class="login-btn" name="action" value="cancel" style="width:auto;background:#ef4444;">Cancel</button>
          </form>
        </div>
      <?php } } ?>
    </div>

    <a class="link" href="./driver_complaint.php" style="margin-top:10px;display:inline-block;">File Complaint</a>
    <a class="link" href="./home.php">Back</a>
  </div>
<?php include __DIR__ . '/includes/footer.php'; ?>

