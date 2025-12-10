<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard' ?></title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="/assets/css/admin.css" rel="stylesheet">


</head>

<body>

    <!-- Sidebar -->
    <div id="sidebar" class="sidebar">
        <h5 class="text-center mb-4">Admin Panel</h5>
        <a href="/dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="/curriculum"><i class="bi bi-journal-text"></i> Curriculum</a>

        <a href="#studentMenu" data-bs-toggle="collapse">
            <i class="bi bi-person-lines-fill"></i> Students <i class="bi bi-chevron-down ms-auto"></i>
        </a>

        <div class="collapse ms-4" id="studentMenu">
            <a href="/students" class="small">Student List</a>
            <a href="/enrollments" class="small">Enrollments</a>
        </div>

        <a data-bs-toggle="collapse" href="#settingsMenu">
            <i class="bi bi-gear"></i> Settings <i class="bi bi-chevron-down ms-auto"></i>
        </a>



        <div class="collapse ms-4" id="settingsMenu">
            <a href="/program" class="small">Program</a>
            <a href="/subjects" class="small">Subjects</a>
            <a href="/sections" class="small">Sections</a>
            <a href="/announcements" class="small">Announcements</a>
        </div>
        <!-- <a href="/users"><i class="bi bi-people"></i> Users</a> -->
        <a href="/logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
        <div class="sidebar-footer">© <?= date('Y') ?> AI-Powered Student Performance Predictor</div>
    </div>

    <div id="content" class="content">
        <?php if (($showTopbar ?? true) === true): ?>
            <div class="topbar d-flex justify-content-between align-items-center mb-4 p-3">
                <div class="d-flex align-items-center gap-3">
                    <button id="menuBtn" class="btn btn-outline-primary btn-sm d-md-none">
                        <i class="bi bi-list"></i>
                    </button>
                    <h5 class="m-0">Welcome Back <?= $user->role ?> | <?= $user->name ?></h5>
                </div>
            </div>
        <?php endif; ?>

        <div class="container-fluid">
            <?= $content ?>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- 2. jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- 3. DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <!-- Page-specific scripts -->
    <?= $this->section('scripts') ?>

</body>

</html>