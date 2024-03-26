<?php
    function fetchDataFromDatabase($query)
{
    $DB_host = "localhost";
    $DB_user = "root";
    $DB_pass = "";
    $DB_name = "hostelmsphp";

    try {
        $DB_con = new PDO("mysql:host={$DB_host};dbname={$DB_name}", $DB_user, $DB_pass);
        $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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

function generateHostelStudentsReport()
{
    $query = "SELECT regNo, regDate, firstName, gender, contactNo, email FROM userregistration";
    $HostelStudentsData = fetchDataFromDatabase($query);

    $tableOutput = '<table border="1">';
    $tableOutput .= '<tr><th>Reg No</th><th>Reg Date</th><th>Students Name</th><th>Gender</th><th>Contact No</th><th>Email</th></tr>';

    foreach ($HostelStudentsData as $student) {
        $tableOutput .= '<tr>';
        $tableOutput .= '<td>' . $student['regNo'] . '</td>';
        $tableOutput .= '<td>' . $student['regDate'] . '</td>';
        $tableOutput .= '<td>' . $student['firstName'] . '</td>';
        $tableOutput .= '<td>' . $student['gender'] . '</td>';
        $tableOutput .= '<td>' . $student['contactNo'] . '</td>';
        $tableOutput .= '<td>' . $student['email'] . '</td>';
        $tableOutput .= '</tr>';
    }

    $tableOutput .= '</table>';

    return $tableOutput;

}
function generateManageRoomsReport()
{
    $query = "SELECT room_no, fees, seater FROM rooms";
    $manageRoomsData = fetchDataFromDatabase($query);

    $tableOutput = '<table border="1">';
    $tableOutput .= '<tr><th>Room No</th><th>Fees</th><th>Seater</th></tr>';

    foreach ($manageRoomsData as $room) {
        $tableOutput .= '<tr>';
        $tableOutput .= '<td>' . $room['room_no'] . '</td>';
        $tableOutput .= '<td>' . $room['fees'] . '</td>';
        $tableOutput .= '<td>' . $room['seater'] . '</td>';
        $tableOutput .= '</tr>';
    }

    $tableOutput .= '</table>';

    return $tableOutput;
}
    
function generateManageCoursesReport()
{
    $query = "SELECT course_fn, course_sn, course_code FROM courses";
    $ManageCoursesData = fetchDataFromDatabase($query);

    $tableOutput = '<table border="1">';
    $tableOutput .= '<tr><th>Course Full Name</th><th>Course Shortform</th><th>Course Code</th></tr>';

    foreach ($ManageCoursesData as $course) {
        $tableOutput .= '<tr>';
        $tableOutput .= '<td>' . $course['course_fn'] . '</td>';
        $tableOutput .= '<td>' . $course['course_sn'] . '</td>';
        $tableOutput .= '<td>' . $course['course_code'] . '</td>';
        $tableOutput .= '</tr>';
    }

    $tableOutput .= '</table>';

    return $tableOutput;
}

function generateViewStudentAccountsReport()
{
    $query = "SELECT regno, firstName, gender, roomno, contactno, emailid, guardianName, guardianRelation FROM registration";
    $ViewStudentsAccountsData = fetchDataFromDatabase($query);

    $tableOutput = '<table border="1">';
    $tableOutput .= '<tr><th>Reg No</th><th>Students Name</th><th>Gender</th><th>Room No</th><th>Contact No</th><th>Email<th>Guardians Name</th><th>Guardians Relation</th></tr>';

    foreach ($ViewStudentsAccountsData as $account) {
        $tableOutput .= '<tr>';
        $tableOutput .= '<td>' . $account['regno'] . '</td>';
        $tableOutput .= '<td>' . $account['firstName'] . '</td>';
        $tableOutput .= '<td>' . $account['gender'] . '</td>';
        $tableOutput .= '<td>' . $account['roomno'] . '</td>';
        $tableOutput .= '<td>' . $account['contactno'] . '</td>';
        $tableOutput .= '<td>' . $account['emailid'] . '</td>';
        $tableOutput .= '<td>' . $account['guardianName'] . '</td>';
        $tableOutput .= '<td>' . $account['guardianRelation'] . '</td>';
        $tableOutput .= '</tr>';
    }

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
            animation: glowing 2s infinite;
        }
        @keyframes glowing {
  0% {
    box-shadow: 0 0 10px rgba(0, 123, 255, 0.6);
  }
  50% {
    box-shadow: 0 0 20px rgba(0, 123, 255, 0.6);
  }
  100% {
    box-shadow: 0 0 10px rgba(0, 123, 255, 0.6);
  }
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

        .return-button {
            display: block;
            margin-top: 20px;
            text-align: center;
        }

        .return-button button {
            background-color: red; /* Set button background color to red */
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

        .return-button button:hover {
            background-color: #ff0000; /* Set button hover background color to a darker shade of red */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4);
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
        <button onclick="returnToDashboard()">Return</button> <!-- New return button -->
    </div>

    <script>
        function printReports() {
            window.print();
        }
        function returnToDashboard() {
            window.location.href = "dashboard.php"; // Replace "dashboard.php" with the actual URL of your dashboard page
        }
    </script>
    
</body>
</html>