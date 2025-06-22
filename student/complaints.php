<?php
session_start();
include('../includes/dbconn.php');
include('../includes/check-login.php');
check_login();

if (isset($_POST['submit'])) {
    $student_id = $_SESSION['id'];
    $subject = $_POST['subject'];
    $complaint_type = $_POST['complaint_type'];
    $description = $_POST['description'];

    $query = "INSERT INTO complaints (student_id, subject, complaint_type, description) VALUES (?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('isss', $student_id, $subject, $complaint_type, $description);
    $stmt->execute();

    if ($stmt) {
        echo "<script>alert('Complaint submitted successfully!');</script>";
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
                        <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Complaints</h4>
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
                                                    <label>Subject</label>
                                                    <input type="text" name="subject" class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Complaint Type</label>
                                                    <select name="complaint_type" class="form-control" required>
                                                        <option value="">Select Type</option>
                                                        <option value="maintenance">Maintenance</option>
                                                        <option value="roommate">Roommate</option>
                                                        <option value="mess">Mess</option>
                                                        <option value="other">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Description</label>
                                                    <textarea name="description" class="form-control" rows="4" required></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <div class="text-right">
                                            <button type="submit" name="submit" class="btn btn-info">Submit Complaint</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">My Complaints</h4>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Subject</th>
                                                <th>Type</th>
                                                <th>Description</th>
                                                <th>Status</th>
                                                <th>Admin Remarks</th>
                                                <th>Submitted On</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $student_id = $_SESSION['id'];
                                        $ret = "SELECT * FROM complaints WHERE student_id=?";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->bind_param('i', $student_id);
                                        $stmt->execute();
                                        $res = $stmt->get_result();
                                        while ($row = $res->fetch_object()) {
                                        ?>
                                            <tr>
                                                <td><?php echo $row->subject;?></td>
                                                <td><?php echo ucfirst($row->complaint_type);?></td>
                                                <td><?php echo $row->description;?></td>
                                                <td>
                                                    <span class="badge badge-<?php 
                                                    if($row->status == 'pending') echo 'warning';
                                                    else if($row->status == 'in_progress') echo 'info';
                                                    else echo 'success';
                                                    ?>">
                                                        <?php echo ucfirst(str_replace('_', ' ', $row->status));?>
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