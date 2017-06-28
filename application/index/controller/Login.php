<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/19 0019
 * Time: 10:17
 */

namespace app\index\controller;


use think\Controller;


class Login extends Controller
{
    public function login(){

        return $this->fetch();
    }

}