<?php
session_start();

// Database connection
$conn = mysqli_connect("fdb1030.awardspace.net", "4581015_rentongo", "Movi#1024", "4581015_rentongo");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get CusID from session
$cusID = isset($_SESSION['CusID']) ? $_SESSION['CusID'] : ''; 
$rentalID = '';
$vehicleType = isset($_GET['vehicle_type']) ? htmlspecialchars($_GET['vehicle_type']) : '';
$vehiclePrice = isset($_GET['price']) ? floatval($_GET['price']) : 0;
$drivers = []; 

if (isset($_GET['renter_id'])) {
    $rentalID = (int)$_GET['renter_id']; 

    // load drivers based on RentalID 
    $driverQuery = "SELECT DriverID, DName, PerDayPrice FROM Drivers WHERE RentalID = $rentalID";
    $result = mysqli_query($conn, $driverQuery);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $drivers[] = $row;
        }
    }
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>RentOnGo Booking</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Catamaran:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Catamaran', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #F9F9F9, #D7EDEB);
            background-size: 200% 200%;
            animation: backgroundShift 15s ease infinite;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        @keyframes backgroundShift {
            0% { background-position: 0% 0%; }
            50% { background-position: 100% 100%; }
            100% { background-position: 0% 0%; }
        }
        .card {
            background: rgba(255, 255, 255, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.35);
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            border-radius: 20px;
            backdrop-filter: blur(20px);
            padding: 2.5rem;
            width: 100%;
            max-width: 500px;
            animation: fadeIn 0.8s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        h1 {
            font-size: 2rem;
            color: #165066;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        label {
            display: block;
            font-weight: bold;
            margin-top: 1rem;
            margin-bottom: 0.3rem;
            color: #165066;
        }
        input[type="text"],
        input[type="date"],
        select {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ccc;
            border-radius: 12px;
            background-color: rgba(255, 255, 255, 0.6);
            font-size: 1rem;
            color: #333;
            outline: none;
            transition: all 0.3s ease;
        }
        input[type="text"]:focus,
        input[type="date"]:focus,
        select:focus {
            border-color: #165066;
            background-color: #fff;
        }
        .btn {
            width: 100%;
            padding: 0.9rem;
            background: #165066;
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: bold;
            font-size: 1.1rem;
            margin-top: 1.5rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .btn:hover {
            background: #337D8F;
        }
        .note {
            font-size: 0.85rem;
            margin-top: 1rem;
            color: #444;
            text-align: center;
        }
        .note a {
            color: #165066;
            text-decoration: none;
            font-weight: bold;
        }
        .checkbox-row {
            display: flex;
            align-items: center;
            margin-top: 1rem;
        }
        .checkbox-row input {
            margin-right: 10px;
        }
        @media (max-width: 500px) {
            .card {
                padding: 1.5rem;
                margin: 1rem;
            }
            h1 {
                font-size: 1.6rem;
            }
        }
    </style>
</head>
<body>
    <form class="form card" action="ConfirmBooking.php" method="post" onsubmit="return validation();">
        <h1>Book Your Ride</h1>

        <label class="startDate">Start Date</label>
        <input type="date" name="startDate" id="startDate" required>

        <label class="endDate">End Date</label>
        <input type="date" name="endDate" id="endDate" required>

        <label class="txtVehicleType">Vehicle Type</label>
        <input type="text" name="txtVehicleType" id="txtVehicleType" value="<?= $vehicleType ?>" readonly>

        <label class="txtRentalID">Rental ID</label>
        <input type="text" name="txtRentalID" id="txtRentalID" value="<?= $rentalID ?>" readonly>

        <label class="txtCusID">Customer ID</label>
        <input type="text" name="txtCusID" id="txtCusID" value="<?= htmlspecialchars($cusID) ?>" readonly>

        <label class="cmbDriver">Select Driver</label>
		<select name="cmbDriver" id="cmbDriver">
  	    <!-- Default option for when no driver is selected -->
   			 <option value="" data-price="0">No Driver Selected</option>

   			 <!-- Dynamically generate driver options from the $drivers array -->
   			 <?php foreach ($drivers as $driver): ?>
     		   <option 
        	    	value="<?= htmlspecialchars($driver['DriverID']) ?>"                 
           	    	data-price="<?= htmlspecialchars($driver['PerDayPrice']) ?>">       
            		<?= htmlspecialchars($driver['DName']) ?> -                       
            		Rs.<?= htmlspecialchars($driver['PerDayPrice']) ?>/day              
        	   </option>
   			 <?php endforeach; ?>
		</select>



        <label class="cmbPayment">Payment Method</label>
        <select name="cmbPayment" id="cmbPayment" required>
            <option value="">Select</option>
            <option value="Card">Card</option>
            <option value="Cash">Cash</option>
        </select>

        <button type="button" class="btn" onclick="Caltotal()">Generate Bill</button>
        <input type="text" name="txtBill" id="txtBill" readonly placeholder="Total Bill (Rs.)">

        <div class="checkbox-row">
            <input type="checkbox" id="cboxAgree" name="cboxAgree">
            <label class="cboxAgree">I agree to the Terms & Conditions</label>
        </div>

        <input type="submit" value="Confirm Booking" name="btnConfirm" id="btnConfirm" class="btn">

        <div class="note">
            By clicking confirm, you accept our <a href="#">Rental Terms</a>.
        </div>
    </form>

    <script>
        const vehiclePrice = <?= $vehiclePrice ?>;

        function validation() {
            const payment = document.getElementById('cmbPayment').value;
            const agree = document.getElementById('cboxAgree').checked;
            const bill = document.getElementById('txtBill').value;

            if (!payment) {
                alert("Please select a valid payment method.");
                return false;
            }
            if (!agree) {
                alert("You must agree to the terms and conditions.");
                return false;
            }
            if (!bill) {
                alert("Please generate the bill before confirming.");
                return false;
            }
            return true;
        }

        function Caltotal() {
            const start = new Date(document.getElementById('startDate').value);
            const end = new Date(document.getElementById('endDate').value);
            const driverSelect = document.getElementById('cmbDriver');
            const selectedOption = driverSelect.options[driverSelect.selectedIndex];
            const driverPrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;

            if (!start || !end || isNaN(start) || isNaN(end)) {
                alert("Please enter valid dates.");
                return;
            }

            const diff = end.getTime() - start.getTime();
            const days = Math.ceil(diff / (1000 * 3600 * 24));

            if (days <= 0) {
                alert("End date must be after start date.");
                return;
            }

            const total = (vehiclePrice * days) + (driverPrice * days);
            document.getElementById('txtBill').value = total.toFixed(2);
        }
    </script>
</body>
</html>
