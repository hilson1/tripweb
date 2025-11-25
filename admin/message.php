<!DOCTYPE html>
<html lang="en">

<head>  
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

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
        include("frontend/sidebar.php");
        ?>

        <!-- Main Content -->
        <div class="ml-64 p-6 w-full mt-16">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-4"><br></h1>
                <div class="bg-gray-100 p-4 rounded-lg mb-6">
                    <form>
                        <div class="flex flex-wrap justify-between mb-4">
                            <button class="bg-red-500 text-white px-4 py-2 rounded mb-2 sm:mb-0">Compose</button>
                            <div class="flex flex-wrap space-x-2">
                                <a href="your-link-here"
                                    class="bg-gray-300 text-black px-4 py-2 rounded mb-2 sm:mb-0 inline-block">
                                    Inbox
                                </a>
                                <a href="your-link-here"
                                    class="bg-gray-300 text-black px-4 py-2 rounded mb-2 sm:mb-0 inline-block">
                                    Sent
                                </a>
                                <a href="your-link-here"
                                    class="bg-gray-300 text-black px-4 py-2 rounded mb-2 sm:mb-0 inline-block">
                                    Important
                                </a>
                                <a href="your-link-here"
                                    class="bg-gray-300 text-black px-4 py-2 rounded mb-2 sm:mb-0 inline-block">
                                    starred
                                </a>
                                <a href="your-link-here"
                                    class="bg-gray-300 text-black px-4 py-2 rounded mb-2 sm:mb-0 inline-block">
                                    Draft
                                </a>
                                <a href="your-link-here"
                                    class="bg-gray-300 text-black px-4 py-2 rounded mb-2 sm:mb-0 inline-block">
                                    Trash
                                </a>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-gray-700">To:</label>
                                <input type="text" class="w-full px-4 py-2 border rounded" />
                            </div>
                            <div>
                                <label class="block text-gray-700">Subject:</label>
                                <input type="text" class="w-full px-4 py-2 border rounded" />
                            </div>
                            <div>
                                <label class="block text-gray-700">Paragraph</label>
                                <div class="border rounded p-2">
                                    <div id="toolbar">
                                        <button class="ql-bold"></button>
                                        <button class="ql-italic"></button>
                                        <button class="ql-underline"></button>
                                        <button class="ql-list" value="bullet"></button>
                                        <button class="ql-list" value="ordered"></button>
                                        <button class="ql-link"></button>
                                        <button class="ql-image"></button>
                                        <button class="ql-table"></button>
                                        <button class="ql-undo"></button>
                                        <button class="ql-redo"></button>
                                    </div>
                                    <div id="editor" class="h-32"></div>
                                </div>
                            </div>
                            <div class="flex flex-wrap space-x-2">
                                <button type="submit" class="bg-[#008080] text-white px-4 py-2 rounded">Send </button>
                                <button type="submit" class="bg-[red] text-white px-4 py-2 rounded">Discard </button>
                                <button type="submit" class="bg-[Gray] text-white px-4 py-2 rounded">Draft </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        var quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: '#toolbar'
            }
        });
    </script>
</body>

</html>