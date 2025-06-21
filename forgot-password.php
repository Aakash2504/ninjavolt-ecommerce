<?php
session_start();
include("connection.php");

$step = isset($_GET['step']) ? $_GET['step'] : '1';
$email_error = $otp_error = $password_error = '';
$success_message = '';
$display_otp = '';

// Step 1: Email verification
if (isset($_POST['verify_email'])) {
    $email = $_POST['email'];
    
    // Check if email exists in database
    $sql = "SELECT * FROM login WHERE usergmail='$email'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        // Generate OTP
        $otp = rand(100000, 999999);
        $_SESSION['reset_otp'] = $otp;
        $_SESSION['reset_email'] = $email;
        
        // For testing: Display OTP on screen instead of sending email
        $display_otp = $otp;
        
        // Redirect to OTP verification step
        header("Location: forgot-password.php?step=2&test_otp=$otp");
        exit();
    } else {
        $email_error = "No account found with this email address";
    }
}

// Step 2: OTP verification
if (isset($_POST['verify_otp'])) {
    $entered_otp = $_POST['otp'];
    $stored_otp = $_SESSION['reset_otp'] ?? '';
    
    if ($entered_otp == $stored_otp) {
        // OTP verified, move to password reset
        header("Location: forgot-password.php?step=3");
        exit();
    } else {
        $otp_error = "Invalid OTP. Please try again";
    }
}

// Step 3: Password reset
if (isset($_POST['reset_password'])) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if (empty($new_password)) {
        $password_error = "Password is required";
    } elseif (strlen($new_password) < 6) {
        $password_error = "Password must be at least 6 characters long";
    } elseif ($new_password !== $confirm_password) {
        $password_error = "Passwords do not match";
    } elseif (is_numeric($new_password) || preg_match('/^\d+$/', $new_password)) {
        $password_error = "Password cannot contain only numbers";
    } else {
        $email = $_SESSION['reset_email'];
        $sql = "UPDATE login SET userpassword='$new_password' WHERE usergmail='$email'";
        
        if (mysqli_query($conn, $sql)) {
            // Clear reset session data
            unset($_SESSION['reset_otp']);
            unset($_SESSION['reset_email']);
            
            $success_message = "Password reset successful! Please login with your new password.";
            // Redirect to login page after 3 seconds
            header("refresh:3;url=login.php");
        } else {
            $password_error = "Error resetting password. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - NinjaVolt</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f5f5f5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .reset-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
        }

        .reset-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            overflow: hidden;
        }

        .reset-header {
            background: #FFDD52;
            padding: 30px;
            text-align: center;
        }

        .reset-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #000;
        }

        .reset-subtitle {
            font-size: 14px;
            color: #333;
        }

        .reset-body {
            padding: 30px;
        }

        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }

        .step-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #ddd;
            margin: 0 8px;
        }

        .step-dot.active {
            background: #FFDD52;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-group input:focus {
            border-color: #FFDD52;
            outline: none;
            box-shadow: 0 0 0 2px rgba(255, 221, 82, 0.2);
        }

        .reset-button {
            width: 100%;
            padding: 12px;
            background: #000;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
        }

        .reset-button:hover {
            background: #333;
        }

        .error-message {
            background: #ffe6e6;
            color: #d63031;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .error-message i {
            margin-right: 10px;
        }

        .success-message {
            background: #e6ffe6;
            color: #00b894;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .success-message i {
            margin-right: 10px;
        }

        .back-to-login {
            text-align: center;
            margin-top: 20px;
        }

        .back-to-login a {
            color: #333;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s;
        }

        .back-to-login a:hover {
            color: #000;
            text-decoration: underline;
        }

        .test-otp-display {
            background: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }

        .test-otp-display .otp-code {
            font-size: 24px;
            font-weight: bold;
            color: #000;
            background: #FFDD52;
            padding: 5px 10px;
            border-radius: 4px;
            margin: 0 5px;
        }
    </style>
</head>
<body>
    <?php include("navbar.php"); ?>

    <div class="reset-container">
        <div class="reset-card">
            <div class="reset-header">
                <h2 class="reset-title">Reset Password</h2>
                <p class="reset-subtitle">
                    <?php
                    switch($step) {
                        case '1':
                            echo "Enter your email address to receive OTP";
                            break;
                        case '2':
                            echo "Enter the OTP code";
                            break;
                        case '3':
                            echo "Create your new password";
                            break;
                    }
                    ?>
                </p>
            </div>

            <div class="reset-body">
                <div class="step-indicator">
                    <div class="step-dot <?php echo $step >= 1 ? 'active' : ''; ?>"></div>
                    <div class="step-dot <?php echo $step >= 2 ? 'active' : ''; ?>"></div>
                    <div class="step-dot <?php echo $step >= 3 ? 'active' : ''; ?>"></div>
                </div>

                <?php if (!empty($success_message)): ?>
                    <div class="success-message">
                        <i class="fas fa-check-circle"></i>
                        <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>

                <?php if ($step == '2' && isset($_GET['test_otp'])): ?>
                    <div class="test-otp-display">
                        <p><strong>Testing Mode:</strong> Your OTP code is <span class="otp-code"><?php echo $_GET['test_otp']; ?></span></p>
                        <p><small>In a real application, this would be sent to your email</small></p>
                    </div>
                <?php endif; ?>

                <?php if ($step == '1'): ?>
                    <!-- Step 1: Email Verification -->
                    <form method="post" action="">
                        <?php if (!empty($email_error)): ?>
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                <?php echo $email_error; ?>
                            </div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" placeholder="Enter your email address" required>
                        </div>

                        <button type="submit" name="verify_email" class="reset-button">
                            Continue
                        </button>
                    </form>

                <?php elseif ($step == '2'): ?>
                    <!-- Step 2: OTP Verification -->
                    <form method="post" action="">
                        <?php if (!empty($otp_error)): ?>
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                <?php echo $otp_error; ?>
                            </div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="otp">Enter OTP</label>
                            <input type="text" id="otp" name="otp" placeholder="Enter 6-digit OTP" maxlength="6" required>
                        </div>

                        <button type="submit" name="verify_otp" class="reset-button">
                            Verify OTP
                        </button>
                    </form>

                <?php elseif ($step == '3'): ?>
                    <!-- Step 3: New Password -->
                    <form method="post" action="">
                        <?php if (!empty($password_error)): ?>
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                <?php echo $password_error; ?>
                            </div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" id="new_password" name="new_password" placeholder="Enter new password" required>
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">Confirm Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm new password" required>
                        </div>

                        <button type="submit" name="reset_password" class="reset-button">
                            Reset Password
                        </button>
                    </form>
                <?php endif; ?>

                <div class="back-to-login">
                    <a href="login.php">Back to Login</a>
                </div>
            </div>
        </div>
    </div>

    <?php include('footer.php'); ?>
</body>
</html> 