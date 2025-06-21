<?php
// Start the session at the very beginning of your script
session_start();

include("connection.php");

// Initialize variables for errors
$name_error = $email_error = $password_error = $login_error = $signup_error = $signup_success = $phone_error = "";

// Check if user is already logged in - redirect to home page
if(isset($_SESSION['user_id']) && !isset($_GET['logout'])) {
    header("Location: index.php");
    exit();
}

// Handle logout
if(isset($_GET['logout'])) {
    // Destroy the session
    session_unset();
    session_destroy();
    
    // Redirect to login page
    header("Location: login.php");
    exit();
}

// Handle form submissions
if (isset($_POST['login_submit'])) {
    $usergmail = $_POST['usergmail'];
    $userpassword = $_POST['userpassword'];
    $valid = true;

    // Validate email
    if (empty($usergmail)) {
        $email_error = "Email is required";
        $valid = false;
    }

    // Validate password
    if (empty($userpassword)) {
        $password_error = "Password is required";
        $valid = false;
    }

    // If all validations pass, check login
    if ($valid) {
        $usergmail = mysqli_real_escape_string($conn, $usergmail);
        $userpassword = mysqli_real_escape_string($conn, $userpassword);
        $sql = "SELECT * FROM users WHERE email = '$usergmail' AND password = '$userpassword'";
        $result = mysqli_query($conn, $sql);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            // Set session variables
            $_SESSION['username'] = $user['username'];
            $_SESSION['usergmail'] = $user['email'];
            $_SESSION['user_id'] = $user['id']; // Store user ID in session
            
            // Check if there's a redirect parameter
            if (isset($_GET['redirect'])) {
                switch ($_GET['redirect']) {
                    case 'cart':
                        header("Location: cart.php");
                        break;
                    case 'checkout':
                        header("Location: checkout.php");
                        break;
                    default:
                        header("Location: index.php");
                }
            } else {
                // Redirect to home page
                header("Location: index.php");
            }
            exit();
        } else {
            $login_error = "Invalid email or password";
        }
    }
}

