<?php
# DESCRIPTION
/**
 * Testimonial model
 */

class Testimonial
{
    # ATTRIBUTES
    private Database $db;

    # CONSTRUCTOR
    public function __construct()
    {
        $this->db = new Database;
    }

    # METHODS 
    // To get all visible testimonials
    public function getVisibleTestimonials()
    {
        // Prepare query
        $this->db->query('SELECT p.display_name, p.main_picture, l.name, l.badge, t.date, t.text FROM testimonials t INNER JOIN profiles p ON t.author_id = p.id INNER JOIN users u ON p.owner_id = u.id INNER JOIN levels l ON u.level_id = l.id WHERE t.visible = 1');

        return $this->db->allResults();
    }
}
