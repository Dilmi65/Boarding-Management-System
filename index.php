<?php
session_start();
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header("Location: admin/dashboard.php");
    exit;
}

$error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign In · Boarding System</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="auth-wrapper flex items-center justify-center p-4">

<div class="w-full max-w-4xl grid lg:grid-cols-2 rounded-3xl overflow-hidden shadow-2xl">

  <!-- Left brand panel -->
  <div class="hidden lg:flex flex-col justify-between bg-white/5 backdrop-blur-md border border-white/10 p-10 text-white">
    <div class="flex items-center gap-3">
      <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-500 flex items-center justify-center">
        <i data-lucide="building-2" class="w-6 h-6"></i>
      </div>
      <span class="font-bold text-lg">Boarding<span class="text-indigo-300">Sys</span></span>
    </div>
    <div>
      <h2 class="text-3xl font-extrabold leading-tight mb-3">Manage your boarding house, effortlessly.</h2>
      <p class="text-indigo-100/70 text-sm leading-relaxed">Rooms, students, payments and complaints — all in one clean, modern dashboard built for administrators.</p>
    </div>
    <div class="flex gap-6 text-indigo-100/60 text-xs">
      <div><i data-lucide="door-open" class="w-4 h-4 inline mb-1"></i><br>Room Management</div>
      <div><i data-lucide="wallet" class="w-4 h-4 inline mb-1"></i><br>Payment Tracking</div>
      <div><i data-lucide="bar-chart-3" class="w-4 h-4 inline mb-1"></i><br>Live Reports</div>
    </div>
  </div>

  <!-- Right form panel -->
  <div class="bg-white p-8 sm:p-12 flex flex-col justify-center">
    <div class="mb-8">
      <h1 class="text-2xl font-bold text-gray-800">Welcome back</h1>
      <p class="text-gray-500 text-sm mt-1">Sign in to access the admin dashboard.</p>
    </div>

    <?php if ($error): ?>
    <div class="mb-5 flex items-center gap-2 bg-red-50 text-red-600 border border-red-100 rounded-xl px-4 py-3 text-sm">
      <i data-lucide="alert-circle" class="w-4 h-4"></i>
      <?php echo htmlspecialchars($error); ?>
    </div>
    <?php endif; ?>

    <form action="auth/login.php" method="POST" class="space-y-5">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Username</label>
        <div class="relative">
          <i data-lucide="user" class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
          <input type="text" name="username" required autofocus
            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm">
        </div>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
        <div class="relative">
          <i data-lucide="lock" class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
          <input type="password" name="password" required
            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm">
        </div>
      </div>
      <button type="submit"
        class="w-full py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-semibold text-sm shadow-lg shadow-indigo-600/25 hover:shadow-xl hover:shadow-indigo-600/30 transition-shadow">
        Sign In
      </button>
    </form>

    <p class="text-xs text-gray-400 mt-8 text-center">Default demo login — admin / admin123</p>
    <p class="text-xs text-gray-400 mt-2 text-center">
      Student? <a href="student/login.php" class="text-indigo-600 font-medium hover:underline">Sign in to your portal</a>
    </p>
  </div>
</div>

<script>lucide.createIcons();</script>
</body>
</html>
