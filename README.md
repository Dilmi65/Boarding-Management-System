# Boarding Management System 

A boarding house system. core idea (rooms, students, payments, complaints) written with a clean
dashboard UI, secure database access, and a working login.

## Two portals

- **Admin panel** (`/index.php`) — rooms, students, payments, complaints, reports.
- **Student portal** (`/student/login.php`) — each student signs in with their
  Student ID + password to see their room, payment history, and to view or
  send their own complaints. Demo login: `STU001` / `student123`.

## Featurs

- **Modern UI**: Tailwind CSS-based dashboard with a dark sidebar, gradient
  accents, stat cards, and responsive layout (mobile-friendly sidebar toggle).
- **Security**: All SQL  uses PDO prepared statements (the original used
  raw string-interpolated queries, which were vulnerable to SQL injection).
  Passwords are hashed with `password_hash()` / verified with
  `password_verify()`.
- **Completed the app**: . This  fills in the full
  CRUD flow for Rooms, Students, Payments and Complaints, plus a real login
  system and dashboard with live stats and a revenue chart.
- **Added a student portal**: students  have their own login, dashboard,
  profile page (with password change), and can view/send their own
  complaints — separate from the admin side, with a teal color scheme so
  it's visually distinct from the admin's indigo theme.

## Setup

1. Create the database:
   ```
   mysql -u root -p < database.sql
   ```
2. Edit `config/db.php` with your MySQL credentials if they differ from the
   defaults (`root` / no password / `localhost`).
3. Serve the folder with PHP's built-in server (or place it in your
   Apache/Nginx web root):
   ```
   php -S localhost:8000
   ```
4. Visit `http://localhost:8000`:
   - **Admin login:** `admin` / `admin123`
   - **Student login** (at `/student/login.php`): Student ID `STU001` / `student123`

## Structure

```
├── index.php                 Admin login page
├── auth/                      login.php / logout.php (admin)
├── config/db.php              PDO database connection
├── includes/                  Shared header/sidebar/footer + auth guards (admin & student)
├── admin/                     Dashboard, Rooms, Students, Payments, Complaints, Reports
├── student/                   Student login, dashboard, profile, my complaints, send complaint
├── assets/                    css/js
└── database.sql               Schema + sample data
```

## Notes

- When an admin adds a student (`admin/add_students.php`), they now also set
  that student's initial portal password — give it to the student so they
  can sign in and change it themselves from **My Profile**.
- Change the default admin password after first login (there's no in-app
  password-change screen for admins yet — update the `users` table directly,
  hashing the new password with `password_hash()`).
- Tailwind and Chart.js are loaded from CDN, so an internet connection is
  needed for the styling/charts to render.
