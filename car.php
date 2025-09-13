<?php 
session_start(); if(!isset($_GET['id'])){ header('Location: ./home.php'); exit; }
require_once __DIR__ . '/model/car_model.php';
require_once __DIR__ . '/model/db.php';
$pageTitle='Car Details';
include __DIR__ . '/includes/header.php';
$id=(int)$_GET['id'];
$car=car_find_by_id($id);
?>
  <div class="box home-wrap">
    <div class="header">Car Details</div>
    <?php if(!$car){ echo '<div class="alert alert-error">Car not found</div>'; } else { ?>
      <div class="car-card" style="display:flex;gap:12px;align-items:center;">
        <?php if(!empty($car['image'])): ?>
          <img class="car-img" style="width:160px;height:120px;" src="<?php echo htmlspecialchars($car['image']); ?>" alt="car">
        <?php endif; ?>
        <div>
          <div class="car-name"><?php echo htmlspecialchars($car['model']); ?></div>
          <div class="car-owner">Owner: <?php echo htmlspecialchars($car['owner']); ?></div>
        </div>
      </div>
      <?php 
        $conn=db_connect();
        $driverName=''; $avg=0; $count=0;
        if(!empty($car['driver_id'])){
          $did=(int)$car['driver_id'];
          $uRes=mysqli_query($conn,"SELECT name FROM users WHERE id=$did LIMIT 1");
          $uRow=$uRes? mysqli_fetch_assoc($uRes):null; $driverName=$uRow? $uRow['name'] : '';
          $rRes=mysqli_query($conn,"SELECT AVG(stars) AS a, COUNT(*) AS c FROM ratings WHERE driver_id=$did");
          $rRow=$rRes? mysqli_fetch_assoc($rRes):['a'=>0,'c'=>0]; $avg=(float)($rRow['a']?:0); $count=(int)($rRow['c']?:0);
        }
        mysqli_close($conn);
      ?>
      <div class="car-card" style="margin-top:10px;">
        <div class="car-name">Driver: <?php echo htmlspecialchars($driverName?:'Not assigned'); ?></div>
        <div class="car-owner">Rating: <?php echo number_format($avg,1); ?> (<?php echo (int)$count; ?> reviews)</div>
      </div>
      <?php if(!empty($car['driver_id'])): ?>
      <div class="cars-title" style="margin-top:12px;">Reviews</div>
      <div class="cars-grid">
        <?php 
          $conn=db_connect();
          $did=(int)$car['driver_id'];
          $revRes=mysqli_query($conn,"SELECT stars, review FROM ratings WHERE driver_id=$did ORDER BY id DESC");
          if(!$revRes || mysqli_num_rows($revRes)==0){ echo '<div class=\'car-owner\'>No reviews yet.</div>'; }
          else { while($rr=mysqli_fetch_assoc($revRes)){ echo '<div class=\'car-card\'><div class=\'car-name\'>'.(int)$rr['stars'].'/5</div><div class=\'car-owner\'>'.htmlspecialchars($rr['review']).'</div></div>'; } }
          mysqli_close($conn);
        ?>
      </div>
      <?php endif; ?>
      <a class="login-btn car-rent-btn" href="./rent.php?car_id=<?php echo (int)$car['id']; ?>" style="width:auto;display:inline-block;margin-top:10px;">Rent this car</a>
    <?php } ?>
  </div>
<?php include __DIR__ . '/includes/footer.php'; ?>

