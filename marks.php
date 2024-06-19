<?php
// Include the database connection file
include 'db_connection.php';

// Start the session
session_start();

// Check if the user is authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not authenticated
    exit();
}

// Fetch student details based on the logged-in user's ID
$studentId = $_SESSION['user_id'];
$query = "SELECT students.*, marks.subject_code, marks.test1_marks, marks.test2_marks, marks.average_marks FROM students
          LEFT JOIN marks ON students.student_id = marks.student_id
          WHERE students.student_id = '$studentId'";
$result = $conn->query($query);

// Check if student details and marks are found
if ($result->num_rows > 0) {
    // Fetch the first row to get student name and USN
    $student = $result->fetch_assoc();
    $studentName = $student['first_name'] . ' ' . $student['last_name'];
    $studentUSN = $student['usn'];

    // Fetch the first row of subject code and marks
    $firstSubject = array(
        'subject_code' => $student['subject_code'],
        'test1_marks' => $student['test1_marks'],
        'test2_marks' => $student['test2_marks'],
        'average_marks' => $student['average_marks']
    );

    // Fetch the remaining rows
    $subjects = array();
    while ($student = $result->fetch_assoc()) {
        $subjects[] = array(
            'subject_code' => $student['subject_code'],
            'test1_marks' => $student['test1_marks'],
            'test2_marks' => $student['test2_marks'],
            'average_marks' => $student['average_marks']
        );
    }
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
    <title>Student Marks</title>
    <style>
       body {
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    background-color: #f4f7f6; /* Lighter background for the whole page */
}

header {
    background-color: #005792; /* Dark blue for header */
    color: #ffffff;
    text-align: center;
    padding: 20px 10px; /* Increased padding for a better look */
}

.container {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.container1 {
    display: flex;
    justify-content: space-around;
    background-color: #e8f1f2; /* Soft blue for container background */
    padding: 20px;
    width: 100%;
    box-sizing: border-box;
    border-radius: 5px; /* Rounded corners */
    margin-top: 20px; /* Added space from header */
}

.table1 {
    margin-top: 20px;
    width: 80%;
    box-sizing: border-box;
    display: flex;
    justify-content: center;
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    overflow: hidden;
}

th, td {
    border: 1px solid #ddd;
    padding: 12px;
    text-align: left;
}

th {
    background-color: #0077b6; /* Slightly lighter blue for headers */
    color: #ffffff;
}

tr:nth-child(even) {
    background-color: #f2f2f2;
}

tr:hover {
    background-color: #ddd;
}

.download-btn, .exit-btn {
    margin-top: 20px;
    padding: 10px;
    background-color: #005792; /* Unified button color */
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    text-decoration: none; /* For exit-btn */
}

.download-btn:hover, .exit-btn:hover {
    background-color: #0077b6; /* Slightly lighter on hover */
}

.exit-btn:active {
    color: #fff;
}

.pdf-header {
    background-color: #005792; /* Matching the main header */
    color: #fff;
    padding: 10px;
    text-align: center;
    border-radius: 5px 5px 0 0;
}

.pdf-content {
    padding: 20px;
    border: 1px solid #ddd;
    border-top: none;
    border-radius: 0 0 5px 5px;
}

.pdf-table th, .pdf-table td {
    border: 1px solid #ddd;
    padding: 12px;
    text-align: left;
}

.pdf-table th {
    background-color: #0077b6; /* Slightly lighter blue for headers */
    color: #fff;
}

.pdf-table tr:nth-child(even) {
    background-color: #f2f2f2;
}

.pdf-table tr:hover {
    background-color: #ddd;
}

    </style>
</head>
<body>
    <header>
        <h1>VidyaVibhushan</h1>
    </header>

    <div class="container">
        <div class="container1">
            <p>Student name: <?php echo isset($studentName) ? $studentName : ''; ?></p>
            <p>Student USN: <?php echo isset($studentUSN) ? $studentUSN : ''; ?></p>
        </div>

        <div class="table1">
            <table>
                <tr>
                    <th>Subjects</th>
                    <th>Test 1</th>
                    <th>Test 2</th>
                    <th>Average</th>
                </tr>
                <?php
                // Display the first row of subject code and marks
                if (!empty($firstSubject)) {
                    ?>
                    <tr>
                        <td><?php echo $firstSubject['subject_code']; ?></td>
                        <td><?php echo $firstSubject['test1_marks']; ?></td>
                        <td><?php echo $firstSubject['test2_marks']; ?></td>
                        <td><?php echo $firstSubject['average_marks']; ?></td>
                    </tr>
                    <?php
                }

                // Loop through the remaining subjects array and display each subject and marks
                foreach ($subjects as $subject) {
                    ?>
                    <tr>
                        <td><?php echo $subject['subject_code']; ?></td>
                        <td><?php echo $subject['test1_marks']; ?></td>
                        <td><?php echo $subject['test2_marks']; ?></td>
                        <td><?php echo $subject['average_marks']; ?></td>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>

        <button class="download-btn" onclick="downloadData()">Download PDF</button>
        <a href="stud.php" class="exit-btn">Back to portal</a>

    </div>

    <script src="https://rawgit.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js"></script>
    <script>
        function downloadData() {
    // Get student name and USN
    var studentName = "<?php echo isset($studentName) ? $studentName : ''; ?>";
    var studentUSN = "<?php echo isset($studentUSN) ? $studentUSN : ''; ?>";

    // Get table data
    var table = document.querySelector(".table1 table");

    // Create a clone of the table to avoid modifying the original table
    var clonedTable = table.cloneNode(true);

    // Append the student name and USN as additional rows
    var headerRow = document.createElement("tr");
    headerRow.innerHTML = `<th colspan="4">Student Name: ${studentName}, Student USN: ${studentUSN}</th>`;
    clonedTable.querySelector("tbody").insertBefore(headerRow, clonedTable.querySelector("tbody").firstChild);

    // Create a new div to wrap the cloned table
    var div = document.createElement("div");

    // Add PDF header
    var pdfHeader = document.createElement("div");
    pdfHeader.className = "pdf-header";
    pdfHeader.innerHTML = `
        <p>Kammavari Sangham (R) 1952, K.S.Group of Institutions</p>
        <p>K. S. INSTITUTE OF TECHNOLOGY</p>
        <p>No.14, Raghuvanahalli, Kanakapura Road, Bengaluru - 560109, 9900710055</p>
        <p>Affiliated to VTU, Belagavi & Approved by AICTE, New Delhi, Accredited by NBA, NAAC & IEI</p>
        <p>Student Data Report</p>
    `;
    div.appendChild(pdfHeader);

    // Add PDF content
    var pdfContent = document.createElement("div");
    pdfContent.className = "pdf-content";
    pdfContent.appendChild(clonedTable);
    div.appendChild(pdfContent);

    // Convert the div to a PDF using html2pdf.js
    html2pdf(div, {
        margin: 10,
        filename: `${studentName}_${studentUSN}_report.pdf`,
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
    });
}
    </script>
</body>
</html>
