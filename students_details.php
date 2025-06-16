<?php
// Include your database connection file
include 'db_connection.php'; // Make sure this file contains the database connection settings

// Query to retrieve data only if register number length is greater than zero
$query = "SELECT * FROM students_details WHERE LENGTH(student_reg_no) > 0";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// HTML and CSS for the table
echo '<html>';
echo '<head>';
echo '<title>Students Details</title>';
echo '<style>
        body {
            font-family: Arial, sans-serif; /* Default font for the body */
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .header-container {
            display: flex;
            justify-content: space-between; /* Space between text and button */
            align-items: center; /* Center align vertically */
            padding: 20px; /* Padding around the header */
        }
        h2 {
            color: #4CAF50; /* Green color for the header */
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3); /* Text shadow for a 3D effect */
            margin: 0; /* Remove default margin */
            font-size: 2.5em; /* Larger font size */
        }
        table {
            width: 90%; /* Set a max width for the table */
            margin: 20px auto; /* Center the table */
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px; /* Rounded corners */
            overflow: hidden; /* Ensures the border radius is respected */
        }
        table, th, td {
            border: 1px solid #ddd; /* Light border for better aesthetics */
        }
        th, td {
            padding: 12px; /* Increased padding for better spacing */
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
            text-transform: uppercase; /* Uppercase letters for headers */
            letter-spacing: 1px; /* Spacing between letters */
        }
        tr:nth-child(even) {
            background-color: #f2f2f2; /* Light gray for even rows */
        }
        tr:hover {
            background-color: #ddd; /* Highlight row on hover */
        }
        .btn {
            padding: 12px 25px; /* Larger button size */
            text-decoration: none;
            background-color: #4CAF50;
            color: white;
            border-radius: 5px;
            border: none;
            font-size: 18px; /* Larger font size */
            cursor: pointer;
            transition: background-color 0.3s; /* Smooth transition */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Shadow for buttons */
        }
        .btn:hover {
            background-color: #45a049; /* Darker green on hover */
        }
    </style>';
echo '</head>';
echo '<body>';

echo '<div class="header-container">';
echo '<h2>Student Details</h2>'; // Header text
echo '<a href="cse.html" class="btn">Home</a>'; // Home button
echo '</div>';

echo '<table>';

// Check if data is available
if (mysqli_num_rows($result) > 0) {
    // Start creating the table
    echo '<tr>
            <th>Student Reg No</th>
            <th>Student Name</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Student Aadhar</th>
          </tr>';

    // Fetch and display each row of the table
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>
                <td>' . htmlspecialchars($row['student_reg_no']) . '</td>
                <td>' . htmlspecialchars($row['student_name']) . '</td>
                <td>' . htmlspecialchars($row['email']) . '</td>
                <td>' . htmlspecialchars($row['mobile']) . '</td>
                <td>' . htmlspecialchars($row['student_aadhar']) . '</td>
              </tr>';
    }
    
    echo '</table>';
} else {
    echo "<p style='text-align: center;'>No results found!</p>";
}

echo '</body>';
echo '</html>';

// Close the database connection
mysqli_close($conn);
?>
