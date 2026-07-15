<?php
// Expects: $pageTitle, $activePage (set by the including page before this include)
$pageTitle  = $pageTitle  ?? 'Boarding System';
$activePage = $activePage ?? '';

function nav_active($page, $activePage) {
    return $page === $activePage ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($pageTitle); ?> · Boarding System</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="min-h-screen">

<!-- Mobile overlay -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black/40 z-30 hidden lg:hidden"></div>

<div class="flex min-h-screen">

  <!-- Sidebar -->
  <aside id="sidebar" class="sidebar fixed lg:static z-40 w-64 min-h-screen -translate-x-full lg:translate-x-0 transition-transform duration-200 flex flex-col">
    <div class="flex items-center gap-3 px-6 py-6">
      <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-500 flex items-center justify-center shadow-lg shadow-indigo-900/40">
        <i data-lucide="building-2" class="w-5 h-5 text-white"></i>
      </div>
      <div>
        <p class="text-white font-bold leading-tight">Boarding<span class="text-indigo-300">Sys</span></p>
        <p class="text-[11px] text-indigo-200/60">Admin Panel</p>
      </div>
    </div>

    <nav class="flex-1 px-4 space-y-1 overflow-y-auto pb-6">
      <p class="px-3 pt-2 pb-1 text-[11px] uppercase tracking-wider text-indigo-200/40 font-semibold">Overview</p>
      <a href="dashboard.php" class="nav-link <?php echo nav_active('dashboard', $activePage); ?>">
        <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Dashboard
      </a>

      <p class="px-3 pt-4 pb-1 text-[11px] uppercase tracking-wider text-indigo-200/40 font-semibold">Management</p>
      <a href="rooms.php" class="nav-link <?php echo nav_active('rooms', $activePage); ?>">
        <i data-lucide="door-open" class="w-4 h-4"></i> Rooms
      </a>
      <a href="view_students.php" class="nav-link <?php echo nav_active('students', $activePage); ?>">
        <i data-lucide="users" class="w-4 h-4"></i> Students
      </a>
      <a href="view_payments.php" class="nav-link <?php echo nav_active('payments', $activePage); ?>">
        <i data-lucide="wallet" class="w-4 h-4"></i> Payments
      </a>
      <a href="view_complaints.php" class="nav-link <?php echo nav_active('complaints', $activePage); ?>">
        <i data-lucide="message-square-warning" class="w-4 h-4"></i> Complaints
      </a>

      <p class="px-3 pt-4 pb-1 text-[11px] uppercase tracking-wider text-indigo-200/40 font-semibold">Insights</p>
      <a href="reports.php" class="nav-link <?php echo nav_active('reports', $activePage); ?>">
        <i data-lucide="bar-chart-3" class="w-4 h-4"></i> Reports
      </a>
    </nav>

    <div class="px-4 pb-5">
      <a href="../auth/logout.php" class="nav-link hover:bg-red-500/20 hover:text-red-200">
        <i data-lucide="log-out" class="w-4 h-4"></i> Logout
      </a>
    </div>
  </aside>

  <!-- Main -->
  <div class="flex-1 flex flex-col min-w-0">

    <!-- Topbar -->
    <header class="sticky top-0 z-20 bg-white/80 backdrop-blur border-b border-gray-100 px-4 sm:px-8 py-4 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <button id="sidebarToggle" class="lg:hidden w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center">
          <i data-lucide="menu" class="w-5 h-5 text-gray-600"></i>
        </button>
        <div>
          <h1 class="text-lg sm:text-xl font-bold text-gray-800"><?php echo htmlspecialchars($pageTitle); ?></h1>
        </div>
      </div>
      <div class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-500 to-violet-500 flex items-center justify-center text-white text-sm font-semibold">
          <?php echo strtoupper(substr($_SESSION['username'] ?? 'A', 0, 1)); ?>
        </div>
        <span class="hidden sm:block text-sm font-medium text-gray-700"><?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?></span>
      </div>
    </header>

    <main class="flex-1 p-4 sm:p-8 fade-in">
