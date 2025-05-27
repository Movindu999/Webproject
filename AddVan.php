<?php
session_start(); 

$VanNum = $_REQUEST["txtVanNum"];
$Type = $_REQUEST["txtType"];
$Price = $_REQUEST["txtPrice"];

$RentalID = isset($_SESSION['RentalID']) ? $_SESSION['RentalID'] : null;

if (!$RentalID) {
    echo "<script>alert('Session expired or invalid access. Please log in again.'); window.location.href='Rlogin.html';</script>";
    exit();
}

$con = mysqli_connect("fdb1030.awardspace.net", "4581015_rentongo", "Movi#1024", "4581015_rentongo");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

$imageFileName = "";

// Check for image upload
if (isset($_FILES["txtImage"]) && $_FILES["txtImage"]["error"] === UPLOAD_ERR_OK) {
    $targetDir = "uploads/";
    $imageFileName = basename($_FILES["txtImage"]["name"]);
    $targetFilePath = $targetDir . $imageFileName;
    $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    // Validate file type
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowedTypes)) {
        echo "<script>alert('Only JPG, JPEG, PNG, and GIF files are allowed.'); window.history.back();</script>";
        exit();
    }

    // Move uploaded file
    if (!move_uploaded_file($_FILES["txtImage"]["tmp_name"], $targetFilePath)) {
        echo "<script>alert('Failed to upload image.'); window.history.back();</script>";
        exit();
    }
}

// ADD VEHICLE
if (isset($_POST["btnAdd"])) {
    if (!is_numeric($Price)) {
        echo "<script>alert('Invalid price. Please enter a numeric value.'); window.history.back();</script>";
        exit();
    }

    $sql = "INSERT INTO Van (VanNumber, Type, PerDayPrice, RentalID, Image) 
            VALUES ('$VanNum', '$Type', '$Price', '$RentalID', '$imageFileName')";

    if (mysqli_query($con, $sql)) {
        echo "<script>alert('Vehicle added successfully!'); window.location.href='ManageVan.php';</script>";
    } else {
        echo "Error: " . mysqli_error($con);
    }
}

// UPDATE VEHICLE
if (isset($_POST["btnUpdate"])) {
    if (!is_numeric($Price)) {
        echo "<script>alert('Invalid price. Please enter a numeric value.'); window.history.back();</script>";
        exit();
    }

    $updateImage = "";
    if (!empty($imageFileName)) {
        $updateImage = ", Image = '$imageFileName'";
    }

    $sql = "UPDATE Van 
            SET Type = '$Type', PerDayPrice = '$Price' $updateImage
            WHERE VanNumber = '$VanNum' AND RentalID = '$RentalID'";

    if (mysqli_query($con, $sql)) {
        echo "<script>alert('Vehicle updated successfully!'); window.location.href='ManageVan.php';</script>";
    } else {
        echo "Error: " . mysqli_error($con);
    }
}

// DELETE VEHICLE
if (isset($_POST["btnRemove"])) {
    $sql = "DELETE FROM Van 
            WHERE VanNumber = '$VanNum' AND RentalID = '$RentalID'";

    if (mysqli_query($con, $sql)) {
        echo "<script>alert('Vehicle deleted successfully!'); window.location.href='ManageVan.php';</script>";
    } else {
        echo "Error: " . mysqli_error($con);
    }
}

mysqli_close($con);
?>
