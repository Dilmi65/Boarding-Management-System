<?php
require_once("../includes/student_auth_check.php");
require_once("../config/db.php");

$sid = $_SESSION['student_db_id'];
$info = '';
$error = '';

if (isset($_POST['update_profile'])) {
    $stmt = $connection->prepare("UPDATE students SET name = ?, email = ?, phone = ? WHERE id = ?");
    $stmt->execute([trim($_POST['name']), trim($_POST['email']), trim($_POST['phone']), $sid]);
    $_SESSION['student_name'] = trim($_POST['name']);
    $info = "Profile updated successfully.";
}

if (isset($_POST['change_password'])) {
    $stmt = $connection->prepare("SELECT password FROM students WHERE id = ?");
    $stmt->execute([$sid]);
    $current = $stmt->fetch()['password'];

    if (!password_verify($_POST['current_password'], $current)) {
        $error = "Current password is incorrect.";
    } elseif (strlen($_POST['new_password']) < 6) {
        $error = "New password must be at least 6 characters.";
    } elseif ($_POST['new_password'] !== $_POST['confirm_password']) {
        $error = "New passwords do not match.";
    } else {
        $newHash = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        $upd = $connection->prepare("UPDATE students SET password = ? WHERE id = ?");
        $upd->execute([$newHash, $sid]);
        $info = "Password changed successfully.";
    }
}

$stmt = $connection->prepare(
    "SELECT s.*, r.room_number FROM students s LEFT JOIN rooms r ON r.id = s.room_id WHERE s.id = ?"
);
$stmt->execute([$sid]);
$student = $stmt->fetch();

$pageTitle  = 'My Profile';
$activePage = 'profile';
require_once("../includes/student_header.php");
?>

<div class="max-w-xl space-y-6">

  <?php if ($info): ?>
  <div data-autohide class="flex items-center gap-2 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-xl px-4 py-3 text-sm">
    <i data-lucide="check-circle" class="w-4 h-4"></i> <?php echo htmlspecialchars($info); ?>
  </div>
  <?php endif; ?>
  <?php if ($error): ?>
  <div class="flex items-center gap-2 bg-red-50 text-red-600 border border-red-100 rounded-xl px-4 py-3 text-sm">
    <i data-lucide="alert-circle" class="w-4 h-4"></i> <?php echo htmlspecialchars($error); ?>
  </div>
  <?php endif; ?>

  <div class="glass-card p-7">
    <div class="flex items-center gap-4 mb-6">
      <div class="w-14 h-14 rounded-full bg-gradient-to-br from-teal-400 to-emerald-500 flex items-center justify-center text-white text-xl font-bold">
        <?php echo strtoupper(substr($student['name'], 0, 1)); ?>
      </div>
      <div>
        <p class="font-bold text-gray-800"><?php echo htmlspecialchars($student['name']); ?></p>
        <p class="text-xs text-gray-400"><?php echo htmlspecialchars($student['student_id']); ?> · Room <?php echo $student['room_number'] ? htmlspecialchars($student['room_number']) : 'Unassigned'; ?></p>
      </div>
    </div>

    <form method="POST" class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name</label>
        <input type="text" name="name" required value="<?php echo htmlspecialchars($student['name']); ?>" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-teal-500/40 focus:border-teal-400 text-sm">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
        <input type="email" name="email" required value="<?php echo htmlspecialchars($student['email']); ?>" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-teal-500/40 focus:border-teal-400 text-sm">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Phone</label>
        <input type="text" name="phone" required value="<?php echo htmlspecialchars($student['phone']); ?>" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-teal-500/40 focus:border-teal-400 text-sm">
      </div>
      <button type="submit" name="update_profile" class="py-2.5 px-5 rounded-xl bg-gradient-to-r from-teal-500 to-emerald-600 text-white font-semibold text-sm shadow-lg shadow-teal-600/20 hover:shadow-xl transition-shadow">
        Save Changes
      </button>
    </form>
  </div>

  <div class="glass-card p-7">
    <h3 class="font-semibold text-gray-800 mb-1">Change Password</h3>
    <p class="text-sm text-gray-500 mb-5">Choose a new password for your account.</p>
    <form method="POST" class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Current Password</label>
        <input type="password" name="current_password" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-teal-500/40 focus:border-teal-400 text-sm">
      </div>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1.5">New Password</label>
          <input type="password" name="new_password" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-teal-500/40 focus:border-teal-400 text-sm">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1.5">Confirm Password</label>
          <input type="password" name="confirm_password" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-teal-500/40 focus:border-teal-400 text-sm">
        </div>
      </div>
      <button type="submit" name="change_password" class="py-2.5 px-5 rounded-xl bg-gray-800 text-white font-semibold text-sm hover:bg-gray-900 transition-colors">
        Update Password
      </button>
    </form>
  </div>
</div>

<?php require_once("../includes/footer.php"); ?>
