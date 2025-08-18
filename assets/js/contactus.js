document.getElementById("contactForm").addEventListener("submit", function (e) {
    e.preventDefault(); // Prevent default form submission
  
    // Show success message
    const successMessage = document.getElementById("successMessage");
    successMessage.classList.remove("d-none");
  
    // Reset form fields
    document.getElementById("contactForm").reset();
  
    // Hide success message after 3 seconds
    setTimeout(() => {
      successMessage.classList.add("d-none");
    }, 3000);
  });
  