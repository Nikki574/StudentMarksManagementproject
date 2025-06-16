<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "21_cse";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$semesters = ['cse_1_1', 'cse_1_2', 'cse_2_1', 'cse_2_2', 'cse_3_1', 'cse_3_2'];

$sql = "SELECT register_number, full_name FROM cse_1_1";
$result = $conn->query($sql);

$final_data = [];

if ($result->num_rows > 0) {
    while ($student = $result->fetch_assoc()) {
        $register_number = $student['register_number'];
        $full_name = $student['full_name'];
        
        $backlog_count = 0;
        $sgpa_sum = 0;
        $valid_semesters = 0; 

        foreach ($semesters as $semester) {
            $sql_sem = "SELECT * FROM `$semester` WHERE `register_number` = ?";
            $stmt = $conn->prepare($sql_sem);
            $stmt->bind_param('s', $register_number);
            $stmt->execute();
            $result_sem = $stmt->get_result();
            
            if ($result_sem->num_rows > 0) {
                $row = $result_sem->fetch_assoc();

                foreach ($row as $key => $value) {
                    if ($key != 'sgpa' && $key != 'percentage' && $key != 'register_number' && $key != 'full_name') {
                        if ($value == 'F') {
                            $backlog_count++;
                        }
                    }
                }

                if (!empty($row['sgpa']) && is_numeric($row['sgpa'])) {
                    $sgpa_sum += $row['sgpa'];
                    $valid_semesters++;
                }
            }
        }

        $cgpa = $valid_semesters > 0 ? $sgpa_sum / $valid_semesters : 0;

        $final_data[] = [
            'register_number' => $register_number,
            'full_name' => $full_name,
            'backlogs' => $backlog_count,
            'cgpa' => round($cgpa, 2)  
        ];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Backlogs and CGPA</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 1em;
            text-align: left;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
        .dropdown {
            margin-bottom: 20px;
        }
        .dropdown select {
            padding: 8px;
            font-size: 16px;
        }
        h2 {
            text-align: center; 
            font-size: 28px; 
            font-family: Arial, sans-serif;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3); /* 3D effect */
        }
        .button {
            background-color: #4CAF50; /* Green */
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 10px 2px;
            cursor: pointer;
            border-radius: 5px;
            float: right;
        }
        .button:hover {
            background-color: #45a049; /* Darker green on hover */
        }
    </style>
</head>
<body>

<h2>Backlogs and CGPA for All Students (1-1 to 3-2)</h2>

<div class="dropdown">
    <label for="sort">Sort by: </label>
    <select id="sort" onchange="sortTable()">
        <option value="register_number">Register Number</option>
        <option value="backlogs">Backlogs</option>
        <option value="cgpa">CGPA</option>
    </select>
<button class="button" onclick="location.href='cse.html'">Home</button>
</div>

<!-- Home Button -->

<table id="studentTable">
    <thead>
        <tr>
            <th>Register Number</th>
            <th>Student Name</th>
            <th>Backlogs</th>
            <th>CGPA</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($final_data as $data) {
            echo "<tr>
                    <td>" . htmlspecialchars($data['register_number']) . "</td>
                    <td>" . htmlspecialchars($data['full_name']) . "</td>
                    <td>" . htmlspecialchars($data['backlogs']) . "</td>
                    <td>" . htmlspecialchars($data['cgpa']) . "</td>
                  </tr>";
        }
        ?>
    </tbody>
</table>

<script>
    function sortTable() {
        var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
        table = document.getElementById("studentTable");
        switching = true;
        
        var sortBy = document.getElementById("sort").value;
        
        dir = (sortBy === 'cgpa') ? "desc" : "asc";

        while (switching) {
            switching = false;
            rows = table.rows;
            for (i = 1; i < (rows.length - 1); i++) {
                shouldSwitch = false;
                x = rows[i].getElementsByTagName("TD")[getColumnIndex(sortBy)];
                y = rows[i + 1].getElementsByTagName("TD")[getColumnIndex(sortBy)];
                
                if (dir == "asc") {
                    if (isNaN(x.innerHTML) || isNaN(y.innerHTML)) {
                        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    } else {
                        if (parseFloat(x.innerHTML) > parseFloat(y.innerHTML)) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                } else if (dir == "desc") {
                    if (isNaN(x.innerHTML) || isNaN(y.innerHTML)) {
                        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    } else {
                        if (parseFloat(x.innerHTML) < parseFloat(y.innerHTML)) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                }
            }
            if (shouldSwitch) {
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
                switchcount++;
            } else {
                if (switchcount == 0 && dir == "asc") {
                    dir = "desc";
                    switching = true;
                }
            }
        }
    }
    function getColumnIndex(sortBy) {
        if (sortBy == 'register_number') return 0;
        if (sortBy == 'backlogs') return 2;
        if (sortBy == 'cgpa') return 3;
    }
</script>

</body>
</html>
