<?php 
$pageTitle='Admin Panel';
session_start(); 
if(!isset($_SESSION['role']) || $_SESSION['role']!=='admin'){ 
  header('Location: ./login.php'); exit; 
}
require_once __DIR__ . '/model/db.php'; 
$conn=db_connect(); 
include __DIR__ . '/includes/header.php';
?>
  <div class="box home-wrap">
    <div class="toolbar">
      <div class="title">Admin Panel</div>
      <?php $pendingDrivers = mysqli_query($conn,"SELECT COUNT(*) AS c FROM users WHERE role='driver' AND approved=0"); $pd = $pendingDrivers? mysqli_fetch_assoc($pendingDrivers):['c'=>0]; ?>
      <div class="pill" style="cursor:default;">Pending drivers: <?php echo (int)$pd['c']; ?></div>
    </div>

    <div class="cars-title">Users</div>
    <div class="cars-grid">
      <?php $res=mysqli_query($conn,"SELECT * FROM users ORDER BY id DESC"); while($res && $u=mysqli_fetch_assoc($res)): ?>
        <div class="car-card">
          <div class="car-name"><?php echo htmlspecialchars($u['name']); ?> <span class="car-owner">(<?php echo htmlspecialchars($u['role']); ?>)</span></div>
          <div class="car-owner"><?php echo htmlspecialchars($u['email']); ?> | approved: <?php echo $u['approved']? 'yes':'no'; ?></div>
          <?php if($u['role']==='driver' && !$u['approved']): ?>
            <form method="POST" action="./controller/admin_controller.php" style="margin-top:8px;">
              <input type="hidden" name="action" value="approve_driver">
              <input type="hidden" name="user_id" value="<?php echo (int)$u['id']; ?>">
              <button class="login-btn" style="width:auto;">Approve</button>
            </form>
          <?php endif; ?>
        </div>
      <?php endwhile; ?>
    </div>

    <div class="cars-title" style="margin-top:16px;">Cars</div>
    <div class="cars-grid">
      <?php $res2=mysqli_query($conn,"SELECT * FROM cars ORDER BY id DESC"); while($res2 && $c=mysqli_fetch_assoc($res2)): ?>
        <div class="car-card">
          <div class="car-name"><?php echo htmlspecialchars($c['model']); ?></div>
          <div class="car-owner">Owner: <?php echo htmlspecialchars($c['owner']); ?> <?php if(isset($c['approved']) && $c['approved']==0){ echo '(driver not approved)'; } ?></div>
          <?php if(!empty($c['image'])){ echo '<img src="'.htmlspecialchars($c['image']).'" alt="car" class="car-img" style="height:120px;">'; } ?>
          <form method="POST" action="./controller/admin_controller.php" style="display:flex;gap:8px;margin-top:8px;">
            <input type="hidden" name="action" value="delete_car">
            <input type="hidden" name="car_id" value="<?php echo (int)$c['id']; ?>">
            <button class="login-btn" style="width:auto;background:#ef4444;">Delete</button>
          </form>
        </div>
      <?php endwhile; ?>
      <div class="car-card form-card" style="grid-column: 1 / -1;">
        <form method="POST" action="./controller/admin_controller.php" enctype="multipart/form-data" class="grid-2">
          <input type="hidden" name="action" value="add_car">
          <div class="form-group">
            <label for="model">Model</label>
            <input type="text" id="model" name="model">
          </div>
          <div class="form-group">
            <label for="owner">Owner</label>
            <input type="text" id="owner" name="owner">
          </div>
          <div class="form-group">
            <label for="driver_id">Assign Driver Id (optional)</label>
            <input type="number" id="driver_id" name="driver_id">
          </div>
          <div class="form-group">
            <label for="image">Car Image (optional)</label>
            <input type="file" id="image" name="image" accept="image/*">
          </div>
          <div><button class="login-btn" style="width:auto;">Add Car</button></div>
        </form>
      </div>
    </div>

    <div class="cars-title" style="margin-top:16px;">Bookings</div>
    <div class="cars-grid">
      <?php $res3=mysqli_query($conn,"SELECT * FROM rides ORDER BY id DESC"); if($res3 && mysqli_num_rows($res3)>0){ while($r=mysqli_fetch_assoc($res3)){ ?>
        <div class="car-card">
          <div class="car-name">#<?php echo (int)$r['id']; ?> | <?php echo htmlspecialchars($r['pickup']); ?> → <?php echo htmlspecialchars($r['dropoff']); ?></div>
          <div class="car-owner">Status: <?php echo htmlspecialchars($r['status']); ?> | $<?php echo htmlspecialchars($r['total_cost']); ?></div>
          <form method="POST" action="./controller/admin_controller.php" style="display:flex;gap:8px;margin-top:8px;align-items:center;">
            <input type="hidden" name="action" value="set_booking_status">
            <input type="hidden" name="ride_id" value="<?php echo (int)$r['id']; ?>">
            <select name="status" style="padding:6px;border-radius:6px;">
              <option value="pending" <?php echo $r['status']==='pending'?'selected':''; ?>>pending</option>
              <option value="accepted" <?php echo $r['status']==='accepted'?'selected':''; ?>>accepted</option>
              <option value="cancelled" <?php echo $r['status']==='cancelled'?'selected':''; ?>>cancelled</option>
              <option value="completed" <?php echo $r['status']==='completed'?'selected':''; ?>>completed</option>
            </select>
            <button class="login-btn" style="width:auto;">Update</button>
          </form>
        </div>
      <?php } } else { echo '<div class="car-owner">No bookings yet.</div>'; } ?>
    </div>
    <div class="cars-title" style="margin-top:16px;">Driver Complaints</div>
    <div class="cars-grid">
      <?php $cRes=mysqli_query($conn,"SELECT c.id, c.subject, c.details, c.created_at, u.name FROM complaints c JOIN users u ON c.driver_id=u.id ORDER BY c.id DESC"); if($cRes && mysqli_num_rows($cRes)>0){ while($cRow=mysqli_fetch_assoc($cRes)){ ?>
        <div class="car-card">
          <div class="car-name"><?php echo htmlspecialchars($cRow['subject']); ?></div>
          <div class="car-owner">By: <?php echo htmlspecialchars($cRow['name']); ?> | <?php echo htmlspecialchars($cRow['created_at']); ?></div>
          <div class="car-owner"><?php echo htmlspecialchars($cRow['details']); ?></div>
        </div>
      <?php } } else { echo '<div class="car-owner">No complaints.</div>'; } ?>
    </div>
    <?php mysqli_close($conn); ?>
  </div>
<?php include __DIR__ . '/includes/footer.php'; ?>

