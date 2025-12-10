<div class="row g-4">

    <!-- PAGE TITLE -->
    <div class="col-12">
        <h3 class="fw-bold mb-0">Dashboard</h3>
        <span class="text-muted">Overview of system activity & performance</span>
    </div>

    <!-- ===== STAT CARDS ===== -->
    <div class="col-md-3 col-sm-6">
        <div class="card shadow-sm stats-card border-0 p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Total Students</h6>
                    <h3 class="fw-bold"><?= $student_count ?></h3>
                </div>
                <div class="stats-icon bg-primary text-white">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="card shadow-sm stats-card border-0 p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Teachers</h6>
                    <h3 class="fw-bold">84</h3>
                </div>
                <div class="stats-icon bg-success text-white">
                    <i class="bi bi-person-badge-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="card shadow-sm stats-card border-0 p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Programs</h6>
                    <h3 class="fw-bold">12</h3>
                </div>
                <div class="stats-icon bg-warning text-white">
                    <i class="bi bi-journal-bookmark-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="card shadow-sm stats-card border-0 p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Announcements</h6>
                    <h3 class="fw-bold">5</h3>
                </div>
                <div class="stats-icon bg-danger text-white">
                    <i class="bi bi-megaphone-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== CHART ===== -->
    <div class="col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h6 class="fw-bold mb-0">Enrollment Overview</h6>
            </div>
            <div class="card-body">
                <canvas id="enrollmentChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- ===== CALENDAR ===== -->
    <div class="col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">School Calendar</h6>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addEventModal">
                    <i class="bi bi-plus-circle"></i> Add Event
                </button>
            </div>
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    <!-- ===== LATEST EVENTS ===== -->
    <div class="col-lg-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h6 class="fw-bold mb-0">Latest Events</h6>
            </div>
            <ul class="list-group list-group-flush" id="latestEventsList">
                <li class="list-group-item">No events yet</li>
            </ul>
        </div>
    </div>

</div>

<!-- ===== ADD EVENT MODAL ===== -->
<div class="modal fade" id="addEventModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Add Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Event Title</label>
                    <input type="text" class="form-control" id="eventTitle" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Event Date</label>
                    <input type="date" class="form-control" id="eventDate" required>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" type="button" id="saveEventBtn">Save</button>
            </div>
        </form>
    </div>
</div>
<?php $this->start('scripts') ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ---------------- CHART ---------------- */
    new Chart(document.getElementById('enrollmentChart'), {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
            datasets: [{
                label: 'Students',
                data: [120, 150, 180, 170, 200],
                tension: 0.4,
                borderWidth: 2
            }]
        }
    });

    /* ---------------- CALENDAR ---------------- */
    var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
        initialView: 'dayGridMonth',
        height: 500,
        selectable: true,
        events: [],
        eventAdd: function(info) {
            updateLatestEvents(info.event);
        }
    });
    calendar.render();

    /* -------- Add Event -------- */
    document.getElementById('saveEventBtn').addEventListener('click', function () {
        let title = eventTitle.value;
        let date = eventDate.value;

        if (title && date) {
            calendar.addEvent({ title, start: date });
            updateLatestEvents({ title, start: date });

            eventTitle.value = "";
            eventDate.value = "";

            bootstrap.Modal.getInstance(addEventModal).hide();
        }
    });

    /* -------- Latest Events -------- */
    function updateLatestEvents(event) {
        let list = document.getElementById('latestEventsList');
        list.innerHTML = "";

        let li = document.createElement('li');
        li.classList = "list-group-item";
        li.textContent = `${event.title} — ${new Date(event.start).toDateString()}`;
        list.prepend(li);
    }

});
</script>

<?php $this->end() ?>
