<?php
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: admin_login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Registration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="icon" href="./images/logo.png" sizes="180x180" type="image/png">
 <link rel="apple-touch-icon" sizes="180x180" href="./images/logo.png">
    <link rel="stylesheet" href="./css/base.css">
</head>
<body>
<?php include 'navbar.php'?>
 <div class="forms_all">
<div class="forms">
<h2>Register Member</h2>

   
  </p>
</div>
    <?php
    include 'db.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $company_name = $_POST['company_name'];
        
        // Check for duplicate member
        $check_stmt = $conn->prepare("SELECT * FROM members WHERE name = ? AND company_name = ?");
        $check_stmt->execute([$name, $company_name]);
        
        if ($check_stmt->rowCount() > 0) {
            echo "A member with the same name and company name already exists.";
        } else {
            // Handle file upload
            $target_dir = "uploads/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
            move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file);

            // Generate membership ID
            $stmt = $conn->query("SELECT COUNT(*) AS count FROM members");
            $row = $stmt->fetch();
            $count = $row['count'] + 1;
            $membership_id = sprintf("SMPA-%03d", $count);

            // Insert member data into the database
            $sql = "INSERT INTO members (membership_id, name, company_name, profile_picture) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$membership_id, $name, $company_name, $target_file]);

            echo "Member registered successfully with Membership ID: " . $membership_id;
        }
    }
    ?>
    
    <form action="" method="POST" enctype="multipart/form-data">
      <div class="forms">
      <label for="name">Name:</label>
      <input type="text" id="name" name="name" required>
      </div>
        
     <div class="forms">
     <label for="company_name">Company Name:</label>
     <input type="text" id="company_name" name="company_name" required>
     </div>
        
   <div class="forms">
   <label for="profile_picture">Profile Picture:</label>
   <input type="file" id="profile_picture" name="profile_picture" required>
   </div>
        <div class="forms">
            <button type="submit">Register</button>
        </div>
    </form>
 </div>
</body>
</html>
