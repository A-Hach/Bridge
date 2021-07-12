<?php
# DESCRIPTION
/**
 * Authentification controller
 */

class AuthController extends Controller
{
    # ATTRIBUTES
    private Auth $auth;
    private Country $country;
    private $data = [
        'page_info' => [
            'title' => SITE_NAME . ' - Ouverture d\'une session',
            'dark_mode' => false,
            'nav_bar' => 'welcome-nav-bar',
            'tab' => '',
            'footer' => 'main-footer'
        ],
        'fields' => [
            'first_name' => '',
            'last_name' => '',
            'username' => '',
            'email' => '',
            'password' => '',
            'confirm_password' => '',
            'day' => '',
            'month' => '',
            'year' => '',
            'country' => '',
            'city' => '',
            'sexe' => ''
        ],
        'errors' => [
            'first_name' => '',
            'last_name' => '',
            'username' => '',
            'email' => '',
            'password' => '',
            'confirm_password' => ''
        ],
        'login_fields' => [
            'login' => '',
            'password' => ''
        ],
        'login_errors' => [
            'login' => '',
            'password' => ''
        ]
    ];

    # CONSTRUCTOR
    public function __construct()
    {
        $this->auth = $this->model('Auth');
        $this->country = $this->model('Country');
    }

    # METHODS
    // To load auth page
    public function index()
    {
        if (isset($_SESSION['user']))
            redirect('home');

        // Set tab
        $this->data['page_info']['tab'] = isset($_GET['tab']) && $_GET['tab'] == 'register' ? 'register' : 'login';

        // Check if request method
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['login']))
                $this->login();
            elseif (isset($_POST['register']))
                $this->register();
            else
                redirect('auth?tab=login');
        } else
            // Load view
            $this->view('auth/index.php', $this->data);
    }

    // To register
    public function register()
    {
        if (isset($_SESSION['user']))
            redirect('home');

        // Check if post method
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get and define data
            $this->data['fields']['first_name'] = isset($_POST['first_name']) ? $_POST['first_name'] : '';
            $this->data['fields']['last_name'] = isset($_POST['last_name']) ? $_POST['last_name'] : '';
            $this->data['fields']['username'] = isset($_POST['username']) ? $_POST['username'] : '';
            $this->data['fields']['email'] = isset($_POST['email']) ? $_POST['email'] : '';
            $this->data['fields']['password'] = isset($_POST['password']) ? $_POST['password'] : '';
            $this->data['fields']['confirm_password'] = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
            $this->data['fields']['day'] = !isset($_POST['day']) ? '' : ((int) $_POST['day']) == 0 ? '' : sprintf("%02d", $_POST['day']);
            $this->data['fields']['month'] = !isset($_POST['month']) ? '' : !getMonthNumber($_POST['month']) ? '' : sprintf("%02d", getMonthNumber($_POST['month']));
            $this->data['fields']['year'] = isset($_POST['year']) ? $_POST['year'] : '';
            $this->data['fields']['country'] = isset($_POST['country']) ? $_POST['country'] : '';
            $this->data['fields']['city'] = isset($_POST['city']) ? $_POST['city'] : '';
            $this->data['fields']['sexe'] = isset($_POST['sexe']) ? $_POST['sexe'] : '';


            // Check first name
            if (empty($this->data['fields']['first_name']))
                $this->data['errors']['first_name'] = 'Ce champ est requis.';
            elseif (!preg_match("/^[a-zA-Z ]*$/", $this->data['fields']['first_name']))
                $this->data['errors']['first_name'] = 'Juste des lettres et des espaces.';

            // Check last name
            if (empty($this->data['fields']['last_name']))
                $this->data['errors']['last_name'] = 'Ce champ est requis.';
            elseif (!preg_match("/^[a-zA-Z ]*$/", $this->data['fields']['last_name']))
                $this->data['errors']['last_name'] = 'Juste des lettres et des espaces.';

            // Check username
            if (empty($this->data['fields']['username']))
                $this->data['errors']['username'] = 'Ce champ est requis.';
            elseif (strlen($this->data['fields']['username']) < 4 || strlen($this->data['fields']['username']) > 14)
                $this->data['errors']['username'] = 'Ce champ doit contenir 4 à 15.';
            elseif (!preg_match('/^[a-zA-Z_.1-9]+$/', $this->data['fields']['username']))
                $this->data['errors']['username'] = 'Just les caractères (a-z . _) sont autorisés.';
            elseif (!preg_match("/^(?!.*\.\.)(?!.*\.$)[^\W][\w.]{3,13}$/", $this->data['fields']['username']))
                $this->data['errors']['username'] = 'Le nom d\'utilisateur ne doit pas commencer par un point.';
            elseif ($this->auth->findUserByUsername($this->data['fields']['username']))
                $this->data['errors']['username'] = 'Ce nom d\'utilisateur est déjà pris.';

            // Check email
            if (empty($this->data['fields']['email']))
                $this->data['errors']['email'] = 'Ce champ est requis.';
            elseif (!filter_var($this->data['fields']['email'], FILTER_VALIDATE_EMAIL))
                $this->data['errors']['email'] = 'Adresse email invalide.';
            elseif ($this->auth->findUserByEmail($this->data['fields']['email']))
                $this->data['errors']['email'] = 'Cet e-mail est déjà enregistré.';

            // Check passwords
            if (empty($this->data['fields']['password']))
                $this->data['errors']['password'] = 'Ce champ est requis.';
            elseif (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/", $this->data['fields']['password']))
                $this->data['errors']['password'] = 'Au moins 8 lettres et chiffres.';
            elseif ($this->data['fields']['password'] != $this->data['fields']['confirm_password'])
                $this->data['errors']['confirm_password'] = 'Vos mots de passe ne correspondent pas.';

            if (!isEmptyOrNull($this->data['errors'])) {
                flash('register_flash', 'Veuillez vérifier les erreurs ci-dessus et les corriger!', 'flash flash-error');
                // Load view with errors
                $this->view('auth/index.php', $this->data);
            } else {
                // Set country ID
                if (!empty($this->data['fields']['country']))
                    $this->data['fields']['country'] = $this->country->findCountryID($this->data['fields']['country'])['id'];

                // INSERT data
                if ($this->auth->register($this->data['fields'])) {
                    // Flash & redirect
                    flash('login_flash', 'Incroyable! Vous êtes membre, veuillez vous connecter.', 'flash flash-success');
                    redirect('auth?tab=login');
                } else {
                    flash('register_flash', 'Oops! Il y a une erreur de serveur, veuillez réessayer!', 'flash flash-error');
                    // Load view with errors
                    $this->view('auth/index.php', $this->data);
                }
            }
        }
    }

    // To login
    public function login()
    {
        if (isset($_SESSION['user']))
            redirect('home');

        // Check if post method
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get and define data
            $this->data['login_fields']['login'] = isset($_POST['id']) ? $_POST['id'] : '';
            $this->data['login_fields']['password'] = isset($_POST['login_password']) ? $_POST['login_password'] : '';

            // Check login
            if (empty($this->data['login_fields']['login']))
                $this->data['login_errors']['login'] = 'Ce champ est requis.';

            // Check password
            if (empty($this->data['login_fields']['password']))
                $this->data['login_errors']['password'] = 'Ce champ est requis.';

            // State
            if (!isEmptyOrNull($this->data['login_errors'])) {
                flash('login_flash', 'Veuillez vérifier les erreurs ci-dessus et les corriger!', 'flash flash-error');
                // Load view with errors
                $this->view('auth/index.php', $this->data);
            } elseif (!$this->auth->login($this->data['login_fields']['login'], $this->data['login_fields']['password'])) {
                flash('login_flash', 'Votre identifiant ou mot de passe est incorrect!', 'flash flash-error');
                // Load view with errors
                $this->view('auth/index.php', $this->data);
            } else {
                $user = $this->auth->login($this->data['login_fields']['login'], $this->data['login_fields']['password']);
                // Create session
                $_SESSION['user'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                // Redirect
                redirect('home');
            }
        }
    }

    // To logout
    public function logout()
    {
        if (isset($_SESSION['user']))
            $this->auth->logout();
    }
}
