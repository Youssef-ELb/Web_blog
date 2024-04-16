<?php
    function getAdminUsers(){
        global $conn;

        $sql="SELECT * FROM `users`;";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $topic = mysqli_fetch_all($result, MYSQLI_ASSOC);
            return $topic;
        } else {
            // Gestion des erreurs si la requête échoue
            return null;
        }
    }
?>

<?php
    // Admin user variables
    $admin_id = 0;
    $isEditingUser = false;
    $username = "";
    $email = "";
    // Topics variables
    $topic_id = 0;
    $isEditingTopic = false;
    $topic_name = "";
    // general variables
    $errors=array();
    /* - - - - - - - - - -
    -
    - Admin users actions
    -
    - - - - - - - - - - -*/
    // if user clicks the create admin button
    if (isset($_POST['create_admin'])) {
        getAdminRoles(); // Add this line to retrieve admin roles
        createAdmin($_POST);
    }
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    * - Returns all admin users and their corresponding roles
    * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    if (isset($_POST['update_admin'])) {
        updateAdmin($_POST);
    }
    if (!empty($_GET["edit-admin"])) {
        $admin_id = $_GET["edit-admin"];
        $isEditingUser = true;
    
        $sql = "SELECT * FROM users WHERE id=$admin_id LIMIT 1";
        $result = mysqli_query($conn, $sql);
        $user= mysqli_fetch_assoc($result);
        $username = $user['username'];
        $email = $user['email'];
        
    } 
    if (isset($_GET['delete-admin'])) {
        $admin_id = $_GET['delete-admin'];
        deleteAdmin($admin_id);
    }
// Fonction pour récupérer tous les utilisateurs administrateurs et leurs rôles correspondants






    // Fonction pour récupérer tous les rôles d'administrateur
    function getAdminRoles(){
        global $conn;

        $sql="SELECT * FROM roles";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $role = mysqli_fetch_all($result, MYSQLI_ASSOC);
            return $role;
        } else {
            // Gestion des erreurs si la requête échoue
            return null;
        }
    }

    // Fonction pour créer un nouvel utilisateur administrateur
    function createAdmin($request_values){
        global $conn;
        global $errors;
        // Récupérer les données du formulaire
        $username = mysqli_real_escape_string($conn, $request_values['username']);
        
        $email = mysqli_real_escape_string($conn, $request_values['email']);
        $password1 = mysqli_real_escape_string($conn, $request_values['password']);
        $password2 = mysqli_real_escape_string($conn, $request_values['passwordConfirmation']);
        if (!isset($role_id) || empty($role_id)) { array_push($errors, "Role is required for admin users"); }
        else{
            $role_id = mysqli_real_escape_string($conn, $request_values['role_id']);
        }
        // Vérifier si les champs obligatoires sont vides
        if (empty($username)) { array_push($errors, "Username required"); }
        if (empty($email)) { array_push($errors, "Email required"); }
        if (empty($password1)) { array_push($errors, "Password required"); }
        // Vérifier si les mots de passe correspondent
        if ($password1 != $password2) {
            array_push($errors, "Passwords do not match");
        }
        
        // Vérifier l'unicité du couple (username, email)
        $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
        $result = mysqli_query($conn, $user_check_query);
        $user = mysqli_fetch_assoc($result);
        if ($user) {
            if ($user['username'] === $username) {
                array_push($errors, "Username already exists");
            }
            if ($user['email'] === $email) {
                array_push($errors, "Email already exists");
            }
        }
        // Si aucune erreur, inscrire l'utilisateur
        if (empty($errors)) {
            // Hasher le mot de passe avant de le stocker dans la base de données
            $password_hash = md5($password1);
            // Récupérer la date et l'heure actuelles
            $currentDateTime = date("Y-m-d H:i:s");
            // Récupérer le rôle
            // Récupérer le nom du rôle d'administrateur
            $sql="SELECT * FROM roles WHERE id=$role_id";
            $result = mysqli_query($conn, $sql);
            $role = mysqli_fetch_assoc($result);


            // Utiliser cette date dans votre requête SQL
            $query = "INSERT INTO `users` VALUES (NULL, '$username', '$email', '".$role["name"]."', '$password_hash',  '$currentDateTime', '$currentDateTime')";
            mysqli_query($conn, $query);
            // Vérifier si l'insertion a réussi
            if (mysqli_affected_rows($conn) > 0) {
                // Succès
                return "Utilisateur administrateur créé avec succès";
            } else {
                // Échec
                return "Erreur lors de la création de l'utilisateur administrateur";
            }
        }
       
    }
    // Fonction pour mettre à jour un utilisateur administrateur
    function updateAdmin($request_values){
        global $conn;
        global $errors;
        // Récupérer les données du formulaire
        $admin_id = mysqli_real_escape_string($conn, $request_values['admin_id']);
        $username = mysqli_real_escape_string($conn, $request_values['username']);
        $email = mysqli_real_escape_string($conn, $request_values['email']);
        $password1 = mysqli_real_escape_string($conn, $request_values['password']);
        $password2 = mysqli_real_escape_string($conn, $request_values['passwordConfirmation']);
        if (!isset($request_values['role_id']) || empty($request_values['role_id'])) { array_push($errors, "Role is required for admin users"); }
        else{
            $role_id = mysqli_real_escape_string($conn, $request_values['role_id']);
        }
        // Vérifier si les champs obligatoires sont vides
        if (empty($username)) { array_push($errors, "Username required"); }
        if (empty($email)) { array_push($errors, "Email required"); }
        if (empty($password1)) { array_push($errors, "Password required"); }
        // Vérifier si les mots de passe correspondent
        if ($password1 != $password2) {
            array_push($errors, "Passwords do not match");
        }
       
        // Si aucune erreur, inscrire l'utilisateur
        if (empty($errors)) {
            // Hasher le mot de passe avant de le stocker dans la base de données
            $password_hash = md5($password1);
            // Récupérer la date et l'heure actuelles
            $currentDateTime = date("Y-m-d H:i:s");
            // Récupérer le rôle
            // Récupérer le nom du rôle d'administrateur
            $sql="SELECT * FROM roles WHERE id=$role_id";
            $result = mysqli_query($conn, $sql);
            $role = mysqli_fetch_assoc($result);
            // Utiliser cette date dans votre requête SQL
            $query = "UPDATE `users` SET username='$username', email='$email', role='".$role["name"]."', password='$password_hash', updated_at='$currentDateTime' WHERE id=$admin_id";
            mysqli_query($conn, $query);
            // Vérifier si l'insertion a réussi
            if (mysqli_affected_rows($conn) > 0) {
                // Succès
                return "Utilisateur administrateur mis à jour avec succès";
            } else {
                // Échec
                return "Erreur lors de la mise à jour de l'utilisateur administrateur";
            }
        }
    }
    // Fonction pour supprimer un utilisateur administrateur
    function deleteAdmin($admin_id){
        global $conn;
        $sql = "DELETE FROM users WHERE id=$admin_id";
        if (mysqli_query($conn, $sql)) {
            return "Utilisateur administrateur supprimé avec succès";
        } else {
            return "Erreur lors de la suppression de l'utilisateur administrateur";
        }
    }



?>