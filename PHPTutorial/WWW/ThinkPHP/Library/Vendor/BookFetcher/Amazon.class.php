<?php
// +----------------------------------------------------------------------
// | 亚马逊数据抓取类
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://bookorz.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 尹沛敏 <yin@peimin.cn> <http://www.peimin.cn>
// +----------------------------------------------------------------------
require_once(dirname(__FILE__)."/BookFetcher.class.php");
class Amazon extends BookFetcher{
	/**
     * 构造函数
     * @param boolean $open_api 是否开启Open API
     */
	public function __construct($open_api=false){
		parent::__construct($open_api);
	}

	/**
     * 搜索关键字，返回搜索列表
     * @access public
     * @param string $keyword 搜索关键字
     * @param string $page 搜索列表的页码，默认显示第一页
     * @return 成功返回array()|失败返回boolean false
     */
    public function search($keyword,$page=''){
    	$url='http://www.amazon.cn/s/search-alias=stripbooks&field-keywords='.$keyword;
    	if(!empty($page)&&preg_match('/\d+/', $page))
    		$url.='&page='.$page;
    	$data=$this->_fetch($url);
    	if(!$data)
    		return false;
    	if(!preg_match('/对不起，没有找到您想要的/sU', $data['content'])){
			if(preg_match_all('/<div id="result_\d+" class=".+" name="(\w+)">.*<br clear="all">/sU', $data['content'], $matches_raw)){
				$arr=array();
				foreach ($matches_raw[1] as $key => $value) {
					if($key==16)
						break;
					$item=array(
						'id' => $value,//这本书在博库网的id *
						'title' => '',//书名 *
						'original_price' => '',//原价 *
						'selling_price' => '',//售价 *
						'picture' => '',//图片（小图）地址 *
						'author' => '',//作者*
						'publisher' => '',//出版社*
						'publish_date' => '',//出版时间
						'time' => time() //数据获取时间
					);
					//标题、作者、出版社、出版时间
					if(preg_match('/<span class="lrg">(.*)<\/span><\/a> <span class="med reg">(.*) ([^ ]*)  \((\d{4}\-\d{2})\)<\/span>/U', $matches_raw[0][$key], $match)){
						$item['title'] = html_entity_decode($match[1]);
						$item['author'] = $match[2];
						$item['publisher'] = $match[3];
						$item['publish_date'] = $match[4].'-01';
					}

					//价格
					if(preg_match('/<del class="grey">￥(.*)<\/del><span class="bld lrg red"> ￥(.*)<\/span>/U', $matches_raw[0][$key], $match)){
						$item['original_price']=$match[1];
						$item['selling_price']=$match[2];
					}

					//图片
					if(preg_match('/<img onload="viewCompleteImageLoaded.*" src="(.*)"  class="productImage cfMarker" alt="产品详细信息" \/>/U', $matches_raw[0][$key], $match)){
						$item['picture']=$this->picSizeSwitch($match[1],'s');//过滤图片URL,去除"在线试读"水印
					}

					//作者
					if(preg_match('/<img onload="viewCompleteImageLoaded(this, new Date().getTime(), 16, false);" src="(.*)"  class="productImage cfMarker" alt="产品详细信息" \/>/U', $matches_raw[0][$key], $match)){
						$item['picture']=$match[1];
					}
					$arr[]=$item;
				}
				$page_match=array();
				preg_match('/<h2 id="s-result-count" class="s-result-count a-size-base a-text-normal s-first-column">\n                显示： (\d+)\-(\d+)条， 共(\d+)条 <a href=/U', $data['content'], $page_match);
				return array('total_number'=>(int)$page_match['3'],
					'total_page'=>(int)($page_match['3']/16)+1,
					'now_page'=>(int)($page_match['1']/16)+1,
					'list'=>$arr);
			}else{
				$this->error='列表数据匹配失败，可能页面结构发生了变化，请更新正则表达式！';
				return false;
			}
		}else{
			$this->error='无搜索结果！';
			return false;
		}
    }

