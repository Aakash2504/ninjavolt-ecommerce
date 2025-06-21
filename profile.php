<?php
// Start session
session_start();

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

include("connection.php");

$user_id = $_SESSION['user_id'];
$success_message = '';
$error_message = '';

// Get user data
$query = "SELECT * FROM login WHERE id = $user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Handle profile image update
if (isset($_POST['update_profile'])) {
    // Check if file was uploaded without errors
    if (isset($_FILES["profile_image"]) && $_FILES["profile_image"]["error"] == 0) {
        $allowed = ["jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png"];
        $filename = $_FILES["profile_image"]["name"];
        $filetype = $_FILES["profile_image"]["type"];
        $filesize = $_FILES["profile_image"]["size"];
    
        // Verify file extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!array_key_exists($ext, $allowed)) {
            $error_message = "Error: Please select a valid file format (JPG, JPEG, PNG, GIF).";
        }
    
        // Verify file size - 5MB maximum
        $maxsize = 5 * 1024 * 1024;
        if ($filesize > $maxsize) {
            $error_message = "Error: File size is larger than the allowed limit (5MB).";
        }
    
        // Verify MIME type of the file
        if (in_array($filetype, $allowed)) {
            // Check if file exists before uploading
            $target_dir = "../uploads/";
            
            // Create directory if it doesn't exist
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            
            // Create unique filename
            $new_filename = uniqid() . "." . $ext;
            $target_file = $target_dir . $new_filename;
            
            // Upload file
            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                // File uploaded successfully, update database
                $profile_image_path = "uploads/" . $new_filename;
                $update_query = "UPDATE login SET profile_image = '../$profile_image_path' WHERE id = $user_id";
                
                if (mysqli_query($conn, $update_query)) {
                    $success_message = "Profile image updated successfully.";
                    
                    // Update user data
                    $result = mysqli_query($conn, $query);
                    $user = mysqli_fetch_assoc($result);
                } else {
                    $error_message = "Error updating profile image in database: " . mysqli_error($conn);
                }
            } else {
                $error_message = "Error uploading file.";
            }
        } else {
            $error_message = "Error: There was a problem with your file. Please try again.";
        }
    } else if ($_FILES["profile_image"]["error"] > 0) {
        $error_message = "Error: " . $_FILES["profile_image"]["error"];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - NinjaVolt</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap");

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", serif;
        }

        body {
            background-color: rgb(0, 0, 0);
            overflow-x: auto;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            color: white;
        }

        .profile-container {
            width: 100%;
            max-width: 800px;
            margin: 40px auto;
            padding: 30px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            backdrop-filter: blur(10px);
        }

        .profile-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .profile-header h1 {
            font-size: 2.5rem;
            background: linear-gradient(45deg, #00bcd4, #9c27b0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-fill-color: transparent;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(45deg, #00bcd4, #9c27b0);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .profile-avatar i {
            font-size: 50px;
            color: white;
        }

        .profile-details {
            margin-top: 20px;
        }

        .detail-item {
            background: rgba(255, 255, 255, 0.05);
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .detail-label {
            font-size: 0.9rem;
            color: #9c27b0;
            margin-bottom: 5px;
        }

        .detail-value {
            font-size: 1.1rem;
            color: white;
        }

        .btn {
            background: linear-gradient(45deg, #00bcd4, #9c27b0);
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            transition: transform 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-3px);
            background: black;
            color: white;
        }
    </style>
</head>
<body>
    <?php include("navbar.php"); ?>

    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-avatar">
                <i class="fas fa-user"></i>
            </div>
            <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
        </div>

        <div class="profile-details">
            <?php if (!empty($success_message)): ?>
                <div class="success-message">
                    <i class="fas fa-check-circle"></i>
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($error_message)): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <div class="detail-item">
                <div class="detail-label">Username</div>
                <div class="detail-value"><?php echo $_SESSION['username']; ?></div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Email</div>
                <div class="detail-value"><?php echo $_SESSION['usergmail']; ?></div>
            </div>
            <!-- You can add more user details here -->
        </div>

        <form class="profile-form" action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="profile_image">Update Profile Image</label>
                <input type="file" id="profile_image" name="profile_image" accept="image/*" required>
            </div>
            <button type="submit" name="update_profile" class="btn">
                Update Profile Image
            </button>
        </form>

        <a href="login.php?logout=true" class="btn">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>

    <?php include("footer.php"); ?>
</body>
</html>