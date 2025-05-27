<?php
$RentalID=$_REQUEST["txtRentalID"];
$RName = $_REQUEST["txtDriverName"];
$Age = $_REQUEST["txtAge"];
$PerDayPrice = $_REQUEST["txtPerDayPrice"];
$Email = $_REQUEST["txtEmail"];
$Contact = $_REQUEST["txtContact"];
$UserName = $_REQUEST["txtUserName"];
$Password = $_REQUEST["txtPassword"];
$Repassword = $_REQUEST["txtRepassword"];



if (isset($_POST["btnSignUp"])) {
    if ($Password !== $Repassword) {
        echo "<script>alert('Passwords do not match!'); window.history.back();</script>";
        exit(); // Stop further execution
    }
    if (!is_numeric($Age)) {
        echo "<script>alert('Age should be numeric!'); window.history.back();</script>";
        exit(); // Stop further execution
    }
    if (!is_numeric($PerDayPrice)) {
        echo "<script>alert('Invalid Per day price!'); window.history.back();</script>";
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

    // Prepare SQL query
    $sql = "INSERT INTO Drivers (DName, Age,PerDayPrice, Email, Contact,UserName, Password,RentalID) 
            VALUES ('$RName', '$Age','$PerDayPrice', '$Email', '$Contact', '$UserName', '$Password','$RentalID')";

    if (mysqli_query($con, $sql)) {
    echo "<script>alert('Registration Successful!'); window.location.href='RentalHome.html';</script>";
	} else {
    echo "Error: " . mysqli_error($con);
	}

    mysqli_close($con);
}
?>
