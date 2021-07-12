<?php
# DESCRIPTION
/**
 * Data controller
 */

class DataController extends Controller
{
    # ATTRIBIUTES
    private User $user;
    private Comment $comment;

    # CONSTRUCTORS
    public function __construct()
    {
        $this->user = $this->model('User');
        $this->comment = $this->model('Comment');
    }

    # METHODS
    // To get monthly data
    public function getMonthlyData()
    {
        // Get data
        $data = $this->user->getMonthlyData((int) $_GET['count']);

        echo json_encode($data);
    }

    // To get seven days data
    public function sevenDaysData()
    {
        // Get data
        $data = $this->user->getSevenDaysData($_GET['count'], $this->user->getUserInfoByUsername($_SESSION['username'])[0]['profile_id']);

        echo json_encode($data);
    }

    // To get friends around the world data
    public function friendsAroundWorld()
    {
        // Get data
        $data = $this->user->getFriendsAroundTheWorld($this->user->getUserInfoByUsername($_SESSION['username'])[0]['profile_id']);

        echo json_encode($data);
    }

    // To post testimonial
    public function testimony()
    {
        $message = !isset($_POST['message']) ? '' : $_POST['message'];
        $flash_message = '';
        $flash_type = 'flash-error';

        if (!empty($message)) {
            $this->user->testimony($message);
            $flash_message = SITE_NAME . ' est heureux d\'entendre votre opinion sur nous!';
            $flash_type = 'flash-success';
        } else
            $flash_message = 'Veuillez remplir le champ.';

        flash('testimony_flash', $flash_message, 'flash ' . $flash_type);

        // Load view with errors
        redirect('pages/testimony');
    }

    // To post question
    public function ask()
    {
        $message = !isset($_POST['question']) ? '' : $_POST['question'];
        $name = null;

        if (isset($_SESSION['user']))
            $name = $this->user->getUserInfo()[0]['display_name'];
        else {
            if (isset($_POST['first_name']))
                if (!empty($_POST['first_name']))
                    $name = $_POST['first_name'];

            if (isset($_POST['last_name']))
                if (!empty($_POST['last_name']))
                    $name = empty($name) ? $_POST['last_name'] : ($name . ' ' . $_POST['last_name']);
        }

        $flash_message = '';
        $flash_type = 'flash-error';

        if (!empty($message)) {
            $this->user->ask($message, $name);
            $flash_message = SITE_NAME . ' est heureux d\'entendre votre question, nous essaierons autant que possible de répondre sur votre question!';
            $flash_type = 'flash-success';
        } else
            $flash_message = 'Veuillez remplir le champ.';

        flash('ask_flash', $flash_message, 'flash ' . $flash_type);

        // Load view with errors
        redirect('pages/ask');
    }

    // To comment
    public function comment()
    {
        $referer = $_SERVER['HTTP_REFERER'];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get profile ID
            $profileID = $this->user->getUserInfo()[0]['profile_id'];
            $postID = !isset($_POST['post_id']) ? '' : $_POST['post_id'];
            $text = !isset($_POST['comment_text']) ? '' : $_POST['comment_text'];

            // Get the file
            $file = $_FILES['media'];
            $filename = '';

            if ($file['error'] == 0) {
                if (!isValidMedia($file['name']))
                    die('-1');

                if ($file['size'] > 41943040)
                    die('-2');

                // everything is okey, so let's upload the file to files.bridge
                $filename = getRandomString(10) . '.' . pathinfo($file['name'])['extension'];
                move_uploaded_file($file['tmp_name'], FILES_FOLDER . '/' . $filename);
            }

            if ((!empty($text) || !empty($filename)) && !empty($postID)) {
                // Add comment to database
                if (empty($filename))
                    $this->comment->addComment($text, $profileID, $postID, null, 'text');
                else
                    $this->comment->addComment($text, $profileID, $postID, $filename, getFileType($file['name']));
            }
        }

        header('location: ' . $referer);
    }

    // To send friendship request
    public function sendFriendship()
    {
        $user = !isset($_POST['user']) ? '' : $_POST['user'];

        if (!empty($user))
            $this->user->sendFriendship($user);
    }

    // To delete account
    public function deleteAccount()
    {
        // Delete user
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Delete account
            $this->user->delete();
            redirect('auth');
        } else
            redirect('home');
    }

    // To confirm friendship request
    public function confirmFriendship()
    {
        $user = !isset($_POST['user']) ? '' : $_POST['user'];

        if (!empty($user))
            $this->user->confirmFriendship($user);
    }

    // To cancel friendship request
    public function cancelFriendship()
    {
        $user = !isset($_POST['user']) ? '' : $_POST['user'];

        if (!empty($user))
            $this->user->cancelFriendship($user);
    }

    // To destroy friendship
    public function destroyFriendship()
    {
        $user = !isset($_POST['user']) ? '' : $_POST['user'];

        if (!empty($user))
            $this->user->destroyFriendship($user);
    }

    // To get user info
    public function getUser()
    {
        if (isset($_SESSION['user']) && $_SERVER['REQUEST_METHOD'] == 'GET')
            echo json_encode($this->user->getConnectedUser());
    }

    // To save message
    public function saveMessage()
    {
        if (isset($_SESSION['user']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
            $text = !isset($_POST['text']) ? '' : $_POST['text'];
            $error = null;

            if (empty($text))
                $error = [
                    'code' => 0,
                    'message' => 'Merci d\'écrire votre message'
                ];
            elseif (!$this->user->sendMessage($text))
                $error = [
                    'code' => 1,
                    'message' => 'Oups! Il y a une erreur de serveur, veuillez réessayer'
                ];

            echo json_encode($error);
        }
    }

    // To delete comment
    public function deleteComment()
    {
        // Delete user
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment']))
            // Delete account
            $this->comment->deleteComment($_POST['comment']);
    }
}
