<?php
function generateHostelStudentsReport()
{
    function fetchHostelStudentsData()
    {
        // Fetch data for Hostel Students report from the database
        // ...

        $DB_host = "localhost";
        $DB_user = "root";
        $DB_pass = "";
        $DB_name = "hostelmsphp";

        try {
            $DB_con = new PDO("mysql:host={$DB_host};dbname={$DB_name}", $DB_user, $DB_pass);
            $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $query = "SELECT firstName, gender, contactNo FROM userregistration";
            $stmt = $DB_con->query($query);

            if ($stmt) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                return null;
            }
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    $userregistrationData = fetchHostelStudentsData();

    $tableOutput = '<table border="1">';
    $tableOutput .= '<tr><th>Name</th><th>Gender</th><th>Contact No</th></tr>';

    foreach ($userregistrationData as $student) {
        $tableOutput .= '<tr>';
        $tableOutput .= '<td>' . $student['firstName'] . '</td>';
        $tableOutput .= '<td>' . $student['gender'] . '</td>';
        $tableOutput .= '<td>' . $student['contactNo'] . '</td>';
        $tableOutput .= '</tr>';
    }

    $tableOutput .= '</table>';

    return $tableOutput;
}

function generateManageRoomsReport()
{
    // Fetch data for Manage Rooms report from the database
    // ...

    // Placeholder code
    $tableOutput = '<table border="1">';
    $tableOutput .= '<tr><th>Room Name</th><th>Capacity</th><th>Status</th></tr>';
    $tableOutput .= '<tr><td>Room 101</td><td>4</td><td>Occupied</td></tr>';
    $tableOutput .= '<tr><td>Room 102</td><td>2</td><td>Available</td></tr>';
    $tableOutput .= '</table>';

    return $tableOutput;
}

function generateManageCoursesReport()
{
    // Fetch data for Manage Courses report from the database
    // ...

    // Placeholder code
    $tableOutput = '<table border="1">';
    $tableOutput .= '<tr><th>Course Name</th><th>Instructor</th><th>Enrollment</th></tr>';
    $tableOutput .= '<tr><td>Mathematics</td><td>John Doe</td><td>30</td></tr>';
    $tableOutput .= '<tr><td>English Literature</td><td>Jane Smith</td><td>25</td></tr>';
    $tableOutput .= '</table>';

    return $tableOutput;
}

function generateViewStudentAccountsReport()
{
    // Fetch data for View Student Accounts report from the database
    // ...

    // Placeholder code
    $tableOutput = '<table border="1">';
    $tableOutput .= '<tr><th>Student Name</th><th>Account Balance</th></tr>';
    $tableOutput .= '<tr><td>John Doe</td><td>$500</td></tr>';
    $tableOutput .= '<tr><td>Jane Smith</td><td>$750</td></tr>';
    $tableOutput .= '</table>';

    return $tableOutput;
}

// Generate the report based on the selected report type
$reportType = $_GET['report'] ?? '';

switch ($reportType) {
    case 'hostel_students':
        $reportTitle = 'Hostel Students Report';
        $tableOutput = generateHostelStudentsReport();
        break;

    case 'manage_rooms':
        $reportTitle = 'Manage Rooms Report';
        $tableOutput = generateManageRoomsReport();
        break;

    case 'manage_courses':
        $reportTitle = 'Manage Courses Report';
        $tableOutput = generateManageCoursesReport();
        break;

    case 'view_student_accounts':
        $reportTitle = 'View Student Accounts Report';
        $tableOutput = generateViewStudentAccountsReport();
        break;

    default:
        $reportTitle = 'Report Management';
        $tableOutput = '';
}

?>
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: #333;
            text-align: center;
            margin-top: 0;
        }

        ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            overflow: hidden;
            background-color: #333;
            border-radius: 5px;
            transition: max-height 0.3s ease-out;
        }

        li {
            float: left;
        }

        li a {
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            transition: transform 0.3s ease-out;
        }

        .slide-left {
            transform: translateX(0);
        }

        .slide-right {
            transform: translateX(0);
        }

        .slide-left:hover {
            transform: translateX(-10px);
        }

        .slide-right:hover {
            transform: translateX(10px);
        }

        .report-container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            margin-top: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        .print-button {
            display: block;
            margin-top: 20px;
            text-align: center;
        }

        .print-button button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            border-radius: 5px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            border: 2px solid #333;
        }

        .print-button button:hover {
            background-color: #45a049;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4);
        }

        
        .dashboard-content {
            display: none;
        }

        @media print {
            .print-button {
                display: none;
            }

            .dashboard-content {
                display: block;
            }

        }
    </style>
</head>
<body>
    <h1><?php echo $reportTitle; ?></h1>
    <ul>
        <li><a href="reports.php?report=hostel_students" class="slide-left">Hostel Students</a></li>
        <li><a href="reports.php?report=manage_rooms" class="slide-right">Manage Rooms</a></li>
        <li><a href="reports.php?report=manage_courses" class="slide-left">Manage Courses</a></li>
        <li><a href="reports.php?report=view_student_accounts" class="slide-right">View Student Accounts</a></li>
    </ul>
    <div class="report-container">
        <?php echo $tableOutput; ?>
    </div>

    <div class="print-button">
        <button onclick="printReports()">Print</button>
    </div>

    <script>
        function printReports() {
            window.print();
        }
    </script>
    
</body>
</html>