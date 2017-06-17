<?php
namespace app\index\controller;
use think\Db;
use think\Request;
use think\Validate;
use think\View;
use app\index\model\Link as Links;
class Index extends \think\Controller
{
    public function index()
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_bd568ce7058a1091"></thinkad>';
    }
    public function ceshi(){
//        $view=new View();
//        $view->fetch('add');
//        $this->assign([
//            'gg'=>11,
//        ]);
        $datas=[
            ['id'=>1,'name'=>22],
            ['id'=>2,'name'=>55]
        ];
//        $data=db('article')->limit(2)->select();
        $data=db('article')->find(78);
//        var_dump($data);exit;
        $this->assign('gg','ThinkPHP');
        return view('ceshi',['data'=>$data,'datas'=>$datas]);
    }
    //列表页
    public function articlelist(){
        /*$data=db('article')->limit(10)->order('id desc')->select();
        $this->assign('data',$data);
        return view();*/
        //在控制器查询文章表，每页10条。
        $data=Db::table('d_article')->order('id desc')->paginate(8);
        //渲染到模板的文章数据
        $this->assign('data' ,$data);
//        return view();
        return $this->fetch();
    }
    //table表
    public function tablelist(){
        $data=db('article')->paginate(20);
        return $this->fetch('tablelist',['data'=>$data]);
    }
    //详情页
    public function detail($id){
//        $data=db('article')->find($id);
        $data=db('article')->where('id',$id)->find();
        //去首空格
        $data['description'] = mb_ereg_replace('^(　| )+', '', $data['description']);
        return view('detail',['data'=>$data]);
    }
    //删除
    public function delete($id){
        $res=db('article')->where(['id'=>$id])->find();
        if(empty($res)){
            $this->error('数据出错了');exit;
        }
        $r=db('article')->delete($id);
        if($r){
            $this->success('删除成功');exit;
        }else{
            $this->error('删除失败');exit;
        }
    }
    //添加
    public function adds(){
        if($_POST){
            //获取当前的请求信息
            $request = Request::instance();
//            var_dump(input('server.'));
            $data=[
                'tag'=>$request->ip(),
                'title'=>$request->param('title'),
                'auth'=>$request->param('auth'),
                'description'=>$request->param('description'),
                'create_time'=>time(),
            ];
            //图片上传  存在就上传
            if(!empty($_FILES['img']['name'])){
                $file = $request->file('img');
                $info = $file->move(ROOT_PATH . 'public\\'.'upload' );
                $data['img']='\\'.'upload\\'.$info->getSaveName();
            };
            $res=Db::name('article')->insert($data);
            if($res){
                $this->success('添加成功','tablelist');exit;
            }else{
                $this->error('添加失败');exit;
            }
        }
        return view('adds');
    }
    //验证添加
    public function yz_add(){
        if(request()->isPost()){
            //获取当前的请求信息
            $request = Request::instance();
            $data=[
                'tag'=>$request->ip(),
                'title'=>input('title'),
                'auth'=>input('auth'),
                'description'=>input('description'),
                'create_time'=>time(),
            ];
            //图片上传  存在就上传
            if(!empty($_FILES['img']['name'])){
//                $file = $request->file('img');
                $file=input('file.img');
                $info = $file->move(ROOT_PATH . 'public\\'.'upload' );
                $data['img']='\\'.'upload\\'.$info->getSaveName();
            };
            $res=$this->validate($data,'Link');
            if(true!==$res){
                $this->error($res);exit;
            }
            $res=Db::name('article')->insert($data);
            if($res){
                $this->success('添加成功','tablelist');exit;
            }else{
                $this->error('添加失败');exit;
            }
        }
        return view('adds');
    }
    //视频播放
    public function shiping(){
        return view();
    }
}
