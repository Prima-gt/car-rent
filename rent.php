<?php
session_start();
if(!isset($_SESSION['user_id'])){
  header('Location: ./login.php'); 
  exit; 
}
 $pageTitle='Rent a Car'; 
 require_once __DIR__ . '/model/car_model.php';  

$pref = isset($_GET['car_id']) ? (int)$_GET['car_id'] : 0; 
include __DIR__ . '/includes/header.php';

?>
  <div class="box" style="max-width: 500px;">
    <div class="header">Rent a Car</div>
    <form method="POST" action="./controller/rent_controller.php" onsubmit="return validateRent();">
      <div class="form-group">
        <label for="pickup">Pickup Date</label>
        <input type="date" id="pickup" name="pickup">
        <span id="pickupError" class="error"></span>
      </div>
      <div class="form-group">
        <label for="dropoff">Dropoff Date</label>
        <input type="date" id="dropoff" name="dropoff">
        <span id="dropoffError" class="error"></span>
      </div>
      <div class="form-group">
        <label for="car_id">Car</label>
        <select id="car_id" name="car_id" style="width:100%;padding:10px;border:1px solid #ccc;border-radius:6px;" <?php echo $pref? 'disabled' : ''; ?>>
          <?php 
          $cars=cars_all(); 
          foreach($cars as $c){
             $label=$c['model'];
             if(!empty($c['image'])){ $label .= ' (img)'; }
             $sel = ($pref && $pref==(int)$c['id']) ? ' selected' : '';
             echo '<option value="'.(int)$c['id'].'"'.$sel.'>'.htmlspecialchars($label).'</option>'; 
          } ?>
        </select>
        <?php if($pref): ?><input type="hidden" name="car_id" value="<?php echo (int)$pref; ?>"><?php endif; ?>
        <span id="carError" class="error"></span>
      </div>
      <div id="carImgWrap" style="text-align:center;margin:10px 0;"></div>
      <div class="form-group">
        <label>Estimated Cost</label>
        <div id="estimate" style="font-weight:bold;color:#2563eb;">$0</div>
        <div style="font-size:12px;color:#6b7280;">Rate: $50 per day</div>
      </div>
      <button type="submit" class="login-btn">Book</button>
    </form>
    <a href="./home.php" class="link">Back</a>
  </div>
  <script>
    function validateRent(){
      var pickup=document.getElementById('pickup').value;
      var dropoff=document.getElementById('dropoff').value;
      var car=document.getElementById('car_id').value;
      var valid=true;
      document.getElementById('pickupError').innerHTML='';
      document.getElementById('dropoffError').innerHTML='';
      document.getElementById('carError').innerHTML='';
      if(!pickup){document.getElementById('pickupError').innerHTML='Select pickup date';valid=false;}
      if(!dropoff){document.getElementById('dropoffError').innerHTML='Select dropoff date';valid=false;}
      if(!car){document.getElementById('carError').innerHTML='Select a car';valid=false;}
      return valid;
    }
    (function(){
      var select=document.getElementById('car_id');
      var wrap=document.getElementById('carImgWrap');
      var data=<?php echo json_encode($cars); ?>;
      var pickup=document.getElementById('pickup');
      var dropoff=document.getElementById('dropoff');
      var est=document.getElementById('estimate');
      function render(){
        var id=parseInt(select.value,10);
        var found=(data||[]).find(function(x){return parseInt(x.id,10)===id});
        if(found && found.image){
          wrap.innerHTML='<img src="'+found.image+'" alt="car" style="max-width:100%;border-radius:6px;">';
        } else { wrap.innerHTML=''; }
        // estimate cost (days * 50)
        var p=new Date(pickup.value);
        var d=new Date(dropoff.value);
        if(pickup.value && dropoff.value && d>=p){
          var days=Math.ceil((d - p)/(1000*60*60*24));
          if(days<1) days=1;
          var cost=days*50;
          est.textContent='$'+cost.toFixed(2);
        } else {
          est.textContent='$0';
        }
      }
      select.addEventListener('change',render);
      pickup.addEventListener('change',render);
      dropoff.addEventListener('change',render);
      render();
    })();
  </script>
<?php include __DIR__ . '/includes/footer.php'; ?>

