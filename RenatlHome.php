<?php
session_start();

// Database connection
$conn = mysqli_connect("fdb1030.awardspace.net", "4581015_rentongo", "Movi#1024", "4581015_rentongo");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
//Check renter login
$renterID = isset($_GET['renter_id']) ? intval($_GET['renter_id']) : 0;
$renterName = "Unknown Renter";

if ($renterID > 0) {
    $sql = "SELECT RName FROM Renters WHERE RentalID = $renterID";
    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        $renterName = htmlspecialchars($row['RName']);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="styles.css"> 
    <script src="jquery-3.7.1.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"> <!-- Logout icon -->

    <title>RentOnGo</title>
</head>
<body class="gradient-background">
        
   <header>
    <div class="container d-flex justify-content-between align-items-center py-3 px-4">
        <div class="brand-text fw-bold" style="font-size: 32px; background: linear-gradient(90deg, #00c6ff, #0072ff); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
            RentOnGo
        </div>

        <div class="d-flex align-items-center gap-4">
            <div class="renter-name fw-bold text-white" style="font-size: 18px;">
            <!-- Logout icon -->
			<a href="javascript:history.back()" class="logout-icon" title="Go Back">
 		  	  <i class="fas fa-sign-out-alt"></i>
			</a>
            </div>
            <div class="navicon d-flex gap-3">
                    Welcome to <?php echo $renterName; ?>         
            </div>
                       
        </div>
    </div>
</header>
 
    <section class="all-events">
        <div class="container-fluid">
            <br><br><br><br><br>
            <div class="vehicle-container">
                <div class="event-card">
                    <img src="car.jpg" alt="Car" class="img-fluid">
                    <div class="event-info">
                        <h3>Car</h3>
                        <br><br>
			<a href="ViewVehicles.php?renter_id=<?php echo $renterID; ?>&vehicle_type=Car" class="btn btn-primary">View</a>
                    </div>
                </div>
                <div class="event-card">
                    <img src="kdh.jpg" alt="Van" class="img-fluid">
                    <div class="event-info">
                        <h3>Van</h3>
                        <br><br>
			<a href="ViewVehicles.php?renter_id=<?php echo $renterID; ?>&vehicle_type=Van" class="btn btn-primary">View</a>
                    </div>
                </div>
                <div class="event-card">
                    <img src="bk.jpg" alt="Bike" class="img-fluid">
                    <div class="event-info">
                        <h3>Motor Bike</h3>
                        <br><br>
			<a href="ViewVehicles.php?renter_id=<?php echo $renterID; ?>&vehicle_type=Motor Bike" class="btn btn-primary">View</a>
                    </div>
                </div>
                <div class="event-card">
                    <img src="bus.jpg" alt="Bus" class="img-fluid">
                    <div class="event-info">
                        <h3>Bus</h3>
                        <br><br>
			<a href="ViewVehicles.php?renter_id=<?php echo $renterID; ?>&vehicle_type=Bus" class="btn btn-primary">View</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer id="footer">
        <div>
            <a href="#">Facebook</a> |
            <a href="#">Twitter</a> |
            <a href="#">Instagram</a>
        </div>
        <p>&copy; 2025 RentOnGo. All rights reserved.</p>
    </footer>

    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .vehicle-container {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
            padding: 20px;
        }
            
        .logout-icon {
            position: absolute;
            top: 7px;
            right: 30px;
            font-size: 24px;
            color: #2c3e50;
            text-decoration: none;
            z-index: 1000;
            transition: color 0.3s ease, transform 0.2s ease;
        }

        .logout-icon:hover {
            color: #e74c3c;
            transform: scale(1.1);
        }

        .event-card {
            background-color: white;
            text-align: center;
            width: 250px;
            padding: 20px;
            border-radius: 8px;
            transition: transform 0.2s;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .event-card:hover {
            transform: scale(1.05);
        }

        .event-card img {
            width: 100%;
            border-radius: 8px;
        }

        .btn-primary {
            background-color: royalblue;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-primary:hover {
            background-color: darkblue;
        }

        #footer {
            background: linear-gradient(135deg, #165066, #337D8F);
            color: white;
            padding: 30px 0;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        #footer a {
            color: #a3d8e5;
            text-decoration: none;
            margin: 0 10px;
        }

        footer {
            margin-top: 170px; 
        }
    </style>
</body>
</html>
