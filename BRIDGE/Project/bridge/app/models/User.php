<?php
# DESCRIPTION
/**
 * User model
 */

class User
{
    # ATTRIBUTES
    private Database $db;

    # CONSTRUCTOR
    public function __construct()
    {
        $this->db = new Database;
    }

    // To get members count
    public function getMembersCount()
    {
        // Prepare query & bind value
        $this->db->query('SELECT COUNT(*) as members_count FROM users');

        return $this->db->singleResult();
    }

    // To get monthly data
    public function getMonthlyData($days)
    {
        // Prepare query & bind value
        $this->db->query('CALL getMonthlyData(:days)');
        $this->db->bind(':days', $days);

        return $this->db->allResults();
    }

    // To get seven days data
    public function getSevenDaysData($days, $profile)
    {
        // Prepare query & bind value
        $this->db->query('CALL sevenDaysData(:days, :profile)');
        $this->db->bind(':days', $days);
        $this->db->bind(':profile', $profile);

        return $this->db->allResults();
    }

    // Get all user info
    public function getUserInfo()
    {
        // Prepare query & bind value
        $this->db->query('SELECT *, l.name AS level_name, c.name AS country_name, p.id AS profile_id FROM users u INNER JOIN profiles p ON u.id = p.owner_id INNER JOIN levels l ON u.level_id  = l.id INNER JOIN countries c ON u.country_id = c.id WHERE u.id = :userID');
        $this->db->bind(':userID', $_SESSION['user']);

        return $this->db->allResults();
    }

    // Get connected user
    public function getConnectedUser()
    {
        // Prepare query & bind value
        $this->db->query('SELECT u.username, p.display_name AS name, p.main_picture AS picture FROM users u INNER JOIN profiles p ON u.id = p.owner_id WHERE u.id = :userID');
        $this->db->bind(':userID', $_SESSION['user']);

        return $this->db->singleResult();
    }

    // Get all user info by username
    public function getUserInfoByUsername($username)
    {
        // Prepare query & bind value
        $this->db->query('SELECT *, l.name AS level_name, c.name AS country_name, p.id AS profile_id FROM users u INNER JOIN profiles p ON u.id = p.owner_id INNER JOIN levels l ON u.level_id  = l.id INNER JOIN countries c ON u.country_id = c.id WHERE u.username = :username');
        $this->db->bind(':username', $username);

        return $this->db->allResults();
    }

    // To send a public message
    public function sendMessage($text)
    {
        $myID = $this->getUserInfo()[0]['profile_id'];

        // Prepare query & bind value
        $this->db->query('INSERT INTO public_messages(id, text, sender_id) VALUES (NULL, :text, :sender)');
        $this->db->bind(':text', $text);
        $this->db->bind(':sender', $myID);

        return $this->db->execute();
    }

    // To get all public messages
    public function getAllPublicMessage()
    {
        $myID = $this->getUserInfo()[0]['profile_id'];

        // Prepare query & bind value
        $this->db->query('SELECT u.id, u.username, p.main_picture, pm.text, DATE_FORMAT(pm.sending_date, "%d/%m/%Y") AS sending_date FROM public_messages pm INNER JOIN profiles p ON sender_id = p.id INNER JOIN users u ON u.id = p.owner_id ORDER BY pm.sending_date');

        return $this->db->allResults();
    }

    // Get links
    public function getLinks($username)
    {
        // Prepare query and bind value
        $this->db->query('SELECT sm.name, sm.main_url, sm.favicon, l.username FROM links l INNER JOIN social_media sm ON l.social_media_id = sm.id INNER JOIN users u ON u.id = l.user_id WHERE u.username = :username');
        $this->db->bind(':username', $username);

        return $this->db->allResults();
    }

    // Check friendship
    public function isMyFriend($username)
    {
        // Get user by username
        $user = $this->getUserInfoByUsername($username);
        $me = $this->getUserInfo($_SESSION['user']);

        if (count($user) == 0)
            return false;

        // Prepare query & bind values
        $this->db->query('SELECT * FROM invitations WHERE state = 1 AND ((sender_id = :myID AND receiver_id = :userID) OR (sender_id = :userID AND receiver_id = :myID))');
        $this->db->bind(':myID', $me[0]['profile_id']);
        $this->db->bind(':userID', $user[0]['profile_id']);

        return count($this->db->allResults()) > 0;
    }

