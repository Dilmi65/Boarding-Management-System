<?php
require_once("../includes/student_auth_check.php");
require_once("../config/db.php");

$sid = $_SESSION['student_db_id'];
$success = false;

if (isset($_POST['submit_complaint'])) {
    $stmt = $connection->prepare(
        "INSERT INTO complaints (student_id, subject, description) VALUES (?, ?, ?)"
    );
    $stmt->execute([$sid, trim($_POST['subject']), trim($_POST['description'])]);
    $success = true;
}

$pageTitle  = 'Send Complaint';
$activePage = 'add_complaint';
require_once("../includes/student_header.php");
?>

<div class="max-w-lg mx-auto">
  <div class="glass-card p-7">
    <?php if ($success): ?>
      <div class="text-center py-6">
        <div class="w-14 h-14 rounded-full bg-emerald-50 flex items-center justify-center mx-auto mb-4">
          <i data-lucide="check-circle" class="w-7 h-7 text-emerald-500"></i>
        </div>
        <h2 class="text-lg font-bold text-gray-800 mb-1">Complaint sent</h2>
        <p class="text-sm text-gray-500 mb-6">The warden will review it shortly.</p>
        <div class="flex justify-center gap-4">
          <a href="complaints.php" class="text-sm font-semibold text-teal-600 hover:underline">View my complaints</a>
          <a href="add_complaint.php" class="text-sm font-semibold text-gray-500 hover:underline">Send another</a>
        </div>
      </div>
    <?php else: ?>
      <h3 class="text-lg font-bold text-gray-800 mb-1">Send a Complaint</h3>
      <p class="text-sm text-gray-500 mb-6">Let us know about any issue with your room or facilities.</p>

      <form method="POST" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1.5">Subject</label>
          <input type="text" name="subject" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-teal-500/40 focus:border-teal-400 text-sm">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
          <textarea name="description" rows="5" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-teal-500/40 focus:border-teal-400 text-sm"></textarea>
        </div>
        <button type="submit" name="submit_complaint" class="w-full py-2.5 rounded-xl bg-gradient-to-r from-teal-500 to-emerald-600 text-white font-semibold text-sm shadow-lg shadow-teal-600/20 hover:shadow-xl transition-shadow">
          Send Complaint
        </button>
      </form>
    <?php endif; ?>
  </div>
</div>

<?php require_once("../includes/footer.php"); ?>
