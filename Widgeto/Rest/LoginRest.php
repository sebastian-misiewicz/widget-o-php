<?php

namespace Widgeto\Rest;

class LoginRest {

    /* @var $app \Slim\Slim */
    public function __construct($app) {
        $app->post('/rest/login/', function () use ($app) {
            $user = json_decode($app->request->getBody(), true);

            if (!isset($user['username']) || empty($user['username'])) {
                $app->error();
            }
            
            if (!isset($user['password']) || empty($user['password'])) {
                $app->error();
            }
            
            $result = \dibi::query(
                'select username FROM `user` where username = %s and password = %s', 
                $user['username'], md5($user['password']))
                    ->fetchAll();
            
            if (sizeof($result) != 1) {
                return $app->error();
            }
            
            $oldToken = \dibi::query(
                    'select token from `oauth` where username = %s', 
                    $user['username'])->fetch();
            
            $token = bin2hex(openssl_random_pseudo_bytes(16));
            
            $auth = array();
            $auth["username"] = $user['username'];
            $auth["token"] = $token;
            if ($oldToken["token"]) {
                \dibi::query(
                        'update `oauth` set token = %s where username = %s', 
                        $token, $user['username']);
            } else {
                \dibi::query(
                        'insert into `oauth`', 
                        $auth);
            }
            
            echo json_encode($token);
        });
    }
    
}
