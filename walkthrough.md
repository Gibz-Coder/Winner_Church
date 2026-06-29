# Winner Church Inventory & Asset Monitoring System - Walkthrough

This walkthrough outlines the full implementation of the complete Inventory Management and Asset Monitoring System for Winner Church. The implementation uses the **Vue 3 + Inertia v3 + TS + Tailwind CSS v4** starter kit.

---

## 1. Database & Schema Updates

We created and populated several new migrations to support the church's tracking operations:
- **`roles` & `permissions` tables**: Pivot tables connecting users, roles, and capability gates (`manage_assets`, `borrow_assets`, `approve_borrowing`, `process_returns`, `view_reports`, `view_audit_logs`, `manage_maintenance`, `manage_disposed`).
- **`borrow_requests` table**: Tracks request date, expected return, actual return dates, borrow/return conditions, remarks, and authorizing admin ID.
- **`maintenance_logs` table**: Tracks preventive checkups, calibration, servicing costs, technician/agency names, and resolution details.
- **`audit_logs` table**: Tracks system actions performed by administrators, linking to affected entities morphically with IP addresses.
- **`assets` table**: Augmented with `description`, `assigned_ministry`, `image`, and `qr_code` link.

---

## 2. Models & Enums

- **[AssetStatus.php](file:///c:/ProjectDev/Winner_Church/app/Enums/AssetStatus.php)**: Enumerates `Available`, `Borrowed`, `Reserved`, `UnderMaintenance`, `Lost`, and `Disposed` states.
- **Models**: Created [Role.php](file:///c:/ProjectDev/Winner_Church/app/Models/Role.php), [Permission.php](file:///c:/ProjectDev/Winner_Church/app/Models/Permission.php), [BorrowRequest.php](file:///c:/ProjectDev/Winner_Church/app/Models/BorrowRequest.php), [MaintenanceLog.php](file:///c:/ProjectDev/Winner_Church/app/Models/MaintenanceLog.php), and [AuditLog.php](file:///c:/ProjectDev/Winner_Church/app/Models/AuditLog.php).
- **[User.php](file:///c:/ProjectDev/Winner_Church/app/Models/User.php)**: Added dynamic role checks (`hasRole`, `hasPermission`) and relation bindings.

---

## 3. Backend Controllers & API Routes

- **[AssetController.php](file:///c:/ProjectDev/Winner_Church/app/Http/Controllers/AssetController.php)**: Implements standard asset CRUD, authorization gates, audit trails, and automatic QR code generation pointing to the asset's detail page via QRServer.
- **[BorrowRequestController.php](file:///c:/ProjectDev/Winner_Church/app/Http/Controllers/BorrowRequestController.php)**: Implements borrowing lifecycle gates (requesting -> reserving -> checkout/approval -> return inspections).
- **[MaintenanceController.php](file:///c:/ProjectDev/Winner_Church/app/Http/Controllers/MaintenanceController.php)**: Allows scheduling, starting, and completing asset repairs, tracking costs and changing asset status.
- **[UserController.php](file:///c:/ProjectDev/Winner_Church/app/Http/Controllers/UserController.php)**: Manages role allocation panel updates.
- **[ReportController.php](file:///c:/ProjectDev/Winner_Church/app/Http/Controllers/ReportController.php)**: Prepares category breakdown values, top borrowed utilization metrics, and stream downloads for Inventory, Borrowing, and Maintenance history in CSV format.
- **[AuditLogController.php](file:///c:/ProjectDev/Winner_Church/app/Http/Controllers/AuditLogController.php)**: Feeds administrative grid lists.

---

## 4. Frontend SPA Pages (Inertia + Vue 3)

The interface is built using standard Tailwind CSS v4 styling with full dark mode support:
- **[Welcome.vue](file:///c:/ProjectDev/Winner_Church/resources/js/pages/Welcome.vue)**: Renders a premium responsive landing page highlighting church features and login/dashboard action buttons.
- **[Dashboard.vue](file:///c:/ProjectDev/Winner_Church/resources/js/pages/Dashboard.vue)**: Renders stats cards (Total, Available, Borrowed, Reserved, Under Maintenance, Disposed, Lost, Pending Requests) with live timelines and categories progress bars.
- **[AppSidebar.vue](file:///c:/ProjectDev/Winner_Church/resources/js/components/AppSidebar.vue)**: Generates sidebar navigation links dynamically based on the current user's role, hiding admin panels from normal members.
- **[borrow_requests/Index.vue](file:///c:/ProjectDev/Winner_Church/resources/js/pages/borrow_requests/Index.vue)**: Full interface for members to request borrowings, and admins to approve, reject, or process returns with condition checklists.
- **[maintenance/Index.vue](file:///c:/ProjectDev/Winner_Church/resources/js/pages/maintenance/Index.vue)**: Schedules and updates status/cost logs for asset repairs.
- **[users/Index.vue](file:///c:/ProjectDev/Winner_Church/resources/js/pages/users/Index.vue)**: Multi-checkbox dialog for assigning roles.
- **[reports/Index.vue](file:///c:/ProjectDev/Winner_Church/resources/js/pages/reports/Index.vue)**: Summarizes acquisition costs, item counts, top utilized assets, and supports full PDF printable formats and CSV downloads.
- **[audit_logs/Index.vue](file:///c:/ProjectDev/Winner_Church/resources/js/pages/audit_logs/Index.vue)**: Renders administrative audit trail tables.
- **[Asset Components](file:///c:/ProjectDev/Winner_Church/resources/js/pages/assets/)**: Extended standard show and form inputs with descriptions, assigned ministries, image previews, and dynamic QR Codes.

---

## 5. Verification & Testing

### Automated Tests
We wrote a comprehensive Pest feature test file: **[InventorySystemTest.php](file:///c:/ProjectDev/Winner_Church/tests/Feature/InventorySystemTest.php)** that asserts:
1. User role checks and capability helper checks.
2. Borrow request submission by members, approval by admins, and returning processing.
3. Maintenance log scheduling, starting, completing, and cost calculations.

We executed the entire test suite successfully:
```bash
php artisan test --compact
```
**Test Results:**
- **Status**: PASSED
- **Total Tests**: 50
- **Total Assertions**: 203
- **Duration**: 2.78s

### Code Formatting
Run Laravel Pint to format the PHP files according to standard style guidelines:
```bash
vendor/bin/pint --dirty --format agent
```
All modified PHP files have been successfully formatted.
