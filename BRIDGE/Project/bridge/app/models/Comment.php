<?php
# DESCRIPTION
/**
 * Comment model
 */

class Comment
{
    # ATTRIBUTES
    private Database $db;

    # CONSTRUCTOR
    public function __construct()
    {
        $this->db = new Database;
    }

    # METHODS 
    // To comment a post
    public function addComment($text, $commentator, $post, $path, $media_type)
    {
        // Prepare query and bind all values
        $this->db->query('INSERT INTO comments(id, text, commentator_id, post_id, media, media_type) VALUES (NULL, :text, :commentator_id, :post, :media, :media_type)');
        $this->db->bind(':text', $text);
        $this->db->bind(':commentator_id', $commentator);
        $this->db->bind(':post', $post);
        $this->db->bind(':media', $path);
        $this->db->bind(':media_type', $media_type);

        // Execute
        return $this->db->execute();
    }

    // To delete comment
    public function deleteComment($comment)
    {
        // Prepare query & bind value
        $this->db->query('DELETE FROM comments WHERE id = :commentID');
        $this->db->bind(':commentID', $comment);

        return $this->db->execute();
    }
}
