<?php
require_once("../includes/auth_check.php");
require_once("../config/db.php");

$search = trim($_GET['q'] ?? '');

if ($search !== '') {
    $stmt = $connection->prepare(
        "SELECT s.*, r.room_number FROM students s LEFT JOIN rooms r ON r.id = s.room_id
         WHERE s.name LIKE ? OR s.student_id LIKE ? OR s.email LIKE ?
         ORDER BY s.id DESC"
    );
    $like = "%{$search}%";
    $stmt->execute([$like, $like, $like]);
} else {
    $stmt = $connection->query(
        "SELECT s.*, r.room_number FROM students s LEFT JOIN rooms r ON r.id = s.room_id ORDER BY s.id DESC"
    );
}
$students = $stmt->fetchAll();

$pageTitle  = 'Students';
$activePage = 'students';
require_once("../includes/header.php");
?>

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
  <div>
    <h2 class="text-xl font-bold text-gray-800">Students</h2>
    <p class="text-gray-500 text-sm">All registered boarders.</p>
  </div>
  <div class="flex gap-3">
    <form class="relative" method="GET">
      <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
      <input type="text" name="q" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search students..."
        class="pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm w-56">
    </form>
    <a href="add_students.php" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-violet-600 text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow-lg shadow-indigo-600/20 hover:shadow-xl transition-shadow whitespace-nowrap">
      <i data-lucide="plus" class="w-4 h-4"></i> Add Student
    </a>
  </div>
</div>

<div class="glass-card overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-sm modern-table">
      <thead>
        <tr class="text-left">
          <th class="py-3 px-5">Student</th>
          <th class="py-3 px-5">Contact</th>
          <th class="py-3 px-5">Room</th>
          <th class="py-3 px-5 text-right">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-50">
        <?php foreach ($students as $s): ?>
        <tr>
          <td class="py-3 px-5">
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 font-semibold text-sm shrink-0">
                <?php echo strtoupper(substr($s['name'], 0, 1)); ?>
              </div>
              <div class="min-w-0">
                <p class="font-medium text-gray-800 truncate"><?php echo htmlspecialchars($s['name']); ?></p>
                <p class="text-xs text-gray-400"><?php echo htmlspecialchars($s['student_id']); ?></p>
              </div>
            </div>
          </td>
          <td class="py-3 px-5">
            <p class="text-gray-700"><?php echo htmlspecialchars($s['email']); ?></p>
            <p class="text-xs text-gray-400"><?php echo htmlspecialchars($s['phone']); ?></p>
          </td>
          <td class="py-3 px-5 text-gray-600">
            <?php echo $s['room_number'] ? 'Room ' . htmlspecialchars($s['room_number']) : '<span class="text-gray-300">Unassigned</span>'; ?>
          </td>
          <td class="py-3 px-5">
            <div class="flex justify-end gap-2">
              <a href="edit_student.php?id=<?php echo $s['id']; ?>" class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-indigo-100 flex items-center justify-center text-gray-600 hover:text-indigo-600" title="Edit">
                <i data-lucide="pencil" class="w-4 h-4"></i>
              </a>
              <a href="delete_student.php?id=<?php echo $s['id']; ?>" onclick="return confirmDelete('Delete this student?')" class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-rose-100 flex items-center justify-center text-gray-600 hover:text-rose-600" title="Delete">
                <i data-lucide="trash-2" class="w-4 h-4"></i>
              </a>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($students)): ?>
        <tr><td colspan="4" class="py-10 text-center text-gray-400">No students found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once("../includes/footer.php"); ?>