 	/**
     * 获得图书的详细信息
     * @access public
     * @param string $id 图书在源站点的id
     * @return 成功返回array()|失败返回boolean false
     */
    public function detailFetch($id){
    	if($this->open_api){//访问BookFetcher Open API 获取数据
    		return $this->_APIRequest('http://bookfetcher.sinaapp.com/Api/detailFetch',array('site'=>'amazon','id'=>$id));
    	}
    	$url=$this->getUrl($id);
    	$data=$this->_fetch($url);
    	if(!$data)
    		return false;
    	switch ($data['http_code']) {
    		case 200:
				if(!$this->_isValid($data['content'])){
					$this->error='页面错误，状态码：200';
	                    return false;
				}
				$detail=array('id'=>'',
					'title'=>'',//标题
					'isbn'=>'',//ISBN码
					'category'=>array(),//所属分类，二维数组，因为同一本书可能属于多个分类
					'original_price'=>'',//原价
					'selling_price'=>'',//售价
					'author'=>'',//作者
					'publisher'=>'',//出版社
					'publish_date'=>'',//出版时间
					'edition'=>'',//版本
					'print_date'=>'',//印刷时间
					'print_times'=>'',//印次
					'format'=>'',//开本
					'pagenum'=>'',//页数
					'picture'=>'',//图片(大图)url
					'recommend'=>'',//编辑推荐语
					'about_content'=>'',//内容简介
					'about_author'=>'',//作者简介
					'catalogue'=>'',//目录
					'preface'=>'',//前言
					'goodpage'=>'',//精彩章节
					'time' => time() //数据获取时间
					);
				$detail['id'] = $id;
				//获取ISBN
				preg_match('/<li><b>条形码:<\/b> (.*)<\/li>/U',$data['content'],$match);
				$detail['isbn']=$match[1];
				//获取图书分类信息
				$detail['category'] = array();
				if(preg_match_all('/<span class="zg_hrsr_ladder">-&nbsp;<a href="http:\/\/www\.amazon\.cn\/gp\/bestsellers\/books.*">图书<\/a> &gt; (.*)<\/span>/sU', $data['content'], $matches)){
					foreach ($matches[1] as $key => $value) {
						$detail['category'][] = explode(' &gt; ', strip_tags($value));
					}
				}
				//获取书名
				if(!preg_match('/<title>《(.*)》 (.*)【摘要 书评 试读】图书<\/title>/U',$data['content'],$match)){
					preg_match('/<title>(.*)\/(.*)-图书-亚马逊中国/U',$data['content'],$match);
				}
				$detail['title']=$match[1];
				//获取作者
				$detail['author']=$match[2];	
				if(preg_match('/<td><span id="listPriceValue"  class="listprice">￥ (.*)<\/span><\/td>/U',$data['content'],$match)){
					$detail['original_price']=$match[1];
				}else{
					if(preg_match('/<span class="a-color-secondary">市场价:<\/span> <span class="a-color-secondary a-text-strike">￥(.*)<\/span>/U',$data['content'],$match))
						$detail['original_price']=$match[1];
				}
				if(preg_match('/<td id="actualPriceContent"><span id="actualPriceValue"><b class="priceLarge">￥ (.*)<\/b><\/span>/U',$data['content'],$match)){
					$detail['selling_price']=$match[1];
				}else{
					if(preg_match('/<span class="a-size-medium a-color-price offer-price a-text-normal">￥(.*)<\/span>/U',$data['content'],$match))
						$detail['selling_price']=$match[1];
				}

				//获取出版社
				preg_match('/<li><b>出版社:<\/b> (.*); 第(\d*)版 \((\d{4})年(\d+)月(\d+)日\)<\/li>/U',$data['content'],$match);
				$detail['publisher']=$match[1];

				//获取出版时间
				$detail['publish_date']=$match[3].'-'.$match[4].'-'.$match[5];
				
				//版次
				$detail['edition']=$match[2];

				//获取印刷时间
				$detail['print_date']=NULL;
				
				//印次
				$detail['print_times']=NULL;

				//获取开本
				preg_match('/<li><b>开本:<\/b> (\d*)<\/li>/U',$data['content'],$match);
				$detail['format']=$match[1];

				//获取页数
				preg_match('/<li><b>.*:<\/b> (\d*)页<\/li>/U',$data['content'],$match);
				$detail['pagenum']=$match[1];

				$detail_content=$this->_fetch('http://www.amazon.cn/dp/product-description/'.$id);

				//获取图片
				preg_match('/<img src="(.*)"\\n             id="original-main-image"/U',$detail_content['content'],$match);
				$arr=explode('.', $match[1]);
				$img=$arr[0].'.'.$arr[1].'.'.$arr[2].'.jpg';
				$detail['picture']=$img ? $img : NULL;
				//获取文字信息
				$preg='/<h3 class="productDescriptionSource">(.*)?<\/h3>\\n       <div class="productDescriptionWrapper">\\n       (.*)?\\n      \\n      <div class="emptyClear">/sU';
				preg_match_all($preg,$detail_content['content'],$match);
				foreach($match[1] as $key=>$value){
					switch($value){
						case '编辑推荐':
							$detail['recommend']=$match[2][$key];
							break;
						case '内容简介':
							$detail['about_content']=$match[2][$key];
							break;
						case '作者简介':
							$detail['about_author']=$match[2][$key];
							break;
						case '序言':
							$detail['preface']=$match[2][$key];
							break;
						case '文摘':
							$detail['goodpage']=$match[2][$key];
							break;
						case '目录':
							$detail['catalogue']=$match[2][$key];
							break;
					}
				}		
				return $detail;
				break;
    		
    		case '404':
                // 确保是目标网站返回的404错误
                if($this->_notExist()){
                    $this->error="图书不存在！";
                    return false;
                }else{
                    $this->error='页面错误，状态码：404';
                    return false;
                }
                break;
    		default:
    			$this->error='页面错误，状态码：'.$data['http_code'];
    			return false;
    			break;
    	}
    	
    }

