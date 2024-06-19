<?php
// Start the session
session_start();

// Check if the user is not logged in or is not an admin, redirect to login page
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Include the database connection file
include 'db_connection.php';

// Your existing PHP code goes here
// Initialize variables to store form input
$usn = $subjects = $test1_marks = $test2_marks = $average_marks = [];

// Check if the form for adding subject marks is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["usn"])) {
    // Retrieve form data
    $usn = $_POST["usn"];
    $subjects = $_POST["subjects"];
    $test1_marks = $_POST["test1_marks"];
    $test2_marks = $_POST["test2_marks"];
    
    // Get the student ID from the students table
    $sql = "SELECT student_id FROM students WHERE usn = '$usn'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $student_id = $row["student_id"];

        // Calculate average marks for each subject
        $average_marks = [];
        foreach ($test1_marks as $index => $test1_mark) {
            $average_marks[$index] = ($test1_mark + $test2_marks[$index]) / 2;
        }

        // Insert data into the marks table
        foreach ($subjects as $index => $subject) {
           $sql = "INSERT INTO marks (student_id, usn, subject_code, test1_marks, test2_marks, average_marks) 
        VALUES ('$student_id', '$usn', '$subject', '{$test1_marks[$index]}', '{$test2_marks[$index]}', '{$average_marks[$index]}')";
            if ($conn->query($sql) !== TRUE) {
                echo "Error inserting marks: " . $conn->error;
            }
        }

        // Update or insert into the average table
        $updateSql = "INSERT INTO average (student_id, usn, final_av) 
                      VALUES ('$student_id', '$usn', (
                          SELECT AVG(m.average_marks)
                          FROM marks m
                          WHERE m.student_id = '$student_id'
                          GROUP BY m.student_id
                      ))
                      ON DUPLICATE KEY UPDATE final_av = (
                          SELECT AVG(m.average_marks)
                          FROM marks m
                          WHERE m.student_id = '$student_id'
                          GROUP BY m.student_id
                      )";
        if ($conn->query($updateSql) !== TRUE) {
            echo "Error updating average table: " . $conn->error;
        }

        // Update student category
        $updateCategorySql = "UPDATE students SET category = CASE
                                WHEN (SELECT final_av FROM average WHERE student_id = '$student_id') < 10 THEN 'Below Average'
                                ELSE 'Above Average'
                              END
                              WHERE student_id = '$student_id'";
        if ($conn->query($updateCategorySql) !== TRUE) {
            echo "Error updating student category: " . $conn->error;
        }

        // JavaScript alert after form submission
        echo "<script>alert('Marks are final and cannot be changed for USN: $usn');</script>";

        // Redirect to the same page after successful submission to avoid resubmission on page refresh
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    } else {
        echo "Student not found!";
    }
}

