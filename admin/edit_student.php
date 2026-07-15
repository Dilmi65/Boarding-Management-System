<?php
require_once("../includes/auth_check.php");
require_once("../config/db.php");

$stmt = $connection->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$_GET['id'] ?? 0]);
$student = $stmt->fetch();

if (!$student) {
    header("Location: view_students.php");
    exit;
}

$rooms = $connection->query("SELECT * FROM rooms ORDER BY room_number")->fetchAll();

$pageTitle  = 'Edit Student';
$activePage = 'students';
require_once("../includes/header.php");
?>

<div class="max-w-lg mx-auto">
  <a href="view_students.php" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-indigo-600 mb-4">
    <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Students
  </a>

  <div class="glass-card p-7">
    <h3 class="text-lg font-bold text-gray-800 mb-1">Edit Student</h3>
    <p class="text-sm text-gray-500 mb-6">Update details for <?php echo htmlspecialchars($student['name']); ?>.</p>

    <form action="update_student.php" method="POST" class="space-y-4">
      <input type="hidden" name="id" value="<?php echo (int) $student['id']; ?>">

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name</label>
        <input type="text" name="name" required value="<?php echo htmlspecialchars($student['name']); ?>" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
        <input type="email" name="email" required value="<?php echo htmlspecialchars($student['email']); ?>" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Phone</label>
        <input type="text" name="phone" required value="<?php echo htmlspecialchars($student['phone']); ?>" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Room</label>
        <select name="room_id" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm">
          <option value="">Unassigned</option>
          <?php foreach ($rooms as $r): ?>
          <option value="<?php echo $r['id']; ?>" <?php echo $student['room_id'] == $r['id'] ? 'selected' : ''; ?>>Room <?php echo htmlspecialchars($r['room_number']); ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Student ID</label>
        <input type="text" name="sid" required value="<?php echo htmlspecialchars($student['student_id']); ?>" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Reset Portal Password</label>
        <input type="text" name="password" minlength="6" placeholder="Leave blank to keep current password" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm">
      </div>

      <button type="submit" class="w-full py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-semibold text-sm shadow-lg shadow-indigo-600/20 hover:shadow-xl transition-shadow">
        Update Student
      </button>
    </form>
  </div>
</div>

<?php require_once("../includes/footer.php"); ?>
