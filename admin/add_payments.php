<?php
require_once("../includes/auth_check.php");
require_once("../config/db.php");

if (isset($_POST['add_payment'])) {
    $stmt = $connection->prepare(
        "INSERT INTO payments (student_id, amount, payment_date, status) VALUES (?, ?, ?, ?)"
    );
    $stmt->execute([
        (int) $_POST['student_id'],
        (float) $_POST['amount'],
        $_POST['payment_date'],
        $_POST['status'],
    ]);
    header("Location: view_payments.php");
    exit;
}

$students = $connection->query("SELECT * FROM students ORDER BY name")->fetchAll();

$pageTitle  = 'Add Payment';
$activePage = 'payments';
require_once("../includes/header.php");
?>

<div class="max-w-lg mx-auto">
  <a href="view_payments.php" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-indigo-600 mb-4">
    <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Payments
  </a>

  <div class="glass-card p-7">
    <h3 class="text-lg font-bold text-gray-800 mb-1">Add Payment</h3>
    <p class="text-sm text-gray-500 mb-6">Record a rent payment for a student.</p>

    <form method="POST" class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Student</label>
        <select name="student_id" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm">
          <?php foreach ($students as $s): ?>
          <option value="<?php echo $s['id']; ?>"><?php echo htmlspecialchars($s['name']); ?> (<?php echo htmlspecialchars($s['student_id']); ?>)</option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Amount (Rs.)</label>
        <input type="number" min="0" step="0.01" name="amount" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Payment Date</label>
        <input type="date" name="payment_date" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
        <select name="status" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm">
          <option value="Paid">Paid</option>
          <option value="Pending">Pending</option>
        </select>
      </div>

      <button type="submit" name="add_payment" class="w-full py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-semibold text-sm shadow-lg shadow-indigo-600/20 hover:shadow-xl transition-shadow">
        Add Payment
      </button>
    </form>
  </div>
</div>

<?php require_once("../includes/footer.php"); ?>
