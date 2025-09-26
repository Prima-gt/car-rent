<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rate Driver</title>
  <link rel="stylesheet" href="./assets/css/login.css">
</head>
<body>
  <div class="box" style="max-width: 500px;">
    <div class="header">Rate Driver</div>
    <form method="POST" action="#" onsubmit="return validateRate();">
      <div class="form-group">
        <label for="driver">Driver Name</label>
        <input type="text" id="driver" name="driver">
        <span id="driverError" class="error"></span>
      </div>
      <div class="form-group">
        <label for="stars">Stars (1-5)</label>
        <input type="number" id="stars" min="1" max="5" name="stars">
        <span id="starsError" class="error"></span>
      </div>
      <div class="form-group">
        <label for="review">Review</label>
        <input type="text" id="review" name="review">
        <span id="reviewError" class="error"></span>
      </div>
      <button class="login-btn" type="submit">Submit</button>
    </form>
    <a class="link" href="./home.php">Back</a>
  </div>
  <script>
    function validateRate(){
      var d=document.getElementById('driver').value.trim();
      var s=parseInt(document.getElementById('stars').value,10);
      var r=document.getElementById('review').value.trim();
      var v=true;
      document.getElementById('driverError').innerHTML='';
      document.getElementById('starsError').innerHTML='';
      document.getElementById('reviewError').innerHTML='';
      if(d.length<2){document.getElementById('driverError').innerHTML='Enter driver name';v=false;}
      if(isNaN(s) || s<1 || s>5){document.getElementById('starsError').innerHTML='Enter 1-5';v=false;}
      if(r.length<3){document.getElementById('reviewError').innerHTML='Add a short review';v=false;}
      return v;
    }
  </script>
</body>
</html>

