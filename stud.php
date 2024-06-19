<?php
// Include the database connection file
include 'db_connection.php';

// Start the session
session_start();

// Check if the user is logged in. In this case, we check if 'user_id' is set in the session.
if (!isset($_SESSION['user_id'])) {
    // If the session variable is not set, redirect to the login page
    header("Location: login.php");
    exit(); // Ensure no further code is executed
}
// Fetch student details based on the logged-in user's ID
$studentId = $_SESSION['user_id'];
$query = "SELECT students.*, average.final_av FROM students
          LEFT JOIN average ON students.student_id = average.student_id
          WHERE students.student_id = '$studentId'";
$result = $conn->query($query);

// Check if student details are found
if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();

    // Check if skills value is null
    $skills = isset($student['skills']) ? $student['skills'] : 'No skills obtained yet';
} else {
    // Redirect to login page if student details are not found
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Nunito', sans-serif;
      background-color: #F0F4F8;
      background: linear-gradient(145deg, #f4f7f6, #accbee);
      color: #333;
      transition: all 0.3s ease;
    }

    header {
      background: linear-gradient(145deg, #1e4158, #1597bb);
      color: #ffffff;
      text-align: center;
      padding: 20px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
    }

    nav {
      display: flex;
      justify-content: space-around;
      background: linear-gradient(145deg, #2c5364, #203a43);
      box-shadow: 0 3px 5px rgba(0, 0, 0, 0.2);
      padding: 10px;
    }

    nav a, nav .dropdown-toggle {
      color: #d1dce5; /* Soft white */
      text-decoration: none;
      transition: color 0.3s ease, transform 0.3s ease;
    }

    nav a:hover, nav .dropdown-toggle:hover {
      color: #c8f6f7; /* Light blue for hover */
      transform: scale(1.1);
    }

    main {
      display: grid;
      
      grid-template-columns: 1fr 1fr;
      grid-gap: 20px; /* Increase space between grid items */
      justify-content: space-around;
      align-content: stretch;
    }

    .student-details,
    .notification-details {
      padding: 20px;
      border-radius: 3%;
      margin: 60px; /* Increase margin around grid items */
      position: relative; /* Add relative positioning to the container */
    }

    .see-marks-button {
      display: inline;
      margin-top: 20px; /* Add margin to separate the button from the skills section */
      padding: 10px 20px;
      background-color: #4CAF50;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    footer {
      background: linear-gradient(145deg, #1e4158, #1597bb);
      color: #ffffff;
      text-align: center;
      padding: 20px 10px;
      margin-top: 20px;
      box-shadow: 0 -4px 6px rgba(0, 0, 0, 0.2);
      
    }

    /* Dropdown box */
    

    /* Show the dropdown content when the dropdown button is clicked */
 
    .dropdown-menu {
      font-size: 14px; /* Increase font size */
      width: 200px; /* Set a custom width */
      padding: 10px; /* Add padding */
     align-items: center;
     justify-content: center;
      border-radius: 5px; /* Add border radius */
      text-align: center;
      border: 0px none;
      padding: 10px;
      background-color: #F0F4F8;
    }
    .dropdown-item{
      border: 2px solid black;
      border-radius: 5%;
      background-color: #e34f4f;
      
    }
    .fas{
      color: #d1dce5;
    }
  </style>
</head>
<body>
  <header>
    <h1>VidyaVibhushan</h1>
  </header>

  <nav>
    <a href="index.html">Home</a>
    <a href="about_us.html">About Us</a>
    <a href="#contact">Contact Us</a>
    <div class="dropdown text-end">
          <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fas fa-user"></i>
          </a>
          <ul class="dropdown-menu text-small">
            <li>Student USN : <?php echo $student['usn']; ?></li><br>
            <li><a class="dropdown-item" href="login.php"><i class="fas fa-sign-out" style=" color: #e34f4f;"></i>Sign out</a></li>
          </ul>
        </div>
  </nav>

  <main>
    <div class="student-details">
      <h2>Student Details</h2>
      <!-- Display dynamic student details here -->
      <p>Name: <?php echo $student['first_name'] . ' ' . $student['last_name']; ?></p>
      <p>Student ID: <?php echo $student['usn']; ?></p>
      <p>Phone Number: <?php echo $student['phone_number']; ?></p>
      <p>Date of Birth: <?php echo $student['date_of_birth']; ?></p>
      <p>Address: <?php echo $student['address']; ?></p>
      <p>Skills: <?php echo $skills; ?></p>
      <button class="see-marks-button" onclick="location.href='marks.php'">Click here to see the marks</button>
    </div>

    <!-- Notification Details -->
    <div class="notification-details">
      <h2>Notification Details</h2>
      <!-- Include notification details here -->
      <?php
      // Fetch notifications based on the final average
      $category = $student['final_av'] >= 11 ? 'Above Average' : 'Below Average';
      $query = "SELECT * FROM notifications WHERE category = '$category'";
      $notificationResult = $conn->query($query);

      // Display notifications
      if ($notificationResult->num_rows > 0) {
          $count = 1;
          while ($notification = $notificationResult->fetch_assoc()) {
              echo "<hr><p><b>Notification $count:</b></p>";
              echo "<p>Notification Name: {$notification['notification_name']}</p>";
              echo "<p>Notification Description: {$notification['notification_description']}</p>";
              echo "<p>Notification Link: <a href='{$notification['notification_link']}'>{$notification['notification_link']}</a></p>";
              $count++;
          }
      } else {
          echo "<p>No notifications available</p>";
      }
      ?>
    </div>
  </main>

  <footer>
    <p>Team Members' Contact:</p>
    <!-- Add team members' email addresses and phone numbers here -->
  </footer>

  <!-- JavaScript for dropdown functionality -->
  
</body>
</html>
