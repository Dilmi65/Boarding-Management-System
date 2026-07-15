<?php
require_once("../includes/auth_check.php");
require_once("../config/db.php");

$students   = $connection->query("SELECT COUNT(*) c FROM students")->fetch()['c'];
$rooms      = $connection->query("SELECT COUNT(*) c FROM rooms")->fetch()['c'];
$available  = $connection->query("SELECT COUNT(*) c FROM rooms WHERE status='Available'")->fetch()['c'];
$revenue    = $connection->query("SELECT COALESCE(SUM(amount),0) t FROM payments WHERE status='Paid'")->fetch()['t'];
$pending    = $connection->query("SELECT COALESCE(SUM(amount),0) t FROM payments WHERE status='Pending'")->fetch()['t'];
$complaints = $connection->query("SELECT COUNT(*) c FROM complaints WHERE status='Pending'")->fetch()['c'];

$recentStudents = $connection->query(
    "SELECT s.*, r.room_number FROM students s LEFT JOIN rooms r ON r.id = s.room_id ORDER BY s.id DESC LIMIT 5"
)->fetchAll();

$recentComplaints = $connection->query(
    "SELECT c.*, s.name FROM complaints c JOIN students s ON s.id = c.student_id ORDER BY c.id DESC LIMIT 5"
)->fetchAll();

$pageTitle  = 'Dashboard';
$activePage = 'dashboard';
require_once("../includes/header.php");
?>

<h2 class="text-xl font-bold text-gray-800 mb-1">Welcome back 👋</h2>
<p class="text-gray-500 text-sm mb-6">Here's what's happening in your boarding house today.</p>

<!-- Stat cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">

  <div class="stat-card glass-card p-5 flex items-center gap-4">
    <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center">
      <i data-lucide="users" class="w-6 h-6 text-indigo-600"></i>
    </div>
    <div>
      <p class="text-gray-400 text-xs font-medium">Total Students</p>
      <p class="text-2xl font-bold text-gray-800"><?php echo $students; ?></p>
    </div>
  </div>

  <div class="stat-card glass-card p-5 flex items-center gap-4">
    <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center">
      <i data-lucide="door-open" class="w-6 h-6 text-emerald-600"></i>
    </div>
    <div>
      <p class="text-gray-400 text-xs font-medium">Rooms (<?php echo $available; ?> available)</p>
      <p class="text-2xl font-bold text-gray-800"><?php echo $rooms; ?></p>
    </div>
  </div>

  <div class="stat-card glass-card p-5 flex items-center gap-4">
    <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center">
      <i data-lucide="wallet" class="w-6 h-6 text-amber-600"></i>
    </div>
    <div>
      <p class="text-gray-400 text-xs font-medium">Revenue Collected</p>
      <p class="text-2xl font-bold text-gray-800">Rs. <?php echo number_format($revenue, 2); ?></p>
    </div>
  </div>

  <div class="stat-card glass-card p-5 flex items-center gap-4">
    <div class="w-12 h-12 rounded-xl bg-rose-50 flex items-center justify-center">
      <i data-lucide="message-square-warning" class="w-6 h-6 text-rose-600"></i>
    </div>
    <div>
      <p class="text-gray-400 text-xs font-medium">Pending Complaints</p>
      <p class="text-2xl font-bold text-gray-800"><?php echo $complaints; ?></p>
    </div>
  </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

  <!-- Recent students -->
  <div class="xl:col-span-2 glass-card p-5">
    <div class="flex items-center justify-between mb-4">
      <h3 class="font-semibold text-gray-800">Recent Students</h3>
      <a href="view_students.php" class="text-xs font-medium text-indigo-600 hover:underline">View all</a>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-sm modern-table">
        <thead>
          <tr class="text-left">
            <th class="py-2 px-3 rounded-l-lg">Student</th>
            <th class="py-2 px-3">Room</th>
            <th class="py-2 px-3 rounded-r-lg">Phone</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          <?php foreach ($recentStudents as $s): ?>
          <tr>
            <td class="py-3 px-3">
              <p class="font-medium text-gray-800"><?php echo htmlspecialchars($s['name']); ?></p>
              <p class="text-xs text-gray-400"><?php echo htmlspecialchars($s['student_id']); ?></p>
            </td>
            <td class="py-3 px-3 text-gray-600"><?php echo htmlspecialchars($s['room_number'] ?? '—'); ?></td>
            <td class="py-3 px-3 text-gray-600"><?php echo htmlspecialchars($s['phone']); ?></td>
          </tr>
          <?php endforeach; ?>
          <?php if (empty($recentStudents)): ?>
          <tr><td colspan="3" class="py-6 text-center text-gray-400">No students yet.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Recent complaints -->
  <div class="glass-card p-5">
    <div class="flex items-center justify-between mb-4">
      <h3 class="font-semibold text-gray-800">Recent Complaints</h3>
      <a href="view_complaints.php" class="text-xs font-medium text-indigo-600 hover:underline">View all</a>
    </div>
    <div class="space-y-3">
      <?php foreach ($recentComplaints as $c): ?>
      <div class="flex items-start gap-3 p-3 rounded-xl bg-gray-50">
        <div class="w-8 h-8 rounded-lg bg-rose-100 flex items-center justify-center shrink-0">
          <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-500"></i>
        </div>
        <div class="min-w-0">
          <p class="text-sm font-medium text-gray-800 truncate"><?php echo htmlspecialchars($c['subject']); ?></p>
          <p class="text-xs text-gray-400"><?php echo htmlspecialchars($c['name']); ?></p>
        </div>
        <span class="ml-auto badge-pill <?php echo $c['status'] === 'Resolved' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600'; ?>">
          <?php echo htmlspecialchars($c['status']); ?>
        </span>
      </div>
      <?php endforeach; ?>
      <?php if (empty($recentComplaints)): ?>
      <p class="text-center text-gray-400 text-sm py-6">No complaints logged.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php require_once("../includes/footer.php"); ?>
