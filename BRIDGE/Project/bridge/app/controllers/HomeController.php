<?php
# DESCRIPTION
/**
 * Home controller
 * Loads home (All Posts)
 */

class HomeController extends Controller
{
    # ATTRIBUTES
    private $data = [
        'page_info' => [
            'title' => SITE_NAME,
            'dark_mode' => false,
            'nav_bar' => 'welcome-nav-bar',
            'footer' => 'main-footer'
        ],
        'user' => null,
        'friends' => null,
        'posts' => null,
        'lovely_posts' => null,
        'top_friends_posts' => null,
        'saved_posts' => null,
        'is_room' => false,
        'public-messages' => null,
        'last_messages' => null
    ];
    private User $user;
    private Post $post;

    # CONSTRUCTOR
    public function __construct()
    {
        // Set models
        $this->user = $this->model('User');
        $this->post = $this->model('Post');

        if (isset($_SESSION['user'])) {
            $this->data['page_info']['nav_bar'] = 'user-nav-bar';

            // User details
            $this->data['user'] = $this->user->getUserInfo();
            $this->data['friends'] = $this->user->getFriends();
            $this->data['last_messages'] = $this->user->getLastMessages();
        } else
            redirect('auth');
    }
    # METHODS

    // To load home page
    public function index()
    {
        // Data
        $this->data['page_info']['title'] = SITE_NAME . ' - Accueil (@' . $_SESSION['username'] . ')';
        $this->data['posts'] = $this->post->getAllAlowedPosts();
        $this->data['lovely_posts'] = $this->post->getTopPosts();
        $this->data['top_friends_posts'] = $this->post->getTopFriendsPosts();

        // Load view
        $this->view('home/index.php', $this->data);
    }

    // To load saves page
    public function saves()
    {
        // Data
        $this->data['page_info']['title'] = SITE_NAME . ' - Playlist (@' . $_SESSION['username'] . ')';
        $this->data['lovely_posts'] = $this->post->getTopPosts();
        $this->data['saved_posts'] = $this->post->playList();
        // Load view
        $this->view('home/saves.php', $this->data);
    }

    // To post text
    public function text()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get profile ID
            $profileID = $this->user->getUserInfo()[0]['profile_id'];

            // Execute
            $this->post->postText($_POST['text'], $_POST['tags'], $profileID);
        }
        redirect('home');
    }

    // To post media
    public function media()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get profile ID
            $profileID = $this->user->getUserInfo()[0]['profile_id'];

            // Get the file
            $file = $_FILES['media'];

            if (!isValidMedia($file['name']))
                die('-1');

            if ($file['size'] > 41943040)
                die('-2');

            // everything is okey, so let's upload the file to files.bridge
            $filename = getRandomString(10) . '.' . pathinfo($file['name'])['extension'];
            move_uploaded_file($file['tmp_name'], FILES_FOLDER . '/' . $filename);

            // Add to database
            if ($this->post->postMedia($_POST['text'], $filename, $_POST['tags'], $profileID, getFileType($file['name'])))
                die('1');
            else
                die('0');
        }
    }

    // Chat room
    public function chat()
    {
        $okey = true;
        // Check if ?u param is set
        if (!isset($_GET['u']))
            $okey = false;
        elseif (empty($_GET['u']))
            $okey = false;
        elseif (count($this->user->getUserInfoByUsername($_GET['u'])) == 0)
            $okey = false;
        elseif ($this->user->getUserInfoByUsername($_GET['u'])[0]['profile_id'] == $_SESSION['user'])
            $okey = false;

        if (!$okey)
            redirect('home');

        die('Connect with ' . $_GET['u']);
    }

    public function room()
    {
        $this->data['page_info']['title'] = SITE_NAME . ' - Salle de discussion (@' . $_SESSION['username'] . ')';
        $this->data['lovely_posts'] = $this->post->getTopPosts();
        $this->data['is_room'] = true;
        $this->data['public_messages'] = $this->user->getAllPublicMessage();

        // Load view
        $this->view('home/room.php', $this->data);
    }
}
