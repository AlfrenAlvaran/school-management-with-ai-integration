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
                    <th style="width: 50px;">Department Code</th>
                    <th>Department Name</th>
                    <th>Description</th>
                    <th>Head Teacher</th>
                    <th class="text-center" style="width: 50px;">Actions</th>
                </tr>
            </thead>


            <tbody>

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
                    <i class="bi bi-diagram-3 me-1"></i>
                    <i class="bi bi-plus"></i> Create Department

                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form action="/create-section" method="POST">

                    <div class="mb-3">
                        <label for="sectionName" class="form-label">Department Code</label>
                        <input type="text" name="department_code" id="" class="form-control shadow-sm" placeholder="Enter Department Code">
                    </div>
                    <div class="mb-3">
                        <label for="yearLevel" class="form-label">Department Name</label>
                        <input type="text" name="department_name" id="" class="form-control shadow-sm" placeholder="Enter Department Name">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-card-text me-1"></i> Description
                        </label>
                        <textarea name="description" class="form-control shadow-sm" rows="3" placeholder="Enter Department Description"></textarea>
                    </div>

                    <div class="mb-3">
                        <select name="head_teacher" id="head_teacher" class="form-control">

                            <?php foreach($teachers as $teacher): ?>
                                <option value="<?= $teacher->id ?>"><?= $teacher->name ?></option>
                            <?php endforeach ?>


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