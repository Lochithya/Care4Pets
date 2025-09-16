document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("loginForm");
  const errorBox = document.getElementById("errorBox");
  const errorMsg = document.getElementById("errorMsg");
  const dismissBtn = document.getElementById("dismissBtn");

  // Dismiss error box
  dismissBtn.addEventListener("click", () => {
    errorBox.style.display = "none";
  });

  // Handle form submit
  form.addEventListener("submit", async (e) => {
    e.preventDefault(); // stop refresh

    const formData = new FormData(form);

    try {
      const response = await fetch("../includes/login_process.php", {
        method: "POST",
        body: formData
      });
      const result = await response.json();

      if (result.success) {
        window.location.href = "../public/index.php"; // redirect on success
      } else {
        errorMsg.textContent = result.message;
        errorBox.style.display = "flex";
      }
    } catch (err) {
      errorMsg.textContent = "⚠️ Server error. Please try again.";
      errorBox.style.display = "flex";
    }
  });
});
