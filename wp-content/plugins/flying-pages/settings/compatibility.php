<?php
function flying_pages_compatibility() {

  $url = home_url();
  $url_protocol = parse_url($url)['scheme'];
  $headers = get_headers($url, 1);
  $cache_control_header = isset($headers["Cache-Control"]) ? $headers["Cache-Control"] : "";

  ?>

  <h3>HTTPS Check</h3>
  <?php 
    if($url_protocol === "https")
      echo '<p style="color:green">✅ Your website is served over HTTPS</p>';
    else 
      echo '<p style="color:red">❌ Your website is not served over HTTPS</p>';
  ?>
  

  <h3>Cache-Control Check</h3>
  <?php
    if(strpos($cache_control_header, 'no-store') === false)
      echo '<p style="color:green">✅ "no-store" is not found in your Cache-Control response header</p>';
    else {
      echo '<p style="color:red">❌ "no-store" is found in your Cache-Control response header. Please contact your hosting provider to remove this.</p>';
      echo "<p>Current Cache-Control: <code>{$cache_control_header}</code></p>";
      echo "<p>Suggested Cache-Control: <code>no-cache, must-revalidate, max-age=0</code></p>";
    }
}
?>