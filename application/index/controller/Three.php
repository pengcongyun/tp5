<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/17 0017
 * Time: 16:01
 */

namespace app\index\controller;


use think\Config;

class Three
{
    public function index(){
        return view();
    }
    //qq
    public function qq(){
        $config = Config::get('thirdlogin.qq');
        // 获取回调地址 http://xxx.com/public/home/Qqlogin/index
        $url = request()->root(true).'/'.request()->path();
        // trace('qq url '.$url);
        $redirect_uri = urlencode($url);
        // 公众号的id和secret
        $appid = $config['appid'];
        $appsecret = $config['appsecret'];
        $_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
        // 获取code码，用于和QQ服务器申请token。 注：依据OAuth2.0要求，此处授权登录需要用户端操作
        if(!isset($_GET['code']) && !isset($_SESSION['code'])){
            //以下信息可安放在用户登录界面上：
            $url= 'https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id='.$appid.'&redirect_uri='.$redirect_uri.'&scope=get_user_info&state='.$_SESSION['state'];
            header('Location:'.$url);//跳转到第三方登录入口
            exit;
        }
        // 依据code码去获取openid和access_token，自己的后台服务器直接向QQ服务器申请即可
        if (isset($_GET['code']) && !isset($_SESSION['token'])){
            $_SESSION['code'] = $_GET['code'];
            $url="https://graph.qq.com/oauth2.0/token?client_id=".$appid.
                "&client_secret=".$appsecret."&code=".$_GET['code']."&grant_type=authorization_code"
                ."&state=".$_SESSION['state']."&redirect_uri=".$redirect_uri;
            $res = $this->https_request($url);
            $res=(json_decode($res, true));
            $_SESSION['token'] = $res;
        }
        //  print_r($_SESSION);
        // 依据申请到的access_token和openid，申请Userinfo信息。
        if (isset($_SESSION['token']['access_token'])){
            $url = "https://graph.qq.com/user/get_user_info?oauth_consumer_key=".$appid."&access_token=".$_SESSION['token']['access_token']."&openid=".$_SESSION['token']['openid'].'&format=json';
            //echo $url;
            $res = $this->https_request($url);
            $res = json_decode($res, true);//最终得到的用户信息
            $_SESSION['userinfo'] = $res;

            header('Location:'.Url('Three/qq?userfrom=2'));//跳转到第三方登录入口,0自行注册1微信2QQ
            exit;
            // return $res;

        }
    }
    // cURL函数简单封装
    function https_request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
    //微信第三方登录
    public function wx(){
        $config = Config::get('thirdlogin.weixin');
        // 获取回调地址 http://xxx.com/public/home/Wxlogin/index
        $url = request()->root(true).'/'.request()->path();
        // trace('weixin url '.$url);
        $url = urlencode($url);
        // 公众号的id和secret
        $appid = $config['appid'];
        $appsecret = $config['appsecret'];
        // 获取code码，用于和微信服务器申请token。 注：依据OAuth2.0要求，此处授权登录需要用户端操作
        if(!isset($_GET['code']) && !isset($_SESSION['code'])){
            //以下信息可安放在用户登录界面上：
            $url= 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri='.$url.'&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect';
            header('Location:'.$url);//跳转到第三方登录入口
            exit;
        }
        // 依据code码去获取openid和access_token，自己的后台服务器直接向微信服务器申请即可
        if (isset($_GET['code']) && !isset($_SESSION['token'])){
            $_SESSION['code'] = $_GET['code'];
            $url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid.
                "&secret=".$appsecret."&code=".$_GET['code']."&grant_type=authorization_code";
            $res = $this->https_request($url);
            $res=(json_decode($res, true));
            $_SESSION['token'] = $res;
        }
        //  print_r($_SESSION);
        // 依据申请到的access_token和openid，申请Userinfo信息。
        if (isset($_SESSION['token']['access_token'])){
            $url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$_SESSION['token']['access_token']."&openid=".$_SESSION['token']['openid']."&lang=zh_CN";
            //echo $url;
            $res = $this->https_request($url);
            $res = json_decode($res, true);//最终得到的用户信息
            $_SESSION['userinfo'] = $res;
            header('Location:'.Url('Three/wx?userfrom=1'));//跳转到第三方登录入口
            exit;
            // return $res;
        }
    }
}