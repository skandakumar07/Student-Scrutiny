<?php
    // Include the database connection file
    include 'db_connection.php';
    
    // Initialize variables to store student details
    $student = [];
    
    // Check if the form is submitted with a USN
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["usn"])) {
        // Retrieve the USN from the form
        $usn = $_GET["usn"];
    
        // Fetch student details from the database
        $sql_student = "SELECT * FROM students WHERE usn = '$usn'";
        $result_student = $conn->query($sql_student);
        if ($result_student->num_rows > 0) {
            $student = $result_student->fetch_assoc();
    

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
        $last_name = $_POST["last_name"];
        $email = $_POST["email"];
        $skills = $_POST["skills"];
        $phone_number = $_POST["phone_number"];

        // Add other fields as needed
    
        // Update student details in the database
        $sql_update_student = "UPDATE students SET first_name = '$first_name', last_name = '$last_name', email = '$email', skills = '$skills', phone_number = '$phone_number' WHERE usn = '$usn'";

        // Add similar update statements for other fields
    
        if ($conn->query($sql_update_student) !== TRUE) {
            echo "Error updating student details: " . $conn->error;
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
            justify-items: center;
            justify-content: space-around;
            background: linear-gradient(145deg, #2c5364, #203a43);
            box-shadow: 0 3px 5px rgba(0, 0, 0, 0.2);
            padding: 10px;
            align-items: center;
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
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 50px;
            gap:180px;
        }

        .student-details {
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            background-color: transparent;
            text-align: center;
            width: 400px;
        }

         label {
            display: block;
            margin-bottom: 10px;
        }

         input[type="text"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: border-color 0.3s ease;
        }

         input[type="text"]:focus {
            outline: none;
            border-color: #1597bb;
        }

         button {
            background-color: #1597bb;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

         button:hover {
            background-color: #0d7495;
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
        <a href="about_us.html">About Us</a>
        <a href="tel:+917019400282">Contact Us</a>
        <a href="admin.php">Go back</a>
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
                <label for="last_name">last Name:</label>
                <input type="text" id="last_name" name="last_name" value="<?php echo isset($student['last_name']) ? $student['last_name'] : ""; ?>">
                <label for="phone_number">phone_number:</label>
                <input type="text" id="phone_number" name="phone_number" value="<?php echo isset($student['phone_number']) ? $student['phone_number'] : ""; ?>">
                <label for="email">email:</label>
                <input type="text" id="email" name="email" value="<?php echo isset($student['email']) ? $student['email'] : ""; ?>">
                <label for="skills">skills:</label>
                <input type="text" id="skills" name="skills" value="<?php echo isset($student['skills']) ? $student['skills'] : ""; ?>">
                <!-- Include other student details fields here -->

               

                <button type="submit" >Save Changes</button>
            </form>
        </div>
    </main>

    <footer>
        <p>Team Members' Contact:</p>
        <!-- Add team members' email addresses and phone numbers here -->
    </footer>


    <script>
        <?php
        // Check if the form submission was successful
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["usn"])) {
            // Check if the query executed successfully
            if ($conn->query($sql_update_student) === TRUE) {
                // Display success message
                echo "<script>
            alert('Student details updated successfully!');
            setTimeout(function(){
                window.location.href = '{$_SERVER['PHP_SELF']}?usn=$usn';
            }, 1000); // Redirect after 1 second (adjust the delay as needed)
          </script>";
            } else {
                // Display error message
                echo "alert('Error updating student details: " . $conn->error . "');";
            }
        }
        ?>
    </script>

    <!-- Include necessary JavaScript code here -->
</body>
</html>
