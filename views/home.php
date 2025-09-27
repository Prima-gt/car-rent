<?php

// session_start();
// var_dump($_SESSION['user_pic']);
// exit();

session_start();
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

$firstName = isset($_SESSION['user_fname']) ? $_SESSION['user_fname'] : '';
$profilePic = isset($_SESSION['user_pic']) ? '../assets/profile_picture/' . $_SESSION['user_pic'] : '../assets/profile_picture/default.jpg';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Dashboard</title>
    <link rel="stylesheet" href="..\assets\CSS\home.css">
    <script src="https://maps.googleapis.com/maps/api/js?key=KEY_HIDDEN&callback=initMap" async defer></script>
    
</head>
<body>
    <div class="header-container">
        <h1 class="header-title">Driver Dashboard</h1>
        <div class="header-links">
            <div class="user-info">
                <img src="<?php echo htmlspecialchars($profilePic); ?>" alt="Profile Picture" class="profile-pic">
                <a href="profile.php" class="user-fname-link"><?php echo htmlspecialchars($firstName); ?></a>
            </div>
            <a href="Ride_his.html" class="header-link">History</a>
            <a href="complaint.html" class="header-link">Complaint</a>
            <a href="../controllers/logout_action.php" class="header-link">Logout</a>
        </div>
    </div>
    <div class="content-container">
        <div class="main-content">
            <div class="map-box">
                <div id="map"></div>
            </div>
            <div class="right-column">
                <div class="dashboard-info">
                    <div class="info-title">TOTAL EARNINGS TODAY</div>
                    <div class="info-value">2579Tk</div>
                </div>
                <div class="jobs-list">
                    <div class="jobs-list-header">
                        <h2 class="section-heading">Jobs Available (3)</h2>
                        <a href="Ride_his.html" class="history-link">Ride History</a>
                    </div>
                    <div class="job-card">
                        <div class="job-details">
                            <h3 class="job-title">Rider 1</h3>
                            <p class="job-text">5.2 km away</p>
                        </div>
                        <div class="job-buttons">
                            <span class="job-earning">532Tk</span>
                            <a href="ride_info.html" class="accept-btn">Accept</a>
                            <button class="cancel-btn">Cancel</button>
                        </div>
                    </div>
                    <div class="job-card">
                        <div class="job-details">
                            <h3 class="job-title">Rider 2</h3>
                            <p class="job-text">8.1 km away</p>
                        </div>
                        <div class="job-buttons">
                            <span class="job-earning">849Tk</span>
                            <a href="ride_info.html" class="accept-btn">Accept</a>
                            <button class="cancel-btn">Cancel</button>
                        </div>
                    </div>
                    <div class="job-card">
                        <div class="job-details">
                            <h3 class="job-title">Rider 3</h3>
                            <p class="job-text">2.5 km away</p>
                        </div>
                        <div class="job-buttons">
                            <span class="job-earning">266Tk</span>
                            <a href="ride_info.html" class="accept-btn">Accept</a>
                            <button class="cancel-btn">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    function initMap() {
        const map = new google.maps.Map(document.getElementById("map"), {
            center: { lat: 23.8221, lng: 90.4274 },
            zoom: 12,
        });
    }

</script>
</body>

</html>
