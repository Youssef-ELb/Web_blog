<!-- errors.php -->

<?php
if (!empty($errors)) {
    echo '<div class="error-container">';
    echo '<h2>Error(s) occurred:</h2>';
    echo '<ul>';
    
    foreach ($errors as $error) {
        echo '<li>' . htmlspecialchars($error) . '</li>';
    }
    
    echo '</ul>';
    echo '</div>';
}
?>
