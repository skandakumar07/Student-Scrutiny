<?php


include 'db_connection.php'; // Include the database connection file


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <style>
 * {
    margin: 0;
 
}

body{
    background-image: url('login.png');
    background-size: cover; /* Cover the entire page */
    background-position: center; /* Center the background image */
}
.container {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
   
    margin: 0;
    display: flex;
    align-items: center;
    height: 92vh;
}

.login-container {
    background-color: transparent;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    width: 80%; /* Adjust the width as needed */
    max-width: 400px; /* Set a maximum width for better responsiveness */
    text-align: center;
    transition: transform 0.3s ease-in-out;
    position: relative;
    left: 90px;
}

.toggle-container {
    margin-top: 10px;
    display: flex;
    justify-content: space-around;
}

.toggle-btn {
    cursor: pointer;
    padding: 10px;
    border: none;
    text-decoration: none;
    font-weight: bold;
    transition: color 0.3s ease-in-out;
    display: flex;
    align-items: center;
    justify-content: center;
    border-bottom: 2px solid transparent; /* Add transparent border-bottom by default */
}

.toggle-btn.student-btn {
    color: #3498db; /* Blue color for Student button */
}

.toggle-btn.student-btn:hover {
    color: #2072a6; /* Change text color when Student button is hovered */
    border-bottom: 2px solid #2072a6; /* Change border color when Student button is hovered */
}

.toggle-btn.interviewer-btn {
    color: #f3f309; /* Yellow color for Interviewer button */
}

.toggle-btn.interviewer-btn:hover {
    color: #f3f309; /* Change text color when Interviewer button is hovered */
    border-bottom: 2px solid #f3f309; /* Change border color when Interviewer button is hovered */
}

.toggle-btn.admin-btn {
    color: #e74c3c; /* Red color for Admin button */
}

.toggle-btn.admin-btn:hover {
    color: #e74c3c; /* Change text color when Admin button is hovered */
    border-bottom: 2px solid #e74c3c; /* Change border color when Admin button is hovered */
}

.form-group {
    margin-bottom: 20px;
    text-align: left;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #333;
}

.form-group input {
    width: calc(100% - 20px); /* Adjust the width as needed */
    padding: 10px;
    margin-bottom: 10px; /* Add margin-bottom for spacing */
    border: 1px solid #ccc;
    border-radius: 5px;
}

.form-group button {
    width: 100%;
    padding: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease-in-out;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #c7abf8; /* Sets the background color to dark purple */
}

.form-group button.student-btn:hover {
    background-color: #2072a6;
    color: #fff; /* Adjusted color for better visibility */
}

.form-group button.admin-btn:hover, .admin-login-btn:hover {
    background-color: #e74c3c;
    color: #fff; /* Adjusted color for better visibility */
}

.form-group button.interviewer-btn:hover, .interviewer-login-btn:hover {
    background-color: #f3f309;
    color: #fff; /* Adjusted color for better visibility */
}

.form-group p {
    margin-top: 10px;
    color: #777;
}

/* Style for icons */
.icon {
    margin-right: 5px;
}

header {
    background-color: transparent;
    padding: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 20px;
}

nav ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
}

nav li {
    display: inline;
    margin-right: 90px;
    font-size: large;

}

nav a {
    text-decoration: none;
    color: #7e57c2; /* Deep purple for bright visibility on light purple */
    font-weight: bold;
}

nav a:hover {
    color: #5e35b1; /* Slightly darker purple for hover effect */
}

    </style>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="about_us.html">About</a></li>
                <li><a href="tel:+917019400282">Contact</a></li>
            </ul>
        </nav>
    </header>
<div class="container">
<div class="login-container">
    <h2>Login</h2>
    
    <div class="toggle-container">
        <span class="toggle-btn student-btn" onclick="toggleForm('student-form')">
            <i class="fas fa-user icon"></i> Student
        </span>
        <span class="toggle-btn admin-btn" onclick="toggleForm('admin-form')">
            <i class="fas fa-user-shield icon"></i> Admin
        </span>
        
    </div>
    
    <!-- Student login form -->
    <form id="student-form" class="form-group" action="validate.php" method="post">
        <label for="student-username">Student ID:</label>
        <input type="text" id="student-username" name="student-username" required>
        <label for="student-password">Password:</label>
        <input type="password" id="student-password" name="student-password" required>
        <button type="submit" class="student-btn" name="student-login">
            <i class="fas fa-sign-in-alt icon"></i> Login as Student
        </button>
    </form>

    <!-- Admin login form -->
    <form id="admin-form" class="form-group" style="display: none;" action="validate.php" method="post">
        <label for="admin-username">Admin ID:</label>
        <input type="text" id="admin-username" name="admin-username" required>
        <label for="admin-password">Password:</label>
        <input type="password" id="admin-password" name="admin-password" required>
        <button type="submit" class="admin-btn admin-login-btn" name="admin-login">
            <i class="fas fa-sign-in-alt icon"></i> Login as Admin
        </button>
    </form>
    
   

</div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>

<script>
    function toggleForm(formId) {
        var studentForm = document.getElementById('student-form');
        var adminForm = document.getElementById('admin-form');
        

        if (formId === 'student-form') {
            studentForm.style.display = 'block';
            adminForm.style.display = 'none';
            
        } else if (formId === 'admin-form') {
            studentForm.style.display = 'none';
            adminForm.style.display = 'block';
           
        }
        
    }
</script>

</body>
</html>