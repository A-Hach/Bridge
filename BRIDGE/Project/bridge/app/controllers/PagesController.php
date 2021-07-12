<?php
# DESCRIPTION
/**
 * Pages controller
 */

class PagesController extends Controller
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
        'testimonials' => null,
        'members_count' => 0,
        'last_messahes' => null
    ];
    private User $user;
    private Testimonial $testimonial;

    # CONSTRUCTOR
    public function __construct()
    {
        // Set models
        $this->user = $this->model('User');
        $this->testimonial = $this->model('Testimonial');

        if (isset($_SESSION['user'])) {
            $this->data['page_info']['nav_bar'] = 'user-nav-bar';

            // User details
            $this->data['user'] = $this->user->getUserInfo();
            $this->data['last_messages'] = $this->user->getLastMessages();
        }
    }

    # METHODS
    // To load welcome page
    public function index()
    {
        // Modify data
        $this->data['testimonials'] = $this->testimonial->getVisibleTestimonials();
        $this->data['page_info']['title'] = SITE_NAME . ' - ' . DESCRIPTION;
        $this->data['members_count'] = $this->user->getMembersCount()['members_count'];

        // Load view
        $this->view('pages/index.php', $this->data);
    }

    // To load FAQ page
    public function faq()
    {
        // Modify data
        $this->data['page_info']['title'] = SITE_NAME . ' - Questions fréquemment posées sur ' . SITE_NAME;

        // Load view
        $this->view('pages/faq.php', $this->data);
    }

    // To load team page
    public function team()
    {
        // Modify data
        $this->data['page_info']['title'] = SITE_NAME . ' - Équipe ' . SITE_NAME;

        // Load view
        $this->view('pages/team.php', $this->data);
    }

    // To load testimony page
    public function testimony()
    {
        $this->data['testimonials'] = $this->testimonial->getVisibleTestimonials();

        if (!isset($_SESSION['user'])) {
            $register = '<a href="' . ROOT_URL . '/auth?tab=register' . '">S\'inscrire.</a>';
            flash('login_flash', 'Veuillez vous connecter pour nous donner votre témoignage! Vous n\'avez pas de compte? ' . $register, 'flash flash-info');
            redirect('auth');
        }

        // Modify data
        $this->data['page_info']['title'] = SITE_NAME . ' - Dites-nous votre témoignage';

        // Load view
        $this->view('pages/testimony.php', $this->data);
    }

    // To load ask page
    public function ask()
    {
        // Modify data
        $this->data['page_info']['title'] = SITE_NAME . ' - Questionnez ' . SITE_NAME;

        // Load view
        $this->view('pages/ask.php', $this->data);
    }
}
