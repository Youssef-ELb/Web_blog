<?php
//============================================================================//
//============================================================================//
function getPublishedPosts() {
    global $conn;
    $sql = "SELECT * FROM posts WHERE published=true";
    $result = mysqli_query($conn, $sql);
    $posts = array();
    while ($row = mysqli_fetch_assoc($result)) {
        // Récupérer le topic pour chaque article
        $topic = getPostTopic($row['id']);
        $row['topic'] = $topic;
        unset($row['body']);
        $posts[] = $row;
    }
    return $posts;
}

//============================================================================//
//============================================================================//

function getPostTopic($post_id) {
    global $conn;
    $sql = "SELECT topics.name FROM topics 
            JOIN post_topic ON topics.id = post_topic.topic_id 
            WHERE post_topic.post_id = $post_id";
    $result = mysqli_query($conn, $sql);
    $topics = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $topics[] = $row['name'];
    }
    return $topics;
}

//============================================================================//
//============================================================================//

// Fonction pour récupérer les données d'un article en fonction de son slug
function getPost($slug)
{
    global $conn;

    // Échapper les données pour éviter les injections SQL
    $safe_slug = mysqli_real_escape_string($conn, $slug);

    // Requête pour récupérer le post avec le slug donné
    $query = "SELECT * FROM posts WHERE slug = '$safe_slug'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Erreur dans la requête : " . mysqli_error($conn));
    }

    // Vérifier s'il y a des résultats
    if (mysqli_num_rows($result) > 0) {
        $post = mysqli_fetch_assoc($result);
        
        // Récupérer le topic de l'article
        $topic_id = $post['id']; // Supposons que l'identifiant du topic est stocké dans la même table 'posts'
        $topic = getPostTopic($topic_id);
        
        // Ajouter le topic à l'array des données de l'article
        $post['topic'] = $topic;
        
        return $post;
    } else {
        return null; // Aucun article trouvé avec ce slug
    }
}

//============================================================================//
//============================================================================//

// Fonction pour récupérer tous les topics depuis la base de données

// Fonction pour récupérer tous les topics depuis la base de données
function getAllTopics() {
    global $conn;
    
    // Préparer la requête SQL pour récupérer tous les topics
    $query = "SELECT * FROM topics";
    
    // Exécuter la requête
    $result = mysqli_query($conn, $query);
    
    // Vérifier s'il y a des résultats
    if ($result) {
        // Récupérer tous les topics et les stocker dans un array
        $topics = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $topics;
    } else {
        return null; // Retourner null si aucun topic n'est trouvé
    }
}
//============================================================================//
//============================================================================//

/**
 * This function returns the published posts for a specific topic
 */
function getPublishedPostsByTopic($topic_id) {
    global $conn;

    // Prepare the SQL query to fetch published posts for the specified topic
    $sql = "SELECT * FROM posts WHERE topic_id = $topic_id AND published = true";

    // Execute the SQL query
    $result = mysqli_query($conn, $sql);

    // Initialize an empty array to store the final posts
    $final_posts = array();

    // Check if there are any posts returned by the query
    if (mysqli_num_rows($result) > 0) {
        // Fetch all posts as an associative array called $posts
        $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);

        // Loop through each post
        foreach ($posts as $post) {
            // Get the topic of the post using the getPostTopic function
            $post['topic'] = getPostTopic($post['id']);

            // Push the post with its topic into the final_posts array
            array_push($final_posts, $post);
        }
    }

    // Return the final array of posts
    return $final_posts;
}

//============================================================================//
//============================================================================//
// Dans all_functions.php


?>
