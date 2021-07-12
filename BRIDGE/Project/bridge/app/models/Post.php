<?php
# DESCRIPTION
/**
 * Post model
 */

class Post
{
    # ATTRIBUTES
    private Database $db;

    # CONSTRUCTOR
    public function __construct()
    {
        $this->db = new Database;
    }

    # METHODS 
    // To find post by id
    public function findPostByID($id)
    {
        $user = new User;

        // Prepare query and bind value
        $this->db->query('SELECT u.id AS user_id, pr.id AS profile_id, pr.confidentiality, pr.display_name, u.username, pr.main_picture, p.id AS post_id, p.posting_date, p.text, p.media, p.media_type, p.total_reactions, p.total_comments, CASE WHEN EXISTS (SELECT * FROM reactions WHERE post_id = p.id AND reactor_id = :myID) THEN 1 ELSE 0 END AS liked FROM posts p INNER JOIN profiles pr ON p.author_id = pr.id INNER JOIN users u ON pr.owner_id = u.id WHERE p.id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':myID', $user->getUserInfo()[0]['profile_id']);

        return $this->db->singleResult();
    }

    // To post text
    public function postText($text, $tags, $profileID)
    {
        // Prepare query and bind all values
        $this->db->query('INSERT INTO posts(text, tags, media_type, author_id) VALUES (:text, :tags, :mediaType, :profileID)');
        $this->db->bind(':text', $text);
        $this->db->bind(':tags', $tags);
        $this->db->bind(':mediaType', 'text');
        $this->db->bind(':profileID', $profileID);

        // Execute
        return $this->db->execute();
    }

    // To save post 
    public function save($post)
    {
        $user = new User;

        // Check if the post is exist
        $this->db->query('SELECT * FROM play_lists WHERE author_id = :myID AND post_id = :postID');
        $this->db->bind(':postID', $post);
        $this->db->bind(':myID', $user->getUserInfo()[0]['profile_id']);

        if (count($this->db->allResults()) == 0) {
            // Add it
            $this->db->query('INSERT INTO play_lists(id, author_id, post_id) VALUES (NULL, :myID, :postID)');
            $this->db->bind(':postID', $post);
            $this->db->bind(':myID', $user->getUserInfo()[0]['profile_id']);

            return $this->db->execute();
        }

        return;
    }

    // To unsave post 
    public function unsave($post)
    {
        $user = new User;

        // Check if the post is exist
        $this->db->query('DELETE FROM play_lists WHERE author_id = :myID AND post_id = :postID');
        $this->db->bind(':postID', $post);
        $this->db->bind(':myID', $user->getUserInfo()[0]['profile_id']);

        return $this->db->execute();
    }

    // To delete post
    public function delete($post)
    {
        $user = new User;

        // Check if the post is mine
        $this->db->query('SELECT * FROM posts WHERE author_id = :myID');
        $this->db->bind(':myID', $user->getUserInfo()[0]['profile_id']);

        if (count($this->db->allResults()) == 0)
            // Not mine
            redirect('home');
        else {
            // Prepare query and bind values
            $this->db->query('DELETE FROM posts WHERE id = :postID');
            $this->db->bind(':postID', $post);

            return $this->db->execute();
        }

        return;
    }

    // To post media
    public function postMedia($text, $path, $tags, $profileID, $type)
    {
        // Prepare query and bind all values
        $this->db->query('INSERT INTO posts(text, media, tags, media_type, author_id) VALUES (:text, :media, :tags, :mediaType, :profileID)');
        $this->db->bind(':text', $text);
        $this->db->bind(':media', $path);
        $this->db->bind(':tags', $tags);
        $this->db->bind(':mediaType', $type);
        $this->db->bind(':profileID', $profileID);

        // Execute
        return $this->db->execute();
    }

    // To get top 5 porst of my me and friends
    public function getTopFriendsPosts()
    {
        $user = new User;

        // Prepare query & bind value
        $this->db->query("SELECT pr.main_picture, u.username, u.id, p.posting_date, p.id AS post_id, p.total_reactions, p.total_comments, p.text, p.media, p.media_type FROM posts p INNER JOIN profiles pr ON pr.id = p.author_id INNER JOIN users u ON pr.owner_id = u.id WHERE p.media_type = 'image' AND (pr.id = :myID OR pr.id IN ((SELECT receiver_id FROM invitations WHERE sender_id = :myID  AND state = 1) union (SELECT sender_id FROM invitations WHERE receiver_id = :myID  AND state = 1))) AND (p.total_reactions <> 0 OR p.total_comments <> 0 OR p.total_views <> 0) ORDER BY p.total_reactions DESC, p.total_comments DESC LIMIT 5");
        $this->db->bind(':myID', $user->getUserInfo()[0]['profile_id']);

        return $this->db->allResults();
    }

    // To get all alowed posts
    public function getAllAlowedPosts()
    {
        $user = new User;

        // Prepare query & bind value
        $this->db->query('SELECT pr.main_picture, u.username, u.id, p.posting_date, p.id AS post_id, p.total_reactions, p.total_comments, p.total_views, p.text, p.media, p.media_type, CASE WHEN EXISTS (SELECT * FROM reactions WHERE post_id = p.id AND reactor_id = :myID) THEN 1 ELSE 0 END AS liked FROM posts p INNER JOIN profiles pr ON pr.id = p.author_id INNER JOIN users u ON pr.owner_id = u.id WHERE pr.id = :myID OR pr.id IN ((SELECT receiver_id FROM invitations WHERE sender_id = :myID AND state = 1) union (SELECT sender_id FROM invitations WHERE receiver_id = :myID AND state = 1)) ORDER BY p.posting_date DESC');
        $this->db->bind(':myID', $user->getUserInfo()[0]['profile_id']);

        return $this->db->allResults();
    }

    // To get top 5 activitis
    public function getTopPosts()
    {
        $user = new User;

        // Prepare query & bind value
        $this->db->query("SELECT p.media, p.id, p.total_reactions, p.total_comments, p.media_type FROM posts p INNER JOIN profiles pr ON pr.id = p.author_id WHERE pr.id = :myID AND p.media_type <> 'text' AND (p.total_reactions <> 0 OR p.total_comments <> 0 OR p.total_views <> 0) ORDER BY p.total_reactions DESC, p.total_comments DESC, p.total_views DESC LIMIT 5");
        $this->db->bind(':myID', $user->getUserInfo()[0]['profile_id']);

        return $this->db->allResults();
    }

    // To get play list
    public function playList()
    {
        $user = new User;

        // Prepare query & bind value
        $this->db->query('SELECT u.username, u.id AS user_id, p.posting_date, pl.action_date, pr.id AS profile_id, p.total_reactions, p.total_comments, p.total_views, p.text, p.media, p.media_type, p.id AS post_id, pr.main_picture, CASE WHEN EXISTS (SELECT * FROM reactions WHERE post_id = p.id AND reactor_id = :myID) THEN 1 ELSE 0 END AS liked FROM play_lists pl INNER JOIN profiles pr ON pr.id = (SELECT author_id FROM posts WHERE id = pl.post_id) INNER JOIN users u ON pr.owner_id = u.id INNER JOIN posts p ON p.id = pl.post_id WHERE pl.author_id = :myID ORDER BY pl.action_date DESC, p.total_reactions, p.total_comments');
        $this->db->bind(':myID', $user->getUserInfo()[0]['profile_id']);

        return $this->db->allResults();
    }

    // To like post
    public function react($post)
    {
        $user = new User;

        // Check if is exist
        $this->db->query('SELECT * FROM reactions WHERE reactor_id = :myID AND post_id = :postID');
        $this->db->bind(':myID', $user->getUserInfo()[0]['profile_id']);
        $this->db->bind(':postID', $post);

        $query = '';
        $reaction = 1;
        if (count($this->db->allResults()) != 0) {
            // Unlike
            $query = 'DELETE FROM reactions WHERE reactor_id = :myID AND post_id = :postID';
            $reaction = -1;
        } else
            // Like
            $query = 'INSERT INTO reactions(id, reactor_id, post_id) VALUES(NULL, :myID, :postID)';

        // Prepare query and bind values
        $this->db->query($query);
        $this->db->bind(':myID', $user->getUserInfo()[0]['profile_id']);
        $this->db->bind(':postID', $post);

        return [$reaction, $this->db->execute()];
    }

    // To edit post
    public function edit($post, $text)
    {
        $user = new User;

        // Check if the post is mine
        $this->db->query('SELECT * FROM posts WHERE author_id = :myID');
        $this->db->bind(':myID', $user->getUserInfo()[0]['profile_id']);

        if (count($this->db->allResults()) == 0)
            // Not mine
            redirect('home');
        else {
            // Prepare query and bind values
            $this->db->query('UPDATE posts SET text = :text WHERE id = :postID');
            $this->db->bind(':text', $text);
            $this->db->bind(':postID', $post);

            return $this->db->execute();
        }

        return;
    }

    // To increment post view
    public function postView($post)
    {
        // Prepare query & bind value
        $this->db->query('UPDATE posts SET total_views = total_views + 1 WHERE id = :postID');
        $this->db->bind(':postID', $post);

        return $this->db->execute();
    }

    // To check if I've persmission to see a post
    public function havePermission($post)
    {
        $user = new User;

        $author = $this->findPostByID($post)['profile_id'];
        $me = $user->getUserInfo()[0]['profile_id'];

        if ($author == $me)
            return true;

        $confidentiality = ((int) $this->findPostByID($post)['confidentiality']);

        return ($confidentiality == 0 && ((int) $user->friendshipState($author)) != 0) ? false : true;
    }

    // To get post comments
    public function getComments($post)
    {
        $user = new User;

        // Prepare query & bind value
        $this->db->query('SELECT c.id AS comment_id, u.username, u.id AS user_id, p.id AS profile_id, p.main_picture, c.comment_date, c.text, c.media, c.media_type, CASE WHEN c.commentator_id = :myID THEN 1 ELSE 0 END AS my_comment, CASE WHEN ps.author_id = :myID THEN 1 ELSE 0 END AS my_post FROM comments c INNER JOIN profiles p ON p.id = c.commentator_id INNER JOIN users u ON u.id = p.owner_id INNER JOIN posts ps ON c.post_id = ps.id WHERE c.post_id = :postID ORDER BY c.comment_date DESC');
        $this->db->bind(':postID', $post);
        $this->db->bind(':myID', $user->getUserInfo()[0]['profile_id']);

        return $this->db->allResults();
    }

    // To get all my posts
    public function getMyPosts($username)
    {
        $user = new User;

        // Prepare query & bind value
        $this->db->query('SELECT * FROM posts where author_id = :id ORDER BY posting_date DESC');
        $this->db->bind(':id', $user->getUserInfoByUsername($username)[0]['profile_id']);

        return $this->db->allResults();
    }
}