    // Check if is it my profile
    public function isMyProfile($username)
    {
        return strtolower($_SESSION['username']) == strtolower($username);
    }

    // Get friendshipt state
    public function friendshipState($friend)
    {
        $me = ((int) $this->getUserInfo()[0]['profile_id']);
        $person = ((int) $friend);

        // Prepare query & bind values
        $this->db->query('SELECT friendship_state(:me, :person) AS friendship_state');
        $this->db->bind(':me', $me);
        $this->db->bind(':person', $person);

        return $this->db->singleResult()['friendship_state'];
    }

    // To get friends list
    public function getFriends()
    {
        $me = ((int) $this->getUserInfo()[0]['profile_id']);

        // Prepare query & bind values
        $this->db->query('SELECT pr.display_name, u.username, pr.main_picture, l.badge, l.name, pr.state, pr.last_activity_date from profiles pr INNER JOIN users u ON pr.owner_id = u.id INNER JOIN levels l ON u.level_id = l.id where pr.id in((SELECT receiver_id FROM invitations WHERE sender_id = :myID AND state = 1) union (SELECT sender_id FROM invitations WHERE receiver_id = :myID AND state = 1)) ORDER BY pr.state DESC, pr.display_name');
        $this->db->bind(':myID', $me);

        return $this->db->allResults();
    }

    // To get user friends list
    public function getUserFriends($userID)
    {
        // Prepare query & bind values
        $this->db->query('SELECT pr.display_name, u.username, pr.main_picture, l.badge, l.name, pr.state from profiles pr INNER JOIN users u ON pr.owner_id = u.id INNER JOIN levels l ON u.level_id = l.id where pr.id in((SELECT receiver_id FROM invitations WHERE sender_id = :userID AND state = 1 ORDER BY friendship_date DESC) union (SELECT sender_id FROM invitations WHERE receiver_id = :userID AND state = 1 ORDER BY friendship_date DESC))');
        $this->db->bind(':userID', $userID);

        return $this->db->allResults();
    }

    // To get friends around the world
    public function getFriendsAroundTheWorld($person)
    {
        // Prepare query & bind value
        $this->db->query('CALL aroundWorldFriends(:person)');
        $this->db->bind(':person', $person);

        return $this->db->allResults();
    }

    // To increment profile views
    public function profileView($username)
    {
        if (strtolower($username) != strtolower($_SESSION['username'])) {
            // Prepare query & bind value
            $this->db->query('UPDATE profiles SET total_views = total_views + 1 WHERE owner_id = (SELECT id FROM users WHERE username = :username)');
            $this->db->bind(':username', $username);

            return $this->db->execute();
        }

        return true;
    }

    // To add testimonials
    public function testimony($message)
    {
        $me = ((int) $this->getUserInfo()[0]['profile_id']);

        // Prepare query & bind values
        $this->db->query('INSERT INTO testimonials(id, text, author_id) VALUES(NULL, :message, :myID)');
        $this->db->bind(':message', $message);
        $this->db->bind(':myID', $me);

        return $this->db->execute();
    }

    // To add questions
    public function ask($message, $name)
    {
        $me = isset($_SESSION['user']) ?  ((int) $this->getUserInfo()[0]['profile_id']) : null;
        $name = is_null($name) ? 'Inconnue' : $name;

        // Prepare query & bind values
        $this->db->query('INSERT INTO questions(id, question, name, author_id) VALUES(NULL, :question, :name, :myID)');
        $this->db->bind(':question', $message);
        $this->db->bind(':name', $name);
        $this->db->bind(':myID', $me);

        return $this->db->execute();
    }

    // To send friendship requets
    public function sendFriendship($user)
    {
        $me = ((int) $this->getUserInfo()[0]['profile_id']);

        // Check if doesn't exist
        $this->db->query('SELECT * FROM invitations WHERE (sender_id = :myID AND receiver_id = :userID) OR (sender_id = :userID AND receiver_id = :myID)');
        $this->db->bind(':myID', $me);
        $this->db->bind(':userID', $user);

        if (count($this->db->allResults()) == 0) {
            // Prepare query & bind value
            $this->db->query('INSERT INTO invitations(id, sender_id, receiver_id) VALUES(NULL, :myID, :userID)');
            $this->db->bind(':myID', $me);
            $this->db->bind(':userID', $user);

            return $this->db->execute();
        }
    }

