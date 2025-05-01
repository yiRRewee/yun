<?php
require "../public/header.php";
require "../config/adminAuth.php";

$roles = $_GET['role'] ?? 'all';
$search = $_GET['search'] ?? '';


$users = getAllUser($roles, $search);

if(is_post()){
    $user_id = $_POST['user_id'] ?? null;
    if (isset($_POST['edit_user'])) {
        redirect("adminEditUser.php?user_id=$user_id");
    }
    if (isset($_POST['delete_user'])) {
        deleteUserById($user_id);
        temp("info", "✅ User deleted successfully.");
        redirect();
    }
}

?>

<link rel="stylesheet" href="../assets/css/manageUser.css">
<h2>Manage Users</h2>
<div class="role">
        <a href="?role=all"><b>ALL</b></a> |
        <a href="?role=user"><b>User</b></a> |
        <a href="?role=admin"><b>Admin</b></a> |
    </div>

<div class="role-container">
<div class="top-bar">
<div class="add-user">
<?php if ($role == 'manager'): ?>
<form action="adminAddUser.php">
        <button class="btn-create-user"><b>➕ Create New User / Admin</b></button>
    </form>
    <?php endif; ?>
    </div>
    <form method="GET" class="search-form">
        <?php html_search('search', 'Username / Email / Phone', "class='search-input'"); ?>
        <button type="submit" class="btn-search">Search</button>
    </form>
</div>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Password</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($users): ?>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user->id ?></td>
                    <td><?= $user->username ?></td>
                    <td><?= $user->email ?></td>
                    <td><?= $user->phone_num ?></td>
                    <td><?= $user->password ?></td>
                    <td><?= $user->role ?></td>
                    <td>
                    <form method="post">
                    <input type="hidden" name="user_id" value="<?= $user->id ?>">

                    <button type="submit" name="edit_user" class="btn-edit">Edit</button>
                    <button type="submit" name="delete_user" class="btn-delete" data-confirm="Are You Sure Delete This User Account?">Delete</button>
                    </form>
                    <img src="<?= $user->profile_image ?>" alt="Profile Image" class="popup">
                </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7">No users found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</div>
<?php include "../public/footer.php" ?>