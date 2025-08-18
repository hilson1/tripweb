<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
            dropdownToggles.forEach(toggle => {
                toggle.addEventListener('click', function () {
                    const dropdownMenu = this.nextElementSibling;
                    dropdownMenu.classList.toggle('hidden');
                });
            });
        });
    </script>
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <?php
        include("frontend/asidebar.php");
        ?>
        <!-- Main Content -->
        <div class="flex-1 flex flex-col ml-64">
            <main class="flex-1 p-6 mt-16">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-blue-500 text-white p-4 rounded shadow flex items-center">
                        <i class="fas fa-comments fa-2x"></i>
                        <div class="ml-4">
                            <h2 class="text-2xl font-bold">26</h2>
                            <p>New Comments!</p>
                        </div>
                    </div>
                    <div class="bg-green-500 text-white p-4 rounded shadow flex items-center">
                        <i class="fas fa-tasks fa-2x"></i>
                        <div class="ml-4">
                            <h2 class="text-2xl font-bold">12</h2>
                            <p>New Tasks!</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-6">
                    <div class="lg:col-span-2 bg-white p-4 rounded shadow">
                        <h2 class="text-xl font-bold mb-4">Area Chart Example</h2>
                        <img alt="Area chart example" class="w-full"
                            src="https://storage.googleapis.com/a1aa/image/ysvIGKU5xjouDdZ1JgtBj-n_G9zUPdCJdnrYlDGmREI.jpg" />
                    </div>
                    <div class="bg-white p-4 rounded shadow">
                        <h2 class="text-xl font-bold mb-4">Notifications Panel</h2>
                        <ul>
                            <li class="flex items-center mb-2 text-gray-700">
                                <i class="fas fa-comment fa-fw text-gray-500"></i>
                                <span class="ml-2">New Comment</span>
                                <span class="ml-auto text-gray-500 text-sm">4 minutes ago</span>
                            </li>
                            <li class="flex items-center mb-2 text-gray-700">
                                <i class="fas fa-user-plus fa-fw text-gray-500"></i>
                                <span class="ml-2">3 New Followers</span>
                                <span class="ml-auto text-gray-500 text-sm">12 minutes ago</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>