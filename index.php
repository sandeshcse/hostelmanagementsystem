<?php
    session_start();
    include('includes/dbconn.php');
    if(isset($_POST['login']))
    {
        $email=$_POST['email'];
        $password=$_POST['password'];
        $password = md5($password);
        $stmt=$mysqli->prepare("SELECT email,password,id FROM userregistration WHERE email=? and password=? ");
        $stmt->bind_param('ss',$email,$password);
        $stmt->execute();
        $stmt -> bind_result($email,$password,$id);
        $rs=$stmt->fetch();
        $stmt->close();
        $_SESSION['id']=$id;
        $_SESSION['login']=$email;
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
            $log="insert into userLog(userId,userEmail,userIp,city,country) values('$uid','$uemail','$ip','$city','$country')";
            $mysqli->query($log);
            if($log){
                header("location:student/dashboard.php");
            }
        } else {
            $error = "Invalid email or password";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hostel Management System - Login</title>
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
        }
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 0 30px rgba(0,0,0,0.1);
            overflow: hidden;
            width: 100%;
            max-width: 1000px;
            display: flex;
        }
        .login-image {
            background: linear-gradient(135deg, #6B73FF 0%, #000DFF 100%);
            padding: 40px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            width: 40%;
        }
        .login-form {
            padding: 40px;
            width: 60%;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #e1e1e1;
            font-size: 14px;
        }
        .form-control:focus {
            border-color: #6B73FF;
            box-shadow: 0 0 0 0.2rem rgba(107,115,255,0.25);
        }
        .btn-login {
            background: #6B73FF;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 500;
            width: 100%;
            margin-top: 20px;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            background: #000DFF;
            transform: translateY(-2px);
        }
        .input-group-text {
            background: transparent;
            border-right: none;
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }
        .form-floating {
            margin-bottom: 20px;
        }
        .form-floating label {
            padding-left: 40px;
        }
        .logo {
            width: 80px;
            margin-bottom: 20px;
        }
        @media (max-width: 768px) {
            .login-card {
                flex-direction: column;
            }
            .login-image, .login-form {
                width: 100%;
            }
            .login-image {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-image">
                <img src="assets/images/favicon.png" alt="Logo" class="logo">
                <h2 class="mb-4">Welcome Back!</h2>
                <p class="mb-4">Access your hostel management dashboard with ease</p>
                <div class="mt-4">
                    <a href="admin/" class="btn btn-outline-light btn-lg">Admin Login</a>
                </div>
            </div>
            <div class="login-form">
                <h3 class="mb-4">Student Login</h3>
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="form-floating">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
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
                    <button type="submit" name="login" class="btn btn-primary btn-login">
                        <i class="fas fa-sign-in-alt me-2"></i>Login
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>