
<?php
// Inclusion des fichiers nécessaires
include('config.php');
include('includes/all_functions.php');
include('includes/public/head_section.php');

// Vérification de la présence du paramètre 'topic' dans l'URL
if (isset($_GET['topic'])) {
    // Récupération de l'identifiant du sujet depuis l'URL
    $topic_id = $_GET['topic'];

    // Récupération des publications publiées pour le sujet spécifié
    $posts = getPublishedPostsByTopic($topic_id);

    // Vérification s'il y a des publications pour le sujet spécifié
    if (!empty($posts)) {
        ?>
        <!-- Importation du fichier CSS -->
        <!-- <link rel="stylesheet" type="text/css" href="static/css/public_styling.css"> -->

        <title>Filtered Posts</title>
        </head>
        <body>

            <!-- Navbar -->
            <?php include(ROOT_PATH . '/includes/public/navbar.php'); ?>
            <!-- // Navbar -->

            <div class="container">
                <!-- Content -->
                <div class="content">
                    <?php
                    // Affichage des publications
                    foreach ($posts as $post) {
                        ?>
                        <div class="post full-post-div">
                            <!-- Affichage du titre de la publication -->
                            <h2 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h2>
                            <!-- Affichage du contenu de la publication -->
                            <div class="post-body-div">
                                <p><?php echo $post['body']; ?></p>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <!-- // Content -->
            </div>
            <!-- // Container -->

            <!-- Footer -->
            <?php include(ROOT_PATH . '/includes/public/footer.php'); ?>
            <!-- // Footer -->

        </body>
        </html>
        <?php
    } else {
        // Si aucune publication n'est trouvée pour le sujet spécifié
        echo "<div class='message error'>No posts found for the selected topic!</div>";
    }
} else {
    // Si le paramètre 'topic' n'est pas présent dans l'URL
    echo "<div class='message error'>Topic parameter not found in URL!</div>";
}
?>
