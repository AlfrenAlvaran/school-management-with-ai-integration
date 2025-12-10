<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/student.css">
</head>

<body>
    <div class="d-flex">
        <div class="sidebar p-4">
            <h4 class="fw-bold mb-4">Student Portal</h4>

            <nav class="nav flex-column gap-2">
                <a href="#" class="nav-link d-flex align-items-center gap-2">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a href="#" class="nav-link d-flex align-items-center gap-2">
                    <i class="bi bi-journal-bookmark"></i> Courses
                </a>
                <a href="#" class="nav-link d-flex align-items-center gap-2">
                    <i class="bi bi-calendar-week"></i> Schedule
                </a>
                <a href="#" class="nav-link d-flex align-items-center gap-2">
                    <i class="bi bi-person"></i> Profile
                </a>
            </nav>

            <div class="mt-auto pt-4">
                <a href="/logout" class="btn btn-danger w-100 d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </div>
        <?= $content ?>
    </div>
</body>

</html>