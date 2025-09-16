document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector("#contactForm"); // use id for form

  form.addEventListener("submit", function(e){
    e.preventDefault(); // stop normal form submit

    const formData = new FormData(form);

    let email = document.getElementById('email').value ;

    // 1. Frontend Email validation
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
      alert("❌ Please enter a valid email address.");
      return;
    }

      // 2. Send data to backend
    fetch('../includes/contact.php', {
        method: "POST",
        body: formData,
    })
    .then(response => response.json())                         // result obtained from the backend database file
    .then(result => {
      if (result.success) {
        window.location.href = 'contact.php?status=success' ;
      } else {
        window.location.href = 'contact.php?status=error' ;
      }
    })  
    .catch(()=>{
      alert("⚠️ Failed to send message. Check your connection.");
    });

  });
  const closeButtons = document.querySelectorAll(".close-btn");
  closeButtons.forEach(btn => {
    btn.addEventListener("click", () => {
      btn.parentElement.style.display = "none"; // hide the message

      const url = new URL(window.location);       // create a URL object of current page
      url.searchParams.delete('status');         // remove the 'status' query parameter
      window.history.replaceState({}, document.title, url);  // update the URL without reloading

    });
  });

});
