<?php
// Inclusion du fichier de configuration
include('../config.php');
// Inclusion des fonctions administratives
include(ROOT_PATH . '/includes/admin_functions.php');
// Inclusion de la section d'en-tête de l'administration
include(ROOT_PATH . '/includes/admin/head_section.php');
// Inclusion des fonctions de publication de l'administration
include(ROOT_PATH . '/admin/post_functions.php');
?>

<title>Admin | Gérer les utilisateurs</title>
<!-- Inclusion de la feuille de style Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-dTm5Fd//rFdy4XNXPrz/uS6HdWFTpX7vK9TMpT0eLlH3pm6Pfh+iB4IrHiYzF3Dl" crossorigin="anonymous">
</head>

<body>

    <?php include(ROOT_PATH . '/includes/admin/header.php') ?>
    <!-- Inclusion de l'en-tête de l'administration -->

    <div class="container content">
        <?php include(ROOT_PATH . '/includes/admin/menu.php') ?>
        <!-- Inclusion du menu de l'administration -->

        <table class="table">
            <thead>
                <!-- Entêtes du tableau -->
                <th>N</th>
                <th>Auteur</th>
                <th>Titre</th>
                <th>Vues</th>
                <th>Publié</th>
                <th>Modifier</th>
                <th>Supprimer</th>
            </thead>
            <tbody>
                <?php
                // Récupération de tous les articles
                $posts = getAllPosts();
                foreach ($posts as $key => $post) {
                    ?>
                    <tr>
                        <!-- Ligne de données du tableau -->
                        <td><?php echo $key + 1; ?></td>
                        <td><?php echo $post['author']; ?></td>
                        <td>
                            <!-- Lien vers l'article individuel -->
                            <a href="<?php echo BASE_URL . 'single_post.php?post-slug=' . $post['slug'] ?>">
                                <?php echo $post['title']; ?>
                            </a>
                        </td>
                        <td><?php echo $post['views']; ?></td>
                        <td>
                            <!-- Lien de modification de l'article -->
                            <a class="fa fa-check btn edit" style="background:green;" href="create_post.php?edit-post=<?php echo $post['id'] ?>">
                            </a>
                        </td>
                        <td>
                            <!-- Bouton de modification de l'article -->
                            <a class="fa fa-pencil btn edit" style="background:green;" href="create_post.php?edit-post=<?php echo $post['id'] ?>">
                            </a>
                        </td>
                        <td>
                            <!-- Bouton de suppression de l'article -->
                            <a class="fa fa-trash btn delete" style="background:red;" href="create_post.php?delete-post=<?php echo $post['id'] ?>">
                            </a>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>

<!-- Inclusion du pied de page public -->
<?php include(ROOT_PATH . '/includes/public/footer.php'); ?>
<!-- // Footer -->
