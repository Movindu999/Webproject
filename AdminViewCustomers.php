<?php
session_start();

// Connect to database
$conn = mysqli_connect("fdb1030.awardspace.net", "4581015_rentongo", "Movi#1024", "4581015_rentongo");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}



// Select details from booking table
$query = "SELECT * FROM Customers";
$result = mysqli_query($conn, $query);

// Delete bookings
if (isset($_POST['btnDelete'])) {
    if (!empty($_POST['CusID'])) {
        $CusID = $_POST['CusID'];
        $deleteQuery = "DELETE FROM Customers WHERE CusID = '$CusID'";
        if (mysqli_query($conn, $deleteQuery)) {
            echo "<script>alert('Customer removed successfully.'); window.location.href='AdminViewCustomers.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error removing customers.'); window.location.href='AdminViewCustomers.php';</script>";
            exit();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Customers</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
            
        .delete-btn {
        	background: #2980b9;
        	color: white;
        	padding: 10px 20px;
        	border: none;
        	border-radius: 30px;
        	box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        	cursor: pointer;
        	font-size: 14px;
        	transition: background 0.3s, transform 0.3s ease-out;
        	letter-spacing: 0.5px;
   		}

   		.delete-btn:hover {
        	background: #1f618d;
        	transform: scale(1.05);
   		}
            
         .logout-icon {
            position: absolute;
            top: 20px;
            right: 30px;
            font-size: 1.8rem;
            color: rgba(255, 255, 255, 0.8);
            transition: color 0.3s ease, transform 0.2s ease;
            z-index: 10;
        }

        .logout-icon:hover {
            color: #ec4899;
            transform: scale(1.1);
        }

        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(-20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
 <!-- Logout icon -->
        <a href="AdminHome.html" class="logout-icon" title="Logout">
            <i class="fas fa-sign-out-alt"></i>
        </a>
<?php
if (mysqli_num_rows($result) == 0) {
    echo "<div class='no-booking'>You haven't made any Customers yet!</div>";
} else {
    echo "<div class='table-container'>";
    echo "<table>";
    echo "<thead><tr>
            <th>Customer ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Gender</th>
            <th>Email</th>
            <th>Contact Num</th>
            <th>NIC</th>
            <th>Action</th>
          </tr></thead><tbody>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['CusID'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['FName'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['LName'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['Gender'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['Email'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['Contact'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['NIC'] ?? '') . "</td>";
        echo "<td>
                <form method='POST' action=''>
                    <input type='hidden' name='CusID' value='" . htmlspecialchars($row['CusID']) . "'>
                    <input type='submit' name='btnDelete' value='Remove' class='delete-btn'>
                </form>
              </td>";
        echo "</tr>";
    }

    echo "</tbody></table>";
    echo "</div>";
}

mysqli_close($conn);
?>

</body>
</html>
