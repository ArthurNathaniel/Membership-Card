<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['membership_id'])) {
    $membership_id = $_POST['membership_id'];

    // Fetch the profile picture path to delete the file
    $stmt = $conn->prepare("SELECT profile_picture FROM members WHERE membership_id = ?");
    $stmt->execute([$membership_id]);
    $member = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($member) {
        $profile_picture = $member['profile_picture'];

        // Delete the member from the database
        $sql = "DELETE FROM members WHERE membership_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$membership_id]);

        // Delete the profile picture file
        if (file_exists($profile_picture)) {
            unlink($profile_picture);
        }

        echo "Member deleted successfully.";
        header("Location: view_members.php"); // Redirect back to the members list
        exit();
    } else {
        echo "Member not found.";
    }
} else {
    echo "Invalid request method or missing membership ID.";
}
?>
