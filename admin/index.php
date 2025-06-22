<?php
    session_start();
    include('../includes/dbconn.php');
    if(isset($_POST['login']))
    {
        $username=$_POST['username'];
        $password=$_POST['password'];
        $password = md5($password);
        $stmt=$mysqli->prepare("SELECT username,email,password,id FROM admin WHERE (username=? OR email=?) and password=? ");
        $stmt->bind_param('sss',$username,$username,$password);
        $stmt->execute();
        $stmt -> bind_result($username,$email,$password,$id);
        $rs=$stmt->fetch();
        $stmt->close();
        $_SESSION['id']=$id;
        $_SESSION['login']=$username;
        $uip=$_SERVER['REMOTE_ADDR'];
        $ldate=date('d/m/Y h:i:s', time());
        if($rs){
            $uid=$_SESSION['id'];
            $uemail=$_SESSION['login'];
            $ip=$_SERVER['REMOTE_ADDR'];
            $geopluginURL='http://www.geoplugin.net/php.gp?ip='.$ip;
            $addrDetailsArr = unserialize(file_get_contents($geopluginURL));
            $city = $addrDetailsArr['geoplugin_city'];
            $country = $addrDetailsArr['geoplugin_countryName'];
            $log="insert into adminLog(adminId,adminEmail,adminIp,city,country) values('$uid','$uemail','$ip','$city','$country')";
            $mysqli->query($log);
            if($log){
                header("location:dashboard.php");
            }
        } else {
            $error = "Invalid username/email or password";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login - Hostel Management System</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .admin-login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 0 30px rgba(0,0,0,0.1);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
            padding: 40px;
        }
        .admin-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .admin-logo {
            width: 80px;
            margin-bottom: 20px;
        }
        .admin-title {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .admin-subtitle {
            color: #7f8c8d;
            font-size: 14px;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #e1e1e1;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.2rem rgba(52,152,219,0.25);
        }
        .input-group-text {
            background: transparent;
            border-right: none;
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }
        .btn-admin-login {
            background: #3498db;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 500;
            width: 100%;
            margin-top: 20px;
            transition: all 0.3s ease;
        }
        .btn-admin-login:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }
        .back-to-home {
            text-align: center;
            margin-top: 20px;
        }
        .back-to-home a {
            color: #3498db;
            text-decoration: none;
            font-size: 14px;
        }
        .back-to-home a:hover {
            color: #2980b9;
        }
        .alert {
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="admin-login-card">
        <div class="admin-header">
            <img src="../assets/images/favicon.png" alt="Logo" class="admin-logo">
            <h2 class="admin-title">Admin Login</h2>
            <p class="admin-subtitle">Access the admin dashboard</p>
        </div>

        <?php if(isset($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-floating">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-user-shield"></i>
                    </span>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Username or Email" required>
                </div>
            </div>
            <div class="form-floating">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
            </div>
            <button type="submit" name="login" class="btn btn-primary btn-admin-login">
                <i class="fas fa-sign-in-alt me-2"></i>Login
            </button>
        </form>

        <div class="back-to-home">
            <a href="../index.php">
                <i class="fas fa-arrow-left me-1"></i>Back to Student Login
            </a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>