<?php
require_once("../includes/student_auth_check.php");
require_once("../config/db.php");

$sid = $_SESSION['student_db_id'];

$stmt = $connection->prepare(
    "SELECT s.*, r.room_number, r.rent_price, r.capacity FROM students s
     LEFT JOIN rooms r ON r.id = s.room_id WHERE s.id = ?"
);
$stmt->execute([$sid]);
$student = $stmt->fetch();

$paidTotal = $connection->prepare("SELECT COALESCE(SUM(amount),0) t FROM payments WHERE student_id = ? AND status='Paid'");
$paidTotal->execute([$sid]);
$paidTotal = $paidTotal->fetch()['t'];

$pendingTotal = $connection->prepare("SELECT COALESCE(SUM(amount),0) t FROM payments WHERE student_id = ? AND status='Pending'");
$pendingTotal->execute([$sid]);
$pendingTotal = $pendingTotal->fetch()['t'];

$complaintsCount = $connection->prepare("SELECT COUNT(*) c FROM complaints WHERE student_id = ? AND status='Pending'");
$complaintsCount->execute([$sid]);
$complaintsCount = $complaintsCount->fetch()['c'];

$recentPayments = $connection->prepare("SELECT * FROM payments WHERE student_id = ? ORDER BY payment_date DESC LIMIT 5");
$recentPayments->execute([$sid]);
$recentPayments = $recentPayments->fetchAll();

$recentComplaints = $connection->prepare("SELECT * FROM complaints WHERE student_id = ? ORDER BY id DESC LIMIT 5");
$recentComplaints->execute([$sid]);
$recentComplaints = $recentComplaints->fetchAll();

$pageTitle  = 'Dashboard';
$activePage = 'dashboard';
require_once("../includes/student_header.php");
?>

<h2 class="text-xl font-bold text-gray-800 mb-1">Welcome, <?php echo htmlspecialchars($student['name']); ?> 👋</h2>
<p class="text-gray-500 text-sm mb-6">Here's a summary of your boarding account.</p>

<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">

  <div class="stat-card glass-card p-5 flex items-center gap-4">
    <div class="w-12 h-12 rounded-xl bg-teal-50 flex items-center justify-center">
      <i data-lucide="door-open" class="w-6 h-6 text-teal-600"></i>
    </div>
    <div>
      <p class="text-gray-400 text-xs font-medium">My Room</p>
      <p class="text-2xl font-bold text-gray-800"><?php echo $student['room_number'] ? htmlspecialchars($student['room_number']) : '—'; ?></p>
    </div>
  </div>

  <div class="stat-card glass-card p-5 flex items-center gap-4">
    <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center">
      <i data-lucide="check-circle" class="w-6 h-6 text-emerald-600"></i>
    </div>
    <div>
      <p class="text-gray-400 text-xs font-medium">Total Paid</p>
      <p class="text-2xl font-bold text-gray-800">Rs. <?php echo number_format($paidTotal, 2); ?></p>
    </div>
  </div>

  <div class="stat-card glass-card p-5 flex items-center gap-4">
    <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center">
      <i data-lucide="clock" class="w-6 h-6 text-amber-600"></i>
    </div>
    <div>
      <p class="text-gray-400 text-xs font-medium">Pending Balance</p>
      <p class="text-2xl font-bold text-gray-800">Rs. <?php echo number_format($pendingTotal, 2); ?></p>
    </div>
  </div>

  <div class="stat-card glass-card p-5 flex items-center gap-4">
    <div class="w-12 h-12 rounded-xl bg-rose-50 flex items-center justify-center">
      <i data-lucide="message-square-warning" class="w-6 h-6 text-rose-600"></i>
    </div>
    <div>
      <p class="text-gray-400 text-xs font-medium">Open Complaints</p>
      <p class="text-2xl font-bold text-gray-800"><?php echo $complaintsCount; ?></p>
    </div>
  </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

  <div class="glass-card p-5">
    <div class="flex items-center justify-between mb-4">
      <h3 class="font-semibold text-gray-800">Recent Payments</h3>
    </div>
    <div class="space-y-3">
      <?php foreach ($recentPayments as $p): ?>
      <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50">
        <div>
          <p class="text-sm font-medium text-gray-800">Rs. <?php echo number_format($p['amount'], 2); ?></p>
          <p class="text-xs text-gray-400"><?php echo date('d M Y', strtotime($p['payment_date'])); ?></p>
        </div>
        <span class="badge-pill <?php echo $p['status'] === 'Paid' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600'; ?>">
          <?php echo htmlspecialchars($p['status']); ?>
        </span>
      </div>
      <?php endforeach; ?>
      <?php if (empty($recentPayments)): ?>
      <p class="text-center text-gray-400 text-sm py-6">No payment records yet.</p>
      <?php endif; ?>
    </div>
  </div>

  <div class="glass-card p-5">
    <div class="flex items-center justify-between mb-4">
      <h3 class="font-semibold text-gray-800">Recent Complaints</h3>
      <a href="add_complaint.php" class="text-xs font-medium text-teal-600 hover:underline">Send new</a>
    </div>
    <div class="space-y-3">
      <?php foreach ($recentComplaints as $c): ?>
      <div class="flex items-start gap-3 p-3 rounded-xl bg-gray-50">
        <div class="min-w-0">
          <p class="text-sm font-medium text-gray-800 truncate"><?php echo htmlspecialchars($c['subject']); ?></p>
          <p class="text-xs text-gray-400"><?php echo date('d M Y', strtotime($c['created_at'])); ?></p>
        </div>
        <span class="ml-auto badge-pill <?php echo $c['status'] === 'Resolved' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600'; ?>">
          <?php echo htmlspecialchars($c['status']); ?>
        </span>
      </div>
      <?php endforeach; ?>
      <?php if (empty($recentComplaints)): ?>
      <p class="text-center text-gray-400 text-sm py-6">No complaints filed yet.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php require_once("../includes/footer.php"); ?>
