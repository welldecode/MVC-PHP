<?php

namespace app\library;

use Dotenv\Dotenv;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use app\database\models\Users;
use Throwable;

class Auth
{

    public static function auth_login()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Authorization, Content-Type, x-xsrf-token, x_csrftoken, Cache-Control, X-Requested-With');
 
        $authorization = $_SERVER['HTTP_AUTHORIZATION'];
        $token = str_replace('Bearer', '', $authorization);
        $data = []; 
        try {
            $token_b = self::checkToken($token);
            $data = ['status' => 'success', 'hash' => $token_b->id_hash];
        } catch (Throwable $e) {
            if ($e->getMessage() === 'Expired token') {
                $data = ['status' => 'error', 'Token invalido', 'type' => 'true'];
            }
        }
        echo json_encode($data);
    }

    public static function get_info_user(string $info_user)
    {

        $check_auth = self::is_user_logged_in();

        $decoded = self::checkToken();

        if ($check_auth) {
            $users = Users::whereFetch('id_hash', $decoded->id_hash);
            return $users[$info_user];
        }
    }

    public static function get_user_image(string $user_id = null)
    {

        $decoded = self::checkToken();
        $id_h = $user_id ?? $decoded->id_hash;
        $users = Users::whereFetch('id_hash', $id_h);

        $src = str_replace($_SERVER['DOCUMENT_ROOT'], '', $users['profile_image']);
        $url = '/assets/img/users/user.png';
        if ($src != '') {
            $url = '/assets/img/users/' . $src . '?v=' . time();
        }
        return $url;
    }

    public static function is_user_logged_in()
    {
        return (bool)self::checkToken();
    }

    public static function get_current_user_id()
    {
        $decoded = self::checkToken();
        $id_h = $user_id ?? $decoded->id_hash;
        $users = Users::whereFetch('id_hash', $id_h);
        if ($users) return $users['id'];
    }

    protected static function checkToken(string $token_bearer = null)
    {
        $token = $token_bearer ?? $_COOKIE['session'] ?? null;
        if ($token) {
            $decoded = JWT::decode($token, new Key('dhaisd7sds8dsodshdisuds7d8sd8gsd8suidgs8dsnxsxss989dslkd', 'HS256'));
            return $decoded;
        }
    }

    public static function getLogoutUser(string $user_id) {
        
    }

    public static function createPath($path)
    {
        $ret = @mkdir($path);
        $return = (($ret === true || is_dir($path)) ? true : false);
        return $return;
    }

    public static function createFolder(string $path)
    { 
        if (is_dir($path)) {
            return false;
        } else {
            if (!self::createPath($path)) {
                return false;
            }
        }
    } 
    
    public static function getBase64ImageSize($base64Image, $type = '')
    { 
        try {
            $size_in_bytes = (int)(strlen(rtrim($base64Image, '=')) * 0.75);

            $medidas = ['KB', 'MB', 'GB', 'TB'];

     
            if ($size_in_bytes < 999) {
                $size_in_bytes = 1000;
            }

            for ($i = 0; $size_in_bytes > 999; $i++) {
                $size_in_bytes /= 1024;
            }

            if ($type != '') {
                return $medidas[$i - 1];
            } else {
                return round($size_in_bytes);
            } 

        } catch (Exception $e) {
            return $e;
        }
    }
}
