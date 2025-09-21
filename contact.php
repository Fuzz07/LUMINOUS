<?php include 'includes/header.php'; ?>
<main>
  <h2>Contact Us</h2>
  <form method="post" action="contact.php">
    <input type="text" name="name" placeholder="Your Name" required>
    <input type="email" name="email" placeholder="Your Email" required>
    <textarea name="message" placeholder="Your Message" required></textarea>
    <button type="submit">Send</button>
  </form>
</main>
<?php include 'includes/footer.php'; ?>