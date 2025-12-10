<!-- Create Program Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow border-0 rounded-4">

            <!-- Modal Header -->
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-semibold d-flex align-items-center gap-2">
                    <i class="bi bi-journal-plus text-primary fs-4"></i>
                    Create Program
                </h5>
                <button type="button" class="btn-close rounded-circle p-2 shadow-sm" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body px-4 pb-4 mt-2">
                <form action="/create-programs" method="POST" class="needs-validation" novalidate>

                    <div class="mb-3">
                        <label class="form-label fw-medium">
                            <i class="bi bi-hash text-secondary me-1"></i> Program Code
                        </label>
                        <input type="text" class="form-control rounded-3 shadow-sm" name="code" placeholder="Enter program code" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">
                            <i class="bi bi-card-text text-secondary me-1"></i> Description
                        </label>
                        <textarea class="form-control rounded-3 shadow-sm" rows="3" name="description" placeholder="Enter description" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 rounded-3 py-2 fw-semibold shadow-sm">
                        <i class="bi bi-check-circle me-1"></i> Save Program
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>



<!-- Page Content -->
<div class="container mt-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold d-flex align-items-center gap-2">
            <i class="bi bi-kanban-fill text-primary"></i> Program Management
        </h3>

        <button class="btn btn-primary rounded-3 shadow-sm px-4 d-flex align-items-center gap-2"
                data-bs-toggle="modal" data-bs-target="#staticBackdrop">
            <i class="bi bi-plus-circle-fill"></i> Add Program
        </button>
    </div>


    <!-- Search Section -->
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body">
            <form class="row g-3" method="GET">
                <div class="col-md-6">
                    <div class="input-group shadow-sm rounded-3">
                        <span class="input-group-text bg-white border-end-0 rounded-start-3">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" name="search"
                            value="<?= htmlspecialchars($search ?? '') ?>"
                            class="form-control border-start-0 rounded-end-3"
                            placeholder="Search program code or description...">
                    </div>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-dark w-100 rounded-3 shadow-sm">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- Table -->
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light border-bottom">
                    <tr>
                        <th class="py-3"><i class="bi bi-hash text-primary me-1"></i> Program Code</th>
                        <th><i class="bi bi-card-text text-primary me-1"></i> Description</th>
                        <th class="text-center"><i class="bi bi-gear-fill text-primary me-1"></i> Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (empty($programs)): ?>
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted fs-6">
                                <i class="bi bi-emoji-neutral fs-3 d-block mb-2 text-secondary"></i>
                                No programs found.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($programs as $program): ?>
                            <tr>
                                <td class="fw-semibold">
                                    <i class="bi bi-tag text-primary me-2"></i>
                                    <?= htmlspecialchars($program->code) ?>
                                </td>

                                <td><?= htmlspecialchars($program->description) ?></td>

                                <td class="text-center">
                                    <form action="/program/delete/<?= $program->id ?>" method="POST"
                                          onsubmit="return confirm('Delete this program?')">

                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger rounded-3 px-3 shadow-sm">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>


    <!-- Pagination -->
    <?php if (!empty($pagination) && $pagination['total_pages'] > 1): ?>
        <nav class="mt-4">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                    <li class="page-item <?= $pagination['current_page'] == $i ? 'active' : '' ?>">
                        <a class="page-link rounded-3 mx-1 shadow-sm"
                           href="?page=<?= $i ?>&search=<?= urlencode($search ?? '') ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>
