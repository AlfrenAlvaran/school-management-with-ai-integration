<div class="container mt-5">
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="javascript:history.back()" class="text-decoration-none text-dark">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h2 class="mb-0">Teacher Information</h2>
    </div>

    <form action="/students/create-student" method="post" id="studentForm">

        <div class="row mb-3">
            <div class="col">
                <label for="firstname" class="form-label">First Name</label>
                <input type="text" class="form-control shadow-sm" id="firstname" name="firstname" required>
            </div>
            <div class="col">
                <label for="middlename" class="form-label">Middle Name</label>
                <input type="text" class="form-control shadow-sm" id="middlename" name="middlename">
            </div>
            <div class="col">
                <label for="lastname" class="form-label">Last Name</label>
                <input type="text" class="form-control shadow-sm" id="lastname" name="lastname" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="Email" class="form-label">Email</label>
            <input type="text" class="form-control shadow-sm">
        </div>

        <div class="mb-3">
            <label for="Position" class="form-label">Position</label>
            <input type="text" class="form-control shadow-sm">
        </div>

        <div class="mb-3">
            <label for="specialization" class="form-label">Specialization</label>
            <input type="text" class="form-control shadow-sm">
        </div>

        <div class="mb-3">
            <label for="birthdate" class="form-label">Birth Date</label>
            <input type="date" name="" id=""  class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>