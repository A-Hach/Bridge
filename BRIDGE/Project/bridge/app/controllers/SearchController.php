<?php
# DESCRIPTION
/**
 * Search controller
 */

class SearchController extends Controller
{
    # ATTRIBUTES 
    private Search $search;
    private User $user;
    private Post $post;
    private $data = [
        'page_info' => [
            'title' => '',
            'dark_mode' => false,
            'nav_bar' => 'user-nav-bar',
            'footer' => 'main-footer',
            'tab' => 'activities'
        ],
        'users' => [],
        'posts' => [],
        'user' => null,
        'friends' => null,
        'lovely_posts' => null,
        'last_messages' => null
    ];

    # CONSTRUCTOR
    public function __construct()
    {
        // Set models
        $this->search = $this->model('Search');
        $this->user = $this->model('User');
        $this->post = $this->model('Post');

        $this->data['page_info']['title'] = SITE_NAME . ' - ' . $_GET['q'];

        if (isset($_SESSION['user'])) {
            $this->data['page_info']['nav_bar'] = 'user-nav-bar';

            // User details
            $this->data['user'] = $this->user->getUserInfo();
            $this->data['friends'] = $this->user->getFriends();
            $this->data['lovely_posts'] = $this->post->getTopPosts();
            $this->data['last_messages'] = $this->user->getLastMessages();
        } else
            redirect('auth');
    }

    # METHODS
    // To load auth page
    public function index()
    {
        // Check if the query is set
        if (!isset($_GET['q']))
            redirect('home');
        elseif ($_GET['q'] == '')
            redirect('home');

        if (isset($_GET['tab']))
            if ($_GET['tab'] == 'bridgers')
                $this->data['page_info']['tab'] = 'bridgers';

        $user = $this->user->getUserInfo();


        $this->data['users'] = $this->search->users($_GET['q'], $user[0]['profile_id']);
        $this->data['posts'] = $this->search->posts($_GET['q'], $user[0]['profile_id']);

        // Load view
        $this->view('search/index.php', $this->data);
    }
}
