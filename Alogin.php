<?php
session_start();

if (isset($_POST['btnLogin'])) 
{
    $user = $_POST['txtUserName'];
    $pass = $_POST['txtPassword'];

    $conn=mysqli_connect("fdb1030.awardspace.net","4581015_rentongo","Movi#1024");
    mysqli_select_db($conn,"4581015_rentongo");

    if (!$conn) 
    {
        die("Connection failed: " . mysqli_connect_error());
    }
    else
    {
        $sql = "SELECT * FROM Admin WHERE UserName='$user' AND Password='$pass'";
        $result = mysqli_query($conn, $sql);

        if ($row = mysqli_fetch_array($result))
        {
           $_SESSION['Username'] = $user; 
            header("Location:AdminHome.html"); 
            exit();
        }
        else
        {
            echo "<script>alert('Invalid Username or Password');</script>";
            echo "<script>window.location.href = 'Alogin.html';</script>";
            exit();
        }
    }

}
?>