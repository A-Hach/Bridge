<?php
# DESCRIPTION
/**
 * Search model
 */

class Search
{
    # ATTRIBUTES
    private Database $db;

    # CONSTRUCTOR
    public function __construct()
    {
        $this->db = new Database;
    }

    # METHODS 
    // To find results of users
    public function users($keywords)
    {
        // Prepare query and bind value
        $this->db->query("SELECT * FROM profiles pr INNER JOIN users u ON pr.owner_id = u.id INNER JOIN levels l ON l.id = u.level_id WHERE pr.display_name LIKE CONCAT('%', :keywords, '%') OR pr.personnalities LIKE CONCAT('%', :keywords, '%') OR u.username LIKE CONCAT('%', :keywords, '%')");
        $this->db->bind(':keywords', $keywords);

        return $this->db->allResults();
    }

    // To find result of posts
    public function posts($tags, $profileID)
    {
        // Prepare query and bind value
        $this->db->query("SELECT u.id AS user_id, pr.id AS profile_id, u.username, pr.main_picture, p.id AS post_id, p.posting_date, p.text, p.media, p.media_type, p.total_reactions, p.total_comments, p.total_views, CASE WHEN EXISTS (SELECT * FROM reactions WHERE post_id = p.id AND reactor_id = :myProfileID) THEN 1 ELSE 0 END AS liked FROM posts p INNER JOIN profiles pr ON p.author_id = pr.id INNER JOIN users u ON pr.owner_id = u.id WHERE (p.tags LIKE CONCAT('%', :tags, '%') OR p.text LIKE CONCAT('%', :tags, '%')) AND ((pr.id = :myProfileID) OR (pr.confidentiality = 1 OR (pr.confidentiality = 0 AND EXISTS(SELECT * FROM invitations WHERE state = 1 AND ((sender_id = :myProfileID AND receiver_id = pr.id) OR (sender_id = pr.id AND receiver_id = :myProfileID)))))) ORDER BY p.total_reactions DESC, p.posting_date DESC");
        $this->db->bind(':tags', $tags);
        $this->db->bind(':myProfileID', $profileID);

        return $this->db->allResults();
    }
}
