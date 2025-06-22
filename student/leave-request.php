<?php
session_start();
include('../includes/dbconn.php');
include('../includes/check-login.php');
check_login();

if (isset($_POST['submit'])) {
    $student_id = $_SESSION['id'];
    $leave_type = $_POST['leave_type'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $reason = $_POST['reason'];

    $query = "INSERT INTO leave_requests (student_id, leave_type, start_date, end_date, reason, status) VALUES (?, ?, ?, ?, ?, 'pending')";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('issss', $student_id, $leave_type, $start_date, $end_date, $reason);
    $stmt->execute();

    if ($stmt) {
        echo "<script>alert('Leave request submitted successfully!');</script>";
    } else {
        echo "<script>alert('Something went wrong. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
    <title>Hostel Management System</title>
    <link href="../assets/extra-libs/c3/c3.min.css" rel="stylesheet">
    <link href="../assets/libs/chartist/dist/chartist.min.css" rel="stylesheet">
    <link href="../assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
    <link href="../dist/css/style.min.css" rel="stylesheet">
    <link href="../dist/css/modal-fix.css" rel="stylesheet">
    <link href="../dist/css/active-menu.css" rel="stylesheet">
</head>

<body>
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">
        <?php include '../includes/student-navigation.php';?>
        <div class="page-wrapper" style="margin: 0; min-height: 100vh;">
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-7 align-self-center">
                        <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Leave Request</h4>
                    </div>
                    <div class="col-5 align-self-center text-right">
                        <button onclick="history.back()" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Back</button>
                    </div>
                </div>
            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form method="POST">
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Leave Type</label>
                                                    <select name="leave_type" class="form-control" required>
                                                        <option value="">Select Type</option>
                                                        <option value="medical">Medical Leave</option>
                                                        <option value="personal">Personal Leave</option>
                                                        <option value="vacation">Vacation</option>
                                                        <option value="other">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Start Date</label>
                                                    <input type="date" name="start_date" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>End Date</label>
                                                    <input type="date" name="end_date" class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Reason</label>
                                                    <textarea name="reason" class="form-control" rows="4" required></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <div class="text-right">
                                            <button type="submit" name="submit" class="btn btn-info">Submit Request</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">My Leave Requests</h4>
                                <div class="table-responsive">
                                    <table id="zero_config" class="table table-striped table-bordered no-wrap">
                                        <thead>
                                            <tr>
                                                <th>Leave Type</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Reason</th>
                                                <th>Status</th>
                                                <th>Admin Remarks</th>
                                                <th>Submitted On</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $student_id = $_SESSION['id'];
                                        $ret = "SELECT * FROM leave_requests WHERE student_id=? ORDER BY created_at DESC";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->bind_param('i', $student_id);
                                        $stmt->execute();
                                        $res = $stmt->get_result();
                                        while ($row = $res->fetch_object()) {
                                        ?>
                                            <tr>
                                                <td><?php echo ucfirst($row->leave_type);?></td>
                                                <td><?php echo date('Y-m-d', strtotime($row->start_date));?></td>
                                                <td><?php echo date('Y-m-d', strtotime($row->end_date));?></td>
                                                <td><?php echo $row->reason;?></td>
                                                <td>
                                                    <span class="badge badge-<?php 
                                                    if($row->status == 'pending') echo 'warning';
                                                    else if($row->status == 'approved') echo 'success';
                                                    else echo 'danger';
                                                    ?>">
                                                        <?php echo ucfirst($row->status);?>
                                                    </span>
                                                </td>
                                                <td><?php echo $row->admin_remarks ? $row->admin_remarks : 'No remarks yet';?></td>
                                                <td><?php echo date('Y-m-d', strtotime($row->created_at));?></td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include '../includes/footer.php';?>
        </div>
    </div>
    <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../dist/js/app-style-switcher.js"></script>
    <script src="../dist/js/feather.min.js"></script>
    <script src="../assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="../dist/js/sidebarmenu.js"></script>
    <script src="../dist/js/custom.min.js"></script>
    <script src="../assets/extra-libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="../dist/js/pages/datatable/datatable-basic.init.js"></script>
</body>
</html>