if (isset($_POST['signup_submit'])) {
    $username = $_POST['username'];
    $usergmail = $_POST['usergmail'];
    $userpassword = $_POST['userpassword'];
    $phone = $_POST['phone'];
    $valid = true;

    // Validate name (check if it contains only numbers)
    if (empty($username)) {
        $name_error = "Name is required";
        $valid = false;
    } elseif (is_numeric($username) || preg_match('/^\d+$/', $username)) {
        $name_error = "Name cannot contain only numbers";
        $valid = false;
    }

    // Validate phone number
    if (empty($phone)) {
        $phone_error = "Phone number is required";
        $valid = false;
    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
        $phone_error = "Please enter a valid 10-digit phone number";
        $valid = false;
    } else {
        // Check if phone column exists
        $check_column = "SHOW COLUMNS FROM users LIKE 'phone'";
        $column_result = mysqli_query($conn, $check_column);
        
        if ($column_result && mysqli_num_rows($column_result) > 0) {
            // Phone column exists, check for duplicate
            $phone = mysqli_real_escape_string($conn, $phone);
            $check_phone = "SELECT * FROM users WHERE phone = '$phone'";
            $check_phone_result = mysqli_query($conn, $check_phone);
            
            if ($check_phone_result && mysqli_num_rows($check_phone_result) > 0) {
                $phone_error = "Phone number already exists. Please use a different number.";
                $valid = false;
            }
        }
    }

    // Validate email
    if (empty($usergmail)) {
        $email_error = "Email is required";
        $valid = false;
    } elseif (!filter_var($usergmail, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Please enter a valid email address";
        $valid = false;
    } else {
        // Check if email already exists
        $usergmail = mysqli_real_escape_string($conn, $usergmail);
        $check_email = "SELECT * FROM users WHERE email = '$usergmail'";
        $check_result = mysqli_query($conn, $check_email);
        
        if ($check_result && mysqli_num_rows($check_result) > 0) {
            $email_error = "Email already exists. Please use a different email.";
            $valid = false;
        }
    }

    // Validate password
    if (empty($userpassword)) {
        $password_error = "Password is required";
        $valid = false;
    } elseif (strlen($userpassword) < 6) {
        $password_error = "Password must be at least 6 characters long";
        $valid = false;
    } elseif (is_numeric($userpassword) || preg_match('/^\d+$/', $userpassword)) {
        $password_error = "Password cannot contain only numbers";
        $valid = false;
    }

    // If all validations pass, insert new user
    if ($valid) {
        $username = mysqli_real_escape_string($conn, $username);
        $usergmail = mysqli_real_escape_string($conn, $usergmail);
        $userpassword = mysqli_real_escape_string($conn, $userpassword);
        $phone = mysqli_real_escape_string($conn, $phone);
        
        $sql = "INSERT INTO users(username, email, password, phone) VALUES ('$username', '$usergmail', '$userpassword', '$phone')";
        
        if (mysqli_query($conn, $sql)) {
            $signup_success = "Account created successfully! Please login.";
            // Automatically show login form after successful signup
            $show_login = true;
            
            // Clear the form data
            $username = "";
            $usergmail = "";
            $userpassword = "";
            $phone = "";
        } else {
            $signup_error = "Error: " . mysqli_error($conn);
        }
    }
}

// By default, show signup form first unless login is specifically requested
$show_login = isset($show_login) ? $show_login : false;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Signup - NinjaVolt</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
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

    .auth-container {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
        background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
        position: relative;
        overflow: hidden;
        
    }

    .auth-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 400px;
        position: relative;
        z-index: 1;
        overflow: hidden;
        
    }

    .auth-header {
        background: #FFDD52;
        padding: 30px;
        text-align: center;
        position: relative;
    }

    .auth-header::after {
        content: '';
        position: absolute;
        bottom: -20px;
        left: 0;
        width: 100%;
        height: 40px;
        background: white;
        border-radius: 50% 50% 0 0;
    }

    .auth-logo {
        font-size: 24px;
        color: #000;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .auth-tabs {
        display: flex;
        margin: 20px;
        border-radius: 10px;
        background: #f5f5f5;
        padding: 5px;
        position: relative;
        z-index: 1;
    }

    .auth-tab {
        flex: 1;
        padding: 15px;
        text-align: center;
        color: #666;
        cursor: pointer;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .auth-tab.active {
        background: #FFDD52;
        color: #000;
        font-weight: 600;
    }

    .auth-forms {
        padding: 30px;
        
    }

    .form-group {
        margin-bottom: 20px;
        position: relative;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #333;
        font-weight: 500;
        font-size: 14px;
    }

    .form-group input {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #eee;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-group input:focus {
        border-color: #FFDD52;
        box-shadow: 0 0 0 3px rgba(255, 221, 82, 0.2);
        outline: none;
    }

    .form-group .field-error {
        color: #ff4d4d;
        font-size: 12px;
        margin-top: 5px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .auth-button {
        width: 100%;
        padding: 14px;
        background: #000;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 10px;
    }

    .auth-button:hover {
        background: #FFDD52;
        color: #000;
        transform: translateY(-2px);
    }

    .success-message, .error-message {
        padding: 12px 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
    }

    .success-message {
        background-color: #e8f5e9;
        color: #2e7d32;
        border: 1px solid #c8e6c9;
    }

    .error-message {
        background-color: #fdecea;
        color: #d32f2f;
        border: 1px solid #ffcdd2;
    }

    .hidden {
        display: none;
    }

    .password-toggle {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #666;
    }

    .remember-forgot {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin: 15px 0;
    }

    .remember-me {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #666;
    }

    .forgot-password {
        font-size: 13px;
        color: #FFDD52;
        text-decoration: none;
        font-weight: 500;
    }

    .forgot-password:hover {
        text-decoration: underline;
    }

    @media (max-width: 480px) {
        .auth-card {
            margin: 10px;
        }

        .auth-header {
            padding: 20px;
        }

        .auth-forms {
            padding: 20px;
        }
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }
</style>

<body>
    <?php include("navbar.php"); ?>

    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo">NinjaVolt</div>
                <div class="auth-tabs">
                    <div class="auth-tab <?php echo $show_login ? 'active' : ''; ?>" onclick="toggleForm('login')">Login</div>
                    <div class="auth-tab <?php echo !$show_login ? 'active' : ''; ?>" onclick="toggleForm('signup')">Sign Up</div>
                </div>
            </div>

            <div class="auth-forms">
                <!-- Login Form -->
                <form id="loginForm" action="" method="post" class="<?php echo $show_login ? '' : 'hidden'; ?>">
                    <?php if (isset($login_error) && !empty($login_error)): ?>
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            <?php echo $login_error; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($signup_success) && !empty($signup_success)): ?>
                        <div class="success-message">
                            <i class="fas fa-check-circle"></i>
                            <?php echo $signup_success; ?>
                        </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="loginEmail">Email</label>
                        <input type="email" id="loginEmail" name="usergmail" placeholder="Enter your email" required>
                        <?php if (isset($email_error) && !empty($email_error) && isset($_POST['login_submit'])): ?>
                            <div class="field-error">
                                <i class="fas fa-exclamation-circle"></i>
                                <?php echo $email_error; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="loginPassword">Password</label>
                        <input type="password" id="loginPassword" name="userpassword" placeholder="Enter your password" required>
                        <i class="fas fa-eye password-toggle" onclick="togglePassword('loginPassword')"></i>
                        <?php if (isset($password_error) && !empty($password_error) && isset($_POST['login_submit'])): ?>
                            <div class="field-error">
                                <i class="fas fa-exclamation-circle"></i>
                                <?php echo $password_error; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="remember-forgot">
                        <label class="remember-me">
                            <input type="checkbox" name="remember">
                            Remember me
                        </label>
                        <a href="forgot-password.php" class="forgot-password">Forgot Password?</a>
                    </div>

                    <button type="submit" name="login_submit" class="auth-button">
                        Login
                    </button>
                </form>

                <!-- Signup Form -->
                <form id="signupForm" action="" method="post" class="<?php echo !$show_login ? '' : 'hidden'; ?>">
                    <?php if (isset($signup_error) && !empty($signup_error)): ?>
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            <?php echo $signup_error; ?>
                        </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="signupName">Full Name</label>
                        <input type="text" id="signupName" name="username" placeholder="Enter your full name" required>
                        <?php if (isset($name_error) && !empty($name_error)): ?>
                            <div class="field-error">
                                <i class="fas fa-exclamation-circle"></i>
                                <?php echo $name_error; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="signupPhone">Phone Number</label>
                        <input type="tel" id="signupPhone" name="phone" placeholder="Enter your phone number" pattern="[0-9]{10}" maxlength="10" required>
                        <?php if (isset($phone_error) && !empty($phone_error)): ?>
                            <div class="field-error">
                                <i class="fas fa-exclamation-circle"></i>
                                <?php echo $phone_error; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="signupEmail">Email</label>
                        <input type="email" id="signupEmail" name="usergmail" placeholder="Enter your email" required>
                        <?php if (isset($email_error) && !empty($email_error) && isset($_POST['signup_submit'])): ?>
                            <div class="field-error">
                                <i class="fas fa-exclamation-circle"></i>
                                <?php echo $email_error; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="signupPassword">Password</label>
                        <input type="password" id="signupPassword" name="userpassword" placeholder="Create a strong password" required>
                        <i class="fas fa-eye password-toggle" onclick="togglePassword('signupPassword')"></i>
                        <?php if (isset($password_error) && !empty($password_error) && isset($_POST['signup_submit'])): ?>
                            <div class="field-error">
                                <i class="fas fa-exclamation-circle"></i>
                                <?php echo $password_error; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <button type="submit" name="signup_submit" class="auth-button">
                        Create Account
                    </button>
                </form>
            </div>
        </div>
    </div>

    <?php include('footer.php'); ?>

    <script>
        function toggleForm(formType) {
            const loginForm = document.getElementById('loginForm');
            const signupForm = document.getElementById('signupForm');
            const loginTab = document.querySelector('.auth-tab:first-child');
            const signupTab = document.querySelector('.auth-tab:last-child');

            if (formType === 'login') {
                loginForm.classList.remove('hidden');
                signupForm.classList.add('hidden');
                loginTab.classList.add('active');
                signupTab.classList.remove('active');
            } else {
                loginForm.classList.add('hidden');
                signupForm.classList.remove('hidden');
                loginTab.classList.remove('active');
                signupTab.classList.add('active');
            }
        }

        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.nextElementSibling;
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        <?php if (isset($login_error) || isset($signup_error)): ?>
            const formContainer = document.querySelector('.auth-card');
            formContainer.style.animation = 'shake 0.5s ease-in-out';
            setTimeout(() => {
                formContainer.style.animation = '';
            }, 500);
        <?php endif; ?>
    </script>
</body>
</html>