    // To confirm friendship requets
    public function confirmFriendship($user)
    {
        $me = ((int) $this->getUserInfo()[0]['profile_id']);

        // Check if doesn't exist
        $this->db->query('SELECT * FROM invitations WHERE (sender_id = :myID AND receiver_id = :userID) OR (sender_id = :userID AND receiver_id = :myID)');
        $this->db->bind(':myID', $me);
        $this->db->bind(':userID', $user);

        if (count($this->db->allResults()) > 0) {
            // Prepare query & bind value
            $this->db->query('UPDATE invitations SET state = 1, friendship_date = CURRENT_TIMESTAMP() WHERE (sender_id = :myID AND receiver_id = :userID) OR (sender_id = :userID AND receiver_id = :myID)');
            $this->db->bind(':myID', $me);
            $this->db->bind(':userID', $user);

            return $this->db->execute();
        }
    }

    // To cancel friendship requets
    public function cancelFriendship($user)
    {
        $me = ((int) $this->getUserInfo()[0]['profile_id']);

        // Check if doesn't exist
        $this->db->query('SELECT * FROM invitations WHERE (sender_id = :myID AND receiver_id = :userID) OR (sender_id = :userID AND receiver_id = :myID)');
        $this->db->bind(':myID', $me);
        $this->db->bind(':userID', $user);

        if (count($this->db->allResults()) > 0) {
            // Prepare query & bind value
            $this->db->query('DELETE FROM invitations WHERE (sender_id = :myID AND receiver_id = :userID) OR (sender_id = :userID AND receiver_id = :myID)');
            $this->db->bind(':myID', $me);
            $this->db->bind(':userID', $user);

            return $this->db->execute();
        }
    }

    // To destroy friendship
    public function destroyFriendship($user)
    {
        $this->cancelFriendship($user);
    }

    // To update profile
    public function update($fields, $main_picture, $cover_picture)
    {
        $profileID = $this->getUserInfo()[0]['profile_id'];

        $display_name = $fields['display_name'];
        $bio = $fields['bio'];
        $confidentiality = strtolower($fields['confidentiality']) == 'publique' ? 1 : 0;
        $personnalities = $fields['personnalities'];

        $query = 'UPDATE profiles SET display_name = :display_name, confidentiality = :confidentiality, personnalities = :personnalities, bio = :bio';
        if (!empty($main_picture))
            $query .= ', main_picture = :mainPic';
        if (!empty($cover_picture))
            $query .= ', cover_picture = :coverPic';

        $query .= ' WHERE id = :myID';

        $this->db->query($query);
        $this->db->bind(':display_name', $display_name);
        $this->db->bind(':confidentiality', $confidentiality);
        $this->db->bind(':personnalities', $personnalities);
        $this->db->bind(':bio', $bio);
        $this->db->bind(':myID', $profileID);

        if (!empty($main_picture))
            $this->db->bind(':mainPic', $main_picture);
        if (!empty($cover_picture))
            $this->db->bind(':coverPic', $cover_picture);

        return $this->db->execute();
    }

    // Update user
    public function updateUser($fields)
    {
        // Prepare query & bind values
        $first_name = $fields['first_name'];
        $last_name = $fields['last_name'];
        $city = $fields['city'];
        $sexe = strtolower($fields['sexe']) == 'male' ? 'Male' : 'Female';
        $country = $fields['country'];
        $password = !empty($fields['new_password']) ? password_hash($fields['new_password'], PASSWORD_BCRYPT) : '';

        // Check if new password exists
        if (!empty($password)) {
            $this->db->query('UPDATE users SET last_password = password, password_update_date = CURRENT_TIMESTAMP() WHERE id = ' . $_SESSION['user']);
            $this->db->execute();
        }

        // Change user
        $query = 'UPDATE users SET first_name = :fName, last_name = :lName, date_of_birth = :date_of_birth, place_of_birth = :city, country_id = :countryID, sexe = :sexe';

        if (!empty($password))
            $query .= ', password = :password';

        $query .= ' WHERE id = :myID';
        // Bind
        $this->db->query($query);
        $this->db->bind(':fName', $first_name);
        $this->db->bind(':lName', $last_name);
        $this->db->bind(':city', $city);
        $this->db->bind(':countryID', $country);
        $this->db->bind(':sexe', $sexe);
        $this->db->bind(':myID', $_SESSION['user']);

        if (!empty($password))
            $this->db->bind(':password', $password);

        // Check for date if is valid
        if (is_int((int) $fields['month']) && is_int((int) $fields['year']) && is_int((int) $fields['day']))
            if (checkdate((int) $fields['month'], (int) $fields['day'], (int) $fields['year']))
                $this->db->bind(':date_of_birth', getDateString($fields['year'], $fields['month'], $fields['day']));
            else
                $this->db->bind(':date_of_birth', null);
        else
            $this->db->bind(':date_of_birth', null);

        return $this->db->execute();
    }

