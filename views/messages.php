<?php 
session_start(); 
if(!isset($_SESSION['user_id'])){ header('Location: ./login.php'); exit; }
require_once 'model/db.php';
$pageTitle='Messages';
include __DIR__ . '/includes/header.php';
$conn=db_connect();
$uid=(int)$_SESSION['user_id'];
$sql="SELECT r.id as ride_id, r.pickup, r.dropoff, u.name as other_name
      FROM rides r
      LEFT JOIN users u ON (CASE WHEN $uid=r.user_id THEN r.driver_id ELSE r.user_id END)=u.id
      WHERE (r.user_id=$uid OR r.driver_id=$uid) AND r.driver_id IS NOT NULL
      ORDER BY r.id DESC";
$res=mysqli_query($conn,$sql);
?>
  <div class="box home-wrap">
    <div class="header">Conversations</div>
    <?php if(!$res || mysqli_num_rows($res)==0){ echo '<div class="car-owner">No conversations yet.</div>'; } else { while($row=mysqli_fetch_assoc($res)){ ?>
      <div class="car-card" style="display:flex;align-items:center;justify-content:space-between;">
        <div>
          <div class="car-name">Ride #<?php echo (int)$row['ride_id']; ?> with <?php echo htmlspecialchars($row['other_name'] ?: 'Unknown'); ?></div>
          <div class="car-owner"><?php echo htmlspecialchars($row['pickup'].' → '.$row['dropoff']); ?></div>
        </div>
        <a class="login-btn" style="width:auto;min-width:90px;" href="./chat.php?ride_id=<?php echo (int)$row['ride_id']; ?>">Open</a>
      </div>
    <?php } } mysqli_close($conn); ?>
  </div>
<?php include __DIR__ . '/includes/footer.php'; ?>

