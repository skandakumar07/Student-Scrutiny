<?php
// Include the database connection file
include 'db_connection.php';

// Function to calculate and update average marks and final_av
function updateAverage($conn, $usn) {
    // Calculate average marks for the student
    $averageQuery = "SELECT AVG(average_marks) AS average FROM marks WHERE usn = '$usn'";
    $averageResult = $conn->query($averageQuery);
    if ($averageResult->num_rows > 0) {
        $averageRow = $averageResult->fetch_assoc();
        $average = $averageRow['average'];
    }

    // Update average_marks in the marks table
    $updateAverageQuery = "UPDATE average SET final_av = '$average' WHERE usn = '$usn'";
    if ($conn->query($updateAverageQuery) !== TRUE) {
        echo "Error updating average marks: " . $conn->error;
    }
}

// Check if the USN is provided
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["usn"])) {
    // Retrieve USN from the request
    $usn = $_GET["usn"];

    // Initialize an array to store student data
    $studentData = array();

    // Fetch student details from the database
    $studentQuery = "SELECT * FROM students WHERE usn = '$usn'";
    $studentResult = $conn->query($studentQuery);

    if ($studentResult->num_rows > 0) {
        // Fetch student details
        $studentRow = $studentResult->fetch_assoc();

        // Populate student data array
        $studentData["usn"] = $studentRow["usn"];
        $studentData["first_name"] = isset($studentRow["first_name"]) ? $studentRow["first_name"] : '';
        $studentData["last_name"] = isset($studentRow["last_name"]) ? $studentRow["last_name"] : '';
        $studentData["phone_number"] = isset($studentRow["phone_number"]) ? $studentRow["phone_number"] : '';
        $studentData["date_of_birth"] = isset($studentRow["date_of_birth"]) ? $studentRow["date_of_birth"] : '';
        $studentData["address"] = isset($studentRow["address"]) ? $studentRow["address"] : '';
        $studentData["skills"] = isset($studentRow["skills"]) ? $studentRow["skills"] : '';

        // Fetch marks details from the database
        $marksQuery = "SELECT * FROM marks WHERE usn = '$usn'";
        $marksResult = $conn->query($marksQuery);
        $marksData = array();

        if ($marksResult->num_rows > 0) {
            // Fetch marks details
            while ($marksRow = $marksResult->fetch_assoc()) {
                $marksData[] = array(
                    "subject_code" => $marksRow["subject_code"],
                    "test1_marks" => $marksRow["test1_marks"],
                    "test2_marks" => $marksRow["test2_marks"],
                    "average_marks" => $marksRow["average_marks"]
                );
            }
        }

        // Add marks data to the student data array
        $studentData["marks"] = $marksData;
    }

    // Convert student data array to JSON and output
    echo json_encode($studentData);
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["usn"])) {
    // Retrieve form data for updating student details
    $usn = $_POST["usn"];
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $phone_number = $_POST["phone_number"];
    $date_of_birth = $_POST["date_of_birth"];
    $address = $_POST["address"];
    $skills = $_POST["skills"];

    // Update student details in the database
    $updateStudentQuery = "UPDATE students SET first_name = '$first_name', last_name = '$last_name', phone_number = '$phone_number', date_of_birth = '$date_of_birth', address = '$address', skills = '$skills' WHERE usn = '$usn'";
    if ($conn->query($updateStudentQuery) !== TRUE) {
        echo "Error updating student details: " . $conn->error;
    } else {
        // Update average marks and final_av
        updateAverage($conn, $usn);
        echo "Student details updated successfully!";
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_marks"])) {
    // Retrieve form data for updating marks details
    $usn = $_POST["usn"];
    $subject_code = $_POST["subject_code"];
    $test1_marks = $_POST["test1_marks"];
    $test2_marks = $_POST["test2_marks"];
    
    // Update marks details in the database
    $updateMarksQuery = "UPDATE marks SET test1_marks = '$test1_marks', test2_marks = '$test2_marks' WHERE usn = '$usn' AND subject_code = '$subject_code'";
    if ($conn->query($updateMarksQuery) !== TRUE) {
        echo "Error updating marks details: " . $conn->error;
    } else {
        // Update average marks and final_av
        updateAverage($conn, $usn);
        echo "Marks details updated successfully!";
    }
} else {
    // Handle invalid or missing requests
    echo "Invalid request!";
}
?>
  

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
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
        .marksedit-details {
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
    </style>
<body>

<header>
        <h1>VidyaVibhushan</h1>
    </header>

  <nav>
        <a href="index.html">Home</a>
        <a href="about_us.html">About Us</a>
        <a href="#contact">Contact Us</a>
    </nav>
    
    <main>
        <!-- Input field for USN -->
        <input type="text" id="usnInput" placeholder="Enter USN">
        <button onclick="fetchStudentData()">Submit</button>

        <!-- Student Details -->
        <div class="student-details">
            <h2>Student Details</h2>
            <form method="post">
                <input type="hidden" name="usn" id="usnField" value="">
                <p>Name: <span id="studentName"></span> <button type="button">edit</button></p>
                <p>USN: <span id="studentUSN"></span> <button type="button">edit</button></p>
                <p>Phone Number: <span id="phoneNumber"></span> <button type="button">edit</button></p>
                <p>Date of Birth: <span id="dob"></span> <button type="button">edit</button></p>
                <p>Address: <span id="address"></span> <button type="button">edit</button></p>
                <p>Skills: <span id="skills"></span> <button type="button">edit</button></p>
                <button type="submit" name="update_student">Save Changes</button>
            </form>
        </div>

        <!-- Marks Details -->
        <div class="marksedit-details">
            <h2>Marks Details</h2>
            <table id="marksTable">
                <!-- Table rows will be dynamically populated here -->
            </table>
            <button type="submit" name="update_marks">Save Changes</button>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <p>Team Members' Contact:</p>
        <!-- Add team members' email addresses and phone numbers here -->
    </footer>

    <script>
        function fetchStudentData() {
            var usn = document.getElementById("usnInput").value;
            // AJAX request to fetch student details
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var studentData = JSON.parse(this.responseText);
                    // Populate student details
                    document.getElementById("usnField").value = studentData.usn;
                    document.getElementById("studentName").innerText = studentData.first_name + " " + studentData.last_name;
                    document.getElementById("studentUSN").innerText = studentData.usn;
                    document.getElementById("phoneNumber").innerText = studentData.phone_number;
                    document.getElementById("dob").innerText = studentData.date_of_birth;
                    document.getElementById("address").innerText = studentData.address;
                    document.getElementById("skills").innerText = studentData.skills;

                    // Populate marks details
                    var marksTable = document.getElementById("marksTable");
                    marksTable.innerHTML = "<tr><th>Subject</th><th>Test 1</th><th>Test 2</th><th>Average</th></tr>";
                    studentData.marks.forEach(function(mark) {
                        var row = "<tr><td>" + mark.subject_code + "</td><td><input type='text' name='test1_marks' value='" + mark.test1_marks + "'></td><td><input type='text' name='test2_marks' value='" + mark.test2_marks + "'></td><td>" + mark.average_marks + "</td></tr>";
                        marksTable.innerHTML += row;
                    });
                }
            };
            xhttp.open("GET", "fetch_student_data.php?usn=" + usn, true);
            xhttp.send();
        }
    </script>
</body>
</html>
