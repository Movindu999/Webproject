<?php
// Database connection
$conn = mysqli_connect("fdb1030.awardspace.net", "4581015_rentongo", "Movi#1024", "4581015_rentongo");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Select comments from Reviews table
$sql = "SELECT Comment FROM Reviews ORDER BY ReviewNum DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Reviews</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f9f9f9, #e0f7fa);
            display: flex;
            justify-content: center;
            padding: 40px;
        }

        table {
            border-collapse: collapse;
            width: 80%;
            background-color: white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 15px 20px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #007BFF;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        caption {
            caption-side: top;
            font-size: 24px;
            margin-bottom: 10px;
            color: #333;
        }
    </style>
</head>
<body>
    <table>
        <caption>Customer Comments</caption>
        <tr>
            <th>Comment</th>
        </tr>
        <?php
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                echo "<tr><td>" . htmlspecialchars($row["Comment"]) . "</td></tr>";
            }
        } else {
            echo "<tr><td>No comments found.</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
mysqli_close($conn);
?>
