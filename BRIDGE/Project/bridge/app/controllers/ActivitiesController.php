<?php
# DESCRIPTION
/**
 * Activities controller
 */

class ActivitiesController extends Controller
{
    # ATTRIBUTES
    private $data = [
        'page_info' => [
            'title' => '',
            'dark_mode' => false,
            'nav_bar' => 'user-nav-bar',
            'footer' => 'main-footer'
        ],
        'post' => [],
        'user' => null,
        'friends' => null,
        'lovely_posts' => null,
        'comments' => null,
        'is_my_post' => false,
        'last_messages' => null
    ];
    private Post $post;
    private User $user;

    # CONSTRUCTOR
    public function __construct()
    {
        if (isset($_SESSION['user'])) {
            // Set models
            $this->post = $this->model('Post');
            $this->user = $this->model('User');

            $this->data['page_info']['title'] = SITE_NAME . ' - @' . $_SESSION['username'];
            $this->data['user'] = $this->user->getUserInfo();

            // User details
            $this->data['user'] = $this->user->getUserInfo();
            $this->data['friends'] = $this->user->getFriends();
            $this->data['lovely_posts'] = $this->post->getTopPosts();
            $this->data['last_messages'] = $this->user->getLastMessages();
        } else
            redirect('auth');
    }

    # METHODS
    // To load activity details page
    public function index($id = null)
    {
        $id = is_null($id) ? 0 : ((int) $id) == 0 ? 0 : (int) $id;

        // Check if the id valid
        if ($id == 0)
            redirect(isset($_SESSION['user']) ? 'home' : '');

        $this->data['post'] = $this->post->findPostByID($id);

        // Check if is exist
        if (!$this->data['post'])
            redirect('home');
        elseif (!$this->post->havePermission($id))
            redirect('home');

        $this->post->postView($id);
        $this->data['comments'] = $this->post->getComments($id);

        // Load view
        $this->view('activities/details.php', $this->data);
    }
}
