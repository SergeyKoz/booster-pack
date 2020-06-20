<?php

/**
 * Created by PhpStorm.
 * User: mr.incognito
 * Date: 10.11.2018
 * Time: 21:36
 */
class Main_page extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        App::get_ci()->load->model('User_model');
        App::get_ci()->load->model('Login_model');
        App::get_ci()->load->model('Post_model');
        App::get_ci()->load->model('Boosterpack_model');

        App::get_ci()->load->model('Processing_model');
        App::get_ci()->load->model('Likeprocessing_model');

        App::get_ci()->load->library('commentservice');
        App::get_ci()->load->library('likeservice');
        App::get_ci()->load->library('profitbankservice');
        App::get_ci()->load->library('walletservice');
        App::get_ci()->load->library('boosterpackservice');

        if (is_prod())
        {
            die('In production it will be hard to debug! Run as development environment!');
        }
    }

    public function index()
    {
        $user = User_model::get_user();

        App::get_ci()->load->view('main_page', ['user' => User_model::preparation($user, 'default')]);
    }

    public function get_all_posts()
    {
        $posts =  Post_model::preparation(Post_model::get_all(), 'main_page');
        return $this->response_success(['posts' => $posts]);
    }

    public function get_post($post_id){ // or can be $this->input->post('news_id') , but better for GET REQUEST USE THIS

        $post_id = intval($post_id);

        if (empty($post_id)){
            return $this->response_error(CI_Core::RESPONSE_GENERIC_WRONG_PARAMS);
        }

        try
        {
            $post = new Post_model($post_id);
        } catch (EmeraldModelNoDataException $ex){
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NO_DATA);
        }

        $posts =  Post_model::preparation($post, 'full_info');
        return $this->response_success(['post' => $posts]);
    }


    /**
     * @param $post_id
     * @param int $parent_comment
     * @return object|string|void
     *
     * /main_page/comment/2/5
     */
    public function comment($post_id, $parent_comment = 0)
    { // or can be App::get_ci()->input->post('news_id') , but better for GET REQUEST USE THIS ( tests )
        try
        {
            $postRequest = $this->parsePostJsonRequest();
            $text = $postRequest['text'];

            if (!User_model::is_logged()){
                return $this->response_error(CI_Core::RESPONSE_GENERIC_NEED_AUTH);
            }

            $post_id = intval($post_id);

            if (empty($post_id) || empty($text)){
                return $this->response_error(CI_Core::RESPONSE_GENERIC_WRONG_PARAMS);
            }

            $post = new Post_model($post_id);
            $this->commentservice->addComment($post, $parent_comment, $text);
            $posts =  Post_model::preparation($post, 'full_info');
        }
        catch (EmeraldModelNoDataException $ex){
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NO_DATA);
        }
        catch (\Exception $ex){
            return $this->response_error($ex->getMessage());
        }

        return $this->response_success(['post' => $posts]);
    }

    public function login()
    {
        try {
            $request = $this->parsePostJsonRequest();

            $login = $request['login'];
            $password =  $request['password'];

            $user_id = Login_model::login($login, $password);
        } catch (Exception $exception) {
            return $this->response_error($exception->getMessage());
        }

        return $this->response_success(['user' => $user_id]);
    }

    public function logout()
    {
        Login_model::logout();
        redirect(site_url('/'));
    }

    public function add_money(){
        if (!User_model::is_logged()){
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NEED_AUTH);
        }
        try {
            $postRequest = $this->parsePostJsonRequest();
            $sum = $postRequest['sum'];
            $reason = sprintf('Refill wallet balance +%d', $sum);
            $this->walletservice->refill($sum, $reason);
        } catch (\Exception $ex){
            return $this->response_error($ex->getMessage());
        }
        // todo: add money to user logic
        return $this->response_success(['amount' => $this->walletservice->balance()]);
    }

    public function buy_boosterpack($id){
        if (!User_model::is_logged()){
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NEED_AUTH);
        }

        try {
            $busterpack = new Boosterpack_model($id);
            [$likes, $profitbank] = $this->boosterpackservice->buy($busterpack);
        } catch (Exception $exception) {
            return $this->response_error($exception->getMessage());
        }

        return $this->response_success(['likes' => $likes, 'profitbank' => $profitbank]);
    }

    /**
     * @param $type string post/comment
     * @param $id
     * @return object|string|void
     */
    public function like($type, $id){

        if (!User_model::is_logged()){
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NEED_AUTH);
        }

        try {
            $likes = $this->likeservice->likesUpdate($id, $type);
        } catch (Exception $exception) {
            return $this->response_error($exception->getMessage());
        }

        return $this->response_success(['likes' => $likes]); // Колво лайков под постом \ комментарием чтобы обновить
    }

    public function create_user() {
        if (!User_model::is_logged()){
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NEED_AUTH);
        }
        try {
            $request = $this->parsePostJsonRequest();

            if (isset($request['password'])) {
                $request['password'] = $this->encrypt->encode($request['password']);
            }
            $user = User_model::create($request);

            return $this->response_success(['user' => $user->get_id()]);

        } catch (Exception $exception) {
            return $this->response_error($exception->getMessage());
        }
    }

    private function parsePostJsonRequest()
    {
        if ($this->input->method() != 'post') {
            throw new \Exception('Wrong request method');
        }

        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        return json_decode($stream_clean, true);
    }

}
