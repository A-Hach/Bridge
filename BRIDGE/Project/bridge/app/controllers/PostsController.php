<?php
# DESCRIPTION
/**
 * Posts controller
 */

class PostsController extends Controller
{
    # ATTRIBIUTES
    private User $user;
    private Post $post;

    # CONSTRUCTORS
    public function __construct()
    {
        $this->user = $this->model('User');
        $this->post = $this->model('Post');

        if ($_SERVER['REQUEST_METHOD'] != 'POST')
            exit;
    }

    // To like a post
    public function react()
    {
        $post = isset($_POST['post']) ? $_POST['post'] : '';

        if (!empty($post))
            echo json_encode($this->post->react($post));
    }

    // To edit post
    public function edit()
    {
        $post = isset($_POST['post_id']) ? $_POST['post_id'] : '';
        $text = isset($_POST['post_text']) ? $_POST['post_text'] : '';

        if (!empty($post) && !empty($text)) {
            $this->post->edit($post, $text);

            // Redirect 
            header('location: ' . $_SERVER['HTTP_REFERER']);
        }
    }

    // To delete post
    public function delete()
    {
        $post = isset($_POST['post']) ? $_POST['post'] : '';

        if (!empty($post))
            $this->post->delete($post);
    }

    // To save post
    public function save()
    {
        $post = isset($_POST['post']) ? $_POST['post'] : '';

        if (!empty($post))
            $this->post->save($post);
    }

    // To unsave post
    public function unsave()
    {
        $post = isset($_POST['post']) ? $_POST['post'] : '';

        if (!empty($post))
            $this->post->unsave($post);
    }
}