    /**
     * 根据源站点id生成超链接
     * @abstract
     * @static
     * @access public
     * @param string $id 源站点id
     * @param string $union_id 联盟id
     * @return string
     */
    static public function getUrl($id,$union_id=''){
    	if(empty($union_id))
    		return $url='http://www.amazon.cn/gp/product/'.$id;
    	else
    		return $url='http://www.amazon.cn/gp/product/'.$id.'?tag='.$union_id;
    }

    /**
     * 切换图片尺寸
     * @abstract
     * @static
     * @access public
     * @param string $url 任意尺寸图片的url
     * @param string $size 尺寸 四种尺寸参数如下：t:tiny,s:small,m:medium,l:large
     * @return string 切换尺寸后的图片URL
     */
    static public function picSizeSwitch($url,$size='l'){
    	// 亚马逊的图片尺寸可任意调节，如下URL中，SL后面的300即为图片尺寸，可调整至任意值
    	// http://ec4.images-amazon.com/images/I/51IFsf2Ga3L.__SL300__.jpg
    	if(preg_match('/^(.*)\/images\/I\/(.*)\./U', $url, $match)){
    		switch (strtoupper($size)) {
    			case 'T':
    			case 'TINY':
    				return $match[1].'/images/I/'.$match['2'].'.__SL100__.jpg';
    				break;
    			case 'S':
    			case 'SMALL':
    				return $match[1].'/images/I/'.$match['2'].'.__SL160__.jpg';
    				break;
    			case 'M':
    			case 'MEDIUM':
    				return $match[1].'/images/I/'.$match['2'].'.__SL300__.jpg';
    				break;
    			case 'L':
    			case 'LARGE':
    			default:
    				return $match[1].'/images/I/'.$match['2'].'.jpg';
    				break;
    		}
    	}else
    		return $url;
    }

    /**
     * 验证打开的页面是否有效
     * @abstract
     * @access protected
     * @param string $content 抓取到的页面内容
     * @return boolean
     */
    protected function _isValid($content){
    	if(preg_match('/(【摘要 书评 试读】图书)|(图书-亚马逊中国)/', $content))
    		return true;
    	else
    		return false;
    }

    /**
     * 判断商品出售单页是否存在
     * @abstract
     * @access protected
     * @param string $content 抓取到的页面内容
     * @return boolean
     */
    protected function _notExist($content){
    	 if(preg_match('/404 - 未找到文档/', $content))
    	 	return true;
    	 else
    	 	return false;
    }
}