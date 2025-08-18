<?php
// template.php
function renderPage($content = '') {
  ob_start();
  include 'asidebar.php';
  $html = ob_get_clean();
  
  // Replace the content placeholder if needed
  if (!empty($content)) {
    $html = str_replace('<?php echo $content; ?>', $content, $html);
  }
  
  echo $html;
}