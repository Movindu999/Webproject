<?php
session_start(); 

// Check if the user is logged in
if (!isset($_SESSION['Username'])) {
    header("Location: Clogin.html"); 
    exit();
}

$username = $_SESSION['Username'];

// Connect to the database
$con=mysqli_connect("fdb1030.awardspace.net","4581015_rentongo","Movi#1024");
    mysqli_select_db($con,"4581015_rentongo");

// Check the connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Select Customer details from database
$sql = "SELECT * FROM Customers WHERE UserName='$username'";
$result = mysqli_query($con, $sql);

// Check if the user exists
if ($row = mysqli_fetch_array($result)) {
    $FName = $row['FName'];
    $LName = $row['LName'];
    $Gender = $row['Gender'];
    $Email = $row['Email'];
    $Contact = $row['Contact'];
    $NIC = $row['NIC'];
    $UserName = $row['UserName'];
    $Password = $row['Password'];
    $Repassword = $Password;
} else {
    
    echo "<script>alert('User not found!'); window.location.href='Clogin.html';</script>";
    exit();
}

if (isset($_POST["btnUpdate"])) {
    $FName = $_POST["txtFirstName"];
    $LName = $_POST["txtLastName"];
    $Gender = $_POST["cmbGender"];
    $Email = $_POST["txtEmail"];
    $Contact = $_POST["txtContact"];
    $NIC = $_POST["txtNIC"];
    $UserName = $_POST["txtUserName"];
    $Password = $_POST["txtPassword"];
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

    
    $updateSql = "UPDATE Customers SET 
            FName='$FName', 
            LName='$LName', 
            Gender='$Gender', 
            Email='$Email', 
            Contact='$Contact', 
            NIC='$NIC', 
            UserName='$UserName', 
            Password='$Password' 
            WHERE UserName='$username'";

    if (mysqli_query($con, $updateSql)) {
       
        echo "<script>alert('Details Updated Successfully!'); window.location.href='CustomerHome.php';</script>";
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
                        <input type="text" name="txtFirstName" id="txtFirstName" value="<?php echo $FName; ?>" placeholder="First Name" required class="input">
                    </div>
                    <div class="form-group">
                        <input type="text" name="txtLastName" id="txtLastName" value="<?php echo $LName; ?>" placeholder="Last Name" required class="input">
                    </div>
                </div>

                <div class="form-group">
                    <select name="cmbGender" id="Gender" required class="input">
                        <option value="">Select Gender</option>
                        <option value="Male" <?php echo ($Gender == 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo ($Gender == 'Female') ? 'selected' : ''; ?>>Female</option>
                    </select>
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
                    <input type="text" name="txtNIC" id="txtNIC" class="input" value="<?php echo $NIC; ?>" placeholder="NIC / Passport" required>
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
