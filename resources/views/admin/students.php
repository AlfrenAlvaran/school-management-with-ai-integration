<div class=" mt-5">

    <!-- HEADER + ACTIONS -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-person-check text-primary"></i> <?= $title ?>
        </h2>

        <div class="d-flex gap-2">


            <a href="/students/add-student" class="btn btn-primary btn-sm shadow-sm">
                <i class="bi bi-plus-lg"></i> Add
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
                    <th>Gender</th>
                    <th>Contact</th>
                    <th>Birth Date</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?= htmlspecialchars($student->student_id) ?></td>
                        <td><?= htmlspecialchars($student->lastname . ", " . $student->firstname . ", " . $student->middlename) ?></td>
                        <td><?= htmlspecialchars($student->sex) ?></td>
                        <td><?= htmlspecialchars($student->contact) ?></td>
                        <td><?= htmlspecialchars($student->birthdate) ?></td>

                        <td class="text-center">
                            <a href="/view/<?= $student->id ?>" class="text-primary me-2" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="/edit/<?= $student->id ?>" class="text-warning me-2" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="/student/delete/<?= $student->id ?>" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this student?');">
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