    // To set social media
    public function setSocialMedia($fields)
    {
        $facebook = $fields['facebook'];
        $instagram = $fields['instagram'];
        $snapchat = $fields['snapchat'];
        $linkedin = $fields['linkedin'];
        $youtube = $fields['youtube'];

        $this->doLink('facebook', $facebook);
        $this->doLink('instagram', $instagram);
        $this->doLink('snapchat', $snapchat);
        $this->doLink('linkedin', $linkedin);
        $this->doLink('youtube', $youtube);
    }

    // To delete account
    public function delete()
    {
        $myID = $_SESSION['user'];

        // Unset sessions
        unset($_SESSION['user']);
        unset($_SESSION['username']);

        // Destroy the session & Redirect
        session_destroy();

        // Prepare query and bind value
        $this->db->query('DELETE FROM users WHERE id = :myID');
        $this->db->bind(':myID', $myID);

        return $this->db->execute();
    }

    // Do a link
    public function doLink($name, $username)
    {
        $smID = $this->getSocialMediaID($name);
        if (!empty($username)) {

            // Check if he does not have a link
            if (!$this->hasLink($smID)) {
                // INSERT
                $this->db->query('INSERT INTO links(id, username, social_media_id, user_id) VALUES (NULL, :username, :smID, :myID)');
                $this->db->bind(':username', $username);
                $this->db->bind(':smID', $smID);
                $this->db->bind(':myID', $_SESSION['user']);
            } else {
                // UPDATE
                $this->db->query('UPDATE links SET username = :username WHERE social_media_id = :smID AND user_id = :myID');
                $this->db->bind(':username', $username);
                $this->db->bind(':smID', $smID);
                $this->db->bind(':myID', $_SESSION['user']);
            }
        } else {
            // DELETE
            $this->db->query('DELETE FROM links WHERE social_media_id = :smID AND user_id = :myID');
            $this->db->bind(':smID', $smID);
            $this->db->bind(':myID', $_SESSION['user']);
        }

        return $this->db->execute();
    }

    // To check if a user has link
    public function hasLink($smID)
    {
        // Prepare query & bind value
        $this->db->query('SELECT * FROM links WHERE user_id = ' . $_SESSION['user'] . ' AND social_media_id = ' . $smID);
        return count($this->db->allResults()) > 0 ? true : false;
    }

    // To get social media id by name
    public function getSocialMediaID($name)
    {
        // Prepare query & bind value
        $this->db->query("SELECT id FROM social_media WHERE LOWER(name) = '" . $name . "'");
        $smID = $this->db->singleResult()['id'];
        return $smID;
    }

    // To get last message of each friend
    public function getLastMessages()
    {
        $profileID = $this->getUserInfo()[0]['profile_id'];

        // Prepare query & bind value
        $this->db->query('SELECT u.username, pr.main_picture, pm.sender_id, pm.text, pm.sending_date FROM public_messages pm INNER JOIN profiles pr ON pm.sender_id = pr.id INNER JOIN users u ON u.id = pr.owner_id WHERE pm.sending_date = (SELECT MAX(pm2.sending_date) FROM public_messages AS pm2 WHERE pm2.sender_id = pm.sender_id) AND pm.sender_id IN (SELECT CASE WHEN sender_id = :myID THEN receiver_id ELSE sender_id END FROM invitations WHERE sender_id = :myID OR receiver_id = :myID AND state = 1) ORDER BY pm.sending_date DESC');
        $this->db->bind(':myID', $profileID);

        return $this->db->allResults();
    }
}
