<?php
session_start();
require_once 'includes/auth_check.php';
require_role('hr');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: Login Form.php");
    exit();
}

$conn = mysqli_connect('localhost', 'root', '', 'hrm');
if(!$conn){
    die("Database connection error: " . mysqli_connect_error());
}

// Handle status updates
if(isset($_POST['action']) && isset($_POST['permission_id'])) {
    $id = mysqli_real_escape_string($conn, $_POST['permission_id']);
    $action = mysqli_real_escape_string($conn, $_POST['action']);
    
    $status = ($action === 'approve') ? 'Approved' : 'Rejected';
    $updateSql = "UPDATE permissions SET status = '$status' WHERE id = '$id'";
    mysqli_query($conn, $updateSql);
    
    // Redirect to remove POST data
    header("Location: ViewPermission.php");
    exit();
}

$sql = "SELECT p.*, e.username as Name, e.department as Department 
        FROM permissions p 
        JOIN employees e ON p.employee_id = e.id 
        ORDER BY p.id DESC";
$results = mysqli_query($conn, $sql);

if (!$results) {
    die("Error fetching permissions: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permission Requests - MOM System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/custom.css">
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <div class="dashboard-sidebar w-64 fixed h-full bg-primary text-white p-6">
            <div class="flex items-center justify-center mb-8">
                <img src="logo.jpg" alt="Logo" class="w-16 h-16 rounded-full object-cover">
            </div>
            
            <nav class="space-y-4">
                <a href="Employee.php" class="flex items-center space-x-2 p-2 rounded hover:bg-blue-700">
                    <i class="bi bi-people-fill"></i>
                    <span>Employees</span>
                </a>
                <a href="ViewPermission.php" class="flex items-center space-x-2 p-2 rounded bg-blue-800 hover:bg-blue-700">
                    <i class="bi bi-file-text"></i>
                    <span>Permission Requests</span>
                </a>
                <a href="Permission.php" class="flex items-center space-x-2 p-2 rounded hover:bg-blue-700">
                    <i class="bi bi-shield-check"></i>
                    <span>Permission</span>
                </a>
                <a href="apply.php" class="flex items-center space-x-2 p-2 rounded hover:bg-blue-700">
                    <i class="bi bi-person-plus"></i>
                    <span>Job Application</span>
                </a>
            </nav>

            <div class="absolute bottom-6 w-48">
                <button onclick="location.href='Home.php'" 
                        class="w-full bg-red-600 text-white p-2 rounded-lg hover:bg-red-700 flex items-center justify-center space-x-2">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="dashboard-content flex-1 ml-64 p-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Permission Requests</h1>
                    <p class="text-gray-600">Manage employee permission requests</p>
                </div>
                <button onclick="location.href='DeletePermissions.php'" 
                        class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 flex items-center space-x-2">
                    <i class="bi bi-trash"></i>
                    <span>Delete All</span>
                </button>
            </div>

            <!-- Permission Requests Table -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Recent Requests</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php
                            if (mysqli_num_rows($results) > 0) {
                                while($rows = mysqli_fetch_assoc($results)){
                                    $statusClass = 'bg-yellow-100';
                                    if($rows['status'] === 'Approved') {
                                        $statusClass = 'bg-green-100';
                                    } else if($rows['status'] === 'Rejected') {
                                        $statusClass = 'bg-red-100';
                                    }
                                    
                                    echo "<tr>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap'>" . htmlspecialchars($rows['Name']) . "</td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap'>" . date('M d, Y', strtotime($rows['request_date'])) . "</td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap'>" . htmlspecialchars($rows['end_time']) . "</td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap'>" . htmlspecialchars($rows['Department']) . "</td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap'>" . htmlspecialchars($rows['reason']) . "</td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap'><span class='px-2 inline-flex text-xs leading-5 font-semibold rounded-full " . $statusClass . " text-" . ($rows['status'] === 'Approved' ? 'green' : ($rows['status'] === 'Rejected' ? 'red' : 'yellow')) . "-600'>" . htmlspecialchars($rows['status']) . "</span></td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium'>";
                                    if($rows['status'] !== 'Approved' && $rows['status'] !== 'Rejected') {
                                        echo "<form method='POST' style='display:inline;'>";
                                        echo "<input type='hidden' name='permission_id' value='" . $rows['id'] . "'>";
                                        echo "<button type='submit' name='action' value='approve' class='text-green-600 hover:text-green-900'>Approve</button>";
                                        echo "<button type='submit' name='action' value='reject' class='ml-3 text-red-600 hover:text-red-900'>Reject</button>";
                                        echo "</form>";
                                    }
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7' class='text-center'>No permission requests found</td></tr>";
                            }
                            mysqli_close($conn);
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            Showing 1 to 10 of 45 entries
                        </div>
                        <div class="flex space-x-2">
                            <button class="px-3 py-1 border rounded text-gray-600 hover:bg-gray-50">Previous</button>
                            <button class="px-3 py-1 border rounded bg-primary text-white hover:bg-blue-700">1</button>
                            <button class="px-3 py-1 border rounded text-gray-600 hover:bg-gray-50">2</button>
                            <button class="px-3 py-1 border rounded text-gray-600 hover:bg-gray-50">3</button>
                            <button class="px-3 py-1 border rounded text-gray-600 hover:bg-gray-50">Next</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add responsive sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.dashboard-sidebar');
            const content = document.querySelector('.dashboard-content');
            
            if (window.innerWidth < 768) {
                sidebar.classList.add('-translate-x-full');
                content.classList.remove('ml-64');
            }

            window.addEventListener('resize', function() {
                if (window.innerWidth < 768) {
                    sidebar.classList.add('-translate-x-full');
                    content.classList.remove('ml-64');
                } else {
                    sidebar.classList.remove('-translate-x-full');
                    content.classList.add('ml-64');
                }
            });
        });
    </script>
</body>
</html>