
function addToCart(productId) {
  const qty = document.getElementById(`qty-${productId}`).value;

  fetch('cart.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `add_to_cart=1&product_id=${productId}&quantity=${qty}`
  })
  .then(response => response.text())
  .then(data => {
    showCartMessage("✅ Product added to cart!");
  })
  .catch(error => {
    showCartMessage("❌ Failed to add product.");
  });
}

function showCartMessage(message) {
  const msgDiv = document.createElement('div');
  msgDiv.className = 'cart-flash-message';
  msgDiv.textContent = message;
  document.body.appendChild(msgDiv);

  setTimeout(() => {
    msgDiv.classList.add('fade-out');
  }, 2000);

  setTimeout(() => {
    msgDiv.remove();
  }, 3000);
}



 document.addEventListener("DOMContentLoaded", function () {
      const username = document.querySelector('input[name="username"]');
      const email = document.querySelector('input[name="email"]');
      const password = document.querySelector('input[name="password"]');
      const confirm = document.querySelector('input[name="confirm_password"]');

      const emailError = document.getElementById('emailError');
      const passwordError = document.createElement('span');
      const confirmError = document.createElement('span');
      const usernameError = document.createElement('span');

      [usernameError, passwordError, confirmError].forEach(span => {
        span.style.color = 'red';
        span.style.fontSize = '0.9em';
      });

      username.parentNode.appendChild(usernameError);
      password.parentNode.appendChild(passwordError);
      confirm.parentNode.appendChild(confirmError);

      username.addEventListener('input', () => {
        usernameError.textContent = username.value.trim().length < 3 ? "Username must be at least 3 characters." : "";
      });

      email.addEventListener('input', () => {
        const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        emailError.textContent = !pattern.test(email.value.trim()) ? "Invalid email format." : "";
      });

      password.addEventListener('input', () => {
        const value = password.value;
        passwordError.textContent = value.length < 8 || !/^[a-zA-Z0-9]+$/.test(value)
          ? "Password must be at least 8 characters and alphanumeric."
          : "";
      });

      confirm.addEventListener('input', () => {
        confirmError.textContent = confirm.value !== password.value ? "Passwords do not match." : "";
      });
    });
