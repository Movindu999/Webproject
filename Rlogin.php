<?php
session_start();

if (isset($_POST['btnLogin'])) 
{
    $user = $_POST['txtUserName'];
    $pass = $_POST['txtPassword'];

    $conn = mysqli_connect("fdb1030.awardspace.net", "4581015_rentongo", "Movi#1024", "4581015_rentongo");

    if (!$conn) 
    {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "SELECT * FROM Renters WHERE UserName = '$user' AND Password = '$pass'";
    $result = mysqli_query($conn, $sql);

    if ($row = mysqli_fetch_array($result))
    {
        $_SESSION['Username'] = $user;
        $_SESSION['RentalID'] = $row['RentalID'];
        header("Location: RentalHome.html");
        exit();
    }
    else
    {
        echo "<script>alert('Invalid Username or Password');</script>";
        echo "<script>window.location.href = 'Rlogin.html';</script>";
        exit();
    }
}
?>
