<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $membership_id = $_POST['membership_id'];
    $name = $_POST['name'];
    $company_name = $_POST['company_name'];
    $profile_picture = $_FILES['profile_picture'];

    // Check if a new profile picture was uploaded
    if ($profile_picture['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($profile_picture["name"]);
        move_uploaded_file($profile_picture["tmp_name"], $target_file);

        // Update member data including the new profile picture
        $sql = "UPDATE members SET name = ?, company_name = ?, profile_picture = ? WHERE membership_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name, $company_name, $target_file, $membership_id]);
    } else {
        // Update member data without changing the profile picture
        $sql = "UPDATE members SET name = ?, company_name = ? WHERE membership_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name, $company_name, $membership_id]);
    }

    echo "Member details updated successfully.";
    header("Location: view_members.php"); // Redirect back to the members list
    exit();
} else {
    echo "Invalid request method.";
}
?>
