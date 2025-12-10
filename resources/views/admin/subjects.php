<div class=" mt-5">

    <!-- HEADER + ACTIONS -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-journal-bookmark text-primary"></i> <?= $title ?>
        </h2>

        <div class="d-flex gap-2">
            <select id="prereqFilter" class="form-select shadow-sm">
                <option value="">All Subjects</option>
                <option value="has">Has Prerequisite</option>
                <option value="none">No Prerequisite</option>
            </select>

            <button type="button" class="btn btn-primary btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#subjectModal">
                <i class="bi bi-plus-lg"></i> Add
            </button>

        </div>
    </div>

    <!-- TABLE -->
    <div class="table-responsive shadow-sm rounded">
        <table id="subjectsTable" class="table table-hover table-striped align-middle mb-0">
            <thead class="table-light text-center">
                <tr>
                    <th>Code</th>
                    <th>Title</th>
                    <th>Units</th>
                    <th>Category</th>
                    <th>Prerequisites</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($subjects)): ?>
                    <?php foreach ($subjects as $sub): ?>
                        <tr>
                            <td class="fw-semibold"><?= htmlspecialchars($sub->code) ?></td>
                            <td><?= htmlspecialchars($sub->title) ?></td>
                            <td class="text-center"><?= $sub->units ?></td>
                            <td><span class="badge bg-secondary"><?= $sub->category ?></span></td>

                            <td>
                                <?php if (!empty($sub->prerequisites)): ?>
                                    <span class="text-dark">
                                        <?= implode(', ', array_map(fn($p) => $p->code, $sub->prerequisites)) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted fst-italic">None</span>
                                <?php endif; ?>
                            </td>

                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-info me-1" title="View Details">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-warning" title="Edit Subject">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-file-earmark-x fs-3 mb-2 d-block"></i>
                            No subjects added yet.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


<!-- CREATE SUBJECT MODAL -->
<div class="modal fade" id="subjectModal" tabindex="-1" aria-labelledby="subjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-semibold">
                    <i class="bi bi-bookmark-plus"></i> Create Subject
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form action="/create-subject" method="POST">

                    <div class="mb-3">
                        <label class="form-label">Subject Code</label>
                        <input type="text" class="form-control shadow-sm" name="code" required placeholder="e.g., CS101">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Subject Title</label>
                        <textarea class="form-control shadow-sm" name="title" rows="2" required placeholder="e.g., Introduction to Computer Science"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Units</label>
                            <input type="number" class="form-control shadow-sm" name="units" min="1" max="6" value="3">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-select shadow-sm" name="category" required>
                                <option value="MAJOR">Major</option>
                                <option value="GENERAL">General</option>
                                <option value="ELECTIVE">Elective</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Prerequisites (optional)</label>
                        <select class="form-select shadow-sm" name="prereq_ids[]" multiple>
                            <?php foreach ($subjects as $sub): ?>
                                <?php if ($sub->id): ?>
                                    <option value="<?= $sub->id ?>">
                                        <?= htmlspecialchars($sub->code . ' - ' . $sub->title) ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted">(hold Ctrl/Cmd to select multiple)</small>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 shadow-sm">
                        <i class="bi bi-save"></i> Save Subject
                    </button>

                </form>
            </div>
        </div>
    </div>
</div>


<?php $this->start('scripts'); ?>
<script>
    $(document).ready(function() {
        var table = $('#subjectsTable').DataTable({
            pageLength: 5,
            lengthMenu: [5, 10, 25, 50],
            order: [
                [0, "asc"]
            ],
            columnDefs: [{
                orderable: false,
                targets: 5
            }]
        });

        $('#prereqFilter').on('change', function() {
            var value = $(this).val();

            if (value === "has") {
                table.column(4).search('^(?!None$).*', true, false).draw();
            } else if (value === "none") {
                table.column(4).search('^None$', true, false).draw();
            } else {
                table.column(4).search('').draw();
            }

        });
    });
</script>
<?php $this->end(); ?>