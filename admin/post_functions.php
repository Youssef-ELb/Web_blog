
<?php

/*--------------------
post_functions.php
------------------------*/

// Post variables
$post_id = 0;
$isEditingPost = false;
$published = 0;
$title = "";
$post_slug = "";
$body = "";
$featured_image = "";
$post_topic = "";


if (isset($_POST['create_post'])) {
    createPost($_POST);
}
if (!empty($_GET["edit-post"])) {
    $post_id = $_GET["edit-post"];
    editPost($post_id);
} 
if(isset($_POST['update_post'])){
    updatePost($_POST);
}
if (isset($_GET['delete-post'])) {
    $post_id = $_GET['delete-post'];
    deletePost($post_id);
}

/* - - - - - - - - - -
- Post functions
- - - - - - - - - - -*/

// get all posts from WEBLOG DATABASE
function getAllPosts() {
    global $conn;
    // fonction a besoin de la fonction getPostAuthorById
    $sql = "SELECT * FROM posts";
    $result = mysqli_query($conn, $sql);
    $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);
    
    // Get the author/username for each post
    foreach ($posts as &$post) {
        $post['author'] = getPostAuthorById($post['user_id']);
    }
    return $posts;
}
function makeSlug($string) {
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
    return $slug;
}

function createPost($request_values) {
    global $conn, $errors, $title, $featured_image, $topic_id, $body, $published;
    $user_id = $_SESSION['user']['id'];
    $title = $request_values['title'];
    $body = htmlentities($request_values['body']);
    // create slug: if title is "The Storm Is Over", return "the-storm-is-over" as slug
    $post_slug = makeSlug($title);
    if (isset($request_values['topic_id'])) {
        $topic_id = $request_values['topic_id'];
    }
    if (isset($request_values['publish'])) {
        $published = $request_values['publish'];
    }
  
    // validate form
    if (empty($title)) { array_push($errors, "Post title is required"); }
    if (empty($body)) { array_push($errors, "Post body is required"); }
    if (empty($topic_id)) { array_push($errors, "Post topic is required"); }
    // Get image name
    $featured_image = $_FILES['featured_image']['name'];
    if (empty($featured_image)) { array_push($errors, "Featured image is required"); }
    // image file directory
    $target = "../static/images/" . basename($featured_image);
    if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $target)) {
        $currentDateTime = date("Y-m-d H:i:s");
        $sql = "INSERT INTO posts VALUES(NULL, $user_id, '$title', '$post_slug', 0,'$featured_image', '$body', $published, '$currentDateTime', '$currentDateTime')";
        echo $sql;
        // Exécutez cette ligne de code à n'importe quel endroit de votre script pour afficher toutes les fonctions exécutées jusqu'à ce point.
       
        if (mysqli_query($conn, $sql)) {
            $inserted_post_id = mysqli_insert_id($conn);
            // create relationship between post and topic
            $sql = "INSERT INTO post_topic VALUES(NULL, $inserted_post_id, $topic_id)";
            echo $sql;
            if (mysqli_query($conn, $sql)) {
                $_SESSION['message'] = "Post created successfully";
                header('location: posts.php');
                exit(0);
            }
        }
    }
}

// get the author/username of a post
// cette fonction est dans post_functions.php
function getPostAuthorById($user_id) {
    global $conn;
    $sql = "SELECT username FROM users WHERE id=$user_id";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        // return username
        return mysqli_fetch_assoc($result)['username'];
    } else {
        return null;
    }
}

function editPost($post_id) {
    global $conn, $title, $post_slug, $body, $isEditingPost;

    $sql = "SELECT * FROM posts WHERE id=$post_id LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $post = mysqli_fetch_assoc($result);
    // set form values on the form to be updated
    $title = $post['title'];
    $body = $post['body'];
    $post_id = $post['id'];
    $isEditingPost = true;

}

function updatePost($request_values) {
    global $conn, $errors, $post_id, $title, $featured_image, $topic_id, $body, $published;
    // function implementation
    $post_id = $request_values['post_id'];
    $title = $request_values['title'];
    $body = $request_values['body'];
    // create slug: if title is "The Storm Is Over", return "the-storm-is-over" as slug
    $post_slug = makeSlug($title);
    if (isset($request_values['topic_id'])) {
        $topic_id = $request_values['topic_id'];
    }
    if (isset($request_values['publish'])) {
        $published = $request_values['publish'];
    }
    // validate form
    if (empty($title)) { array_push($errors, "Post title is required"); }
    if (empty($body)) { array_push($errors, "Post body is required"); }
    if (empty($topic_id)) { array_push($errors, "Post topic is required"); }
    // Get image name
    $featured_image = $_FILES['featured_image']['name'];
    if (empty($featured_image)) { array_push($errors, "Featured image is required"); }
    // image file directory
    $target = "../static/images/" . basename($featured_image);
    if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $target)) {
        $currentDateTime = date("Y-m-d H:i:s");
        $sql = "UPDATE posts SET title='$title', slug='$post_slug', image='$featured_image', body='$body', published=$published, updated_at='$currentDateTime' WHERE id=$post_id";
        if (mysqli_query($conn, $sql)) {
            if (isset($topic_id)) {
                $sql = "UPDATE post_topic SET topic_id=$topic_id WHERE post_id=$post_id";
                if (mysqli_query($conn, $sql)) {
                    $_SESSION['message'] = "Post updated successfully";
                    header('location: posts.php');
                    exit(0);
                }
            }
        }
    }

}

// delete blog post
function deletePost($post_id) {
    global $conn;
    $sql = "DELETE FROM posts WHERE id=$post_id";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = "Post successfully deleted";
        header("location: posts.php");
        exit(0);
    }
}

// delete blog post
function togglePublishPost($post_id, $message) {
    global $conn;
    $sql = "UPDATE posts SET published=!published WHERE id=$post_id";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = $message;
        header("location: posts.php");
        exit(0);
    }
}
function getAllTopics(){
    global $conn;
    // Get single post slug
    $sql = "SELECT * FROM topics";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        // fetch post
        $topics = mysqli_fetch_all($result, MYSQLI_ASSOC);
        
        return $topics;
    } 
}
