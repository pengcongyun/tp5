<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/14 0014
 * Time: 16:26
 */
namespace app\index\validate;

use think\Validate;

class Link extends Validate
{
    protected $rule = [
        'title'  =>  'require|max:10',
        'description' =>  'require',
    ];
    protected $message  =   [
        'title.require' => '标题必须',
        'title.max'     => '名称最多不能超过10个字符',
        'description.require' => '描述必须',
    ];
    protected $scene = [
        'add'   =>  ['title','description'],
        'edit'  =>  ['title'],
    ];
}