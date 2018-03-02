# tp5-pages
> 这是一个基于ThinkPHP5框架的Page类库

## 案例展示
> 基于ThinkPHP5开发呈现分页的效果  
![Image text](http://images.22058.com/github/tp5-page/page_2.jpg)

## 安装
> composer require zhujinkui/tp5-pages

## 控制器层代码举例使用
> 建立Member控制器作为会员模块
```
<?php
namespace app\index\controller;
use think\Controller;

class Member extends Controller
{
    public function index()
	{
		//$data通过select()查询未分页的数据，不能是已经分页的对象
        $data = db('Member')->select();

        //参数一：$data未分页的数据,参数二：每页显示的记录数
        $p = new \think\Page($data,2);
        //把分页后的对象$p渲染到模板
        $this->assign([
            'p' => $p,
        ]);

        return $this->fetch();
    }
}

```

## 视图层代码举例使用
> 分页html模板输出
> 默认已经载入h-ui框架css样式，否则需要重写css样式
```
{$p->render}

```
