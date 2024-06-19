<?php
    // Include the database connection file
    include 'db_connection.php';
    
    // Initialize variables to store student details
    $student = [];
    $subjects = [];
    
    // Check if the form is submitted with a USN
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["usn"])) {
        // Retrieve the USN from the form
        $usn = $_GET["usn"];
    
        // Fetch student details from the database
        $sql_student = "SELECT * FROM students WHERE usn = '$usn'";
        $result_student = $conn->query($sql_student);
        if ($result_student->num_rows > 0) {
            $student = $result_student->fetch_assoc();
    
            // Fetch marks details for the student from the database
            $sql_marks = "SELECT * FROM marks WHERE usn = '$usn'";
            $result_marks = $conn->query($sql_marks);
            if ($result_marks->num_rows > 0) {
                while ($row = $result_marks->fetch_assoc()) {
                    $subjects[] = $row;
                }
            }
        } else {
            // Student not found
            echo "Student not found!";
        }
    }
    
    // Check if the form for editing student details is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["usn"])) {
        // Retrieve form data
        $usn = $_POST["usn"];
        $first_name = $_POST["first_name"];
        // Add other fields as needed
    
        // Update student details in the database
        $sql_update_student = "UPDATE students SET first_name = '$first_name' WHERE usn = '$usn'";
        // Add similar update statements for other fields
    
        if ($conn->query($sql_update_student) !== TRUE) {
            echo "Error updating student details: " . $conn->error;
        }
    
        // Update marks details in the database
        $test1_marks = $_POST["test1_marks"];
        $test2_marks = $_POST["test2_marks"];
    
        foreach ($subjects as $index => $subject) {
            $subject_code = $subject['subject_code'];
            $average_marks = ($test1_marks[$index] + $test2_marks[$index]) / 2;
            $sql_update_marks = "UPDATE marks SET test1_marks = '{$test1_marks[$index]}', test2_marks = '{$test2_marks[$index]}', average_marks = '$average_marks' WHERE usn = '$usn' AND subject_code = '$subject_code'";
            if ($conn->query($sql_update_marks) !== TRUE) {
                echo "Error updating marks: " . $conn->error;
            }
        }
        
        // Redirect to the same page after successful submission to avoid resubmission on page refresh
        header("Location: {$_SERVER['PHP_SELF']}?usn=$usn");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student Details</title>
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

        .usn-details {
            display: grid;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .student-details,
        .marks-details {
            padding: 20px;
            border-radius: 3%;
            margin: 60px; /* Increase margin around grid items */
            position: relative; /* Add relative positioning to the container */
        }

        footer {
            background: linear-gradient(145deg, #1e4158, #1597bb);
            color: #ffffff;
            text-align: center;
            padding: 20px 10px;
            margin-top: 20px;
            box-shadow: 0 -4px 6px rgba(0, 0, 0, 0.2);
        }

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

        .dropdown-item {
            border: 2px solid black;
            border-radius: 5%;
            background-color: #e34f4f;
        }

        .fas {
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
        <a href="#about">About Us</a>
        <a href="#contact">Contact Us</a>
        <div class="dropdown text-end">
            <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user"></i>
            </a>
            
        </div>
    </nav>

    <main>
        <div class="usn-details">
            <h2>USN: <?php echo isset($student['usn']) ? $student['usn'] : ""; ?></h2>
            <form method="GET">
                <input type="text" name="usn" placeholder="Enter Student USN" required>
                <button type="submit">Submit</button>
            </form>
        </div>

        <div class="student-details">
            <h2>Student Details</h2>
            <form method="post">
                <input type="hidden" name="usn" value="<?php echo isset($student['usn']) ? $student['usn'] : ""; ?>">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" value="<?php echo isset($student['first_name']) ? $student['first_name'] : ""; ?>">
                <!-- Include other student details fields here -->

                <h2>Marks Details</h2>
                <table>
                    <tr>
                        <th>Subject</th>
                        <th>Test 1</th>
                        <th>Test 2</th>
                        <th>Average</th>
                    </tr>
                    <?php foreach ($subjects as $index => $subject) { ?>
                        <tr>
                            <td><?php echo $subject['subject_code']; ?></td>
                            <input type="hidden" name="subject_code[]" value="<?php echo $subject['subject_code']; ?>">
                            <td><input type="text" name="test1_marks[<?php echo $index; ?>]" value="<?php echo isset($subject['test1_marks']) ? $subject['test1_marks'] : ""; ?>"></td>
                            <td><input type="text" name="test2_marks[<?php echo $index; ?>]" value="<?php echo isset($subject['test2_marks']) ? $subject['test2_marks'] : ""; ?>"></td>
                            <td><?php echo isset($subject['average_marks']) ? $subject['average_marks'] : ""; ?></td>
                        </tr>
                    <?php } ?>
                </table>

                <button type="submit">Save Changes</button>
            </form>
        </div>
    </main>

    <footer>
        <p>Team Members' Contact:</p>
        <!-- Add team members' email addresses and phone numbers here -->
    </footer>

    <!-- Include necessary JavaScript code here -->
</body>
</html>
