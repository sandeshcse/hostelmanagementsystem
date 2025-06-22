<?php
session_start();
include('../includes/dbconn.php');
include('../includes/check-login.php');
check_login();

if (isset($_POST['update'])) {
    $complaint_id = $_POST['complaint_id'];
    $status = $_POST['status'];
    $remarks = $_POST['remarks'];

    $query = "UPDATE complaints SET status=?, admin_remarks=? WHERE id=?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('ssi', $status, $remarks, $complaint_id);
    $result = $stmt->execute();

    if ($result) {
        $response = array('status' => 'success', 'message' => 'Complaint updated successfully!');
    } else {
        $response = array('status' => 'error', 'message' => 'Something went wrong. Please try again.');
    }

    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}
?>

<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Complaints</title>
    <link href="../assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
    <link href="../assets/libs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../dist/css/style.min.css" rel="stylesheet">
    <style>
        .page-wrapper {
            margin-left: 0 !important;
        }
        .back-button {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 100;
        }
    </style>
</head>

<body>
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-header-position="fixed" data-boxed-layout="full">
        <?php include './includes/navigation.php';?>
        <a href="dashboard.php" class="btn btn-primary back-button"><i class="fas fa-arrow-left mr-2"></i>Back to Dashboard</a>
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Manage Complaints</h4>
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="zero_config" class="table table-striped table-bordered no-wrap">
                                        <thead>
                                            <tr>
                                                <th>Student Name</th>
                                                <th>Subject</th>
                                                <th>Type</th>
                                                <th>Description</th>
                                                <th>Status</th>
                                                <th>Submitted On</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $ret = "SELECT c.*, u.firstName, u.middleName, u.lastName 
                                                FROM complaints c 
                                                JOIN userregistration u ON c.student_id = u.id 
                                                ORDER BY c.created_at DESC";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->execute();
                                        $res = $stmt->get_result();
                                        while ($row = $res->fetch_object()) {
                                        ?>
                                            <tr>
                                                <td><?php echo $row->firstName . ' ' . $row->middleName . ' ' . $row->lastName;?></td>
                                                <td><?php echo $row->subject;?></td>
                                                <td><?php echo ucfirst($row->complaint_type);?></td>
                                                <td><?php echo $row->description;?></td>
                                                <td>
                                                    <span class="badge badge-<?php 
                                                    if($row->status == 'pending') echo 'warning';
                                                    else if($row->status == 'in_progress') echo 'info';
                                                    else if($row->status == 'resolved') echo 'success';
                                                    else echo 'danger';
                                                    ?>">
                                                        <?php echo ucfirst(str_replace('_', ' ', $row->status));?>
                                                    </span>
                                                </td>
                                                <td><?php echo date('Y-m-d H:i', strtotime($row->created_at));?></td>
                                                <td>
                                                    <button type="button" class="btn btn-primary btn-sm update-complaint" data-id="<?php echo $row->id;?>" data-status="<?php echo $row->status;?>" data-remarks="<?php echo $row->admin_remarks;?>">
                                                        Update
                                                    </button>
                                                </td>
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
            <?php include '../includes/footer.php' ?>
        </div>
    </div>

    <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/extra-libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="../dist/js/custom.min.js"></script>
    <script src="../dist/js/update-requests.js"></script>
    <script>
        $(document).ready(function() {
            $('#zero_config').DataTable();
        });
    </script>
</body>
</html>
<script src="../dist/js/app-style-switcher.js"></script>
    <script src="../dist/js/feather.min.js"></script>
    <script src="../assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="../dist/js/sidebarmenu.js"></script>
    <script src="../dist/js/custom.min.js"></script>
    <script src="../assets/extra-libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="../dist/js/pages/datatable/datatable-basic.init.js"></script>
</body>
</html>