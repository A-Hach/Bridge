<?php
# DESCRIPTION
/**
 * Country model
 */

class Country
{
    # ATTRIBUTES
    private Database $db;

    # CONSTRUCTOR
    public function __construct()
    {
        $this->db = new Database;
    }

    # METHODS 
    // To find country ID by name
    public function findCountryID($name)
    {
        // Prepare query and bind value
        $this->db->query('SELECT id FROM countries WHERE name = :name');
        $this->db->bind(':name', $name);

        return $this->db->singleResult();
    }
}
