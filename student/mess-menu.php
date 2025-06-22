<?php
session_start();
include '../includes/dbconn.php';
include '../includes/check-login.php';
check_login();
?>

<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mess Menu</title>
    <link href="../assets/libs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../dist/css/style.min.css" rel="stylesheet">
    <style>
        .menu-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            margin: 2rem auto;
            overflow: hidden;
            max-width: 100%;
        }
        .menu-header {
            background: linear-gradient(135deg, #1e88e5, #1565c0);
            color: white;
            padding: 1.5rem;
            text-align: center;
            font-size: 1.8rem;
            letter-spacing: 1px;
        }
        .menu-table {
            margin-bottom: 0;
        }
        .menu-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
            text-align: center;
        }
        .menu-table td {
            vertical-align: middle;
            padding: 1rem;
            text-align: center;
        }
        .day-column {
            font-weight: 500;
            text-transform: capitalize;
            background-color: #f1f1f1;
            color: #2c3e50;
        }
        .menu-items {
            white-space: pre-line;
            line-height: 1.6;
            color: #34495e;
        }
        .back-btn {
            margin: 1rem;
            padding: 0.5rem 1rem;
            background: #1e88e5;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
        }
        .back-btn:hover {
            background: #1565c0;
            color: white;
            text-decoration: none;
        }
        #main-wrapper[data-layout=vertical][data-sidebartype=full] .page-wrapper {
            margin-left: 0 !important;
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
    <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">
        <?php include '../includes/student-navigation.php';?>
        
        <div class="page-wrapper">
            <div class="container-fluid">
                <a href="dashboard.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
                
                <div class="menu-container">
                    <div class="menu-header">Hostel Mess Menu</div>
                    <div class="table-responsive">
                        <table class="table table-bordered menu-table">
                            <thead>
                                <tr>
                                    <th>Day</th>
                                    <th>Breakfast</th>
                                    <th>Lunch</th>
                                    <th>Dinner</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                                foreach ($days as $day) {
                                    echo "<tr>";
                                    echo "<td class='day-column'>" . ucfirst($day) . "</td>";
                                    
                                    // Get menu for each meal type
                                    $stmt = $mysqli->prepare("SELECT meal_type, menu_items FROM mess_menu WHERE day_of_week = ? ORDER BY FIELD(meal_type, 'breakfast', 'lunch', 'dinner')");
                                    $stmt->bind_param('s', $day);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    
                                    $meals = array_fill_keys(['breakfast', 'lunch', 'dinner'], '');
                                    while ($row = $result->fetch_assoc()) {
                                        $meals[$row['meal_type']] = $row['menu_items'];
                                    }
                                    
                                    foreach ($meals as $menu_items) {
                                        echo "<td class='menu-items'>" . htmlspecialchars($menu_items) . "</td>";
                                    }
                                    
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="../assets/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../dist/js/app-style-switcher.js"></script>
    <script src="../dist/js/feather.min.js"></script>
    <script src="../assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="../dist/js/sidebarmenu.js"></script>
    <script src="../dist/js/custom.min.js"></script>
</body>
</html>