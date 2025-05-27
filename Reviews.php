<?php
// Database connection
$conn = mysqli_connect("fdb1030.awardspace.net", "4581015_rentongo", "Movi#1024", "4581015_rentongo");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

//Insert customer comments to Reviews table
if (isset($_POST["btnSubmit"])) {
    $comment = $_POST['comment'];
    $star = $_POST['star'];

    $sql = "INSERT INTO Reviews (Comment, Star) VALUES ('$comment', '$star')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Thank you for your review!');</script>";
    } else {
        echo "<script>alert('Error submitting review.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Leave a Review</title>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #f0f8ff, #e6f7ff);
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin: 0;
    }

    .review-container {
      background: #fff;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      width: 90%;
      max-width: 500px;
      animation: fadeIn 0.8s ease-in-out;
    }

    .review-container h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #333;
    }

    textarea {
      width: 100%;
      padding: 15px;
      border-radius: 8px;
      border: 1px solid #ccc;
      resize: vertical;
      font-size: 16px;
      margin-bottom: 20px;
      font-family: 'Poppins', sans-serif;
    }

    .rating {
      display: flex;
      justify-content: center;
      gap: 10px;
      margin-bottom: 20px;
    }

    .rating input {
      display: none;
    }

    .rating label {
      font-size: 24px;
      cursor: pointer;
      color: #ccc;
    }

    .rating input:checked ~ label,
    .rating input:checked + label {
      color: gold;
    }

    .submit-btn {
      display: block;
      width: 100%;
      padding: 12px;
      background-color: #007BFF;
      border: none;
      color: white;
      font-size: 16px;
      border-radius: 8px;
      cursor: pointer;
      transition: background 0.3s;
    }

    .submit-btn:hover {
      background-color: #0056b3;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  <div class="review-container">
    <h2>Leave Your Review</h2>
    <form method="POST" action="">
      <textarea name="comment" placeholder="Write your comment..." required rows="5"></textarea>

      <div class="rating">
        <input type="radio" id="star5" name="star" value="5" required><label for="star5">★</label>
        <input type="radio" id="star4" name="star" value="4"><label for="star4">★</label>
        <input type="radio" id="star3" name="star" value="3"><label for="star3">★</label>
        <input type="radio" id="star2" name="star" value="2"><label for="star2">★</label>
        <input type="radio" id="star1" name="star" value="1"><label for="star1">★</label>
      </div>

      <button type="submit" name="btnSubmit" class="submit-btn">Submit Review</button>
    </form>
  </div>
</body>
</html>

<?php
mysqli_close($conn);
?>
