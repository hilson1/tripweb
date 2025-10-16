<?php
include __DIR__ . '/auth-check.php';
require "frontend/connection.php";
session_start();

$id = intval($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tripid = $_POST['tripid'];
    $question = $_POST['question'];
    $answer = $_POST['answer'];

    $stmt = $conn->prepare("UPDATE trip_faqs SET tripid=?, question=?, answer=? WHERE faqid=?");
    $stmt->bind_param("issi", $tripid, $question, $answer, $id);
    $stmt->execute();
    header("Location: allfaq");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM trip_faqs WHERE faqid=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$faq = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit FAQ</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="frontend/sidebar.css">
</head>
<body class="bg-gray-50">
<div class="flex h-screen">
  <?php include("frontend/header.php"); ?>
  <?php include("frontend/sidebar.php"); ?>

  <main class="main-content pt-16 min-h-screen p-6 w-full mt-16" x-data="{ saving: false, showSuccess: false }">
    <div class="gradient-bg rounded-2xl p-6 text-white flex justify-between items-center mb-8 shadow-md">
      <div>
        <h1 class="text-3xl font-bold"><i class="fas fa-edit mr-2"></i>Edit FAQ</h1>
      </div>
      <a href="allfaq" class="bg-white bg-opacity-20 px-4 py-2 rounded-lg hover:bg-opacity-30 transition">
        <i class="fas fa-arrow-left mr-2"></i>Back to FAQs
      </a>
    </div>
  <form method="POST" 
          class="bg-white p-8 rounded-2xl shadow-xl max-w-3xl mx-auto space-y-6"
          x-on:submit="saving = true; setTimeout(() => showSuccess = true, 400)">
    <label class="block mb-2 font-medium">Trip ID</label>
    <input type="number" name="tripid" value="<?= htmlspecialchars($faq['tripid']) ?>" class="w-full border p-2 rounded mb-4">

    <label class="block mb-2 font-medium">Question</label>
    <input type="text" name="question" value="<?= htmlspecialchars($faq['question']) ?>" class="w-full border p-2 rounded mb-4">

    <label class="block mb-2 font-medium">Answer</label>
    <textarea name="answer" class="w-full border p-2 rounded mb-4"><?= htmlspecialchars($faq['answer']) ?></textarea>

    <button class="bg-blue-600 text-white px-4 py-2 rounded">Update FAQ</button>
  </form>
</main>
</div>
</body>
</html>
