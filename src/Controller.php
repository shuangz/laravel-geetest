<?php

namespace Shuangz\Geetest;


/**
*
*/
class Controller
{
    public $request;
    public $geetest;

    public function __construct()
    {
        $this->request = app()->make('request');
        $this->geetest = app()->make('geetest');
    }

    public function start()
    {
        $request = $this->request;

        $user_id = $request->has('user_id') ? $request->input('user_id') : mt_rand(10000, 99999);
        $data = [
                    'user_id'     => sha1($user_id),
                    'client_type' => config('geetest.client_type'),
                    'ip_address'  => $request->ip()
        ];

        /**
         * 验证服务器是否down机
         * @var 0 or 1
         */
        $status   = $this->geetest->pre_process($data, 1);
        $response = $this->geetest->get_response();
        $response['status'] = $status;
        $response['user_id'] = $data['user_id'];

        return $response;
    }
}