<?php
# DESCRIPTION
/**
 * Users controller
 */

class UController extends Controller
{
    # ATTRIBUTES
    private Country $country;
    private $data = [
        'page_info' => [
            'title' => SITE_NAME,
            'dark_mode' => false,
            'nav_bar' => 'welcome-nav-bar',
            'footer' => 'main-footer'
        ],
        'last_messages' => null,
        'user' => null,
        'profile' => null,
        'links' => null,
        'is_my_profile' => false,
        'friendship_state' => null,
        'friends' => null,
        'profile_friends' => null,
        'posts' => null,
        'fields' => [
            'display_name' => '',
            'first_name' => '',
            'last_name' => '',
            'confidentiality' => '',
            'personnalities' => '',
            'bio' => '',
            'password' => '',
            'new_password' => '',
            'confirm_new_password' => '',
            'day' => '',
            'month' => '',
            'year' => '',
            'country' => '',
            'city' => '',
            'sexe' => '',
            'facebook' => '',
            'instagram' => '',
            'youtube' => '',
            'linkedin' => '',
            'snapchat' => ''
        ],
        'errors' => [
            'display_name' => '',
            'first_name' => '',
            'last_name' => '',
            'password' => '',
            'new_password' => '',
            'confirm_new_password' => ''
        ]
    ];
    private User $user;
    private Post $post;
    private Auth $auth;

    # CONSTRUCTOR
    public function __construct()
    {
        // Set models
        $this->user = $this->model('User');
        $this->post = $this->model('Post');
        $this->auth = $this->model('Auth');
        $this->country = $this->model('Country');

        if (isset($_SESSION['user'])) {
            $this->data['page_info']['nav_bar'] = 'user-nav-bar';
            $this->data['last_messages'] = $this->user->getLastMessages();
        } else
            redirect('auth');
    }

    # METHODS
    // To load auth page
    public function index($username = null)
    {
        // Check if the id is set
        if (is_null($username))
            redirect(isset($_SESSION['user']) ? 'home' : '');
        elseif ($username == '')
            redirect(isset($_SESSION['user']) ? 'home' : '');
        else {
            $this->data['profile'] = $this->user->getUserInfoByUsername($username);
            $this->data['links'] = $this->user->getLinks($username);
        }

        // Set the profile tab
        $profile_tab = 'activities';
        switch (true) {
            case !isset($_GET['tab']):
                break;

            case $_GET['tab'] == 'friends':
                $profile_tab = 'friends';
                break;

            case $_GET['tab'] == 'about':
                $profile_tab = 'about';
                break;

            case $_GET['tab'] == 'settings':
                $profile_tab = 'settings';
                break;
        }

        // Data
        if (count($this->data['profile']) != 0) {
            $this->data['page_info']['title'] = SITE_NAME . ' - @' . $this->data['profile'][0]['username'];
            $this->data['page_info']['profile_tab'] = $profile_tab;

            // Increment++ profile views
            $this->user->profileView($this->data['profile'][0]['username']);

            // User details
            $this->data['user'] = $this->user->getUserInfo();
            $this->data['friends'] = $this->user->getFriends($username);
            $this->data['posts'] = $this->post->getMyPosts($username);
            $this->data['friendship_state'] = $this->user->friendshipState($this->data['profile'][0]['profile_id']);

            // Get profile friends
            $profileID = $this->user->getUserInfoByUsername($username)[0]['profile_id'];
            $this->data['profile_friends'] = $this->user->getUserFriends($profileID);
        }


        // Load view
        if (count($this->data['profile']) == 0)
            $this->view('u/no-profile.php', $this->data);
        elseif ($this->data['profile'][0]['confidentiality'] == '0' && !$this->user->isMyProfile($username) && !$this->user->isMyFriend($username))
            $this->view('u/private-profile.php', $this->data);
        else {
            // Not my profile
            $this->data['is_my_profile'] = $this->user->isMyProfile($username);

            if (isset($_POST['save']))
                $this->edit();
            elseif (isset($_POST['delete']))
                die();

            $this->view('u/profile.php', $this->data);
        }
    }

