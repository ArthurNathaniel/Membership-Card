<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Members</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" href="./images/logo.png" sizes="180x180" type="image/png">
 <link rel="apple-touch-icon" sizes="180x180" href="./images/logo.png">
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 15px;
            text-align: left;
        }
        .action-icons span {
            margin-right: 10px;
            cursor: pointer;
        }
        .modal-content {
            position: fixed;
            background-color: white;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            z-index: 1000;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .no-members {
            text-align: center;
            padding: 20px;
            font-size: 18px;
            color: #888;
        }
        .search-container {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
   <div class="all">
   <h2>Registered Members</h2>
    
    <div class="forms">
        <input type="text" id="searchInput" placeholder="Search members..." onkeyup="searchMembers()">
    </div>

    <?php
    include 'db.php';

    // Fetch data from the database
    $stmt = $conn->query("SELECT membership_id, name, company_name, profile_picture FROM members");
    $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <?php if (empty($members)): ?>
        <p class="no-members">No members found.</p>
    <?php else: ?>
        <table id="membersTable">
            <thead>
                <tr>
                    <th>Membership ID</th>
                    <th>Name</th>
                    <th>Company Name</th>
                    <th>Profile Picture</th>
                    <!-- <th>Actions</th> -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($members as $member): ?>
                <tr>
                    <td><?php echo htmlspecialchars($member['membership_id']); ?></td>
                    <td><?php echo htmlspecialchars($member['name']); ?></td>
                    <td><?php echo htmlspecialchars($member['company_name']); ?></td>
                    <td><img src="<?php echo htmlspecialchars($member['profile_picture']); ?>" alt="Profile Picture" width="100"></td>
                    <!-- <td class="action-icons">
                        <span onclick="viewMember('<?php echo htmlspecialchars(json_encode($member)); ?>')"><i class="fas fa-eye"></i></span>
                        <span onclick="editMember('<?php echo htmlspecialchars(json_encode($member)); ?>')"><i class="fas fa-edit"></i></span>
                        <span onclick="deleteMember('<?php echo htmlspecialchars($member['membership_id']); ?>')"><i class="fas fa-trash"></i></span>
                    </td> -->
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- View Modal -->
    <div id="viewModal" style="display:none;">
        <div class="modal-content">
            <span class="close" onclick="closeModal('viewModal')">&times;</span>
            <h2>View Member</h2>
            <p id="viewDetails"></p>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" style="display:none;">
        <div class="modal-content">
            <span class="close" onclick="closeModal('editModal')">&times;</span>
            <h2>Edit Member</h2>
            <form id="editForm" action="edit_member.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" id="editMembershipId" name="membership_id">
                <div class="forms">
                    <label for="editName">Name:</label>
                    <input type="text" id="editName" name="name" required>
                </div>
                <div class="forms">
                    <label for="editCompanyName">Company Name:</label>
                    <input type="text" id="editCompanyName" name="company_name" required>
                </div>
                <div class="forms">
                    <label for="editProfilePicture">Profile Picture:</label>
                    <input type="file" id="editProfilePicture" name="profile_picture">
                </div>
                <div class="forms">
                    <button type="submit">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
   </div>

    <script>
        function searchMembers() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toLowerCase();
            const table = document.getElementById('membersTable');
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

            for (let i = 0; i < rows.length; i++) {
                const cells = rows[i].getElementsByTagName('td');
                let found = false;
                for (let j = 0; j < cells.length - 1; j++) { // -1 to skip the last column (Actions)
                    if (cells[j].innerText.toLowerCase().includes(filter)) {
                        found = true;
                        break;
                    }
                }
                rows[i].style.display = found ? '' : 'none';
            }
        }

        function viewMember(member) {
            const memberObj = JSON.parse(member);
            const viewDetails = `
                <strong>Membership ID:</strong> ${memberObj.membership_id}<br>
                <strong>Name:</strong> ${memberObj.name}<br>
                <strong>Company Name:</strong> ${memberObj.company_name}<br>
                <img src="${memberObj.profile_picture}" alt="Profile Picture" width="100">
            `;
            document.getElementById('viewDetails').innerHTML = viewDetails;
            document.getElementById('viewModal').style.display = 'block';
        }

        function editMember(member) {
            const memberObj = JSON.parse(member);
            document.getElementById('editMembershipId').value = memberObj.membership_id;
            document.getElementById('editName').value = memberObj.name;
            document.getElementById('editCompanyName').value = memberObj.company_name;
            document.getElementById('editModal').style.display = 'block';
        }

        function deleteMember(membershipId) {
            if (confirm('Are you sure you want to delete this member?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'delete_member.php';

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'membership_id';
                input.value = membershipId;
                form.appendChild(input);

                document.body.appendChild(form);
                form.submit();
            }
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
    </script>
</body>
</html>
