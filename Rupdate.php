<?php
session_start(); // Start the session to get the logged-in user

// Check if the user is logged in
if (!isset($_SESSION['Username'])) {
    header("Location: Rlogin.html"); // Redirect to login if not logged in
    exit();
}

// Get the current user details from the session
$username = $_SESSION['Username'];

// Connect to the database
$con=mysqli_connect("fdb1030.awardspace.net","4581015_rentongo","Movi#1024");
    mysqli_select_db($con,"4581015_rentongo");

// Check the connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch the user details based on the logged-in username
$sql = "SELECT * FROM Renters WHERE UserName='$username'";
$result = mysqli_query($con, $sql);

// Check if the user exists
if ($row = mysqli_fetch_array($result)) {
    // Populate the form with the user's existing details
    $RName = $row['RName'];
    $Address = $row['Address'];
    $Email = $row['Email'];
    $Contact = $row['Contact'];
    $UserName = $row['UserName'];
    $Password = $row['Password'];
    $Repassword = $Password;
} else {
    
    echo "<script>alert('User not found!'); window.location.href='Rlogin.html';</script>";
    exit();
}

if (isset($_POST["btnUpdate"])) {
    $RName = $_POST['txtRentalName'];
    $Address = $_POST['txtAddress'];
    $Email = $_POST['txtEmail'];
    $Contact = $_POST['txtContact'];
    $UserName = $_POST['txtUserName'];
    $Password = $_POST['txtPassword'];
    $Repassword = $_POST["txtRepassword"];


    // Check if passwords match
    if ($Password !== $Repassword) {
        echo "<script>alert('Passwords do not match!'); window.history.back();</script>";
        exit(); // Stop further execution
    }

    // Check if contact number is numeric
    if (!is_numeric($Contact)) {
        echo "<script>alert('Contact number should be numeric!'); window.history.back();</script>";
        exit(); // Stop further execution
    }

    
    $updateSql = "UPDATE Renters SET 
            RName='$RName', 
            Address='$Address', 
            Email='$Email', 
            Contact='$Contact', 
            UserName='$UserName', 
            Password='$Password' 
            WHERE UserName='$username'";

    if (mysqli_query($con, $updateSql)) {
       
        echo "<script>alert('Details Updated Successfully!'); window.location.href='RentalHome.html';</script>";
    } else {
        echo "<script>alert('Error updating details!'); window.history.back();</script>";
    }
}


mysqli_close($con);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RentOnGo Update Details</title>
    <link rel="stylesheet" href="Login.css">
</head>
<body>
    <div class="container">
        <div class="img">
            <img src="Login.png">
        </div>
        <div class="login-container">
            
            <form action="#" method="POST">
                <h1>Update Details</h1>
                <div class="form-row">
                    <div class="form-group">
                        <input type="text" name="txtRentalName" id="txtRentalName" value="<?php echo $RName; ?>" placeholder="Rental Name" required class="input">
                    </div>
                    <div class="form-group">
                        <input type="text" name="txtAddress" id="txtAddress" value="<?php echo $Address; ?>" placeholder="Address" required class="input">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <input type="email" name="txtEmail" class="input" id="txtEmail" value="<?php echo $Email; ?>" placeholder="Email@Gmail.com" required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="txtContact" class="input" id="txtContact" value="<?php echo $Contact; ?>" placeholder="Contact no." required>
                    </div>
                </div>
                <div class="form-group">
                    <input type="text" name="txtUserName" class="input" id="txtUserName" value="<?php echo $UserName; ?>" placeholder="User Name" required readonly>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <input type="password" name="txtPassword" class="input" id="txtPassword" value="<?php echo $Password; ?>" placeholder="Password" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="txtRepassword" class="input" id="txtRepassword" value="<?php echo $Repassword; ?>" placeholder="Re-enter Password" required>
                    </div>
                </div>

                <div class="form-group">
                    <input type="submit" class="btn" name="btnUpdate" id="btnUpdate" class="input" value="Update Details">
                </div>
            </form>
        </div>
    </div>
</body>
</html>
