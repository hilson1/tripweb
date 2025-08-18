<?php
require '../connection.php';

// Fetch only inactive activities
$stmt = $conn->prepare("SELECT * FROM activity WHERE activity_status = 'inactive' ORDER BY activity_id DESC");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Same head content as allactivities.php -->
  <title>Inactive Activities - ThankYouNepalTrip</title>
</head>
<body class="bg-gray-50 font-sans leading-normal tracking-normal" x-data="{ sidebarOpen: false }">
  <!-- Same header/sidebar as allactivities.php -->

  <main class="main-content pt-16 min-h-screen transition-all duration-300">
    <div class="p-6">
      <div class="glass-effect rounded-2xl shadow-xl p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Inactive Activities</h1>
        
        <!-- Same table structure as allactivities.php but with only inactive activities -->
        <?php if ($result->num_rows > 0): ?>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php while ($activity = $result->fetch_assoc()): ?>
              <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <?php if (!empty($activity["act_image"])): ?>
                  <img src="../<?php echo htmlspecialchars($activity["act_image"]); ?>" 
                       alt="<?php echo htmlspecialchars($activity["activity"]); ?>" 
                       class="w-full h-48 object-cover"
                       onerror="this.onerror=null; this.src='../assets/no-image.jpg';">
                <?php else: ?>
                  <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                    <i class="fas fa-image text-4xl text-gray-400"></i>
                  </div>
                <?php endif; ?>
                
                <div class="p-4">
                  <h3 class="font-bold text-lg mb-2"><?php echo htmlspecialchars($activity["activity"]); ?></h3>
                  <p class="text-gray-600 mb-4">
                    <?php echo htmlspecialchars(substr($activity["description"], 0, 100)); ?>
                    <?php if (strlen($activity["description"]) > 100) echo '...'; ?>
                  </p>
                  <span class="bg-red-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
                    Inactive
                  </span>
                </div>
              </div>
            <?php endwhile; ?>
          </div>
        <?php else: ?>
          <div class="text-center py-8">
            <i class="fas fa-hiking text-4xl text-gray-400 mb-4"></i>
            <p class="text-gray-500">No inactive activities found</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </main>

  <!-- Same scripts as allactivities.php -->
</body>
</html>