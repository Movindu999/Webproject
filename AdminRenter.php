<?php
$conn = mysqli_connect("fdb1030.awardspace.net", "4581015_rentongo", "Movi#1024", "4581015_rentongo");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rentalID = $_POST['rentalID'] ?? ''; 
    $rname = $_POST['rname'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (isset($_POST['add'])) {
        $sql = "INSERT INTO Renters (RName, Address, Email, Contact, UserName, Password) 
                VALUES ('$rname', '$address', '$email', '$contact', '$username', '$password')";
        $msg = $conn->query($sql) ? "Renter added successfully!" : "Failed to add renter.";
    } elseif (isset($_POST['update'])) {
        $sql = "UPDATE Renters SET RName='$rname', Address='$address', Email='$email', Contact='$contact', 
                UserName='$username', Password='$password' WHERE RentalID='$rentalID'";
        $msg = $conn->query($sql) ? "Renter updated successfully!" : "Failed to update renter.";
    } elseif (isset($_POST['delete'])) {
        $sql = "DELETE FROM Renters WHERE RentalID='$rentalID'";
        $msg = $conn->query($sql) ? "Renter deleted successfully!" : "Failed to delete renter.";
    }
}

$renters = $conn->query("SELECT * FROM Renters");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Manage Renters</title>
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
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #2c3e50;
            outline: none;
        }

       .actions {
   		    grid-column: span 2;
  		    display: flex;
    		flex-direction: row; 
    		justify-content: center;
    		gap: 10px;
		}

		.actions input {
    		padding: 8px 16px;
    		min-width: 100px; 
    		border: none;
    		background-color: #2c3e50;
    		color: white;
    		border-radius: 4px;
    		cursor: pointer;
    		font-size: 14px;
		}

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            font-size: 14px;
            font-weight: 500;
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
            flex-direction: row; 
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

<a href="AdminHome.html" class="back-btn" title="Back to Home">
    <i class="fas fa-arrow-left"></i>
</a>

<div class="container">
    <h2>Renter Management</h2>

    <form method="POST" onsubmit="return validateForm()">
        <!-- Hidden field for RentalID -->
        <input type="hidden" name="rentalID" id="rentalID">

        <div>
            <label for="rname">Renter Name</label>
            <input type="text" name="rname" id="rname" required>
        </div>
        <div>
            <label for="address">Address</label>
            <input type="text" name="address" id="address" required>
        </div>
        <div>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div>
            <label for="contact">Contact</label>
            <input type="text" name="contact" id="contact" required>
        </div>
        <div>
            <label for="username">Username</label>
            <input type="text" name="username" id="username" required>
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div>
            <label for="repassword">Re-enter Password</label>
            <input type="password" id="repassword" required>
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
            <th>ID</th>
            <th>Name</th>
            <th>Address</th>
            <th>Email</th>
            <th>Contact</th>
            <th>Username</th>
        </tr>
        <?php while ($row = $renters->fetch_assoc()): ?>
            <tr onclick='loadToForm(<?= json_encode($row) ?>)'>
                <td><?= $row['RentalID'] ?></td>
                <td><?= $row['RName'] ?></td>
                <td><?= $row['Address'] ?></td>
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
    function loadToForm(renter) {
        document.getElementById('rentalID').value = renter.RentalID;
        document.getElementById('rname').value = renter.RName;
        document.getElementById('address').value = renter.Address;
        document.getElementById('email').value = renter.Email;
        document.getElementById('contact').value = renter.Contact;
        document.getElementById('username').value = renter.UserName;
        document.getElementById('password').value = renter.Password;
        document.getElementById('repassword').value = renter.Password;
    }

    function clearForm() {
        document.querySelectorAll('input[type=text], input[type=email], input[type=password]').forEach(input => input.value = '');
        document.getElementById('rentalID').value = '';
    }

    function validateForm() {
        let password = document.getElementById("password").value;
        let repassword = document.getElementById("repassword").value;
        if (password !== repassword) {
            alert("Passwords do not match.");
            return false;
        }
        return true;
        
    }
</script>

</body>
</html>
