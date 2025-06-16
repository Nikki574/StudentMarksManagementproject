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

// Get form data
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

    // Adding inline CSS for stylish effects and compact display
    echo "<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
            line-height: 1.2; /* Decreasing line height for more compact text */
        }
        h2, h3 {
            text-align: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            margin-bottom: 10px;
            line-height: 1.1; /* Compact line spacing */
        }
        h2 {
            font-size: 2.2em;
            background: -webkit-linear-gradient(#4CAF50, #006400);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
            margin-top: 20px;
        }
        h3 {
            font-size: 1.5em;
            color: #007BFF;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
            margin-top: 5px;
            margin-bottom: 5px;
        }
        table {
            width: 80%;
            margin: 20px auto; /* Reducing margins to fit better on the screen */
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }
        th, td {
            padding: 10px; /* Reduced padding to fit more content */
            border: 1px solid #ddd;
            text-align: center;
            font-size: 0.9em; /* Decrease font size for better visibility on one screen */
        }
        th {
            background-color: #4CAF50;
            color: white;
            font-size: 1.1em;
        }
        td {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
            transition: 0.2s; /* Faster hover transition */
        }
        .buttons {
            text-align: center;
            margin-top: 20px;
        }
        .buttons a {
            text-decoration: none;
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border-radius: 5px;
            margin: 5px;
            transition: background-color 0.3s;
        }
        .buttons a:hover {
            background-color: #45a049;
        }
    </style>";

    // Displaying the register number, student name, and semester above the table with stylish effects
    echo "<h2>Results for Register Number: " . htmlspecialchars($register_number) . "</h2>";
    echo "<h3>Student Name: " . htmlspecialchars($row['full_name']) . "</h3>";
    echo "<h3>Semester: " . htmlspecialchars($semester) . "</h3>";
	
    // Displaying the results in a colorful, compact, 3D-style table format
    echo "<table>";
    echo "<tr><th>SUBJECTS</th><th>MARKS</th></tr>";

    // Loop through subjects and display marks
    foreach ($subjects[$semester] as $subject) {
        echo "<tr><td>" . $subject . "</td><td>" . $row[(str_replace(' ', '_', $subject))] . "</td></tr>";
    }

    // Display SGPA and Percentage
    echo "<tr><td>SGPA</td><td>" . $row['sgpa'] . "</td></tr>";
    echo "<tr><td>Percentage</td><td>" . $row['percentage'] . "</td></tr>";

    echo "</table>";

    // Add buttons for navigation
    echo "<div class='buttons'>
            <a href='cse.html'>Home</a>
            <a href='cse_get_results.html'>Results</a>
          </div>";
} else {
    echo "<h2>No results found for the given register number and semester.</h2>";
}

$stmt->close();
$conn->close();
?>
