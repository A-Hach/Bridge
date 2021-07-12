<?php
# DESCRIPTION
/**
 * Auth model
 */

class Auth
{
    # ATTRIBUTES
    private Database $db;

    # CONSTRUCTOR
    public function __construct()
    {
        $this->db = new Database;
    }

    # METHODS 
    // To find user by username
    public function findUserByUsername($username)
    {
        // Prepare query and bind username value
        $this->db->query('SELECT * FROM users WHERE username = :username');
        $this->db->bind(':username', $username);

        // Return result
        return $this->db->singleResult();
    }

    // To find user by email
    public function findUserByEmail($email)
    {
        // Prepare query and bind email value
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        // Return result
        return $this->db->singleResult();
    }

    // To register
    public function register($data)
    {
        // Prepare query and bind all values
        $this->db->query('INSERT INTO users(first_name, last_name, username, email, password, date_of_birth, place_of_birth, sexe, country_id, level_id, role_id) VALUES (:first_name, :last_name, :username, :email, :password, :date_of_birth, :place_of_birth, :sexe, :country_id, :level_id, :role_id)');

        $this->db->bind(':first_name', $data['first_name']);
        $this->db->bind(':last_name', $data['last_name']);
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', password_hash($data['password'], PASSWORD_BCRYPT));
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':place_of_birth', $data['city']);
        $this->db->bind(':country_id', $data['country']);
        $this->db->bind(':sexe', $data['sexe']);
        $this->db->bind(':level_id', 1);
        $this->db->bind(':role_id', 2);

        // Check for date if is valid
        if (is_int((int) $data['month']) && is_int((int) $data['year']) && is_int((int) $data['day']))
            if (checkdate((int) $data['month'], (int) $data['day'], (int) $data['year']))
                $this->db->bind(':date_of_birth', getDateString($data['year'], $data['month'], $data['day']));
            else
                $this->db->bind(':date_of_birth', null);
        else
            $this->db->bind(':date_of_birth', null);

        if ($this->db->execute()) {
            $user = $this->findUserByUsername($data['username']);
            $this->createProfile($user);

            return true;
        }

        return false;
    }

    public function createProfile($user)
    {
        // INSERT profile
        $this->db->query('INSERT INTO profiles(id, display_name, owner_id) VALUES(NULL, :display_name, :owner_id)');

        // Bind values
        $this->db->bind(':display_name', $user['first_name'] . ' ' . $user['last_name']);
        $this->db->bind(':owner_id', $user['id']);

        return $this->db->execute();
    }

    // To login
    public function login($login, $password)
    {
        // Prepare query & bind values
        $this->db->query('SELECT id, password, username FROM users WHERE username = :login OR email = :login');

        // Bind values
        $this->db->bind(':login', $login);

        $user = $this->db->singleResult();

        if ($user)
            if (password_verify($password, $user['password'])) {
                // Update state from 0 to 1 (Connected)
                $this->db->query("UPDATE profiles p INNER JOIN users u ON p.owner_id = u.id SET p.state = 1 WHERE u.username = :login OR u.email = :login");
                $this->db->bind(':login', $login);
                $this->db->execute();
                return $user;
            }

        return false;
    }

    // To logout
    public function logout()
    {
        // Update state from 1 to 0 (Disconnected)
        $this->db->query("UPDATE profiles p INNER JOIN users u ON p.owner_id = u.id SET p.state = 0 WHERE u.username = :login");
        $this->db->bind(':login', $_SESSION['username']);
        $this->db->execute();

        // Update last activities to current date (Disconnected)
        $this->db->query("UPDATE profiles p INNER JOIN users u ON p.owner_id = u.id SET p.last_activity_date = CURRENT_TIME() WHERE u.username = :login");
        $this->db->bind(':login', $_SESSION['username']);
        $this->db->execute();

        // Unset sessions
        unset($_SESSION['user']);
        unset($_SESSION['username']);

        // Destroy the session & Redirect
        session_destroy();
        redirect('auth');
    }
}
