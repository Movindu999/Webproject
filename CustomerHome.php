<?php
session_start();

$conn = mysqli_connect("fdb1030.awardspace.net", "4581015_rentongo", "Movi#1024", "4581015_rentongo");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Select Rental details for table
$sql = "SELECT RentalID, RName, Address FROM Renters";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RentOnGo</title>

    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="styles.css"> 
    <script src="jquery-3.7.1.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">

    <style>
    header {
        border-bottom: none ;
        box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.05);
        background-color: white;
    }
    .container, .header-content {
        border-bottom: none ;
    }

    .hamburger {
        display: none;
        flex-direction: column;
        cursor: pointer;
    }
    .hamburger .bar {
        height: 3px;
        width: 25px;
        background-color: black;
        margin: 4px 0;
        transition: 0.3s;
    }
    @media (max-width: 768px) {
        .navmenu {
            display: none;
            flex-direction: column;
            background: white;
            position: absolute;
            top: 70px;
            right: 0;
            width: 100%;
            text-align: center;
        }
        .navmenu.active {
            display: flex;
        }
        .hamburger {
            display: flex;
        }
    }

    .logo-text {
        font-weight: bold;
        font-size: 24px;
        color: #001f5c;
        margin-left: 10px;
    }

    /* Style navigation bar */
    .navmenu li {
        list-style: none;
        display: inline-block;
        margin-left: 20px;
    }
    .navmenu li a {
        text-decoration: none;
        color: #001f5c;
        font-weight: bold;
        font-size: 14px;
    }
    </style>
</head>

<body class="gradient-background">

<header>
    <div class="container">
        <div class="header-content" style="display: flex; align-items: center; justify-content: space-between; padding: 15px 0;">
            <div class="logo" style="display: flex; align-items: center;">
                <span class="logo-text">RentOnGo</span>
            </div>

            <!-- Hamburger icon -->
            <div class="hamburger" id="hamburger">
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
            </div>

            <ul class="navmenu" id="navmenu">
                <li><a href="CustomerViewBooking.php">MY BOOKINGS</a></li>
                <li><a href="Reviews.php">ADD COMMENTS</a></li>
            </ul>

            <div class="navicon">
                <a href="test.php"><i class='bx bx-user' style="font-size: 24px; color: #001f5c;"></i></a>
            </div>
        </div>
    </div>
</header>

<script>
// Hamburger menu
const hamburger = document.getElementById('hamburger');
const navmenu = document.getElementById('navmenu');

hamburger.addEventListener('click', () => {
    navmenu.classList.toggle('active');
});
</script>

<br><br><br><br><br>

<section class="all-events" id="Vehicle" style="padding: 40px 0; background: linear-gradient(to right, #e8f8f5, #d1f2eb); font-family: 'Arial', sans-serif; min-height: 100vh;">
    <div style="max-width: 1000px; margin: auto; background-color: white; border-radius: 12px; padding: 30px; box-shadow: 0px 8px 20px rgba(0,0,0,0.1);">
        <h3 style="text-align: center; color: #165066; margin-bottom: 30px;">Available Renters</h3>
        <table style="width: 100%; border-collapse: separate; border-spacing: 0; border: 2px solid #1b5a6b; border-radius: 10px; overflow: hidden;">
            <thead style="background: linear-gradient(135deg, #1b5a6b, #174e5e); color: white;">
                <tr>
                    <th style="padding: 15px; border-bottom: 2px solid #174e5e; text-align: center;">Renter Name</th>
                    <th style="padding: 15px; border-bottom: 2px solid #174e5e; text-align: center;">Location</th>
                    <th style="padding: 15px; border-bottom: 2px solid #174e5e; text-align: center;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                //Check availability of renters
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr style='background-color: #f9f9f9; transition: background-color 0.3s;'>";
                        echo "<td style='padding: 15px; border-bottom: 1px solid #ddd; text-align: center;'>" . htmlspecialchars($row['RName']) . "</td>";
                        echo "<td style='padding: 15px; border-bottom: 1px solid #ddd; text-align: center;'>" . htmlspecialchars($row['Address']) . "</td>";
                        echo "<td style='padding: 15px; border-bottom: 1px solid #ddd; text-align: center;'>
                                <a href='RenatlHome.php?renter_id=" . urlencode($row['RentalID']) . "' 
                                   style='
                                    display: inline-block;
                                    background: linear-gradient(135deg, #1b5a6b, #174e5e);
                                    color: white;
                                    padding: 8px 16px;
                                    text-decoration: none;
                                    font-size: 14px;
                                    border-radius: 30px;
                                    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                                    transition: all 0.3s ease-in-out;
                                   '
                                   onmouseover=\"this.style.background='linear-gradient(135deg, #174e5e, #134351)';\"
                                   onmouseout=\"this.style.background='linear-gradient(135deg, #1b5a6b, #174e5e)';\"
                                >
                                    View Vehicles
                                </a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3' style='text-align: center; padding: 20px;'>No renters found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</section>

</body>
</html>
