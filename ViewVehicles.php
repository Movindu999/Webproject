<?php
session_start();

$RentalID = isset($_SESSION['RentalID']) ? intval($_SESSION['RentalID']) : 0;

$renterID = isset($_GET['renter_id']) ? intval($_GET['renter_id']) : 0;
$vehicleType = isset($_GET['vehicle_type']) ? htmlspecialchars($_GET['vehicle_type']) : '';
$vehicleNumber = isset($_GET['number']) ? htmlspecialchars($_GET['number']) : '';

$conn = mysqli_connect("fdb1030.awardspace.net", "4581015_rentongo", "Movi#1024", "4581015_rentongo");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "";
if ($vehicleType == 'Car') {
    $sql = "SELECT CarNumber, Type, PerDayPrice, Image FROM Car WHERE RentalID = '$renterID'";
} elseif ($vehicleType == 'Van') {
    $sql = "SELECT VanNumber, Type, PerDayPrice, Image FROM Van WHERE RentalID = '$renterID'";
} elseif ($vehicleType == 'Motor Bike') {
    $sql = "SELECT BikeNumber, Type, PerDayPrice, Image FROM Bike WHERE RentalID = '$renterID'";
} elseif ($vehicleType == 'Bus') {
    $sql = "SELECT BusNumber, Type, PerDayPrice, Image FROM Bus WHERE RentalID = '$renterID'";
}

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Vehicles - RentOnGo</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="styles.css">
    <script src="jquery-3.7.1.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .header-container {
            background-color: #343a40;
            padding: 15px 0;
            text-align: center;
            color: white;
        }

        .main-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .table-wrapper {
            width: 80%;
            max-width: 1000px;
            margin: 20px auto;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 auto;
        }

        table th, table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #f8f9fa;
            color: #333;
            font-weight: 500;
        }

        table tr:hover {
            background-color: #f5f5f5;
        }

        .action-btn {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .action-btn:hover {
            background-color: #0069d9;
        }

        @media (max-width: 768px) {
            .table-wrapper {
                width: 95%;
                padding: 10px;
            }
            
            table th, table td {
                padding: 8px 10px;
                font-size: 14px;
            }
        }

        /* Zoom Modal Style */
        #zoomModal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.9);
            align-items: center;
            justify-content: center;
        }

        #zoomModal img {
            max-width: 90%;
            max-height: 90%;
        }

        #zoomModal span {
            position: absolute;
            top: 20px;
            right: 30px;
            font-size: 40px;
            color: white;
            cursor: pointer;
        }

    </style>
</head>
<body>
    <div class="header-container">
        <h1>RentOnGo - Vehicle Management</h1>
    </div>
    <br><br><br>
    <h2><center>Book your vehicle and have a safe ride </center></h2>

    <div class="main-container">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Vehicle Number</th>
                        <th>Rental ID</th>
                        <th>Type</th>
                        <th>Price per day</th>
                        <th>Images</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $vehicleNumber = $vehicleType == 'Car' ? $row['CarNumber'] :
                                             ($vehicleType == 'Van' ? $row['VanNumber'] :
                                             ($vehicleType == 'Motor Bike' ? $row['BikeNumber'] : $row['BusNumber']));
                            
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($vehicleNumber) . "</td>";
                            echo "<td>" . htmlspecialchars($renterID) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Type']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['PerDayPrice']) . "</td>";
                            $imagePath = 'uploads/' . htmlspecialchars($row['Image']);
                            echo "<td><img src='$imagePath' alt='Vehicle Image' style='width:60px; height:40px; cursor:pointer;' onclick='zoomImage(this.src)'></td>";
                            echo "<td><a href='Booking.php?vehicle_type=" . urlencode($row['Type']) . 
                                 "&price=" . urlencode($row['PerDayPrice']) . 
                                 "&renter_id=" . urlencode($renterID) . 
                                 "' class='action-btn'>Book Now</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' style='text-align: center;'>No vehicles found for this renter.</td></tr>";
                    }
                    mysqli_close($conn);
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Zoom Modal -->
    <div id="zoomModal" onclick="closeZoom()">
        <span>&times;</span>
        <img id="zoomedImage" src="">
    </div>

    <script>
        function zoomImage(src) {
            document.getElementById("zoomedImage").src = src;
            document.getElementById("zoomModal").style.display = "flex";
        }

        function closeZoom() {
            document.getElementById("zoomModal").style.display = "none";
        }
    </script>
</body>
</html>
