<div class=" mt-5">


    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-journal-bookmark text-primary"></i> <?= $title ?>
        </h2>

        <div class="d-flex gap-2">


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
                    <th>Section</th>
                    <th>Programs</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>


            <tbody>
                <?php foreach ($sections as $section): ?>
                    <tr>
                        <td><?= $section->year_level ?><?= htmlspecialchars($section->section_code) ?></td>
                        <td><?= htmlspecialchars($section->program->code) ?></td>
                        <td class="text-center">
                            <form action="/delete/section/<?= $section->id ?>" method="POST" style="display:inline;"
                                onsubmit="return confirm('Are you sure you want to delete this section?');">
                                <input type="hidden" name="csrf_token" value="<?= \Core\Http\Session::csrfToken() ?>">
                                <button type="submit" class="btn btn-link p-0 m-0 text-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
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
                    <i class="bi bi-bookmark-plus"></i> Create Section
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form action="/create-section" method="POST">

                    <div class="mb-3">
                        <label for="sectionName" class="form-label">Section Name</label>
                        <input type="text" class="form-control shadow-sm" id="sectionName" name="section_code" required placeholder="e.g., Section A">
                    </div>
                    <div class="mb-3">
                        <label for="yearLevel" class="form-label">Year Level</label>
                        <input type="number" class="form-control shadow-sm" id="yearLevel" name="year_level" required placeholder="e.g., 1" min="1" max="4">
                    </div>

                    <div class="mb-3">
                        <label for="programId" class="form-label">Program</label>
                    </div>



                    <div class="mb-3">
                        <select class="form-select shadow-sm" id="programId" name="program_id" required>
                            <option value="" disabled selected>Select Program</option>
                            <?php foreach ($programs as $program): ?>

                                <option value="<?= $program->id ?>"><?= htmlspecialchars($program->code) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 shadow-sm">
                        <i class="bi bi-save"></i> Save Section
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
                targets: 2
            }]
        });
    });
</script>

<?php $this->end(); ?>