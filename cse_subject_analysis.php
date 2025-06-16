<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "21_cse";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get semester and subject from POST
$semester = $_POST['semester'];
$subject = $_POST['subject'];

// Escape the input to prevent SQL injection
$semester = $conn->real_escape_string($semester);
$subject = $conn->real_escape_string($subject);

// Query to get the grade distribution for the selected subject
$sql = "SELECT `$subject` as grade, COUNT(*) as count 
        FROM `$semester` 
        WHERE `$subject` IN ('A+', 'A', 'B', 'C', 'D', 'E', 'F') 
        GROUP BY `$subject`";

$result = $conn->query($sql);

// Check for query success
if (!$result) {
    die("Query failed: " . $conn->error);
}

$grades = [];
$counts = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $grades[] = $row['grade'];
        $counts[] = $row['count'];
    }
} else {
    echo "No data found for the selected subject.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grade Distribution Analysis</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-image: url('path/to/your/background.jpg'); 
            background-size: cover;
            color: white;
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.7);
        }
        .header-container {
            display: flex; 
            justify-content: center; 
            align-items: center; 
            margin-bottom: 20px;
        }
        .college-logo {
            max-width: 150px;
            margin-right: 20px;
            transition: transform 0.3s;
        }
        .college-logo:hover {
            transform: scale(1.1);
        }
        .college-name {
            text-align: center;
            flex-grow: 1;
            text-shadow: 2px 2px 4px black;
        }
        .college-name h2 {
            color: #FFD700;
            margin: 0;
            font-size: 2em;
        }
        .college-name h3, .college-name h4 {
            background: rgba(0, 0, 0, 0.7);
            padding: 5px;
            border-radius: 5px;
            color: #FFD700;
            margin: 0;
        }
        .container {
            display: flex;
            justify-content: space-between;
        }
        .chart-container {
            max-width: 450px;
            margin-left: 20px;
            perspective: 1000px;
        }
        .table-container {
            max-width: 450px;
            margin-right: 20px;
            background: rgba(0, 0, 0, 0.7);
            border-radius: 10px;
            padding: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.7);
        }
        canvas {
            background: rgba(0, 0, 0, 0.7);
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.7);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 10px;
            overflow: hidden;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #333;
        }
        td {
            border-bottom: 1px solid #ddd;
        }
        .button-container {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }
        .button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            margin: 0 10px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.3s;
        }
        .button:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }
    </style>
</head>
<body>

    <div class="header-container">
        <img src="logo.png" alt="College Logo" class="college-logo"> 
        <div class="college-name">
            <h2>AVANTHI'S ST. THERESSA ENGINEERING AND TECHNOLOGY</h2> 
            <h1>Subject Analysis</h1>
            <h2>Semester: <?php echo htmlspecialchars($semester); ?> Subject: <?php echo htmlspecialchars($subject); ?></h2>
        </div>
    </div>

    <div class="container">
        <div class="table-container">
            <h3>Grade COUNT Analysis</h3>
            <table>
                <tr>
                    <th>Grade</th>
                    <th>Count</th>
                    <th>Percentage</th>
                </tr>
                <?php
                // Reconnect to the database for grade count analysis
                $conn = new mysqli($servername, $username, $password, $dbname);
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $totalCount = 0;
                    while ($row = $result->fetch_assoc()) {
                        $totalCount += $row['count'];
                    }
                    $result->data_seek(0); 
                    while ($row = $result->fetch_assoc()) {
                        $percentage = ($row['count'] / $totalCount) * 100;
                        echo "<tr>";
                        echo "<td style='color: " . ($row['grade'] === 'F' ? 'red' : '#fff') . "'>" . $row['grade'] . "</td>";
                        echo "<td>" . $row['count'] . "</td>";
                        echo "<td>" . round($percentage, 2) . "%</td>";
                        echo "</tr>";
                    }
                }
                $conn->close();
                ?>
            </table>
        </div>

        <div style="text-align: center; margin: 20px 0;">
            <img src="analysis.jpg" alt="College Photo" style="max-width: 90%; height: auto; border-radius: 10px; box-shadow: 0 0 20px rgba(0, 0, 0, 0.7);">
        </div>

        <div class="chart-container">
            <canvas id="myChart"></canvas>
        </div>
    </div>

    <script>
        const grades = <?php echo json_encode($grades); ?>;
        const counts = <?php echo json_encode($counts); ?>;

        const barColors = grades.map(grade => {
            switch (grade) {
                case 'A+': return '#b91d47';
                case 'A': return '#00aba9';
                case 'B': return '#2b5797';
                case 'C': return '#e8c3b9';
                case 'D': return '#1e7145';
                case 'E': return '#f39c12';
                case 'F': return '#FF0000'; 
                default: return '#000000'; 
            }
        });

        const ctx = document.getElementById("myChart").getContext("2d");
        new Chart(ctx, {
            type: "pie",
            data: {
                labels: grades,
                datasets: [{
                    backgroundColor: barColors,
                    data: counts
                }]
            },
            options: {
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: (tooltipItem) => {
                                const total = counts.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((tooltipItem.raw / total) * 100);
                                return `${tooltipItem.label}: ${tooltipItem.raw} (${percentage}%)`;
                            }
                        }
                    },
                    datalabels: {
                        color: '#FFFFFF', 
                        formatter: (value, context) => {
                            return context.chart.data.labels[context.dataIndex];
                        }
                    }
                }
            },
            responsive: true,
            maintainAspectRatio: false,
        });
    </script>

    <div class="button-container">
        <button class="button" onclick="location.href='cse_subject_analysis.html'">Back</button>
		<button class="button" onclick="location.href='cse.html'">Home</button>
    </div>
</body>
</html>
