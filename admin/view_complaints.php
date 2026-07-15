<?php
require_once("../includes/auth_check.php");
require_once("../config/db.php");

if (isset($_GET['resolve'])) {
    $stmt = $connection->prepare("UPDATE complaints SET status = 'Resolved' WHERE id = ?");
    $stmt->execute([(int) $_GET['resolve']]);
    header("Location: view_complaints.php");
    exit;
}

$complaints = $connection->query(
    "SELECT c.*, s.name, s.student_id FROM complaints c
     JOIN students s ON s.id = c.student_id
     ORDER BY c.status = 'Pending' DESC, c.id DESC"
)->fetchAll();

$pageTitle  = 'Complaints';
$activePage = 'complaints';
require_once("../includes/header.php");
?>

<div class="flex items-center justify-between mb-6">
  <div>
    <h2 class="text-xl font-bold text-gray-800">Complaints</h2>
    <p class="text-gray-500 text-sm">Review and resolve student complaints.</p>
  </div>
</div>

<div class="space-y-4">
  <?php foreach ($complaints as $c): ?>
  <div class="glass-card p-5 flex items-start gap-4">
    <div class="w-10 h-10 rounded-xl bg-rose-50 flex items-center justify-center shrink-0">
      <i data-lucide="message-square-warning" class="w-5 h-5 text-rose-500"></i>
    </div>
    <div class="flex-1 min-w-0">
      <div class="flex items-center gap-2 flex-wrap">
        <p class="font-semibold text-gray-800"><?php echo htmlspecialchars($c['subject']); ?></p>
        <span class="badge-pill <?php echo $c['status'] === 'Resolved' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600'; ?>">
          <?php echo htmlspecialchars($c['status']); ?>
        </span>
      </div>
      <p class="text-sm text-gray-500 mt-1"><?php echo nl2br(htmlspecialchars($c['description'])); ?></p>
      <p class="text-xs text-gray-400 mt-2">
        Filed by <?php echo htmlspecialchars($c['name']); ?> (<?php echo htmlspecialchars($c['student_id']); ?>)
        · <?php echo date('d M Y', strtotime($c['created_at'])); ?>
      </p>
    </div>
    <?php if ($c['status'] === 'Pending'): ?>
    <a href="view_complaints.php?resolve=<?php echo $c['id']; ?>"
      class="shrink-0 inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-2 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100">
      <i data-lucide="check" class="w-3.5 h-3.5"></i> Mark Resolved
    </a>
    <?php endif; ?>
  </div>
  <?php endforeach; ?>
  <?php if (empty($complaints)): ?>
  <div class="glass-card p-10 text-center text-gray-400">No complaints have been filed.</div>
  <?php endif; ?>
</div>

<?php require_once("../includes/footer.php"); ?>
