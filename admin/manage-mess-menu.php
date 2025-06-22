<?php
session_start();
include '../includes/dbconn.php';
include '../includes/check-login.php';
check_login();

if (isset($_POST['submit'])) {
    $day = $_POST['day_of_week'];
    $meal = $_POST['meal_type'];
    $menu = $_POST['menu_items'];
    
    $stmt = $mysqli->prepare("SELECT id FROM mess_menu WHERE day_of_week = ? AND meal_type = ?");
    $stmt->bind_param('ss', $day, $meal);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stmt = $mysqli->prepare("UPDATE mess_menu SET menu_items = ? WHERE id = ?");
        $stmt->bind_param('si', $menu, $row['id']);
    } else {
        $stmt = $mysqli->prepare("INSERT INTO mess_menu (day_of_week, meal_type, menu_items) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $day, $meal, $menu);
    }
    
    if ($stmt->execute()) {
        echo "<script>alert('Menu updated successfully');</script>";
    } else {
        echo "<script>alert('Error updating menu');</script>";
    }
}
?>

<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Mess Menu</title>
    <link href="../assets/libs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../dist/css/style.min.css" rel="stylesheet">
    <style>
        #main-wrapper[data-layout=vertical][data-sidebartype=full] .page-wrapper {
            margin-left: 0 !important;
        }
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
        .menu-form {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            margin: 2rem auto;
        }
        .menu-form label {
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }
        .menu-form select, .menu-form textarea {
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            transition: all 0.3s ease;
        }
        .menu-form select:focus, .menu-form textarea:focus {
            border-color: #1e88e5;
            box-shadow: 0 0 0 0.2rem rgba(30,136,229,0.25);
        }
        .menu-form textarea {
            min-height: 120px;
            resize: vertical;
        }
        .btn-update {
            background: linear-gradient(135deg, #1e88e5, #1565c0);
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 8px;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            color: white;
        }
        .btn-update:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(30,136,229,0.3);
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
        <?php include 'includes/navigation.php';?>
        
        <div class="page-wrapper">
            <div class="container-fluid">
                <a href="dashboard.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
                
                <div class="menu-form">
                    <h4 class="mb-4">Update Menu Items</h4>
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Day of Week</label>
                                    <select class="form-control" name="day_of_week" required>
                                        <option value="monday">Monday</option>
                                        <option value="tuesday">Tuesday</option>
                                        <option value="wednesday">Wednesday</option>
                                        <option value="thursday">Thursday</option>
                                        <option value="friday">Friday</option>
                                        <option value="saturday">Saturday</option>
                                        <option value="sunday">Sunday</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Meal Type</label>
                                    <select class="form-control" name="meal_type" required>
                                        <option value="breakfast">Breakfast</option>
                                        <option value="lunch">Lunch</option>
                                        <option value="dinner">Dinner</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Menu Items</label>
                                    <textarea class="form-control" name="menu_items" placeholder="Enter menu items (one per line)..." required></textarea>
                                </div>
                            </div>
                        </div>
                        <button type="submit" name="submit" class="btn btn-update">Update Menu</button>
                    </form>
                </div>
                
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
                                    
                                    $meals = ['breakfast', 'lunch', 'dinner'];
                                    foreach ($meals as $meal) {
                                        $stmt = $mysqli->prepare("SELECT menu_items FROM mess_menu WHERE day_of_week = ? AND meal_type = ?");
                                        $stmt->bind_param('ss', $day, $meal);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        $menu_items = $result->fetch_assoc();
                                        
                                        echo "<td class='menu-items'>" . ($menu_items ? htmlspecialchars($menu_items['menu_items']) : '-') . "</td>";
                                    }
                                    
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
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
</body>
</html>