<?php
require_once("../includes/student_auth_check.php");
require_once("../config/db.php");

$sid = $_SESSION['student_db_id'];

$stmt = $connection->prepare("SELECT * FROM complaints WHERE student_id = ? ORDER BY id DESC");
$stmt->execute([$sid]);
$complaints = $stmt->fetchAll();

$pageTitle  = 'My Complaints';
$activePage = 'complaints';
require_once("../includes/student_header.php");
?>

<div class="flex items-center justify-between mb-6">
  <div>
    <h2 class="text-xl font-bold text-gray-800">My Complaints</h2>
    <p class="text-gray-500 text-sm">Everything you've reported, and its status.</p>
  </div>
  <a href="add_complaint.php" class="inline-flex items-center gap-2 bg-gradient-to-r from-teal-500 to-emerald-600 text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow-lg shadow-teal-600/20 hover:shadow-xl transition-shadow">
    <i data-lucide="plus" class="w-4 h-4"></i> Send Complaint
  </a>
</div>

<div class="space-y-4">
  <?php foreach ($complaints as $c): ?>
  <div class="glass-card p-5 flex items-start gap-4">
    <div class="w-10 h-10 rounded-xl bg-teal-50 flex items-center justify-center shrink-0">
      <i data-lucide="message-square" class="w-5 h-5 text-teal-600"></i>
    </div>
    <div class="flex-1 min-w-0">
      <div class="flex items-center gap-2 flex-wrap">
        <p class="font-semibold text-gray-800"><?php echo htmlspecialchars($c['subject']); ?></p>
        <span class="badge-pill <?php echo $c['status'] === 'Resolved' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600'; ?>">
          <?php echo htmlspecialchars($c['status']); ?>
        </span>
      </div>
      <p class="text-sm text-gray-500 mt-1"><?php echo nl2br(htmlspecialchars($c['description'])); ?></p>
      <p class="text-xs text-gray-400 mt-2">Filed on <?php echo date('d M Y', strtotime($c['created_at'])); ?></p>
    </div>
  </div>
  <?php endforeach; ?>
  <?php if (empty($complaints)): ?>
  <div class="glass-card p-10 text-center text-gray-400">
    You haven't filed any complaints yet.
    <a href="add_complaint.php" class="text-teal-600 font-medium hover:underline">Send one now</a>.
  </div>
  <?php endif; ?>
</div>

<?php require_once("../includes/footer.php"); ?>