// Check if the form for adding notification details is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["notification_name"])) {
    // Retrieve notification data
    $notification_name = $_POST["notification_name"];
    $notification_description = $_POST["notification_description"];
    $notification_link = $_POST["notification_link"];
    $categories = $_POST["category"];

    // Insert notification data into the database
    foreach ($categories as $category) {
        $sql = "INSERT INTO notifications (notification_name, notification_description, notification_link, category) 
                VALUES ('$notification_name', '$notification_description', '$notification_link', '$category')";
        if ($conn->query($sql) !== TRUE) {
            echo "Error inserting notification: " . $conn->error;
        }
    }

    // JavaScript alert after form submission
    echo "<script>alert('Notification added successfully');</script>";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
      body {
    margin: 0;
    padding: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(145deg, #f4f7f6, #accbee);
    color: #333;
    transition: all 0.3s ease;
}

header {
    background: linear-gradient(145deg, #1e4158, #1597bb);
    color: #ffffff;
    text-align: center;
    padding: 20px 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
}

nav {
    display: flex;
    justify-content: space-around;
    background: linear-gradient(145deg, #2c5364, #203a43);
    padding: 10px;
    box-shadow: 0 3px 5px rgba(0, 0, 0, 0.2);
}

nav a {
    color: #d1dce5;
    text-decoration: none;
    transition: color 0.3s ease, transform 0.3s ease;
}

nav a:hover {
    color: #ffdd57;
    transform: scale(1.1);
}

main {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    padding: 20px;
    transition: all 0.3s ease;
}

.student-details, .notification-details {
    flex: 1;
    min-width: 300px;
    background-color: #fff;
    padding: 20px;
    margin: 20px;
    border-radius: 10px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.student-details:hover, .notification-details:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
}

footer {
    background: linear-gradient(145deg, #1e4158, #1597bb);
    color: #ffffff;
    text-align: center;
    padding: 20px 10px;
    margin-top: 20px;
    box-shadow: 0 -4px 6px rgba(0, 0, 0, 0.2);
}

input[type="text"], textarea, input[type="submit"], input[type="checkbox"], button, select {
    padding: 10px 15px;
    border: 1px solid #BEC5C9; /* Subtle border color */
    border-radius: 4px; /* Slightly rounded corners for a modern look */
    font-size: 14px; /* Appropriate font size for formality */
    color: #5A5A5A; /* Darker font color for better readability */
    background-color: #FFFFFF; /* White background */
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1); /* Inner shadow for depth */
    transition: border-color 0.25s ease-in-out, box-shadow 0.25s ease-in-out; /* Smooth transition for interactions */
}

input[type="text"]:focus, textarea:focus, select:focus {
    border-color: #5B9BD5; /* Highlight focus with a more noticeable border color */
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1), 0 0 8px rgba(91, 155, 213, 0.6); /* Outer glow effect */
    outline: none; /* Remove default focus outline */
}

input[type="submit"], button {
    font-weight: bold; /* Make button text bold */
    letter-spacing: 0.05em; /* Slight spacing between letters */
    text-transform: uppercase; /* Uppercase text for a formal button look */
    transition: background-color 0.25s ease-in-out, transform 0.15s ease; /* Smooth background color transition and slight transform on hover */
}

input[type="submit"]:hover, button:hover {
    background-color: #4A90E2; /* Slightly lighter blue on hover for buttons */
    transform: translateY(-2px); /* Slight lift effect on hover */
    border-color: #4A90E2; /* Border color change to match the background */
}

/* Ensuring consistency in disabled button states */
input[type="submit"]:disabled, button:disabled {
    background-color: #cccccc;
    border-color: #cccccc;
    cursor: not-allowed;
}


button a {
    color: #000;
    text-decoration: none;
}

.subject-inputs {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 10px;
    align-items: end;
}

.xyz {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: row;
    animation: fadeIn 2s ease-out;
    gap:10px;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
    </style>
</head>
<body>
<header>
    <h1>VidyaVibhushan</h1>
</header>

<nav>
    <a href="index.html">Home</a>
    <a href="#about">About Us</a>
    <a href="#contact">Contact Us</a>
    <a href="login.php">Log out</a>
</nav>

<main>
    <div class="student-details">
        <h2>Student Details</h2>
        <form method="post">
            <input type="text" name="usn" id="usn" placeholder="USN">
            <br><br>
            <h3>Subject Marks</h3>
            <div id="subject_marks">
                <!-- JavaScript will add input fields here -->
            </div>
            <br><br>
            <button type="button" onclick="addSubject()">Add Subject</button>
            <button type="submit">Submit</button>
        </form>
    </div>

    <div class="notification-details">
        <h2>Notification Details</h2>
        <form method="post">
            <input type="text" name="notification_name" placeholder="Notification Name">
            <input type="text" name="notification_description" placeholder="Notification Description">
            <input type="text" name="notification_link" placeholder="Notification Link">
            <br><br>
            <input type="checkbox" name="category[]" value="Above Average"> Above Average
            <input type="checkbox" name="category[]" value="Below Average"> Below Average
            <br><br>
            <input type="submit" value="Add Notification">
        </form>
    </div>
</main>

<div class="xyz">
    <button><a href="createstud.php">Create new student</a></button>
    <button><a href="view.php">View student details</a></button>
    <button><a href="new_page.php">Edit student details</a></button>

</div>

<footer>
    <p>Team Members' Contact:</p>
    <!-- Add team members' email addresses and phone numbers here -->
</footer>

<script>
    function addSubject() {
        var container = document.getElementById('subject_marks');
        var index = container.childElementCount + 1;

        // Check if the number of subjects is less than or equal to 4
        if (index <= 4) {
            var subjectDiv = document.createElement('div');
            subjectDiv.classList.add('subject-inputs');
            subjectDiv.innerHTML = `
                <input type="text" name="subjects[]" placeholder="Subject ${index} Code">
                <input type="text" name="test1_marks[]" placeholder="Test 1">
                <input type="text" name="test2_marks[]" placeholder="Test 2">
                <br><br>
            `;
            container.appendChild(subjectDiv);

            // Disable the "Add Subject" button if 4 subjects have been added
            if (index === 4) {
                document.querySelector('button[type="button"]').disabled = true;
            }
        }
    }
</script>
</body>
</html>