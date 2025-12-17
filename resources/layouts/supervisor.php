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
        <h5 class="text-center mb-4">SAdmin</h5>
        <a href="/dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="/curriculum"><i class="bi bi-journal-text"></i> Curriculum</a>
        <a href="/department"><i class="bi bi-mortarboard"></i> Department</a>
        <a href="/users" class="d-flex align-items-center gap-2">
           <i class="bi bi-people"></i>
            <span>Users</span>
        </a>
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