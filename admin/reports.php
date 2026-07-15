<?php
require_once("../includes/auth_check.php");
require_once("../config/db.php");

$totalRevenue  = $connection->query("SELECT COALESCE(SUM(amount),0) t FROM payments WHERE status='Paid'")->fetch()['t'];
$pendingAmount = $connection->query("SELECT COALESCE(SUM(amount),0) t FROM payments WHERE status='Pending'")->fetch()['t'];
$occupied      = $connection->query("SELECT COUNT(*) c FROM rooms WHERE status='Full'")->fetch()['c'];
$available     = $connection->query("SELECT COUNT(*) c FROM rooms WHERE status='Available'")->fetch()['c'];
$resolved      = $connection->query("SELECT COUNT(*) c FROM complaints WHERE status='Resolved'")->fetch()['c'];
$pendingCompl  = $connection->query("SELECT COUNT(*) c FROM complaints WHERE status='Pending'")->fetch()['c'];

$monthly = $connection->query(
    "SELECT DATE_FORMAT(payment_date, '%Y-%m') ym, SUM(amount) total
     FROM payments WHERE status='Paid'
     GROUP BY ym ORDER BY ym ASC LIMIT 12"
)->fetchAll();

$labels = array_map(fn($r) => $r['ym'], $monthly);
$values = array_map(fn($r) => (float) $r['total'], $monthly);

$pageTitle  = 'Reports';
$activePage = 'reports';
require_once("../includes/header.php");
?>

<h2 class="text-xl font-bold text-gray-800 mb-1">Reports</h2>
<p class="text-gray-500 text-sm mb-6">A quick snapshot of revenue, occupancy and complaints.</p>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
  <div class="glass-card p-5">
    <p class="text-xs text-gray-400 font-medium mb-1">Collected Revenue</p>
    <p class="text-2xl font-bold text-emerald-600">Rs. <?php echo number_format($totalRevenue, 2); ?></p>
    <p class="text-xs text-gray-400 mt-1">Pending: Rs. <?php echo number_format($pendingAmount, 2); ?></p>
  </div>
  <div class="glass-card p-5">
    <p class="text-xs text-gray-400 font-medium mb-1">Room Occupancy</p>
    <p class="text-2xl font-bold text-indigo-600"><?php echo $occupied; ?> Full</p>
    <p class="text-xs text-gray-400 mt-1"><?php echo $available; ?> rooms available</p>
  </div>
  <div class="glass-card p-5">
    <p class="text-xs text-gray-400 font-medium mb-1">Complaints</p>
    <p class="text-2xl font-bold text-rose-600"><?php echo $pendingCompl; ?> Pending</p>
    <p class="text-xs text-gray-400 mt-1"><?php echo $resolved; ?> resolved</p>
  </div>
</div>

<div class="glass-card p-6">
  <h3 class="font-semibold text-gray-800 mb-4">Monthly Revenue</h3>
  <canvas id="revenueChart" height="90"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
  const ctx = document.getElementById('revenueChart');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: <?php echo json_encode($labels); ?>,
      datasets: [{
        label: 'Revenue (Rs.)',
        data: <?php echo json_encode($values); ?>,
        backgroundColor: 'rgba(99,102,241,0.75)',
        borderRadius: 8,
        maxBarThickness: 42,
      }]
    },
    options: {
      plugins: { legend: { display: false } },
      scales: {
        y: { beginAtZero: true, grid: { color: '#f1f1fa' } },
        x: { grid: { display: false } }
      }
    }
  });
</script>

<?php require_once("../includes/footer.php"); ?>
