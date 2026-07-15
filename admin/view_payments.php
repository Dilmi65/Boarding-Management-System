<?php
require_once("../includes/auth_check.php");
require_once("../config/db.php");

$payments = $connection->query(
    "SELECT p.*, s.name, s.student_id FROM payments p
     JOIN students s ON s.id = p.student_id
     ORDER BY p.payment_date DESC, p.id DESC"
)->fetchAll();

$pageTitle  = 'Payments';
$activePage = 'payments';
require_once("../includes/header.php");
?>

<div class="flex items-center justify-between mb-6">
  <div>
    <h2 class="text-xl font-bold text-gray-800">Payments</h2>
    <p class="text-gray-500 text-sm">Track rent payments and outstanding balances.</p>
  </div>
  <a href="add_payments.php" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-violet-600 text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow-lg shadow-indigo-600/20 hover:shadow-xl transition-shadow">
    <i data-lucide="plus" class="w-4 h-4"></i> Add Payment
  </a>
</div>

<div class="glass-card overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-sm modern-table">
      <thead>
        <tr class="text-left">
          <th class="py-3 px-5">Student</th>
          <th class="py-3 px-5">Amount</th>
          <th class="py-3 px-5">Date</th>
          <th class="py-3 px-5">Status</th>
          <th class="py-3 px-5 text-right">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-50">
        <?php foreach ($payments as $p): ?>
        <tr>
          <td class="py-3 px-5">
            <p class="font-medium text-gray-800"><?php echo htmlspecialchars($p['name']); ?></p>
            <p class="text-xs text-gray-400"><?php echo htmlspecialchars($p['student_id']); ?></p>
          </td>
          <td class="py-3 px-5 text-gray-700 font-medium">Rs. <?php echo number_format($p['amount'], 2); ?></td>
          <td class="py-3 px-5 text-gray-600"><?php echo date('d M Y', strtotime($p['payment_date'])); ?></td>
          <td class="py-3 px-5">
            <span class="badge-pill <?php echo $p['status'] === 'Paid' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600'; ?>">
              <?php echo htmlspecialchars($p['status']); ?>
            </span>
          </td>
          <td class="py-3 px-5">
            <div class="flex justify-end gap-2">
              <a href="delete_payment.php?id=<?php echo $p['id']; ?>" onclick="return confirmDelete('Delete this payment record?')" class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-rose-100 flex items-center justify-center text-gray-600 hover:text-rose-600" title="Delete">
                <i data-lucide="trash-2" class="w-4 h-4"></i>
              </a>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($payments)): ?>
        <tr><td colspan="5" class="py-10 text-center text-gray-400">No payments recorded yet.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once("../includes/footer.php"); ?>
