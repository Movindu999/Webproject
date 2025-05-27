<?php
session_start();

if (!isset($_SESSION['RentalID'])) {
    echo "<script>alert('Unauthorized access. Please login first.'); window.location.href = 'Rlogin.html';</script>";
    exit();
}

$RentalID = $_SESSION['RentalID'];

$conn = mysqli_connect("fdb1030.awardspace.net", "4581015_rentongo", "Movi#1024", "4581015_rentongo");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $driverid = $_POST['txtDriverID'];
    $rentalID = $_POST['txtRentalID'];
    $Dname = $_POST['txtDriverName'];
    $age = $_POST['txtAge'];
    $perDayPrice = $_POST['txtPerDayPrice'];
    $email = $_POST['txtEmail'];
    $contact = $_POST['txtContact'];
    $username = $_POST['txtUserName'];
    $password = $_POST['txtPassword'];

    if (isset($_POST['add'])) {
        $sql = "INSERT INTO Drivers (DName, Age, PerDayPrice, Email, Contact, UserName, Password, RentalID) 
                VALUES ('$Dname', '$age', '$perDayPrice', '$email', '$contact', '$username', '$password', '$rentalID')";
        $msg = $conn->query($sql) ? "Driver added successfully!" : "Failed to add Driver.";
    } elseif (isset($_POST['update'])) {
        $sql = "UPDATE Drivers SET DName='$Dname', Age='$age', PerDayPrice='$perDayPrice', Email='$email', Contact='$contact', 
                UserName='$username', Password='$password' WHERE RentalID='$rentalID' AND DriverID='$driverid'";
        $msg = $conn->query($sql) ? "Driver updated successfully!" : "Failed to update Driver.";
    } elseif (isset($_POST['delete'])) {
        $sql = "DELETE FROM Drivers WHERE DriverID='$driverid' AND RentalID='$rentalID'";
        $msg = $conn->query($sql) ? "Driver deleted successfully!" : "Failed to delete Driver.";
    }
}

$drivers = $conn->query("SELECT * FROM Drivers WHERE RentalID='$RentalID'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Driver Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #eaf2f8, #f4f6f9);
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1000px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
        }

        form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }

        form > div {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: 500;
            margin-bottom: 5px;
            color: #2c3e50;
        }

        input[type="text"], input[type="email"], input[type="password"] {
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #bbb;
            width: 100%;
            box-sizing: border-box;
        }

        .actions {
            grid-column: span 2;
  		    display: flex;
    		flex-direction: row; /* Make buttons align in one row */
    		justify-content: center;
    		gap: 10px;
        }

        .actions input {
            padding: 10px 20px;
            border: none;
            background-color: #2c3e50;
            color: white;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            font-size: 14px;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ccc;
        }

        th {
            background: #2c3e50;
            color: white;
        }

        tr:hover {
            background: #f1f1f1;
            cursor: pointer;
        }

        .back-btn {
            position: absolute;
            top: 20px;
            left: 30px;
            font-size: 22px;
            color: #2c3e50;
            text-decoration: none;
        }

        .back-btn:hover {
            color: #e74c3c;
        }
    </style>
</head>
<body>

<a href="RentalHome.html" class="back-btn" title="Back to Home"><i class="fas fa-arrow-left"></i></a>

<div class="container">
    <h2>Driver Management</h2>

    <form method="POST" action=""onsubmit="return validateForm()">
        <input type="hidden" name="txtDriverID" id="txtDriverID">

        <div>
            <label>Renter ID</label>
            <input type="text" name="txtRentalID" id="txtRentalID" readonly value="<?= $RentalID ?>">
        </div>
        <div>
            <label>Driver Name</label>
            <input type="text" name="txtDriverName" id="txtDriverName" required>
        </div>
        <div>
            <label>Age</label>
            <input type="text" name="txtAge" id="txtAge" required>
        </div>
        <div>
            <label>Per Day Price</label>
            <input type="text" name="txtPerDayPrice" id="txtPerDayPrice" required>
        </div>
        <div>
            <label>Email</label>
            <input type="email" name="txtEmail" id="txtEmail" required>
        </div>
        <div>
            <label>Contact</label>
            <input type="text" name="txtContact" id="txtContact">
        </div>
        <div>
            <label>User Name</label>
            <input type="text" name="txtUserName" id="txtUserName" required>
        </div>
        <div>
            <label>Password</label>
            <input type="password" name="txtPassword" id="txtPassword" required>
        </div>
        <div>
            <label>Re-enter Password</label>
            <input type="password" name="txtRepassword" id="txtRepassword" required>
        </div>

        <div class="actions">
            <input type="submit" name="add" value="Add">
            <input type="submit" name="update" value="Update">
            <input type="submit" name="delete" value="Delete">
            <input type="button" value="Clear" onclick="clearForm()">
        </div>
    </form>

    <table>
        <tr>
            <th>DriverID</th>
            <th>Name</th>
            <th>Age</th>
            <th>PerDayPrice</th>
            <th>Email</th>
            <th>Contact</th>
            <th>Username</th>
        </tr>
        <?php while ($row = $drivers->fetch_assoc()): ?>
            <tr onclick='loadToForm(<?= json_encode($row) ?>)'>
                <td><?= $row['DriverID'] ?></td>
                <td><?= $row['DName'] ?></td>
                <td><?= $row['Age'] ?></td>
                <td><?= $row['PerDayPrice'] ?></td>
                <td><?= $row['Email'] ?></td>
                <td><?= $row['Contact'] ?></td>
                <td><?= $row['UserName'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php if ($msg): ?>
<script>alert("<?= $msg ?>");</script>
<?php endif; ?>

<script>
    function loadToForm(driver) {
        document.getElementById('txtDriverID').value = driver.DriverID;
        document.getElementById('txtDriverName').value = driver.DName;
        document.getElementById('txtAge').value = driver.Age;
        document.getElementById('txtPerDayPrice').value = driver.PerDayPrice;
        document.getElementById('txtEmail').value = driver.Email;
        document.getElementById('txtContact').value = driver.Contact;
        document.getElementById('txtUserName').value = driver.UserName;
        document.getElementById('txtPassword').value = driver.Password;
        document.getElementById('txtRepassword').value = driver.Password;
    }

    function clearForm() {
        document.querySelectorAll('input[type="text"], input[type="email"], input[type="password"]').forEach(input => input.value = '');
        document.getElementById('txtRentalID').value = "<?= $RentalID ?>";
    }
        
    function validateForm() {
        let password = document.getElementById("txtPassword").value;
        let repassword = document.getElementById("txtRepassword").value;
        if (password !== repassword) {
            alert("Passwords do not match.");
            return false;
        }
        return true;
        
    }
        
</script>

</body>
</html>
