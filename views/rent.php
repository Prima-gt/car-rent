<?php
session_start();
if(!isset($_SESSION['user_id'])){
  header('Location: ./login.php'); 
  exit; 
}
 $pageTitle='Rent a Car'; 
 require_once 'model/car_model.php';  

$pref = isset($_GET['car_id']) ? (int)$_GET['car_id'] : 0; 
include __DIR__ . '/includes/header.php';

?>
  <div class="box" style="max-width: 500px;">
    <div class="header">Rent a Car</div>
    <form id="rentForm" method="POST" action="./controller/rent_controller.php" onsubmit="return submitRentAjax(event);">
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
      <button type="submit" id="bookBtn" class="login-btn">Book</button>
      <div id="rentMsg" style="margin-top:10px;"></div>
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
    function runmyfunc(){
      var select=document.getElementById('car_id');
      var wrap=document.getElementById('carImgWrap');
      var data=<?php echo json_encode($cars); ?>;
      var pickup=document.getElementById('pickup');
      var dropoff=document.getElementById('dropoff');
      var est=document.getElementById('estimate');
      
      // Cookie functions
      function setCookie(name, value, days) {
        var expires = "";
        if (days) {
          var date = new Date();
          date.setTime(date.getTime() + (days*24*60*60*1000));
          expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
      }
      
      function getCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for(var i=0;i < ca.length;i++) {
          var c = ca[i];
          while (c.charAt(0)==' ') c = c.substring(1,c.length);
          if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
        }
        return null;
      }
      
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
      
      // Load saved car selection from cookie
      var savedCarId = getCookie('selected_car_id');
      if(savedCarId && !<?php echo $pref ? 'true' : 'false'; ?>){
        select.value = savedCarId;
      }
      
      select.addEventListener('change', function(){
        // Save selected car to cookie
        setCookie('selected_car_id', select.value, 30); // 30 days
        render();
      });
      pickup.addEventListener('change',render);
      dropoff.addEventListener('change',render);
      render();
    };

    runmyfunc();

    function submitRentAjax(e){
      e.preventDefault();
      if(!validateRent()){ return false; }
      var form=document.getElementById('rentForm');
      var btn=document.getElementById('bookBtn');
      var msg=document.getElementById('rentMsg');
      msg.innerHTML='';
      btn.disabled=true; btn.textContent='Booking...';
      var fd=new FormData(form);
      fd.append('ajax','1');
      fetch(form.action,{
        method:'POST',
        headers:{'X-Requested-With':'XMLHttpRequest'},
        body:fd
      }).then(function(r){ return r.json().catch(function(){ return {ok:false,error:'Unexpected response'}; }); })
      .then(function(data){
        if(data && data.ok){
          msg.innerHTML='<div class="alert alert-success">'+(data.message||'Booked successfully')+'</div>';
          setTimeout(function(){ window.location.href = './myrent.php?success='+encodeURIComponent(data.message||'Ride booked successfully'); }, 700);
        } else {
          var err=(data && data.error)? data.error : 'Unable to book ride';
          msg.innerHTML='<div class="alert alert-error">'+err+'</div>';
        }
      }).catch(function(){
        msg.innerHTML='<div class="alert alert-error">Network error. Please try again.</div>';
      }).finally(function(){
        btn.disabled=false; btn.textContent='Book';
      });
      return false;
    }
  </script>
<?php include __DIR__ . '/includes/footer.php'; ?>

