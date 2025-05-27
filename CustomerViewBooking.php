<?php
session_start();

$username = $_SESSION['Username'];

// Connect to database
$conn = mysqli_connect("fdb1030.awardspace.net", "4581015_rentongo", "Movi#1024", "4581015_rentongo");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get CusID based on Username
$getCusIdQuery = "SELECT CusID FROM Customers WHERE UserName = '$username'";
$result = mysqli_query($conn, $getCusIdQuery);

if ($row = mysqli_fetch_assoc($result)) {
    $cusID = $row['CusID'];
} else {
    echo "<script>alert('Customer not found.'); window.location.href='Clogin.html';</script>";
    exit();
}

// Delete bookings and corresponding payment details
if (isset($_POST['btnDelete'])) {
    if (!empty($_POST['bookingNum'])) {
        $bookingNum = $_POST['bookingNum'];

        // both deletions happen together
        mysqli_begin_transaction($conn);

        try {
            // Delete payment details first
            $deletePaymentQuery = "DELETE FROM Payment WHERE BookingNum = '$bookingNum'";
            if (!mysqli_query($conn, $deletePaymentQuery)) {
                throw new Exception('Error deleting payment details.');
            }

            // Delete booking details after payment has been deleted
            $deleteBookingQuery = "DELETE FROM booking WHERE BookingNum = '$bookingNum' AND CusID = '$cusID'";
            if (!mysqli_query($conn, $deleteBookingQuery)) {
                throw new Exception('Error deleting booking details.');
            }

            mysqli_commit($conn);

            echo "<script>alert('Booking deleted successfully.'); window.location.href='CustomerViewBooking.php';</script>";
        } catch (Exception $e) {
            // Rollback the transaction if an error occurs
            mysqli_rollback($conn);

            echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href='CustomerViewBooking.php';</script>";
        }
    }
}

// Select bookings
$query = "SELECT BookingNum, StDate, EndDate, VehicleType, Bill, RentalID FROM booking WHERE CusID = '$cusID'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Bookings</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Logout Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background: linear-gradient(135deg, #eaf2f8, #f4f6f9); 
            margin: 0;
            padding: 0;
            min-height: 100vh;
            position: relative;
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

        .booking-header {
            text-align: center;
            margin-top: 60px;
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

        input[type="submit"] {
            background: #2980b9; 
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            font-size: 15px;
            transition: background 0.3s, transform 0.3s ease-out;
            letter-spacing: 0.5px;
        }

        input[type="submit"]:hover {
            background: #1f618d;
            transform: scale(1.05);
        }

        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(-20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<!-- Logout icon -->
<a href="javascript:history.back()" class="logout-icon" title="Go Back">
    <i class="fas fa-sign-out-alt"></i>
</a>

<?php
if (mysqli_num_rows($result) == 0) {
    echo "<div class='no-booking'>You haven't made any bookings yet!</div>";
} else {
    echo "<div class='booking-header'><strong>Hello, " . htmlspecialchars($username) . "! Here are your bookings</strong></div>";
    echo "<div class='table-container'>";
    echo "<table>";
    echo "<tr>
            <th>Booking Number</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Vehicle Type</th>
            <th>Total Bill</th>
            <th>Rental ID</th>
            <th>Delete Booking?</th>
          </tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['BookingNum']) . "</td>";
        echo "<td>" . htmlspecialchars($row['StDate']) . "</td>";
        echo "<td>" . htmlspecialchars($row['EndDate']) . "</td>";
        echo "<td>" . htmlspecialchars($row['VehicleType']) . "</td>";
        echo "<td>Rs. " . htmlspecialchars($row['Bill']) . "</td>";
        echo "<td>" . htmlspecialchars($row['RentalID']) . "</td>";
        echo "<td>
                <form method='POST' action=''>
                    <input type='hidden' name='bookingNum' value='" . htmlspecialchars($row['BookingNum']) . "'>
                    <input type='submit' name='btnDelete' value='Cancel'>
                </form>
              </td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "</div>";
}
?>

<?php
// Load deleted bookings from delete_booking table
$deletedQuery = "SELECT * FROM deleted_bookings WHERE CusID = '$cusID'";
$deletedResult = mysqli_query($conn, $deletedQuery);

if (mysqli_num_rows($deletedResult) > 0) {
    echo "<div class='booking-header'><strong>Messages: Booking Cancellations</strong></div>";
    echo "<div class='table-container'>";
    echo "<table>";
    echo "<tr>
            <th>Booking Number</th>
            <th>Vehicle Type</th>
            <th>Start Date</th>	
            <th>End Date</th>
          </tr>";

    while ($row = mysqli_fetch_assoc($deletedResult)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['BookingNum']) . "</td>";
        echo "<td>" . htmlspecialchars($row['VehicleType']) . "</td>";
        echo "<td>" . htmlspecialchars($row['StDate']) . "</td>";
        echo "<td>" . htmlspecialchars($row['EndDate']) . "</td>";
        echo "</tr>";
    }

    echo "</table>";
    echo "</div>";
}
?>

</body>
</html>

<?php mysqli_close($conn); ?>
