<?php
session_start();
if (isset($_SESSION['Username'])) {
    $user = $_SESSION['Username'];
    echo "<h2 class='welcome'>Hi, $user</h2>"; 
} else {
    echo "<h2 class='error'>You are not logged in!</h2>";
    header("Location: Rlogin.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Logout</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #165066, #337D8F); /* Matching your theme */
            color: white;
            text-align: center;
        }

        /* Welcome message styling */
        .welcome {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        /* Error message styling */
        .error {
            font-size: 18px;
            color: #ff4d4d;
        }

        .container {
            background: rgba(255, 255, 255, 0.2);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            width: 300px;
        }

        .btn {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: none;
            border-radius: 25px;
            background: linear-gradient(to right, #337D8F, #A3D8E5);
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            margin: 10px 0;
        }

        /* Button hover effect */
        .btn:hover {
            background-position: right;
            transform: scale(1.05);
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Are you sure you want to log out?</h2>
    
    <button type="button" class="btn" id="logoutBtn" onclick="logout()">Logout</button>

    <button type="button" class="btn" onclick="goupdate()">Update details</button>

    <button type="button" class="btn" onclick="goHome()">Go to Home</button> 

</div>

<script type="text/javascript">
    function logout() {
    let confirmation = confirm("Are you sure you want to log out?");
    if (confirmation) {
        window.location.href = 'logout.php'; 
    }
}

    function goHome() {
        window.location.href = 'RentalHome.html'; 
    }

    function goupdate(){
        window.location.href='Rupdate.php';
    }
</script>

</body>
</html>
