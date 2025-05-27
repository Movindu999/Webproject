<?php

$FName = $_REQUEST["txtFirstName"];
$LName = $_REQUEST["txtLastName"];
$Gender = $_REQUEST["cmbGender"];
$Email = $_REQUEST["txtEmail"];
$Contact = $_REQUEST["txtContact"];
$NIC = $_REQUEST["txtNIC"];
$UserName = $_REQUEST["txtUserName"];
$Password = $_REQUEST["txtPassword"];
$Repassword = $_REQUEST["txtRepassword"];

if (isset($_POST["btnSignUp"])) {
    if ($Password !== $Repassword) {
        echo "<script>alert('Passwords do not match!'); window.history.back();</script>";
        exit(); // Stop further execution
    }
    if (!is_numeric($Contact)) {
        echo "<script>alert('Contact number should be numeric!'); window.history.back();</script>";
        exit(); // Stop further execution
    }
    // Connect to database
    $con=mysqli_connect("fdb1030.awardspace.net","4581015_rentongo","Movi#1024");
        mysqli_select_db($con,"4581015_rentongo");


    // Check connection
    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // INSERT DATA TO DATABASE
    $sql = "INSERT INTO Customers (FName, LName, Gender, Email, Contact, NIC, UserName, Password) 
            VALUES ('$FName', '$LName', '$Gender', '$Email', '$Contact', '$NIC', '$UserName', '$Password')";

    if (mysqli_query($con, $sql)) {
    echo "<script>alert('Registration Successful!'); window.location.href='Clogin.html';</script>";
	} else {
    echo "Error: " . mysqli_error($con);
	}

    mysqli_close($con);
}
?>
