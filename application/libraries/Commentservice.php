<?php

class Commentservice
{
    function __construct()
    {
        App::get_ci()->load->model('Comment_model');
        App::get_ci()->load->model('User_model');
    }

    /**
     * @param Post_model $post
     * @param int $parent_commit_id
     * @param string $message
     * @throws Exception
     */
    public function addComment(Post_model $post, int $parent_commit_id, string $message)
    {
        if ($parent_commit_id > 0) {
            $parent_commit = new Comment_model($parent_commit_id);
            if ($parent_commit->get_assign_id() != $post->get_id()) {
                throw new \Exception("Wrong post");
            }
        }

        $user = User_model::get_user();

        $comment = [
            'parent_id' => $parent_commit_id,
            'user_id' => $user->get_id(),
            'assign_id' => $post->get_id(),
            'text' => $message
        ];

        Comment_model::create($comment);
    }

}