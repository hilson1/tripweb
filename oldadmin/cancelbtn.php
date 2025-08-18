<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('input').forEach(input => input.value = '');
        });
    </script>";
}
?>