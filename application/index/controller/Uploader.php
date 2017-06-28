<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/15 0015
 * Time: 10:06
 */

namespace app\index\controller;


use think\Controller;

class Uploader extends Controller
{
    //单图上传
    public function uploadone(){
        if(request()->isPost()){
            $file=request()->file('pic');
            var_dump($file);exit;
            //保存到根目录
            $info=$file->validate(['size'=>15678,'ext'=>'jpg,png,gif,jpeg'])->move(ROOT_PATH . 'public\\'.'upload');
            if($info){
                $pic='\\'.'upload\\'.$info->getSaveName();
                echo $pic;exit;
            }else{
                echo $info->getError();exit;
            }
        }
        return view();
    }
    //多图上传
    public function uploadmore(){
        if(request()->isPost()){
            $files = request()->file('pics');
            $pic=[];
            foreach($files as $file){
                // 移动到框架应用根目录/public/uploads/ 目录下
                $info = $file->validate(['size'=>15678,'ext'=>'jpg,png,gif,jpeg'])->move(ROOT_PATH . 'public' . DS . 'upload');
                if($info){
                    // 成功上传后 获取上传信息
                    $pics='\\'.'upload\\'.$info->getSaveName();
                    // 输出 42a79759f284b767dfcb2a0197904287.jpg
                }
                else{
                    // 上传失败获取错误信息
                    echo $file->getError();exit;
                }
                $pic[]=$pics;
            }
            $pic=implode(',',$pic);
            var_dump($pic);exit;
        }
        return view();
    }
    //ajax上传图片
    public function upload(){

        return view();
    }
    //ajax 上传多图片
    public function up(){
        if(request()->isPost()){
            if(!empty(input("post.paths/a"))){
                $data=[
                    'pic'=>implode(',',input('post.paths/a')),
                ];
            }else{
                $data=[
                    'pic'=>input('post.paths/a')
                ];
            }
            $res=db('apic')->insert($data);
            if($res){
                $this->success('添加成功','piclist');exit;
            }else{
                $this->error('添加失败','piclist');exit;
            }
        }
        return view();
    }
    //图片列表页
    public function piclist(){
        $data=db('apic')->select();
        foreach ($data as $k=>$v){
            if(!empty($v['pic'])){
                $data[$k]['pic']=explode(',',$v['pic']);
            }
        }
        return view('piclist',['data'=>$data]);
    }
    //删除
    public function delete($id){
        $row=db('apic')->find($id);
        if(empty($row)){
            $this->error('数据出错了');exit;
        }
        $r=db('apic')->delete($id);
        if($r){
            $this->success('删除成功');exit;
        }else{
            $this->error('删除失败');exit;
        }
    }
}