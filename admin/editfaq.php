<?php
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
</head>
<body class="bg-gray-50 p-10">
<div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-6">
  <h1 class="text-2xl font-semibold mb-4">Edit FAQ</h1>
  <form method="POST">
    <label class="block mb-2 font-medium">Trip ID</label>
    <input type="number" name="tripid" value="<?= htmlspecialchars($faq['tripid']) ?>" class="w-full border p-2 rounded mb-4">

    <label class="block mb-2 font-medium">Question</label>
    <input type="text" name="question" value="<?= htmlspecialchars($faq['question']) ?>" class="w-full border p-2 rounded mb-4">

    <label class="block mb-2 font-medium">Answer</label>
    <textarea name="answer" class="w-full border p-2 rounded mb-4"><?= htmlspecialchars($faq['answer']) ?></textarea>

    <button class="bg-blue-600 text-white px-4 py-2 rounded">Update FAQ</button>
  </form>
</div>
</body>
</html>
