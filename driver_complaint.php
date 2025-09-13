<?php 
session_start(); 
if(!isset($_SESSION['role']) || $_SESSION['role']!=='driver'){
  header('Location: ./login.php'); exit;
}
$pageTitle='Driver Complaint';
include __DIR__ . '/includes/header.php';
require_once __DIR__ . '/model/db.php';
?>
  <div class="box home-wrap">
    <div class="header">File a Complaint</div>
    <div class="car-card form-card">
      <form method="POST" action="./controller/complaint_controller.php" onsubmit="return validateComplaint();" class="grid-2">
        <div class="form-group">
          <label for="subject">Subject</label>
          <input type="text" id="subject" name="subject">
          <span id="subjectError" class="error"></span>
        </div>
        <div class="form-group" style="grid-column:1 / -1;">
          <label for="details">Details</label>
          <input type="text" id="details" name="details">
          <span id="detailsError" class="error"></span>
        </div>
        <div><button type="submit" class="login-btn" style="width:auto;">Submit</button></div>
      </form>
    </div>

    <div class="cars-title" style="margin-top:16px;">My Complaints</div>
    <div class="cars-grid">
      <?php 
        $conn=db_connect(); $did=(int)$_SESSION['user_id'];
        $cRes=mysqli_query($conn,"SELECT subject, details, created_at FROM complaints WHERE driver_id=$did ORDER BY id DESC");
        if(!$cRes || mysqli_num_rows($cRes)==0){ echo '<div class="car-owner">No complaints yet.</div>'; }
        else { while($row=mysqli_fetch_assoc($cRes)){ echo '<div class="car-card"><div class="car-name">'.htmlspecialchars($row['subject']).'</div><div class="car-owner">'.htmlspecialchars($row['created_at']).'</div><div class="car-owner">'.htmlspecialchars($row['details']).'</div></div>'; } }
        mysqli_close($conn);
      ?>
    </div>
    <a class="link" href="./panel_driver.php" style="margin-top:10px;display:inline-block;">Back</a>
  </div>
  <script>
    function validateComplaint(){
      var s=document.getElementById('subject').value.trim();
      var d=document.getElementById('details').value.trim();
      var v=true;
      document.getElementById('subjectError').innerHTML='';
      document.getElementById('detailsError').innerHTML='';
      if(s.length<3){document.getElementById('subjectError').innerHTML='Enter subject';v=false;}
      if(d.length<5){document.getElementById('detailsError').innerHTML='Enter details';v=false;}
      return v;
    }
  </script>
<?php include __DIR__ . '/includes/footer.php'; ?>

