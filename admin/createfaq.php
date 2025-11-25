<?php
include __DIR__ . '/auth-check.php';
require "frontend/connection.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $tripid = $_POST['tripid'];
    $question = trim($_POST['question']);
    $answer = trim($_POST['answer']);

    $stmt = $conn->prepare("
        INSERT INTO trip_faqs (tripid, question, answer)
        VALUES (?, ?, ?)
    ");
    $stmt->bind_param("iss", $tripid, $question, $answer);
    $stmt->execute();

    header("Location: allfaq");
    exit;
}

$trips = $conn->query("SELECT tripid, title FROM trips ORDER BY title ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create FAQ - ThankYouNepalTrip</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="frontend/sidebar.css">
</head>

<body class="bg-gray-50">
<div class="flex h-screen">
  <?php include("frontend/header.php"); ?>
  <?php include("frontend/sidebar.php"); ?>

  <main class="main-content pt-16 min-h-screen p-6 w-full mt-16">
    <div class="gradient-bg rounded-2xl p-6 text-white flex justify-between items-center mb-8 shadow-md">
      <div>
        <h1 class="text-3xl font-bold"><i class="fas fa-question-circle mr-2"></i>Add Trip FAQ</h1>
        <p class="text-green-100">Create a frequently asked question for a trip</p>
      </div>
      <a href="allfaq" class="bg-white bg-opacity-20 px-4 py-2 rounded-lg hover:bg-opacity-30 transition">
        <i class="fas fa-list mr-2"></i>View All
      </a>
    </div>

    <form id="faqForm" method="POST" class="bg-white p-8 rounded-2xl shadow-xl max-w-2xl mx-auto">
      <label class="block mb-4">
        <span class="text-gray-700 font-medium">Trip</span>
        <select name="tripid" id="tripid" required
          class="w-full border-gray-300 rounded-lg mt-1 p-2 focus:ring-2 focus:ring-blue-500">
          <option value="">Select Trip</option>
          <?php while ($trip = $trips->fetch_assoc()): ?>
            <option value="<?= $trip['tripid'] ?>"><?= htmlspecialchars($trip['title']) ?></option>
          <?php endwhile; ?>
        </select>
      </label>

      <label class="block mb-4">
        <span class="text-gray-700 font-medium">Question</span>
        <input type="text" id="question" name="question" required
          placeholder="Enter the question"
          class="w-full border-gray-300 rounded-lg mt-1 p-2 focus:ring-2 focus:ring-blue-500">
      </label>

      <label class="block mb-4">
        <span class="text-gray-700 font-medium">Answer</span>
        <textarea id="answer" name="answer" rows="4" required
          placeholder="Enter the answer"
          class="w-full border-gray-300 rounded-lg mt-1 p-2 focus:ring-2 focus:ring-green-500"></textarea>
      </label>

      <button type="submit"
        class="mt-6 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
        <i class="fas fa-save mr-2"></i>Save FAQ
      </button>
    </form>
  </main>
</div>

<!-- ✅ JavaScript -->
<script>
document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("faqForm");

  form.addEventListener("submit", function (e) {
    const tripid = document.getElementById("tripid").value.trim();
    const question = document.getElementById("question").value.trim();
    const answer = document.getElementById("answer").value.trim();

    if (!tripid || !question || !answer) {
      alert("Please fill in all fields before submitting.");
      e.preventDefault();
    }
  });
});
</script>

</body>
</html>
