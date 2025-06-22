<?php
session_start();
include('../includes/dbconn.php');
include('../includes/check-login.php');
check_login();

// Handle leave request update
if (isset($_POST['update'])) {
    $leave_id = $_POST['leave_id'];
    $status = $_POST['status'];
    $remarks = $_POST['remarks'];

    $query = "UPDATE leave_requests SET status=?, admin_remarks=? WHERE id=?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ssi', $status, $remarks, $leave_id);
    $result = $stmt->execute();

    if ($result) {
        $response = array('status' => 'success', 'message' => 'Leave request updated successfully!');
    } else {
        $response = array('status' => 'error', 'message' => 'Something went wrong. Please try again.');
    }

    // Always return JSON response for AJAX requests
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>

<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Leave Requests</title>
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
        .status-badge {
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: 500;
        }
        .status-pending {
            background-color: #ffc107;
            color: #000;
        }
        .status-approved {
            background-color: #28a745;
            color: #fff;
        }
        .status-rejected {
            background-color: #dc3545;
            color: #fff;
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
        <?php include 'includes/navigation.php';?>
        <a href="dashboard.php" class="btn btn-primary back-button"><i class="fas fa-arrow-left mr-2"></i>Back to Dashboard</a>
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Manage Leave Requests</h4>
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="zero_config" class="table table-striped table-bordered no-wrap">
                                        <thead>
                                            <tr>
                                                <th>Student Name</th>
                                                <th>Leave Type</th>
                                                <th>From</th>
                                                <th>To</th>
                                                <th>Reason</th>
                                                <th>Status</th>
                                                <th>Applied On</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $ret = "SELECT l.*, u.firstName, u.middleName, u.lastName 
                                                FROM leave_requests l 
                                                JOIN userregistration u ON l.student_id = u.id 
                                                ORDER BY l.created_at DESC";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->execute();
                                        $res = $stmt->get_result();
                                        while ($row = $res->fetch_object()) {
                                        ?>
                                            <tr>
                                                <td><?php echo $row->firstName . ' ' . $row->middleName . ' ' . $row->lastName;?></td>
                                                <td><?php echo ucfirst($row->leave_type);?></td>
                                                <td><?php echo date('Y-m-d', strtotime($row->start_date));?></td>
                                                <td><?php echo date('Y-m-d', strtotime($row->end_date));?></td>
                                                <td><?php echo $row->reason;?></td>
                                                <td>
                                                    <span class="status-badge status-<?php echo $row->status;?>">
                                                        <?php echo ucfirst($row->status);?>
                                                    </span>
                                                </td>
                                                <td><?php echo date('Y-m-d H:i', strtotime($row->created_at));?></td>
                                                <td>
                                                    <button type="button" class="btn btn-primary btn-sm update-leave" 
                                                            data-id="<?php echo $row->id;?>" 
                                                            data-status="<?php echo $row->status;?>" 
                                                            data-remarks="<?php echo htmlspecialchars($row->admin_remarks);?>">
                                                        <i class="fas fa-edit"></i> Update
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

    <!-- Update Modal -->
    <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Update Leave Request</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="updateForm">
                        <input type="hidden" id="leaveId" name="leave_id">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="remarks">Admin Remarks</label>
                            <textarea class="form-control" id="remarks" name="remarks" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="submitUpdate">Update</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/extra-libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="../dist/js/app-style-switcher.js"></script>
    <script src="../dist/js/feather.min.js"></script>
    <script src="../assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="../dist/js/sidebarmenu.js"></script>
    <script src="../dist/js/custom.min.js"></script>
    <script src="../dist/js/pages/datatable/datatable-basic.init.js"></script>
    <script>
        $(document).ready(function() {
            $('#zero_config').DataTable();

            // Handle update button click
            $(document).on('click', '.update-leave', function() {
                const id = $(this).data('id');
                const status = $(this).data('status');
                const remarks = $(this).data('remarks');

                $('#leaveId').val(id);
                $('#status').val(status);
                $('#remarks').val(remarks);

                $('#updateModal').modal({
                    backdrop: 'static',
                    keyboard: false
                }).modal('show');
            });

            // Handle form submission
            $('#submitUpdate').click(function() {
                const formData = {
                    leave_id: $('#leaveId').val(),
                    status: $('#status').val(),
                    remarks: $('#remarks').val(),
                    update: true
                };

                $.ajax({
                    url: 'manage-leave-requests.php',
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            alert(response.message);
                            $('#updateModal').modal('hide');
                            window.location.reload();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                    }
                });
            });
        });
    </script>
</body>
</html> 