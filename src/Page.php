<?php
// 分页类库     
// +----------------------------------------------------------------------
// | PHP version 5.4+                
// +----------------------------------------------------------------------
// | Copyright (c) 2012-2014 http://www.17php.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: zhujinkui <developer@zhujinkui.com>
// +----------------------------------------------------------------------

namespace think;

class Page 
{
    public  $page;          //当前页
    public  $total;         //总记录数
    public  $listRows;      //每页显示记录数
    private $uri;           //动态url
    public  $pageNum;       //总页数
    private $listNum = 6;     //显示页码按钮数量
    public  $render;        //分页后的html模板
    public  $data;          //分页后渲染到模板的数据

    /**
     * [__construct 初始化分页数据]
     * @param integer  $sdata    [待分页的数据]
     * @param integer $listRows [每页记录数]
     */
    public function __construct($sdata, $listRows=15)
    {
        $this->total    = count($sdata);
        $this->listRows = $listRows;
        $this->uri      = $this->getUri();
        $this->page     = !empty($_GET["page"]) ? $_GET["page"] : 1;
        $this->pageNum  = ceil($this->total/$this->listRows);
        $this->render   = $this->pageHtml();
        $this->data     = array_slice($sdata,($this->page-1)*$this->listRows,$listRows);
        return $this->data;
    }

    /**
     * [getUri 动态获取url]
     * @return string   
     */
    private function getUri()
    {
        $url   = $_SERVER["REQUEST_URI"].(strpos($_SERVER["REQUEST_URI"], '?')?'':"?");
        $parse = parse_url($url);

        if(isset($parse["query"])){
            parse_str($parse['query'],$params);
            unset($params["page"]);
            $url = $parse['path'].'?'.http_build_query($params);
        }

        return $url;
    }

    /**
     * [first 首页]
     * @return string  
     */
    private function first()
    {
        $html = "";
        if($this->page==1)
            $html.=" <a style='magin=10px;' class='current btn disabled'>首 页</a>";
        else
            $html.=" <a class='btn btn-primary-outline' href='{$this->uri}&page=1'>首 页</a>";

        return $html;
    }

    /**
     * [prev 上一页]
     * @return string  
     */
    private function prev(){
        $html = "";
        if($this->page==1)
            $html.=" <a class='current btn disabled'>上一页</a>";
        else
            $html.=" <a class='btn btn-primary-outline' href='{$this->uri}&page=".($this->page-1)."'>上一页</a>";

        return $html;
    }

    /**
     * [pageList 页码按钮]
     * @return string  
     */
    private function pageList(){
        $linkPage="";

        $inum=floor($this->listNum/2);

        for($i=$this->page-$inum;$i<=$this->page+$inum;$i++){
            if($i<=0){
                continue;
            }
            if($i>$this->pageNum){
                continue;
            }
            if($i == $this->page){
                $linkPage.=" <a class='current btn btn-secondary'>{$i}</a>";
            }else{
                $linkPage.=" <a class='btn btn-primary-outline' href='{$this->uri}&page={$i}'>{$i}</a>";
            }
        }

        return $linkPage;
    }

    /**
     * [next 下一页]
     * @return string  
     */
    private function next(){
        $html = "";
        if($this->page==$this->pageNum)
            $html.=" <a class='current btn disabled'>下一页</a>";
        else
            $html.=" <a class='btn btn-primary-outline' href='{$this->uri}&page=".($this->page+1)."'>下一页</a>";

        return $html;
    }

    /**
     * [last 尾页]
     * @return string  
     */
    private function last(){
        $html = "";
        if($this->page==$this->pageNum)
            $html.=" <a class='current btn disabled'>尾 页</a>";
        else
            $html.=" <a class='btn btn-primary-outline' href='{$this->uri}&page=".($this->pageNum)."'>尾 页</a>";

        return $html;
    }

    /**
     * [goPage 输入指定页码]
     * @return string  
     */
    private function goPage(){
        return '  <input class="input-text" type="text" onkeydown="javascript:if(event.keyCode==13){var page=(this.value>'.$this->pageNum.')?'.$this->pageNum.':this.value;location=\''.$this->uri.'&page=\'+page+\'\'}" value="'.$this->page.'" style="width:52px"><input class="btn btn-secondary" type="button" value="GO" onclick="javascript:var page=(this.previousSibling.value>'.$this->pageNum.')?'.$this->pageNum.':this.previousSibling.value;location=\''.$this->uri.'&page=\'+page+\'\'">  ';
    }

    /**
     * [selectPage 选择指定页码]
     * @return string  
     */
    function selectPage(){
        $inum=10;
        $location = $this->uri.'&page=';
        $selectPage ="<span class='va-m'>到第 </span> <span class='select-box' style='width:initial'><select class='select' name='topage' size='1' onchange='window.location=\"$location\"+this.value'>";

        for($i=$this->page-$inum;$i<=$this->page+$inum;$i++){
            if($i<=0){
                continue;
            }
            if($i>$this->pageNum){
                continue;
            }
            if($i == $this->page){
                $selectPage .="<option value='$i' selected>$i</option>";
            }else{
                $selectPage .="<option value='$i'>$i</option>";
            }
        }

        $selectPage .="</select></span> <span class='va-m'>页</span>";

        return $selectPage;
    }

    /**
     * [pageHtml 组装分页的html模板]
     * @return string  
     */
    function pageHtml(){
        $html  = "<div class='cl mt-20 text-c'>";
        // $html .= "<span class='pr-20 va-m'>共有<b>{$this->total}</b>条记录</span>";
        // $html .= "<span class='pr-20 va-m'>每页显示<b>{$this->listRows}</b>条</span>";
        // $html .= "<span class='pr-20 va-m'><b>当前{$this->page}/{$this->pageNum}</b>页</span>";

        $html .= $this->first();
        $html .= $this->prev();
        $html .= $this->pageList();
        $html .= $this->next();
        $html .= $this->last();
        $html .= $this->goPage();
        $html .= $this->selectPage();
        $html .= '</div>';

        return $html;
    }
}