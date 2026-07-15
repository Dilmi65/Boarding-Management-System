<?php
// Expects: $pageTitle, $activePage (set by the including page before this include)
$pageTitle  = $pageTitle  ?? 'Student Portal';
$activePage = $activePage ?? '';

function s_nav_active($page, $activePage) {
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
<style>
  .sidebar.student-sidebar { background: linear-gradient(180deg, #0f2e2c 0%, #12403c 100%); }
  .student-sidebar .nav-link.active { background: linear-gradient(135deg, #14b8a6, #0d9488); box-shadow: 0 4px 14px rgba(13,148,136,0.35); }
</style>
</head>
<body class="min-h-screen">

<div id="sidebarOverlay" class="fixed inset-0 bg-black/40 z-30 hidden lg:hidden"></div>

<div class="flex min-h-screen">

  <aside id="sidebar" class="sidebar student-sidebar fixed lg:static z-40 w-64 min-h-screen -translate-x-full lg:translate-x-0 transition-transform duration-200 flex flex-col">
    <div class="flex items-center gap-3 px-6 py-6">
      <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-teal-400 to-emerald-500 flex items-center justify-center shadow-lg shadow-teal-900/40">
        <i data-lucide="graduation-cap" class="w-5 h-5 text-white"></i>
      </div>
      <div>
        <p class="text-white font-bold leading-tight">Boarding<span class="text-teal-300">Sys</span></p>
        <p class="text-[11px] text-teal-200/60">Student Portal</p>
      </div>
    </div>

    <nav class="flex-1 px-4 space-y-1 overflow-y-auto pb-6">
      <a href="dashboard.php" class="nav-link <?php echo s_nav_active('dashboard', $activePage); ?>">
        <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Dashboard
      </a>
      <a href="profile.php" class="nav-link <?php echo s_nav_active('profile', $activePage); ?>">
        <i data-lucide="user-circle" class="w-4 h-4"></i> My Profile
      </a>
      <a href="complaints.php" class="nav-link <?php echo s_nav_active('complaints', $activePage); ?>">
        <i data-lucide="list-checks" class="w-4 h-4"></i> My Complaints
      </a>
      <a href="add_complaint.php" class="nav-link <?php echo s_nav_active('add_complaint', $activePage); ?>">
        <i data-lucide="send" class="w-4 h-4"></i> Send Complaint
      </a>
    </nav>

    <div class="px-4 pb-5">
      <a href="logout.php" class="nav-link hover:bg-red-500/20 hover:text-red-200">
        <i data-lucide="log-out" class="w-4 h-4"></i> Logout
      </a>
    </div>
  </aside>

  <div class="flex-1 flex flex-col min-w-0">

    <header class="sticky top-0 z-20 bg-white/80 backdrop-blur border-b border-gray-100 px-4 sm:px-8 py-4 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <button id="sidebarToggle" class="lg:hidden w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center">
          <i data-lucide="menu" class="w-5 h-5 text-gray-600"></i>
        </button>
        <h1 class="text-lg sm:text-xl font-bold text-gray-800"><?php echo htmlspecialchars($pageTitle); ?></h1>
      </div>
      <div class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-teal-400 to-emerald-500 flex items-center justify-center text-white text-sm font-semibold">
          <?php echo strtoupper(substr($_SESSION['student_name'] ?? 'S', 0, 1)); ?>
        </div>
        <span class="hidden sm:block text-sm font-medium text-gray-700"><?php echo htmlspecialchars($_SESSION['student_name'] ?? 'Student'); ?></span>
      </div>
    </header>

    <main class="flex-1 p-4 sm:p-8 fade-in">
