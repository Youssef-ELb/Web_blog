<?php 
include('config.php');
include('includes/all_functions.php');
include('includes/public/head_section.php');
?>

<!-- Importation du fichier CSS -->
<link rel="stylesheet" type="text/css" href="static/css/public_styling.css">

<title>MyWebSite | Single Post </title>
</head>
<body>

    <!-- Navbar -->
    <?php include(ROOT_PATH . '/includes/public/navbar.php'); ?>
    <!-- // Navbar -->

    <div class="container">
    <!-- Content -->
    <div class="content">
        <?php 
        // Récupérer le slug de l'article depuis l'URL
        if (isset($_GET['post-slug'])) {
            $post = getPost($_GET['post-slug']);
            if ($post) {
                ?>
                <div class="post full-post-div">
                    <!-- Affichage du titre du sujet -->
                    <h2 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h2>
                    <!-- Affichage du contenu du sujet -->
                    <div class="post-body-div">
                        <p><?php echo $post['body']; ?></p>
                    </div>
                </div>
                <?php
            } else {
                echo "<div class='message error'>Article not found!</div>";
            }
        }
        ?>

        <!-- Petit tableau de tous les sujets disponibles -->
        <div class="topics-table card">
            <div class="card-header">
            </div>
            <div class="card-content">
                <table>
                    <thead>
                        <tr>
                            <th>Topics</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $topics = getAllTopics();
                        if ($topics) {
                            foreach ($topics as $topic) {
                                echo "<tr><td>" . $topic['name'] . "</td></tr>";
                            }
                        } else {
                            echo "<tr><td>No topics found!</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- // Petit tableau -->
    </div>
    <!-- // Content -->
</div>
<!-- // Container -->


    <!-- Footer -->
    <?php include(ROOT_PATH . '/includes/public/footer.php'); ?>
    <!-- // Footer -->

</body>
</html>
