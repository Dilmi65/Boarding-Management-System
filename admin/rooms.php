<?php
require_once("../includes/auth_check.php");
require_once("../config/db.php");

$rooms = $connection->query(
    "SELECT r.*, (SELECT COUNT(*) FROM students s WHERE s.room_id = r.id) AS occupants
     FROM rooms r ORDER BY r.id DESC"
)->fetchAll();

$pageTitle  = 'Rooms';
$activePage = 'rooms';
require_once("../includes/header.php");
?>

<div class="flex items-center justify-between mb-6">
  <div>
    <h2 class="text-xl font-bold text-gray-800">Rooms</h2>
    <p class="text-gray-500 text-sm">Manage room inventory, capacity and pricing.</p>
  </div>
  <a href="add_room.php" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-violet-600 text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow-lg shadow-indigo-600/20 hover:shadow-xl transition-shadow">
    <i data-lucide="plus" class="w-4 h-4"></i> Add Room
  </a>
</div>

<div class="glass-card overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-sm modern-table">
      <thead>
        <tr class="text-left">
          <th class="py-3 px-5">Room</th>
          <th class="py-3 px-5">Capacity</th>
          <th class="py-3 px-5">Occupants</th>
          <th class="py-3 px-5">Rent Price</th>
          <th class="py-3 px-5">Status</th>
          <th class="py-3 px-5 text-right">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-50">
        <?php foreach ($rooms as $r): ?>
        <tr>
          <td class="py-3 px-5 font-medium text-gray-800">Room <?php echo htmlspecialchars($r['room_number']); ?></td>
          <td class="py-3 px-5 text-gray-600"><?php echo (int)$r['capacity']; ?></td>
          <td class="py-3 px-5 text-gray-600"><?php echo (int)$r['occupants']; ?></td>
          <td class="py-3 px-5 text-gray-600">Rs. <?php echo number_format($r['rent_price'], 2); ?></td>
          <td class="py-3 px-5">
            <span class="badge-pill <?php echo $r['status'] === 'Available' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600'; ?>">
              <?php echo htmlspecialchars($r['status']); ?>
            </span>
          </td>
          <td class="py-3 px-5">
            <div class="flex justify-end gap-2">
              <a href="edit_room.php?id=<?php echo $r['id']; ?>" class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-indigo-100 flex items-center justify-center text-gray-600 hover:text-indigo-600" title="Edit">
                <i data-lucide="pencil" class="w-4 h-4"></i>
              </a>
              <a href="delete_room.php?id=<?php echo $r['id']; ?>" onclick="return confirmDelete('Delete this room?')" class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-rose-100 flex items-center justify-center text-gray-600 hover:text-rose-600" title="Delete">
                <i data-lucide="trash-2" class="w-4 h-4"></i>
              </a>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($rooms)): ?>
        <tr><td colspan="6" class="py-10 text-center text-gray-400">No rooms added yet.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once("../includes/footer.php"); ?>
