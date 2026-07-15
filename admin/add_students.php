<?php
require_once("../includes/auth_check.php");
require_once("../config/db.php");

$error = '';

if (isset($_POST['add_student'])) {
    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $roomId = $_POST['room_id'] !== '' ? (int) $_POST['room_id'] : null;
    $sid   = trim($_POST['sid']);
    $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $connection->prepare(
        "INSERT INTO students (name, email, phone, room_id, student_id, password) VALUES (?, ?, ?, ?, ?, ?)"
    );
    try {
        $stmt->execute([$name, $email, $phone, $roomId, $sid, $passwordHash]);
        header("Location: view_students.php");
        exit;
    } catch (PDOException $e) {
        $error = "Could not add student. The Student ID might already be in use.";
    }
}

$rooms = $connection->query("SELECT * FROM rooms ORDER BY room_number")->fetchAll();

$pageTitle  = 'Add Student';
$activePage = 'students';
require_once("../includes/header.php");
?>

<div class="max-w-lg mx-auto">
  <a href="view_students.php" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-indigo-600 mb-4">
    <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Students
  </a>

  <div class="glass-card p-7">
    <h3 class="text-lg font-bold text-gray-800 mb-1">Add Student</h3>
    <p class="text-sm text-gray-500 mb-6">Register a new boarder.</p>

    <?php if ($error): ?>
    <div class="mb-5 flex items-center gap-2 bg-red-50 text-red-600 border border-red-100 rounded-xl px-4 py-3 text-sm">
      <i data-lucide="alert-circle" class="w-4 h-4"></i> <?php echo htmlspecialchars($error); ?>
    </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name</label>
        <input type="text" name="name" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
        <input type="email" name="email" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Phone</label>
        <input type="text" name="phone" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Room</label>
        <select name="room_id" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm">
          <option value="">Unassigned</option>
          <?php foreach ($rooms as $r): ?>
          <option value="<?php echo $r['id']; ?>">Room <?php echo htmlspecialchars($r['room_number']); ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Student ID</label>
        <input type="text" name="sid" required placeholder="e.g. STU004" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm">
        <p class="text-xs text-gray-400 mt-1">This is what the student will use to log in to their portal.</p>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Portal Password</label>
        <input type="text" name="password" required minlength="6" placeholder="Given to the student to log in" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm">
        <p class="text-xs text-gray-400 mt-1">They can change this later from their own profile.</p>
      </div>

      <button type="submit" name="add_student" class="w-full py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-semibold text-sm shadow-lg shadow-indigo-600/20 hover:shadow-xl transition-shadow">
        Add Student
      </button>
    </form>
  </div>
</div>

<?php require_once("../includes/footer.php"); ?>
