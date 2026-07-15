<?php
require_once("../includes/auth_check.php");
require_once("../config/db.php");

$stmt = $connection->prepare("SELECT * FROM rooms WHERE id = ?");
$stmt->execute([$_GET['id'] ?? 0]);
$room = $stmt->fetch();

if (!$room) {
    header("Location: rooms.php");
    exit;
}

$pageTitle  = 'Edit Room';
$activePage = 'rooms';
require_once("../includes/header.php");
?>

<div class="max-w-lg mx-auto">
  <a href="rooms.php" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-indigo-600 mb-4">
    <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Rooms
  </a>

  <div class="glass-card p-7">
    <h3 class="text-lg font-bold text-gray-800 mb-1">Edit Room</h3>
    <p class="text-sm text-gray-500 mb-6">Update details for Room <?php echo htmlspecialchars($room['room_number']); ?>.</p>

    <form action="update_room.php" method="POST" class="space-y-4">
      <input type="hidden" name="id" value="<?php echo (int) $room['id']; ?>">

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Room Number</label>
        <input type="text" name="room_number" required value="<?php echo htmlspecialchars($room['room_number']); ?>"
          class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm">
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1.5">Capacity</label>
          <input type="number" min="1" name="capacity" required value="<?php echo (int) $room['capacity']; ?>"
            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1.5">Rent Price (Rs.)</label>
          <input type="number" min="0" step="0.01" name="rent_price" required value="<?php echo htmlspecialchars($room['rent_price']); ?>"
            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm">
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
        <select name="status" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm">
          <option value="Available" <?php echo $room['status'] === 'Available' ? 'selected' : ''; ?>>Available</option>
          <option value="Full" <?php echo $room['status'] === 'Full' ? 'selected' : ''; ?>>Full</option>
        </select>
      </div>

      <button type="submit" class="w-full py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-semibold text-sm shadow-lg shadow-indigo-600/20 hover:shadow-xl transition-shadow">
        Update Room
      </button>
    </form>
  </div>
</div>

<?php require_once("../includes/footer.php"); ?>
