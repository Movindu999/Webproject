<?php
session_start();

// Connect to database
$conn = mysqli_connect("fdb1030.awardspace.net", "4581015_rentongo", "Movi#1024", "4581015_rentongo");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}



// Select details from booking table
$query = "SELECT * FROM booking";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background: linear-gradient(135deg, #eaf2f8, #f4f6f9); 
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        .booking-header {
            text-align: center;
            margin-top: 40px;
            font-size: 32px;
            color: #2c3e50;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .table-container {
            display: flex;
            justify-content: center;
            margin-top: 50px;
        }

        table {
            width: 90%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1s ease-in;
        }

        thead {
            background: #2c3e50;
            color: white;
            text-transform: uppercase;
        }

        th, td {
            padding: 15px 20px;
            text-align: center;
            border-bottom: 1px solid #ddd;
            font-size: 16px;
            color: #34495e;
        }

        th {
            font-size: 18px;
            font-weight: 600;
            border-bottom: 2px solid #2c3e50;
        }

        tr {
            background-color: #ffffff;
            transition: all 0.3s ease;
        }

        tr:hover {
            background-color: #ecf0f1; 
            transform: scale(1.02);
            box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.1);
        }

        .no-booking {
            text-align: center;
            margin-top: 50px;
            font-size: 22px;
            color: #7f8c8d; 
        }

        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(-20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<?php
if (mysqli_num_rows($result) == 0) {
    echo "<div class='no-booking'>You haven't made any bookings yet!</div>";
} else {
    echo "<div class='table-container'>";
    echo "<table>";
    echo "<thead><tr>
            <th>Booking Number</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Vehicle Type</th>
            <th>Total Bill</th>
            <th>Rental ID</th>
            <th>Driver ID</th>
            <th>Customer ID</th>
          </tr></thead><tbody>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['BookingNum'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['StDate'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['EndDate'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['VehicleType'] ?? '') . "</td>";
        echo "<td>Rs. " . htmlspecialchars($row['Bill'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['RentalID'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['DriverID'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['CusID'] ?? '') . "</td>";
        echo "</tr>";
    }

    echo "</tbody></table>";
    echo "</div>";
}
mysqli_close($conn);
?>

</body>
</html>
