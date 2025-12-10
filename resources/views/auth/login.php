<div class="background-shape"></div>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="login-card shadow-lg">

        <div class="row g-0">

            <!-- LEFT ILLUSTRATION -->
            <div class="col-md-6 illustration-box d-flex justify-content-center align-items-center">
                <img src="/assets/svg/Learning.svg" class="img-fluid" />
            </div>

            <!-- RIGHT FORM -->
            <form action="/login" class="col-md-6 p-5" method="post">

                <h4 class="fw-bold mb-2">Welcome Back :)</h4>
                <p class="text-muted mb-4">To keep connected with us please login with your personal information.</p>

                <!-- Email -->
                <div class="input-group mb-3 input-box">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <input type="text" class="form-control border-start-0" placeholder="Email Address" name="email">
                </div>

                <!-- Password -->
                <div class="input-group mb-3 input-box">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input type="password" class="form-control border-start-0" placeholder="Password" name="password">
                </div>

              

                <!-- Buttons -->
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-50 login-btn">Login Now</button>
                 
                </div>

                

            </form>
        </div>

    </div>
</div>

<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 2000">
    <div id="toastError" class="toast align-items-center text-bg-danger border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body" id="toastErrorMessage">
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>


<?php if ($error = \Core\Http\Session::getFlash('error')): ?>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const toastEl = document.getElementById("toastError");
        const messageEl = document.getElementById("toastErrorMessage");

        messageEl.innerHTML = "<?= $error ?>";

        const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
        toast.show();
    });
</script>
<?php endif; ?>

<?php \Core\Http\Session::deleteFlash() ?>
