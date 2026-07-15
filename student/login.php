<?php
session_start();
if (isset($_SESSION['role']) && $_SESSION['role'] === 'student') {
    header("Location: dashboard.php");
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
<title>Student Sign In · Boarding System</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="min-h-screen flex items-center justify-center p-4" style="background:radial-gradient(circle at top left, #14b8a6 0%, #0f2e2c 55%, #0a1f1d 100%);">

<div class="w-full max-w-md">
  <div class="flex items-center gap-3 mb-6 justify-center">
    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-teal-400 to-emerald-500 flex items-center justify-center">
      <i data-lucide="graduation-cap" class="w-6 h-6 text-white"></i>
    </div>
    <span class="font-bold text-lg text-white">Boarding<span class="text-teal-300">Sys</span></span>
  </div>

  <div class="bg-white rounded-3xl shadow-2xl p-8 sm:p-10">
    <h1 class="text-2xl font-bold text-gray-800">Student Sign In</h1>
    <p class="text-gray-500 text-sm mt-1 mb-6">Access your room, payments and complaints.</p>

    <?php if ($error): ?>
    <div class="mb-5 flex items-center gap-2 bg-red-50 text-red-600 border border-red-100 rounded-xl px-4 py-3 text-sm">
      <i data-lucide="alert-circle" class="w-4 h-4"></i>
      <?php echo htmlspecialchars($error); ?>
    </div>
    <?php endif; ?>

    <form action="process_login.php" method="POST" class="space-y-5">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Student ID</label>
        <div class="relative">
          <i data-lucide="badge" class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
          <input type="text" name="student_id" required autofocus placeholder="e.g. STU001"
            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-teal-500/40 focus:border-teal-400 text-sm">
        </div>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
        <div class="relative">
          <i data-lucide="lock" class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
          <input type="password" name="password" required
            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-teal-500/40 focus:border-teal-400 text-sm">
        </div>
      </div>
      <button type="submit"
        class="w-full py-2.5 rounded-xl bg-gradient-to-r from-teal-500 to-emerald-600 text-white font-semibold text-sm shadow-lg shadow-teal-600/25 hover:shadow-xl transition-shadow">
        Sign In
      </button>
    </form>

    <p class="text-xs text-gray-400 mt-8 text-center">Demo login — Student ID <strong>STU001</strong>, password <strong>student123</strong></p>
  </div>

  <p class="text-center text-xs text-teal-100/60 mt-5">
    <a href="../index.php" class="hover:text-white">← Admin Login</a>
  </p>
</div>

<script>lucide.createIcons();</script>
</body>
</html>
