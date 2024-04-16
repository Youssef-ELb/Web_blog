<?php include('config.php'); ?>
<?php include('includes/all_functions.php'); ?>
<?php include('includes/public/head_section.php'); ?>
<?php include('includes/public/registration_login.php'); ?>
<?php include('includes/public/errors.php'); ?>
<?php include('admin/posts.php'); ?>


<title>MyWebSite | Home </title>
</head>
<body>

    <div class="container">

        <!-- Navbar -->
        <?php include(ROOT_PATH . '/includes/public/navbar.php'); ?>
        <!-- // Navbar -->

        <!-- Banner -->
        <?php include(ROOT_PATH . '/includes/public/banner.php'); ?>
        <!-- // Banner -->

        <!-- content -->
        <!-- content -->
    <div class="content">
    <h2 class="content-title">Recent Articles</h2>
    <hr>

      <?php $publishedPosts = getPublishedPosts(); ?>
      <?php foreach ($publishedPosts as $index => $post): ?>
        <div class="post" style="margin-left: 5px;">
            <!-- Display the topic above the image -->
            <div class="topic">
                <?php foreach ($post['topic'] as $topic): ?>
                    <?php $firstTopic = $post['topic'][0]; // Access the first topic ?>
                    <a href="filtered_posts.php?"><?php echo $topic; ?></a>

                <?php endforeach; ?>
                
            </div>
     

            <?php if ($index === 0): ?> <!-- Si c'est le premier post -->
                <img src="static/images/1200px-Sunflower_from_Silesia2.jpg" class="post_image" alt="<?php echo htmlspecialchars($post['title']) ; ?>">
            <?php elseif (!empty($post['image'])): ?> <!-- Autres posts avec une image -->
                <img src="<?php echo $post['image']; ?>" class="post_image" alt="<?php echo htmlspecialchars($post['title']); ?>">
            <?php endif; ?>

            <h3><a href="posts.php?post-slug=<?php echo $post['slug']; ?>"><?php echo htmlspecialchars($post['title']); ?></a></h3>
            <div class="info">
                <span><?php echo date('F j, Y', strtotime($post['created_at'])); ?></span>
                <span class="read_more"><a href="single_post.php?post-slug=<?php echo $post['slug']; ?>">Read more...</a></span>
            </div>
        </div>
    <?php endforeach; ?>
</div>

        <!-- // content -->

    </div>
    <!-- // container -->

    <!-- Footer -->
    <?php include(ROOT_PATH . '/includes/public/footer.php'); ?>
    <!-- // Footer -->

</body>
</html>
