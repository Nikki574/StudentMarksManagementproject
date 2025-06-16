<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "21_cse";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the update form has been submitted
if (isset($_POST['update'])) {
    $register_number = $_POST['register_number'];
    $semester = $_POST['semester'];

    // Prepare the update statement
    $subjects = $_POST['subjects']; // Array of subjects
    $query = "UPDATE `$semester` SET ";
    $params = [];
    $sets = [];

    foreach ($subjects as $subject => $value) {
        $subject_key = str_replace(' ', '_', $subject);
        $sets[] = "`$subject_key` = ?";
        $params[] = $value;
    }

    $query .= implode(", ", $sets) . " WHERE `register_number` = ?";
    $params[] = $register_number;

    // Prepare and bind
    $stmt = $conn->prepare($query);
    if ($stmt) {
        // Bind parameters dynamically
        $types = str_repeat('s', count($params)); // Assuming all are strings
        $stmt->bind_param($types, ...$params);

        // Execute the statement
        if ($stmt->execute()) {
            echo "<h2>Record updated successfully!</h2>";
        } else {
            echo "Error updating record: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}

// Get form data for initial display
if (isset($_POST['register_number']) && isset($_POST['semester'])) {
    $register_number = $_POST['register_number'];
    $semester = $_POST['semester'];

    // Fetch the results based on the semester
    $sql = "SELECT * FROM `$semester` WHERE `register_number` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $register_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Fetch the full name from the row
        $full_name = $row['full_name']; // Ensure you have 'full_name' in your table

        // Mapping subjects for each semester
        $subjects = [
            'cse_1_1' => [
                "communicative_english", "mathematics_1", "applied_physics", 
                "programming_c", "computer_engg_workshop", 
                "english_comm_lab", "applied_physics_lab", 
                "programming_c_lab"
            ],
            'cse_1_2' => [
                "Mathematics 2", "Applied Chemistry", "Computer Organization", 
                "Python Programming", "Data Structures", 
                "Applied Chemistry Lab", "Python Programming Lab", 
                "Data Structures Lab", "Environment Science"
            ],
            'cse_2_1' => [
                "Mathematics 3", "Object Oriented Programming through C++", 
                "Operating System", "Software Engineering", 
                "Mathematical Foundations of Computer Science", 
                "Object_Oriented_Programming_through_C++_Lab", "Operating System Lab", 
                "Software Engineering Lab", "Skill Oriented Course I", 
                "Constitution of India"
            ],
            'cse_2_2' => [
                "Probability and Statistics", "Database Management Systems", 
                "Formal Languages and Automata Theory", "Java Programming", 
                "Managerial Economics and Financial Accountancy", 
                "Database_Management_Systems_Lab", "R Programming Lab", "Java Programming Lab", 
                "Skill_Orinted_Course_II"
            ],
            'cse_3_1' => [
                "Data Warehousing and Data Mining", "Open Elective I", 
                "Professional Elective I", "Data_Warehousing_and_Data_Mining_Lab", 
                "Computer Networks Lab", "Skill Oriented Course III", 
                "Employability Skills I", "Summer Internship"
            ],
            'cse_3_2' => [
                "Machine Learning", "Compiler Design", 
                "Cryptography and Network Security", "Open Elective II", 
                "Professional Elective II", "Machine Learning using Python Lab", 
                "Compiler_Design_Lab", "Cryptography_and_Network_Security_Lab", 
                "Skill_Oriented_Course_IV", "Employability_Skills_II"
            ]
        ];

        // Adding inline CSS for compact layout
        echo "<style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f7f7f7;
                margin: 0;
                padding: 0;
                overflow-x: hidden;
            }
            h2, h3 {
                text-align: center;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                color: #333;
                margin: 5px 0;
                line-height: 1.1;
            }
            h2 {
                font-size: 1.8em;
                background: -webkit-linear-gradient(#4CAF50, #006400);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
            }
            h3 {
                font-size: 1.2em;
                color: #007BFF;
                text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
            }
            table {
                width: 100%;
                margin: 0 auto;
                border-collapse: collapse;
                background-color: #fff;
            }
            th, td {
                padding: 5px;
                border: 1px solid #ddd;
                text-align: center;
                font-size: 0.9em;
            }
            th {
                background-color: #4CAF50;
                color: white;
            }
            td {
                background-color: #f9f9f9;
            }
            tr:hover {
                background-color: #f1f1f1;
                transition: 0.3s;
            }
            .buttons {
                text-align: center;
                margin-top: 15px;
            }
            .buttons a, .buttons input[type='submit'] {
                text-decoration: none;
                padding: 8px 15px;
                background-color: #4CAF50;
                color: white;
                border-radius: 4px;
                margin: 5px;
                font-size: 0.9em;
                transition: background-color 0.3s;
                border: none;
                cursor: pointer;
            }
            .buttons a:hover, .buttons input[type='submit']:hover {
                background-color: #45a049;
            }
        </style>";

        // Display the form with text boxes
        echo "<h2>Update Results for Register Number: " . htmlspecialchars($register_number) . "</h2>";
        echo "<h3>Student Name: " . htmlspecialchars($full_name) . "</h3>"; // Display the student's name
        echo "<h3>Semester: " . htmlspecialchars($semester) . "</h3>";

        echo "<form method='POST' action=''>";
        echo "<input type='hidden' name='register_number' value='" . htmlspecialchars($register_number) . "'>";
        echo "<input type='hidden' name='semester' value='" . htmlspecialchars($semester) . "'>";
        echo "<table>";
        echo "<tr><th>SUBJECTS</th><th>MARKS</th></tr>";

        // Loop through subjects and display marks in text boxes
        foreach ($subjects[$semester] as $subject) {
            $subject_key = str_replace(' ', '_', $subject);
            echo "<tr><td>" . htmlspecialchars($subject) . "</td><td><input type='text' name='subjects[" . htmlspecialchars($subject) . "]' value='" . htmlspecialchars($row[$subject_key]) . "'></td></tr>";
        }

        // Display SGPA and Percentage with editable text boxes
        echo "<tr><td>SGPA</td><td><input type='text' name='subjects[sgpa]' value='" . htmlspecialchars($row['sgpa']) . "'></td></tr>";
        echo "<tr><td>Percentage</td><td><input type='text' name='subjects[percentage]' value='" . htmlspecialchars($row['percentage']) . "'></td></tr>";

        echo "</table>";

        // Add buttons
        echo "<div class='buttons'>
                <a href='cse.html'>Home</a>
                <a href='cse_update_marks.html'>Back</a>
                <input type='submit' name='update' value='Update'>
              </div>";

        echo "</form>";
    } else {
        echo "<h3>No records found for Register Number: " . htmlspecialchars($register_number) . "</h3>";
    }
    $stmt->close();
}

$conn->close();
?>
