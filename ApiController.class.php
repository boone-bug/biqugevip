<?php
namespace Home\Controller;
use Home\Controller\BaseController;
use Think\Controller;
class ApiController extends BaseController {
	/* 该示例仅供参考，以下代码为调用月点击排行前100的小说（url、标题），并进行缓存（有效期5小时） ，调用接口http://www.abc.com/home/api/top100 */
	public function top100(){
		$top100 = S('top100', '', array('temp' => $this->temppath));
		if(!$seolist){
			$article = M('articles');
			$weektime = NOW_TIME - 7*24*3600;
			$arclist = $article->alias('a')->join(C('DB_PREFIX').'article_views av ON a.id=av.aid')->order('av.monthviews desc,a.id desc')->limit(100)->select();
			foreach ($arclist as $key => $value) {
				$row['url'] = C('PCHOST').reurl('view', $value);
				$row['title'] = $value['title'];
				$seolist[] = $row;
			}
			S('seolist', $seolist, array('temp' => $this->temppath, 'expire' => 3600*5));
		}
		$this->ajaxReturn($seolist);
	}
}