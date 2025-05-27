<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['RentalID'])) {
    echo "<script>alert('Unauthorized access. Please login first.'); window.location.href = 'Rlogin.html';</script>";
    exit();
}

$RentalID = $_SESSION['RentalID'];
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RentOnGo Registration</title>
    <link rel="stylesheet" href="Login.css">
    
</head>
<body>
    <div class="container">
        <div class="img">
            <img src="Login.png">
        </div>
        <div class="login-container">
            
            <form action="Dsignup.php" method="POST">	
                <h1>RentOnGo Registration</h1>
                <div class="form-row">
                    <div class="form-group">
                    <input type="text" name="txtRentalID" id="txtRentalID"  readonly value="<?= $RentalID ?>" class="input">
                    </div>
                    <div class="form-group">
                        <input type="text" name="txtDriverName" id="txtDriverName" placeholder="Driver Name" required class="input">
                    </div>
                    <input type="text" name="txtAge" id="txtAge" placeholder="Age" required class="input">
					<div class="form-group">
                        <input type="text" name="txtPerDayPrice" id="txtPerDayPrice" placeholder="Per day price" required class="input">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <input type="email" name="txtEmail"class="input" id="txtEmail" placeholder="Email@Gmail.com" required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="txtContact"class="input" id="txtContact" placeholder="Contact no."  >
                    </div>
                </div>
                <div class="form-group">
                    <input type="text" name="txtUserName" class="input"id="txtUserName" placeholder="User Name" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <input type="password" name="txtPassword"class="input" id="txtPassword" placeholder="Password" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="txtRepassword" class="input"id="txtRepassword" placeholder="Re-enter Password" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <input type="submit" class="btn" name="btnSignUp" id="btnSignUp"class="input" value="SignUp"  >
                </div>
            </form>
        </div>
    </div>
    
    
</body>
</html>
