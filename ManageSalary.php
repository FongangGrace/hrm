<?php
require_once 'check_session.php';
require_role('hr');

$conn = mysqli_connect("localhost", "root", "", "hrm");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Update the initial query to fetch all salary details
$employees_query = "SELECT e.*, 
                   s.basic_salary, 
                   s.id as salary_id,
                   s.performance_bonus,
                   s.overtime_pay,
                   s.tax,
                   s.insurance,
                   s.total_bonus,
                   s.total_deductions,
                   s.net_salary
                   FROM employees e 
                   LEFT JOIN salary_details s ON e.id = s.employee_id";
$employees_result = mysqli_query($conn, $employees_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Salary - MOM System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .modal {
                display: none !important;
            }
            #salaryTable, #salaryTable * {
                visibility: visible;
            }
            #salaryTable {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .no-print {
                display: none;
            }
            .card {
                border: none !important;
            }
            .card-body {
                padding: 0 !important;
            }
            @page {
                size: landscape;
                margin: 2cm;
            }
            thead {
                display: table-header-group;
            }
            tfoot {
                display: table-footer-group;
            }
            .print-header {
                display: block !important;
                text-align: center;
                margin-bottom: 20px;
            }
            .print-footer {
                display: block !important;
                text-align: center;
                margin-top: 20px;
                font-size: 12px;
            }
        }
        .print-header, .print-footer {
            display: none;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid px-4 py-5">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">Manage Employee Salary</h2>
                
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($_GET['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <div class="text-end mb-3">
                            <button onclick="window.print()" class="btn btn-success">
                                <i class="bi bi-printer"></i> Print Salary Report
                            </button>
                        </div>
                        <div class="table-responsive">
                            <div class="print-header">
                                <h2>Employee Salary Report</h2>
                                <p>Generated on: <?php echo date('F j, Y'); ?></p>
                            </div>
                            <div class="print-footer">
                                <p>This is a computer-generated document. No signature is required.</p>
                                <p>Generated from MOM System - <?php echo date('Y'); ?> &copy; All rights reserved.</p>
                            </div>
                            <table class="table table-hover" id="salaryTable">
                                
                                <thead>
                                    <tr>
                                        <th>Employee Name</th>
                                        <th>Department</th>
                                        <th>Basic Salary</th>
                                        <th>Total Bonus</th>
                                        <th>Deductions</th>
                                        <th>Net Salary</th>
                                        <th class="no-print">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($employee = mysqli_fetch_assoc($employees_result)): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($employee['username']); ?></td>
                                        <td><?php echo htmlspecialchars($employee['department']); ?></td>
                                        <td>FCFA <?php echo number_format(isset($employee['basic_salary']) ? $employee['basic_salary'] : 0, 0, '.', ','); ?></td>
                                        <td>FCFA <?php echo number_format(isset($employee['total_bonus']) ? $employee['total_bonus'] : 0, 0, '.', ','); ?></td>
                                        <td>FCFA <?php echo number_format(isset($employee['total_deductions']) ? $employee['total_deductions'] : 0, 0, '.', ','); ?></td>
                                        <td>FCFA <?php echo number_format(isset($employee['net_salary']) ? $employee['net_salary'] : 0, 0, '.', ','); ?></td>
                                        <td class="no-print">
                                            <button type="button" class="btn btn-primary btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#salaryModal<?php echo $employee['id']; ?>">
                                                Manage Salary
                                            </button>
                                        </td>
                                    </tr>
                                    </tr>

                                    <!-- Update the modal form to show existing values -->
                                    <div class="modal fade" id="salaryModal<?php echo $employee['id']; ?>" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Manage Salary - <?php echo htmlspecialchars($employee['username']); ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="process_salary.php" method="POST">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="employee_id" value="<?php echo $employee['id']; ?>">
                                                        <input type="hidden" name="salary_id" value="<?php echo isset($employee['salary_id']) ? $employee['salary_id'] : ''; ?>">
                                                        
                                                        <div class="mb-3">
                                                            <label class="form-label">Basic Salary</label>
                                                            <input type="number" step="0.01" class="form-control salary-component" name="basic_salary" 
                                                                   value="<?php echo isset($employee['basic_salary']) ? $employee['basic_salary'] : ''; ?>" required>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <h6>Bonuses</h6>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Performance Bonus</label>
                                                                    <input type="number" step="0.01" class="form-control salary-component" name="performance_bonus"
                                                                           value="<?php echo isset($employee['performance_bonus']) ? $employee['performance_bonus'] : ''; ?>">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Overtime Pay</label>
                                                                    <input type="number" step="0.01" class="form-control salary-component" name="overtime_pay"
                                                                           value="<?php echo isset($employee['overtime_pay']) ? $employee['overtime_pay'] : ''; ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <h6>Deductions</h6>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Tax</label>
                                                                    <input type="number" step="0.01" class="form-control salary-component" name="tax"
                                                                           value="<?php echo isset($employee['tax']) ? $employee['tax'] : ''; ?>">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Insurance</label>
                                                                    <input type="number" step="0.01" class="form-control salary-component" name="insurance"
                                                                           value="<?php echo isset($employee['insurance']) ? $employee['insurance'] : ''; ?>">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row mt-3">
                                                            <div class="col-md-4">
                                                                <h6>Total Bonus: FCFA <span class="total-bonus">0</span></h6>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <h6>Total Deductions: FCFA <span class="total-deductions">0</span></h6>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <h6>Net Salary: FCFA <span class="net-salary">0</span></h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const modals = document.querySelectorAll('.modal');
        
        modals.forEach(modal => {
            const inputs = modal.querySelectorAll('.salary-component');
            const totalBonusSpan = modal.querySelector('.total-bonus');
            const totalDeductionsSpan = modal.querySelector('.total-deductions');
            const netSalarySpan = modal.querySelector('.net-salary');
    
            function calculateTotals() {
                let basicSalary = parseFloat(modal.querySelector('[name="basic_salary"]').value) || 0;
                let performanceBonus = parseFloat(modal.querySelector('[name="performance_bonus"]').value) || 0;
                let overtimePay = parseFloat(modal.querySelector('[name="overtime_pay"]').value) || 0;
                let tax = parseFloat(modal.querySelector('[name="tax"]').value) || 0;
                let insurance = parseFloat(modal.querySelector('[name="insurance"]').value) || 0;
    
                let totalBonus = performanceBonus + overtimePay;
                let totalDeductions = tax + insurance;
                let netSalary = basicSalary + totalBonus - totalDeductions;
    
                totalBonusSpan.textContent = totalBonus.toLocaleString('en-US', {maximumFractionDigits: 0});
                totalDeductionsSpan.textContent = totalDeductions.toLocaleString('en-US', {maximumFractionDigits: 0});
                netSalarySpan.textContent = netSalary.toLocaleString('en-US', {maximumFractionDigits: 0});
            }
    
            inputs.forEach(input => {
                input.addEventListener('input', calculateTotals);
            });
    
            modal.addEventListener('shown.bs.modal', calculateTotals);
        });
    });
    </script>
</body>
</html>