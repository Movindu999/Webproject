<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["btnConfirm"])) {
    if (
        isset($_POST["startDate"], $_POST["endDate"], $_POST["txtVehicleType"], 
              $_POST["txtRentalID"], $_POST["txtBill"], $_POST["cmbDriver"], $_POST["txtCusID"])
    ) {
        $startDate = $_POST["startDate"];
        $endDate = $_POST["endDate"];
        $vehicleType = $_POST["txtVehicleType"];
        $rentalID = $_POST["txtRentalID"];
        $bill = $_POST["txtBill"];
        $driverID = $_POST["cmbDriver"];
        $cusID = $_POST["txtCusID"];
        $PMethod = $_POST["cmbPayment"];

        if (!is_numeric($bill) || $bill <= 0) {
            echo "<script>alert('Invalid bill amount. Please generate the bill again.'); window.history.back();</script>";
            exit();
        }

        $con = mysqli_connect("fdb1030.awardspace.net", "4581015_rentongo", "Movi#1024", "4581015_rentongo");

        if (!$con) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Check vehicle availability
        $vehicleQuery = "SELECT * FROM booking 
                         WHERE VehicleType = '$vehicleType' 
                         AND ((StDate <= '$startDate' AND EndDate >= '$startDate') 
                           OR (StDate <= '$endDate' AND EndDate >= '$endDate') 
                           OR (StDate >= '$startDate' AND EndDate <= '$endDate'))";

        $vehicleResult = mysqli_query($con, $vehicleQuery);

        if (mysqli_num_rows($vehicleResult) > 0) {
            echo "<script>alert('This vehicle is already booked for the selected dates. Please choose different dates or vehicle.'); window.history.back();</script>";
            mysqli_close($con);
            exit();
        }

        // Check driver availability
        if (!empty($driverID)) {
            $driverQuery = "SELECT * FROM booking 
                            WHERE DriverID = '$driverID' 
                            AND ((StDate <= '$startDate' AND EndDate >= '$startDate') 
                              OR (StDate <= '$endDate' AND EndDate >= '$endDate') 
                              OR (StDate >= '$startDate' AND EndDate <= '$endDate'))";

            $driverResult = mysqli_query($con, $driverQuery);

            if (mysqli_num_rows($driverResult) > 0) {
                echo "<script>alert('This driver is already booked for the selected dates. Please choose different dates or driver.'); window.history.back();</script>";
                mysqli_close($con);
                exit();
            }
        }

        // Insert booking
        if (!empty($driverID)) {
            $insertQuery = "INSERT INTO booking (StDate, EndDate, VehicleType, Bill, RentalID, DriverID, CusID) 
                            VALUES ('$startDate', '$endDate', '$vehicleType', '$bill', '$rentalID', '$driverID', '$cusID')";
        } else {
            $insertQuery = "INSERT INTO booking (StDate, EndDate, VehicleType, Bill, RentalID, DriverID, CusID) 
                            VALUES ('$startDate', '$endDate', '$vehicleType', '$bill', '$rentalID', NULL, '$cusID')";
        }

        if (mysqli_query($con, $insertQuery)) {
            // Get the last inserted BookingNum
            $bookingNum = mysqli_insert_id($con);

            // Insert payment
            $insertPayment = "INSERT INTO Payment (BookingNum, PMethod, Bill) 
                              VALUES ('$bookingNum', '$PMethod', '$bill')";

            if (mysqli_query($con, $insertPayment)) {
                echo "<script>alert('Booking and payment confirmed successfully!'); 
                      window.location.href='RenatlHome.php?renter_id=$rentalID';</script>";
            } else {
                $paymentErr = mysqli_error($con);
                echo "<script>alert('Booking saved but payment failed: $paymentErr'); window.history.back();</script>";
            }
        } else {
            $error = mysqli_error($con);
            echo "<script>alert('Error saving booking: $error'); window.history.back();</script>";
        }

        mysqli_close($con);
    } else {
        echo "<script>alert('Missing required fields.'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href='RenatlHome.php';</script>";
}
?>
