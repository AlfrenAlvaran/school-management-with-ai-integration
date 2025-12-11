<div class=" mt-5">

    <!-- HEADER + ACTIONS -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-person-check text-primary"></i> <?= $title ?>
        </h2>

        <div class="d-flex gap-2">

            <a href="/enrollment_form" class="btn btn-primary btn-sm shadow-sm">
                <i class="bi bi-file-earmark-plus"></i> Enrollment Form
            </a>

        </div>
    </div>

    <!-- TABLE -->
    <div class="table-responsive shadow-sm rounded">
        <table id="subjectsTable" class="table table-hover table-striped align-middle mb-0">
            <thead class="table-light text-center">
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th></th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($studentList as $student) : ?>

                <?php endforeach; ?>
            </tbody>
        </table>
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


    });
</script>
<?php $this->end(); ?>