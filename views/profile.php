<?php
session_start();
require_once('../models/userModel.php');

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

$email = $_SESSION['email'];

$user = getUserByEmail($email);

if ($user) {
    $firstName = $user['Fname'];
    $lastName = $user['Lname'];
    $dob = $user['DoB'];
    $license = $user['License'];
} else {
    header('Location: home.php?error=user_not_found');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Profile</title>
    <link rel="stylesheet" href="..\assets\CSS\login.css">
    <style>
        body {
            background-color: #DBE4C9;
        }
        .profile-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #556B2F;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            color: #EFF5D2;
        }
        .profile-header {
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            color: #DCFFB7;
        }
        .profile-form .form-group {
            margin-bottom: 15px;
        }
        .profile-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .profile-form input[type="text"],
        .profile-form input[type="email"],
        .profile-form input[type="date"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
            background-color: #EFF5D2;
            color: #27391C;
        }
        .update-btn {
            width: 100%;
            padding: 12px;
            background-color: #C6D870;
            color: #0A400C;
            border: none;
            border-radius: 6px;
            font-size: 17px;
            cursor: pointer;
            font-weight: bold;
        }
        .update-btn:hover {
            background-color: #D9EDBF;
        }
        .header-container {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding: 10px 20px;
            background-color: #556B2F;
            color: #DCFFB7;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            box-sizing: border-box;
        }
        .header-link {
            text-decoration: none;
            color: #DCFFB7;
            font-weight: bold;
            font-size: 16px;
        }
        .header-link:hover {
            text-decoration: underline;
        }
        .box {
            max-width: 450px;
            margin-top: 80px; 
        }
        .header {
            text-align: center;
        }
    </style>
</head>
<body>

<div class="header-container">
    <a href="home.php" class="header-link">Back to Home</a>
</div>

<div class="box" style="max-width: 450px;">
    <div class="header">My Profile</div>
    <form class="profile-form" action="../controllers/profile_action.php" method="POST" onsubmit="return validateProfile();">
        <div class="form-group">
            <label for="firstname">First Name</label>
            <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($firstName); ?>">
            <span><?php echo $_GET['e1'] ?? '' ?></span>
            <div id="FirstError" class="error"></div>
        </div>
        <div class="form-group">
            <label for="lastname">Last Name</label>
            <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($lastName); ?>">
            <span><?php echo $_GET['e2'] ?? '' ?></span>
            <div id="lastError" class="error"></div>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" readonly>
            <span><?php echo $_GET['e3'] ?? '' ?></span>
            <div id="emailError" class="error"></div>
        </div>
        <div class="form-group">
            <label for="dob">Date of Birth</label>
            <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($dob); ?>">
            <span><?php echo $_GET['e4'] ?? '' ?></span>
            <div id="dobError" class="error"></div>
        </div>
        <div class="form-group">
            <label for="license">License Number</label>
            <input type="text" id="license" name="license" value="<?php echo htmlspecialchars($license); ?>">
            <span><?php echo $_GET['e5'] ?? '' ?></span>
            <div id="licenseError" class="error"></div>
        </div>
        <span id="formError" class="error"></span>
        <button type="submit" id="updateBtn" class="login-btn">Update Profile</button>
    </form>
</div>
<script src="..\assets\Javascript\profile.js"></script>
</body>
</html>