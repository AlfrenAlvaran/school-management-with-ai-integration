<style>
body {
  background: #f6fcfb;
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
}

.otp-card {
  width: 450px;
  background: #ffffff;
  padding: 40px;
  border-radius: 25px;
  box-shadow: 0 8px 25px rgba(0,0,0,0.08);
  text-align: center;
}

.otp-input {
  width: 55px;
  height: 55px;
  text-align: center;
  font-size: 22px;
  font-weight: bold;
  border: 2px solid #dce3f3;
  border-radius: 12px;
  outline: none;
  transition: 0.2s;
}

.otp-input:focus {
  border-color: #4a6cf7;
  box-shadow: 0 0 8px rgba(74,108,247,0.4);
}

.otp-container {
  display: flex;
  justify-content: center;
  gap: 12px;
  margin: 25px 0;
}

.verify-btn {
  border-radius: 12px;
  height: 48px;
}
</style>

<div class="otp-card">
  <h3 class="fw-bold mb-2">Verify OTP</h3>
  <p class="text-muted">Enter the 6-digit code sent to your email.</p>

  <?php if(!empty($error)): ?>
      <p class="text-danger"><?= $error ?></p>
  <?php endif; ?>

  <form method="POST" action="/verify-otp" id="otp-form">
    <div class="otp-container">
      <input type="text" maxlength="1" class="otp-input" name="otp[]">
      <input type="text" maxlength="1" class="otp-input" name="otp[]">
      <input type="text" maxlength="1" class="otp-input" name="otp[]">
      <input type="text" maxlength="1" class="otp-input" name="otp[]">
      <input type="text" maxlength="1" class="otp-input" name="otp[]">
      <input type="text" maxlength="1" class="otp-input" name="otp[]">
    </div>

    <button type="submit" class="btn btn-primary w-100 verify-btn">Verify</button>
  </form>

  <p class="small text-muted mt-3">
    Didn’t receive the code? <a href="#" class="text-decoration-none">Resend</a>
  </p>
</div>

<script>
const inputs = document.querySelectorAll(".otp-input");
const form = document.getElementById("otp-form");

inputs.forEach((input, index) => {
  input.addEventListener("input", () => {
    if (input.value && index < inputs.length - 1) inputs[index + 1].focus();
  });
  input.addEventListener("keydown", e => {
    if (e.key === "Backspace" && !input.value && index > 0) inputs[index - 1].focus();
  });
});

form.addEventListener("submit", e => {
  e.preventDefault();
  let otpValue = '';
  inputs.forEach(input => otpValue += input.value);

  const hiddenInput = document.createElement('input');
  hiddenInput.type = 'hidden';
  hiddenInput.name = 'otp';
  hiddenInput.value = otpValue;
  form.appendChild(hiddenInput);

  form.submit();
});
</script>
