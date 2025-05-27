<?php
session_start();

$username = $_SESSION['Username'];

// Connect to database
$conn = mysqli_connect("fdb1030.awardspace.net", "4581015_rentongo", "Movi#1024", "4581015_rentongo");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get RentalID based on Username
$getRentalIdQuery = "SELECT RentalID FROM Renters WHERE UserName = '$username'";
$result = mysqli_query($conn, $getRentalIdQuery);

if ($row = mysqli_fetch_assoc($result)) {
    $rentalID = $row['RentalID'];
} else {
    echo "<script>alert('Renter not found.'); window.location.href='Rlogin.html';</script>";
    exit();
}

// Select payment details based on RentalID
$paymentQuery = "
    SELECT p.PaymentID, p.BookingNum, p.PMethod, p.Bill 
    FROM Payment p
    JOIN booking b ON p.BookingNum = b.BookingNum
    WHERE b.RentalID = '$rentalID'
";

$paymentResult = mysqli_query($conn, $paymentQuery);



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Renter Bookings</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">         <!--Logout Icon -->
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
    <a href="RentalHome.html" class="logout-icon" title="Go Back">
        <i class="fas fa-sign-out-alt"></i>
    </a>

    <?php
    if (mysqli_num_rows($paymentResult) == 0) {
        echo "<div class='no-booking'>You haven't received any bookings yet!</div>";
    } else {
        echo "<div class='booking-header'><strong>Hello, $username! Here are your financial details</strong></div>";
        echo "<div class='table-container'>";
        echo "<table>";
        echo "<tr>
                <th>Payment ID</th>
                <th>Booking Number</th>
                <th>Payment method</th>
                <th>Total Bill</th>
              </tr>";

        while ($row = mysqli_fetch_assoc($paymentResult)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['PaymentID']) . "</td>";
            echo "<td>" . htmlspecialchars($row['BookingNum']) . "</td>";
            echo "<td>" . htmlspecialchars($row['PMethod']) . "</td>";
            echo "<td>Rs. " . htmlspecialchars($row['Bill']) . "</td>";
            echo "</tr>";
        }

        echo "</table>";
        echo "</div>";
    }
    ?>

</body>
</html>

<?php
mysqli_close($conn);
?>