    // To edit profile
    public function edit()
    {
        $referer = $_SERVER['HTTP_REFERER'];

        // Check if post method
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->data['page_info']['profile_tab'] = 'settings';
            // Get and define data
            $this->data['fields']['display_name'] = isset($_POST['display_name']) ? $_POST['display_name'] : '';
            $this->data['fields']['first_name'] = isset($_POST['first_name']) ? $_POST['first_name'] : '';
            $this->data['fields']['last_name'] = isset($_POST['last_name']) ? $_POST['last_name'] : '';
            $this->data['fields']['confidentiality'] = isset($_POST['confidentiality']) ? $_POST['confidentiality'] : '';
            $this->data['fields']['personnalities'] = isset($_POST['personnalities']) ? $_POST['personnalities'] : '';
            $this->data['fields']['bio'] = isset($_POST['bio']) ? $_POST['bio'] : '';
            $this->data['fields']['password'] = isset($_POST['password']) ? $_POST['password'] : '';
            $this->data['fields']['new_password'] = isset($_POST['new_password']) ? $_POST['new_password'] : '';
            $this->data['fields']['confirm_new_password'] = isset($_POST['confirm_new_password']) ? $_POST['confirm_new_password'] : '';
            $this->data['fields']['day'] = !isset($_POST['day']) ? '' : ((int) $_POST['day']) == 0 ? '' : sprintf("%02d", $_POST['day']);
            $this->data['fields']['month'] = !isset($_POST['month']) ? '' : !getMonthNumber($_POST['month']) ? '' : sprintf("%02d", getMonthNumber($_POST['month']));
            $this->data['fields']['year'] = isset($_POST['year']) ? $_POST['year'] : '';
            $this->data['fields']['country'] = isset($_POST['country']) ? $_POST['country'] : '';
            $this->data['fields']['city'] = isset($_POST['city']) ? $_POST['city'] : '';
            $this->data['fields']['sexe'] = isset($_POST['sexe']) ? $_POST['sexe'] : '';

            $this->data['fields']['facebook'] = isset($_POST['facebook']) ? $_POST['facebook'] : '';
            $this->data['fields']['instagram'] = isset($_POST['instagram']) ? $_POST['instagram'] : '';
            $this->data['fields']['linkedin'] = isset($_POST['linkedin']) ? $_POST['linkedin'] : '';
            $this->data['fields']['snapchat'] = isset($_POST['snapchat']) ? $_POST['snapchat'] : '';
            $this->data['fields']['youtube'] = isset($_POST['youtube']) ? $_POST['youtube'] : '';

            // Check display name
            if (empty($this->data['fields']['display_name']))
                $this->data['errors']['display_name'] = 'Ce champ est requis.';
            elseif (!preg_match("/^[a-zA-Z ]*$/", $this->data['fields']['display_name']))
                $this->data['errors']['display_name'] = 'Juste des lettres et des espaces.';


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

            // Check passwords
            if (empty($this->data['fields']['password']))
                $this->data['errors']['password'] = 'Ce champ est requis.';
            elseif (!$this->auth->login($_SESSION['username'], $this->data['fields']['password']))
                $this->data['errors']['password'] = 'Votre mot de passe est incorrect.';

            // Check new passwords
            if (!empty($this->data['fields']['new_password'])) {
                if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/", $this->data['fields']['new_password']))
                    $this->data['errors']['new_password'] = 'Au moins 8 lettres et chiffres.';
                elseif ($this->data['fields']['new_password'] != $this->data['fields']['confirm_new_password'])
                    $this->data['errors']['confirm_new_password'] = 'Vos mots de passe ne correspondent pas.';
            }

            if (!isEmptyOrNull($this->data['errors'])) {
                flash('edit_flash', 'Veuillez vérifier les erreurs ci-dessus et les corriger!', 'flash flash-error');
                // Load view with errors
                $this->view('u/profile.php', $this->data);
            } else {
                // Set country ID
                if (!empty($this->data['fields']['country']))
                    $this->data['fields']['country'] = $this->country->findCountryID($this->data['fields']['country'])['id'];

                // Get files
                $mainPic = $_FILES['main_picture'];
                $coverPic = $_FILES['cover_picture'];
                $mainPicFilename = '';
                $coverPicFilename = '';

                if ($mainPic['error'] == 0) {
                    if (!isValidMedia($mainPic['name']))
                        header('location: ' . $referer);

                    if ($mainPic['size'] > 41943040)
                        header('location: ' . $referer);

                    // everything is okey, so let's upload the file to files.bridge
                    $mainPicFilename = getRandomString(10) . '.' . pathinfo($mainPic['name'])['extension'];
                    move_uploaded_file($mainPic['tmp_name'], FILES_FOLDER . '/' . $mainPicFilename);
                }

                if ($coverPic['error'] == 0) {
                    if (!isValidMedia($coverPic['name']))
                        header('location: ' . $referer);

                    if ($coverPic['size'] > 41943040)
                        header('location: ' . $referer);

                    // everything is okey, so let's upload the file to files.bridge
                    $coverPicFilename = getRandomString(10) . '.' . pathinfo($coverPic['name'])['extension'];
                    move_uploaded_file($coverPic['tmp_name'], FILES_FOLDER . '/' . $coverPicFilename);
                }

                // Update profile table
                $this->user->update($this->data['fields'], $mainPicFilename, $coverPicFilename);

                // Update user table
                $this->user->updateUser($this->data['fields']);


                // Add social media if not exist or Update if exist and not empty
                $this->user->setSocialMedia($this->data['fields']);

                header('location: ' . $referer);
            }
        }
    }
}
