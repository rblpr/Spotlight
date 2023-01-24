<?php

class DatabaseHelper{
    private $db;

    public function __construct($servername, $username, $password, $dbname, $port){
        $this->db = new mysqli($servername, $username, $password, $dbname, $port);
        if($this->db->connect_error){
            die("Connesione fallita al db");
        }
    }

    /* 
    public function getRandomPosts($n=2){
        $stmt = $this->db->prepare("SELECT idarticolo, titoloarticolo, imgarticolo FROM articolo ORDER BY RAND() LIMIT ?");
        $stmt->bind_param("i", $n);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }
    */

    public function getArtistLikes($artistId){
        $stmt = $this->db->prepare("SELECT username FROM `likes` WHERE element_link=?");
        $stmt->bind_param("s", $artistId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAlbumLikes($albumId){
        $stmt = $this->db->prepare("SELECT username FROM `likes` WHERE element_link=?");
        $stmt->bind_param("s", $albumId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getPostData($postId) {
        $stmt = $this->db->prepare("SELECT * FROM `post` WHERE post_id=?");
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getUserData($userId) {
        $stmt = $this->db->prepare("SELECT * FROM `user` WHERE username=?");
        $stmt->bind_param("s", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getComments($postId) {
        $stmt = $this->db->prepare("SELECT * FROM `comment` WHERE post_id=?");
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getCommentReplies($commentId) {
        $stmt = $this->db->prepare("SELECT * FROM `replies` WHERE thread=?");
        $stmt->bind_param("i", $commentId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getPostLikes($postId) {
        $stmt = $this->db->prepare("SELECT username FROM `likes_post` WHERE post_id=?");
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getFriendsCount($username) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM friends WHERE Friend_username=? OR username=?");
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getFollowerCount($username) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM follows WHERE Follower_username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getFollowingCount($username) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM follows WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function followUser($sender, $receiver){
        $stmt = $this->db->prepare("INSERT INTO follows (Follower_username, username) VALUES (?,?) ");
        $stmt->bind_param("ss", $receiver, $sender);
        $stmt->execute();
    }

    public function unfollowUser($sender, $receiver){
        $stmt = $this->db->prepare("DELETE FROM follows WHERE Follower_username=? AND username=? ");
        $stmt->bind_param("ss", $receiver, $sender);
        $stmt->execute();
    }

    //friend request?
    public function eliminateFriend($sender, $receiver){
        $stmt = $this->db->prepare("DELETE FROM friends WHERE (Friend_username=? AND username=?) AND (Friend_username=? AND username=?)");
        $stmt->bind_param("ssss", $receiver, $sender, $sender, $receiver);
        $stmt->execute();
    }

    public function isFollower($sender, $receiver){
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM follows WHERE Follower_username=? AND username=?");
        $stmt->bind_param("ss", $sender, $receiver);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function isFriend($sender, $receiver){
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM friends WHERE Friend_username=? AND username=?");
        $stmt->bind_param("ss", $sender, $receiver);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getPostsOfUser($username){
        $stmt = $this->db->prepare("SELECT post_id, text, song, date, number_of_likes, username FROM post WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getReviewsOfUser($username){
        $stmt = $this->db->prepare("SELECT review_id, text, album, date, score, number_of_likes, number_of_dislikes, username FROM review WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getLikedAlbumsByUser($username){
        $stmt = $this->db->prepare("SELECT element_link FROM likes, spotify_element WHERE username=? AND likes.element_link = spotify_element.element_link AND spotify_element.type = Album");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getLikedArtistsByUser($username){
        $stmt = $this->db->prepare("SELECT element_link FROM likes, spotify_element WHERE username=? AND likes.element_link = spotify_element.element_link AND spotify_element.type = Artist");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function updateUserData($username, $data){
        $command = "UPDATE user SET";
        $index = 0;
        $parametric = '';
        $upd = array();
        //potrei fare una funzione generale
        if(isset($data['username'])){
            $command = $command . " username=?";
            $parametric = $parametric . 's';
            array_push($upd, $data['username']);
            $index += 1;
        }
        if(isset($data['first_name'])){
            if($index > 0){
                $command = $command . ",";
            }
            $command = $command . " first_name=?";
            $parametric = $parametric . 's';
            array_push($upd,$data['first_name']);
            $index += 1;
        }
        if(isset($data['last_name'])){
            if($index > 0){
                $command = $command . ",";
            }
            $command = $command . " last_name=?";
            $parametric = $parametric . 's';
            array_push($upd,$data['last_name']);
            $index += 1;
        }
        if(isset($data['profile_pic'])){
            if($index > 0){
                $command = $command . ",";
            }
            $command = $command . " profile_pic=?";
            $parametric = $parametric . 's';
            array_push($upd, $data['profile_pic']);
            $index += 1;
        }
        if($index == 0){
            return;
        }
        $stmt = $this->db->prepare($command . " WHERE 1");
        $stmt->bind_param($parametric, ...$upd);
        $stmt->execute();
    }

    function checkbrute($username) {
        // Recupero il timestamp
        $now = time();
        // Vengono analizzati tutti i tentativi di login a partire dalle ultime due ore.
        $valid_attempts = $now - (2 * 60 * 60); 
        if ($stmt = $this->db->prepare("SELECT time FROM login_attempts WHERE username = ? AND time > ?")) { 
           $stmt->bind_param('si', $username, $valid_attempts); 
           // Eseguo la query creata.
           $stmt->execute();
           $stmt->store_result();
           // Verifico l'esistenza di più di 5 tentativi di login falliti nelle ultime due ore.
           if($stmt->num_rows > 5) {
              return true;
           } else {
              return false;
           }
        }
    }

    function login($email, $password) {
        // Usando statement sql 'prepared' non sarà possibile attuare un attacco di tipo SQL injection.
        if ($stmt = $this->db->prepare("SELECT username, password, salt FROM user WHERE email = ? LIMIT 1")) { 
           $stmt->bind_param('s', $email); // esegue il bind dei parametri.
           $stmt->execute(); // esegue la query appena creata.
           $stmt->store_result();
           $stmt->bind_result($username, $db_password, $salt); // recupera il risultato della query e lo memorizza nelle relative variabili.
           $stmt->fetch();
           $password = hash('sha512', $password.$salt); // codifica la password usando una chiave univoca.
           if($stmt->num_rows == 1) { // se l'utente esiste
              // verifichiamo che non sia disabilitato in seguito all'esecuzione di troppi tentativi di accesso errati.
                if($this->checkbrute($username) == true) { 
                 // Account disabilitato
                 // Invia un e-mail all'utente avvisandolo che il suo account è stato disabilitato.
                    return 0;
            } else {
                if($db_password == $password) { // Verifica che la password memorizzata nel database corrisponda alla password fornita dall'utente.
                 // Password corretta!         
                    $user_browser = $_SERVER['HTTP_USER_AGENT']; // Recupero il parametro 'user-agent' relativo all'utente corrente.
                    $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username); // ci proteggiamo da un attacco XSS
                    $_SESSION['username'] = $username;
                    $_SESSION['login_string'] = hash('sha512', $password.$user_browser);
                    registerLoggedUser($username);
                    // Login eseguito con successo.
                    return 1;    
                } else {
                 // Password incorretta.
                 // Registriamo il tentativo fallito nel database.   
                 $now = time();
                 $this->db->query("INSERT INTO login_attempts (username, time) VALUES ('$username', '$now')");
                 return 2;
              }
            }
            } else {
                // L'utente inserito non esiste.
                return 2;
            }
        }
    }

     function login_check() {
        // Verifica che tutte le variabili di sessione siano impostate correttamente
        if(isset($_SESSION['username'], $_SESSION['login_string'])) {
          $login_string = $_SESSION['login_string'];
          $username = $_SESSION['username'];     
          $user_browser = $_SERVER['HTTP_USER_AGENT']; // reperisce la stringa 'user-agent' dell'utente.
          if ($stmt = $this->db->prepare("SELECT password FROM user WHERE username = ? LIMIT 1")) { 
             $stmt->bind_param('i', $username); // esegue il bind del parametro '$user_id'.
             $stmt->execute(); // Esegue la query creata.
             $stmt->store_result();
      
             if($stmt->num_rows == 1) { // se l'utente esiste
                $stmt->bind_result($password); // recupera le variabili dal risultato ottenuto.
                $stmt->fetch();
                $login_check = hash('sha512', $password.$user_browser);
                if($login_check == $login_string) {
                   // Login eseguito!!!!
                   return true;
                }
             }
          }
        } 
        return false;
    }

    public function checkUser($username, $email){
        $query = "SELECT * FROM user WHERE username = ? OR email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ss',$username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function createFullUser($username, $email, $password, $salt, $first_name, $last_name, $propic){
        $query = "INSERT INTO `user` (`username`, `email`, `password`, `salt`, `first_name`, `last_name`, `profile_pic`) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('sssssss',$username, $email, $password, $salt, $first_name, $last_name, $propic);
        $stmt->execute();
    }

    public function createUser($username, $email, $password, $salt, $first_name, $last_name){
        $query = "INSERT INTO `user` (`username`, `email`, `password`, `salt`, `first_name`, `last_name`) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ssssss',$username, $email, $password, $salt, $first_name, $last_name);
        $stmt->execute();
    }


    public function toggleArtistLike($id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as `num` FROM `likes` WHERE `username` = ? AND `element_link`= ?");
        $stmt->bind_param("ss", $_COOKIE["username"], $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $result = $result->fetch_all(MYSQLI_ASSOC);

        if($result[0]["num"] > 0) {
            $stmt = $this->db->prepare("DELETE FROM `likes` WHERE `username` = ? AND `element_link`= ?");
            $stmt->bind_param("ss", $_COOKIE["username"], $id);
            $stmt->execute();
            return 0;
        } else {
            $stmt = $this->db->prepare("INSERT INTO `likes` VALUES (?, ?)");
            $stmt->bind_param("ss", $id, $_COOKIE["username"]);
            $stmt->execute();
            return 1;
        } 

    }

    public function createSpotifyElement($type, $id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as `num` FROM `spotify_element` WHERE `type` = ? AND `element_link`= ?");
        $stmt->bind_param("ss", $type, $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $result = $result->fetch_all(MYSQLI_ASSOC);

        if($result[0]["num"] == 0) {
            $stmt = $this->db->prepare("INSERT INTO `spotify_element` VALUES (?, ?)");
            $stmt->bind_param("ss", $type, $id);
            $stmt->execute();
        } 

    }
}
?>