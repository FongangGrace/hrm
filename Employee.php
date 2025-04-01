<?php
session_start();
require_once 'includes/auth_check.php';

$conn = mysqli_connect("localhost", "root", "", "hrm");
if (!$conn) {
    die("Error: " . mysqli_connect_error());
}

// Get employees with error handling
$sql = "SELECT * FROM employees";
$result = mysqli_query($conn, $sql);

// Initialize an empty array to store employees
$employees = array();

// Only try to fetch if query was successful
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $employees[] = $row;
    }
} else {
    echo "<div class='alert alert-danger'>Error fetching employees: " . mysqli_error($conn) . "</div>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management - MOM System</title>
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
                <a href="Employee.php" class="flex items-center space-x-2 p-2 rounded bg-blue-800 hover:bg-blue-700">
                    <i class="bi bi-people-fill"></i>
                    <span>Employees</span>
                </a>
                <a href="ViewPermission.php" class="flex items-center space-x-2 p-2 rounded hover:bg-blue-700">
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
                <button onclick="location.href='logout.php'" 
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
                    <h1 class="text-3xl font-bold text-gray-800">Employee Management</h1>
                    <p class="text-gray-600">Manage your organization's workforce</p>
                </div>
                <a href="AddEmployee.php" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    <i class="bi bi-plus-lg"></i> Add Employee
                </a>
            </div>

            <!-- Employee Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <div class="flex items-center space-x-4">
                        <div class="bg-blue-100 p-3 rounded-full">
                            <i class="bi bi-people text-2xl text-primary"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-700">Total Employees</h3>
                            <p class="text-2xl font-bold text-primary"><?php echo count($employees); ?></p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <div class="flex items-center space-x-4">
                        <div class="bg-green-100 p-3 rounded-full">
                            <i class="bi bi-person-check text-2xl text-green-600"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-700">Active</h3>
                            <p class="text-2xl font-bold text-green-600"><?php echo count(array_filter($employees, function($employee) { return $employee['status'] === 'active'; })); ?></p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <div class="flex items-center space-x-4">
                        <div class="bg-yellow-100 p-3 rounded-full">
                            <i class="bi bi-person-dash text-2xl text-yellow-600"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-700">On Leave</h3>
                            <p class="text-2xl font-bold text-yellow-600"><?php echo count(array_filter($employees, function($employee) { return $employee['status'] === 'on leave'; })); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employee Table -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Employee List</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($employees as $row) { ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name=<?php echo htmlspecialchars($row['username']); ?>" alt="">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($row['username']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?php echo htmlspecialchars($row['email']); ?></div>
                                    <div class="text-sm text-gray-500"><?php echo htmlspecialchars($row['phone']); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?php echo htmlspecialchars($row['department']); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php 
                                        switch($row['status']) {
                                            case 'active':
                                                echo 'bg-green-100 text-green-800';
                                                break;
                                            case 'pending':
                                                echo 'bg-yellow-100 text-yellow-800';
                                                break;
                                            case 'inactive':
                                                echo 'bg-red-100 text-red-800';
                                                break;
                                            default:
                                                echo 'bg-gray-100 text-gray-800';
                                        }
                                    ?>">
                                        <?php echo ucfirst(htmlspecialchars($row['status'])); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="EditEmployee.php?ID=<?php echo $row['id']; ?>" class="text-primary hover:text-blue-900">Edit</a>
                                    <a href="DeleteEmployee.php?ID=<?php echo $row['id']; ?>" class="ml-3 text-red-600 hover:text-red-900">Delete</a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            Showing 1 to <?php echo count($employees); ?> of <?php echo count($employees); ?> entries
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