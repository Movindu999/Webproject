<?php
session_start();

// Check if the driver is logged in
if (!isset($_SESSION['Username']) || !isset($_SESSION['DriverID'])) {
    header("Location: Dlogin.html");
    exit();
}

// Connect to database
$conn = mysqli_connect("fdb1030.awardspace.net", "4581015_rentongo", "Movi#1024", "4581015_rentongo");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get driver's details
$driverUsername = mysqli_real_escape_string($conn, $_SESSION['Username']);
$driverID = intval($_SESSION['DriverID']);

// Delete booking 
if (isset($_GET['delete'])) {
    $bookingNumToDelete = intval($_GET['delete']);

    // Fetch the booking details before deleting
    $fetchQuery = "SELECT BookingNum, StDate, EndDate, VehicleType, CusID FROM booking WHERE BookingNum = $bookingNumToDelete AND DriverID = $driverID";
    $resultFetch = mysqli_query($conn, $fetchQuery);

    if ($resultFetch && mysqli_num_rows($resultFetch) > 0) {
        $row = mysqli_fetch_assoc($resultFetch);

        $bookingNum = $row['BookingNum'];
        $stDate = mysqli_real_escape_string($conn, $row['StDate']);
        $endDate = mysqli_real_escape_string($conn, $row['EndDate']);
        $vehicleType = mysqli_real_escape_string($conn, $row['VehicleType']);
        $cusID = intval($row['CusID']);

        //  Delete payment record first
        $deletePaymentQuery = "DELETE FROM Payment WHERE BookingNum = $bookingNum";
        if (!mysqli_query($conn, $deletePaymentQuery)) {
            echo "<script>alert('Failed to delete associated payment.'); window.location.href='DriverHome.php';</script>";
            exit();
        }

        //  Insert the deleted booking
        $insertQuery = "INSERT INTO deleted_bookings (BookingNum, StDate, EndDate, VehicleType, CusID)
                        VALUES ($bookingNum, '$stDate', '$endDate', '$vehicleType', $cusID)";
        mysqli_query($conn, $insertQuery);

        // Delete the booking
        $deleteQuery = "DELETE FROM booking WHERE BookingNum = $bookingNumToDelete AND DriverID = $driverID";
        if (mysqli_query($conn, $deleteQuery)) {
            echo "<script>alert('Booking and payment deleted successfully.'); window.location.href='DriverHome.php';</script>";
            exit();
        } else {
            echo "Error deleting booking: " . mysqli_error($conn);
        }
    } else {
        echo "Booking not found or unauthorized.";
    }
}



// Load bookings details for drivers
$sql = "SELECT BookingNum, StDate, EndDate, VehicleType, CusID FROM booking WHERE DriverID = $driverID";
$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Driver Home</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
    body {
        font-family: 'Poppins', Arial, sans-serif;
        background: linear-gradient(135deg, #eaf2f8, #f4f6f9);
        margin: 0;
        padding: 20px;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
    }

    h1 {
        text-align: center;
        margin-top: 40px;
        font-size: 32px;
        color: #2c3e50;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .table-container {
        width: 100%;
        display: flex;
        justify-content: center;
        margin-top: 40px;
    }

    table {
        width: 90%;
        max-width: 1200px;
        border-collapse: collapse;
        background: white;
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        animation: fadeIn 1s ease-in;
        overflow: hidden;
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

    .delete-btn {
        background: #2980b9;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 30px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        cursor: pointer;
        font-size: 14px;
        transition: background 0.3s, transform 0.3s ease-out;
        letter-spacing: 0.5px;
    }

    .delete-btn:hover {
        background: #1f618d;
        transform: scale(1.05);
    }

    @keyframes fadeIn {
        0% { opacity: 0; transform: translateY(-20px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    .logout-icon {
        position: absolute;
        top: 20px;
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
    </style>
</head>
<body>

<!-- Logout icon -->
<a href="logout.php" class="logout-icon" title="Logout">
    <i class="fas fa-sign-out-alt"></i>
</a>

<h1>Welcome, <?php echo htmlspecialchars($driverUsername); ?></h1>

<?php if (mysqli_num_rows($result) > 0) { ?>
<div class="table-container">
<table>
    <thead>
        <tr>
            <th>Booking Number</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Vehicle Type</th>
            <th>Customer ID</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['BookingNum']); ?></td>
            <td><?php echo htmlspecialchars($row['StDate']); ?></td>
            <td><?php echo htmlspecialchars($row['EndDate']); ?></td>
            <td><?php echo htmlspecialchars($row['VehicleType']); ?></td>
            <td><?php echo htmlspecialchars($row['CusID']); ?></td>
            <td>
                <a href="DriverHome.php?delete=<?php echo $row['BookingNum']; ?>" onclick="return confirm('Are you sure you want to cancel this booking?');">
                    <button class="delete-btn">Cancel</button>
                </a>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>
</div>
<?php } else { ?>
    <h2 style="text-align:center;">No bookings found for you.</h2>
<?php } ?>

</body>
</html>
