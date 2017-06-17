<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/15 0015
 * Time: 11:35
 */

namespace app\index\controller;


use think\Controller;
use think\Image;

class Upload extends Controller
{
    public function upload(){
        $pic1 =  '\upload\thumb\\'. time().'.jpg'; //数据库存的路径
        $pic =  ROOT_PATH.'public'.$pic1; //生成略缩图需要的绝对路径
        $file1 = request()->file('pic1');
        if($file1){
            Image::open($file1)->thumb(150, 150)->save($pic);
        }else{
            $file1->getError();
        }
        return json($pic1);
    }
    //删除
    public function delete($pic){
        $pic=str_replace('http://www.test.com/','/',$pic);
        $front=getcwd();
        $link=$front.$pic;
        $res=unlink($link);
        if($res===false){
            $data=[
                'status'=>1,
                'msg'=>'删除失败',
            ];
        }else{
            $data=[
                'status'=>2,
                'msg'=>'删除成功',
            ];
        }
        return json($data);
    }
}