<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['RentalID'])) {
    echo "<script>alert('Unauthorized access. Please login first.'); window.location.href = 'Rlogin.html';</script>";
    exit();
}

$RentalID = $_SESSION['RentalID'];

// Connect to the database
$conn = mysqli_connect("fdb1030.awardspace.net", "4581015_rentongo", "Movi#1024", "4581015_rentongo");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT CarNumber, Type, PerDayPrice, Image FROM Car WHERE RentalID = '$RentalID'";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Vehicles - RentOnGo</title>
    <link rel="stylesheet" type="text/css" href="managevehicle.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <style>
        tr:hover {
            cursor: pointer;
            background-color: #dff0d8;
        }

        .logout-icon {
            position: absolute;
            top: 20px;
            right: 30px;
            font-size: 24px;
            color: #2c3e50;
            text-decoration: none;
            z-index: 1000;
            transition: color 0.3s ease, transform 0.2s ease;
        }

        .logout-icon:hover {
            color: #e74c3c;
            transform: scale(1.1);
        }

        #imagePreview {
            max-width: 300px;
            display: none;
            border: 1px solid #ccc;
            padding: 5px;
            margin-top: 10px;
        }
    </style>
</head>
<body class="bg-light">

<!-- Logout icon -->
<a href="RentalHome.html" class="logout-icon" title="Go Back">
    <i class="fas fa-sign-out-alt"></i>
</a>

<div class="container mt-5">
    <h2 class="mb-4">Manage Cars</h2>

    <form action="AddCar.php" method="POST" enctype="multipart/form-data" class="mb-5 p-4 bg-white shadow rounded">
        <div class="row">
            <div class="col-md-4 mb-3">
                <label>Rental ID</label>
                <input type="text" id="txtRentalID" name="txtRentalID" value="<?php echo $RentalID; ?>" class="form-control" readonly>
            </div>
            <div class="col-md-4 mb-3">
                <label>Vehicle Number</label>
                <input type="text" name="txtCarNum" id="txtCarNum" class="form-control" required>
            </div>
            <div class="col-md-4 mb-3">
                <label>Type</label>
                <input type="text" name="txtType" id="txtType" class="form-control" required>
            </div>
            <div class="col-md-4 mb-3">
                <label>Price per day</label>
                <input type="text" name="txtPrice" id="txtPrice" class="form-control" required>
            </div>
            <div class="col-md-4 mb-3">
                <label>Upload Image</label>
                <input type="file" name="txtImage" id="txtImage" class="form-control" accept="image/*">
            </div>
        </div>
        <button type="submit" class="btn btn-success" id="btnAdd" name="btnAdd">Add Vehicle</button>
        <button type="submit" class="btn btn-danger" id="btnRemove" name="btnRemove">Remove Vehicle</button>
        <button type="submit" class="btn btn-primary" id="btnUpdate" name="btnUpdate">Update Vehicle</button>
    </form>

    <table class="table table-bordered table-striped bg-white shadow" id="vehicleTable">
        <thead class="bg-success text-white">
            <tr>
                <th>Vehicle Number</th>
                <th>Type</th>
                <th>Price per day</th>
                <th>Image</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['CarNumber']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Type']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['PerDayPrice']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Image'] ?? '') . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4' class='text-center'>No vehicles found.</td></tr>";
            }
            mysqli_close($conn);
            ?>
        </tbody>
    </table>

    <div id="imagePreviewContainer" class="text-center">
        <h5>Vehicle Image Preview</h5>
        <img id="imagePreview" src="" alt="No Image">
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const table = document.getElementById("vehicleTable");
        const rows = table.getElementsByTagName("tr");
        const imagePreview = document.getElementById("imagePreview");

        for (let i = 1; i < rows.length; i++) {
            rows[i].addEventListener("click", function () {
                const cells = this.getElementsByTagName("td");
                if (cells.length >= 4) {
                    document.getElementById("txtCarNum").value = cells[0].innerText;
                    document.getElementById("txtType").value = cells[1].innerText;
                    document.getElementById("txtPrice").value = cells[2].innerText;

                    const imgTag = cells[3].getElementsByTagName("img")[0];
					if (imgTag) {
            			imagePreview.src = imgTag.src;
    					imagePreview.style.display = "block";
					} else {
    					imagePreview.style.display = "none";
    					imagePreview.src = "";
					}
                }
            });
        }
    });
</script>

</body>
</html>
