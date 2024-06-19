<?php
// Start session
session_start();

// Check if admin is not logged in, redirect to login page
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Include the database connection file
include 'db_connection.php';

// Variable to hold the success message and student ID
$successMessage = "";
$studentId = null;

// Define variables and initialize with empty values
$usn = $first_name = $last_name = $password = $email = $phone_number = $date_of_birth = $address = $skills = "";
$usnError = $passwordError = $phoneError = $emailError = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate USN
    if (!preg_match('/^1[A-Z]{2}\d{2}[A-Z]{2}\d{3}$/', $_POST['usn'])) {
        $usnError = "Invalid USN format. Example: 1KS21CS095";
    } else {
        $usn = $_POST['usn'];
    }

    // Validate Password
    if (!preg_match('/^[a-zA-Z0-9]{8}$/', $_POST['password'])) {
        $passwordError = "Password must be an 8-digit alphanumeric string.";
    } else {
        $password = $_POST['password'];
    }

    // Validate Phone Number
    if (!filter_var($_POST['phone_number'], FILTER_VALIDATE_INT) || strlen($_POST['phone_number']) !== 10) {
        $phoneError = "Phone number must be exactly 10 digits.";
    } else {
        $phone_number = $_POST['phone_number'];
    }

    // Validate Email
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) || !preg_match('/@.*\.com$/', $_POST['email'])) {
        $emailError = "Invalid email format. Example: xyz@xyz.com";
    } else {
        $email = $_POST['email'];
    }

    // If all validations pass, proceed with inserting data into the database
    if (empty($usnError) && empty($passwordError) && empty($phoneError) && empty($emailError)) {
        // Prepare and bind parameters
        $stmt = $conn->prepare("INSERT INTO students (usn, first_name, last_name, password, email, phone_number, date_of_birth, address, skills) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $usn, $first_name, $last_name, $password, $email, $phone_number, $date_of_birth, $address, $skills);

        // Set parameters
        $usn = $_POST['usn'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $phone_number = $_POST['phone_number'];
        $date_of_birth = $_POST['date_of_birth'];
        $address = $_POST['address'];
        $skills = $_POST['skills'];

        // Execute statement
        if ($stmt->execute()) {
            // Get the inserted student ID
            $studentId = $conn->insert_id;
            // Set the success message
            $successMessage = "New record created successfully";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <style>

nav a{
    text-decoration: none;
    color: #ffff;

}
    body {
    font-family: Arial, sans-serif;
    background-color: #f5f5f5;
    margin: 0;
    padding: 0;
}

header {
            background-color: #005792;
            color: #fff;
            text-align: center;
            padding: 10px;
        }

        nav {
            display: flex;
            justify-content: space-around;
            background-color: #41C9E2;
            padding: 10px;
        }

        footer {
            background-color: #005792;
            color: #fff;
            text-align: center;
            padding: 10px;
        }

.container {
    width: 50%;
    margin: 50px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h2 {
    text-align: center;
    color: #333;
}

form {
    display: flex;
    flex-direction: column;
}

label {
    font-weight: bold;
    color: #555;
}

input[type="text"],
input[type="email"],
input[type="password"],
textarea,
input[type="date"] {
    width: 100%;
    padding: 12px;
    margin-bottom: 10px; /* Added margin bottom */
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    box-sizing: border-box; /* Added to include padding in width calculation */
}

input[type="submit"] {
    width: 100%;
    padding: 12px;
    background-color: #4caf50;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    font-size: 16px;
}
.back{
    width: 100%;
    padding: 12px;
    background-color: #FDA403;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    font-size: 16px;
}

input[type="submit"] .back :hover {
    background-color: #45a049;
}

.alert {
    background-color: #4caf50;
    color: #fff;
    padding: 10px;
    border-radius: 5px;
    margin-top: 10px;
    text-align: center;
}

@media (max-width: 600px) {
    .container {
        width: 90%;
    }
}
.error {
    color: red;
    font-size: 14px;
}
    </style>
</head>
<body>
    <!-- HTML content -->
    <header>
        <h1>VidyaVibhushan</h1>
    </header>

    <nav>
        <a href="index.html">Home</a>
        <a href="about_us.html">About Us</a>
        <a href="tel:#917019400282">Contact Us</a>
    </nav>

    <div class="container">
        <h2>Add Student</h2>
        <?php 
            // Display success message if it's set
            if (!empty($successMessage)) {
                echo '<script>alert("' . $successMessage . ' Student ID: ' . $studentId . '");</script>';
            }
        ?>
       <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="usn">USN:</label><br>
    <input type="text" id="usn" name="usn" required>
    <span class="error"><?php echo $usnError; ?></span><br>
    
    <label for="first_name">First Name:</label><br>
    <input type="text" id="first_name" name="first_name" required>

    
    <label for="last_name">Last Name:</label><br>
    <input type="text" id="last_name" name="last_name" required>

    
    <label for="password">Password:</label><br>
    <input type="password" id="password" name="password" required>
    <span class="error"><?php echo $passwordError; ?></span><br>
    
    <label for="email">Email:</label><br>
    <input type="email" id="email" name="email" required>
    <span class="error"><?php echo $emailError; ?></span><br>
    
    <label for="phone_number">Phone Number:</label><br>
    <input type="text" id="phone_number" name="phone_number">
    <span class="error"><?php echo $phoneError; ?></span><br>
    
    <label for="date_of_birth">Date of Birth:</label><br>
    <input type="date" id="date_of_birth" name="date_of_birth" required><br>
    
    <label for="address">Address:</label><br>
    <textarea id="address" name="address" required></textarea><br>
    
    <label for="skills">Skills:</label><br>
    <input type="text" id="skills" name="skills"><br>
    
    <!-- Category field not included -->
    
    <input type="submit" value="Submit"><br>
</form>
        <button class="back" onclick="window.location.href = 'admin.php';">Back to Admin Page</button>
    </div>

    <footer>
        <p>Team Members' Contact:</p>
        <!-- Add team members' email addresses and phone numbers here -->
    </footer>
</body>
</html>

