<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student Details</title>
    <style>
        /* Your CSS styles here */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(145deg, #f4f7f6, #accbee);
            color: #333;
            transition: all 0.3s ease;
        }

        .start {
            display: flex;
            gap: 10px;
            flex-direction: column;
            align-items: center;
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

        footer {
            background: linear-gradient(145deg, #1e4158, #1597bb);
            color: #ffffff;
            text-align: center;
            padding: 20px 10px;
            margin-top: 20px;
            box-shadow: 0 -4px 6px rgba(0, 0, 0, 0.2);
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
        textarea,
        input[type="date"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }

        input[type="submit"],
        .back {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 16px;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: #fff;
        }

        .back {
            background-color: #FDA403;
            color: #fff;
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
    <div class="container">
        <?php
        include 'db_connection.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $usn = $_POST['usn'];
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $email = $_POST['email'];
            $phone_number = $_POST['phone_number'];
            $date_of_birth = $_POST['date_of_birth'];
            $address = $_POST['address'];
            $skills = $_POST['skills'];

            $query = "UPDATE students SET first_name='$first_name', last_name='$last_name', email='$email', 
                      phone_number='$phone_number', date_of_birth='$date_of_birth', address='$address', skills='$skills'
                      WHERE usn='$usn'";

            if (mysqli_query($conn, $query)) {
                echo '<p class="alert">Student details updated successfully.</p>';
            } else {
                echo '<p class="alert">Error updating student details: ' . mysqli_error($conn) . '</p>';
            }

            mysqli_close($conn);
        } else {
            // Display form to edit student details
            $usn = $_GET['usn'];
            $query = "SELECT * FROM students WHERE usn='$usn'";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            ?>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="hidden" name="usn" value="<?php echo $row['usn']; ?>">
                
                <label for="first_name">First Name:</label><br>
                <input type="text" id="first_name" name="first_name" value="<?php echo $row['first_name']; ?>" required><br>
                
                <label for="last_name">Last Name:</label><br>
                <input type="text" id="last_name" name="last_name" value="<?php echo $row['last_name']; ?>" required><br>
                
                <label for="email">Email:</label><br>
                <input type="email" id="email" name="email" value="<?php echo $row['email']; ?>" required><br>
                
                <label for="phone_number">Phone Number:</label><br>
                <input type="text" id="phone_number" name="phone_number" value="<?php echo $row['phone_number']; ?>"><br>
                
                <label for="date_of_birth">Date of Birth:</label><br>
                <input type="date" id="date_of_birth" name="date_of_birth" value="<?php echo $row['date_of_birth']; ?>" required><br>
                
                <label for="address">Address:</label><br>
                <textarea id="address" name="address" required><?php echo $row['address']; ?></textarea><br>
                
                <label for="skills">Skills:</label><br>
                <input type="text" id="skills" name="skills" value="<?php echo $row['skills']; ?>"><br>
                
                <input type="submit" value="Submit"><br>
            </form>
        <?php
        }
        ?>
    </div>
</main>

<footer>
    <p>Team Members' Contact:</p>
    <!-- Add team members' email addresses and phone numbers here -->
</footer>

</body>
</html>

