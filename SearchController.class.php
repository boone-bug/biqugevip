<?php

namespace Home\Controller;

use Home\Controller\BaseController;
use Think\Controller;
class SearchController extends BaseController
{
    public function index()
    {
        $xzv_14 = I('post.action');
        $xzv_9 = array('weburl' => C('NOWHOST') . C('HOME_URL'), 'webname' => $this->setting['seo']['webname'], 'src' => is_spider() ? 'src' : 'data-original', 'znsid' => $this->setting['seo']['znsid']);
        if ($xzv_14 == 'search') {
            $xzv_1 = I('post.q', '', 'htmlspecialchars,trim');
            $xzv_1 = mysql_escape_string(addslashes($xzv_1));
            $xzv_5 = I('get.page', '1', 'intval');
            $xzv_15 = 60;
            if ($xzv_1) {
                if (cookie('dosearch')) {
                    header('Content-type: text/html; charset=utf-8');
                    echo '<script>alert("' . $this->setting['seo']['searchlimit'] . '秒内只能搜索一次，请稍后重试！");location.href="/home/search";</script>';
                    die;
                }
                $xzv_13 = str_replace(array('.', "'", '/', '\\', ' '), '__INVALIED__', $xzv_1);
                if (strexists($xzv_13, '__INVALIED__')) {
                    header('Content-type: text/html; charset=utf-8');
                    echo '<script>alert("搜索词不允许包含英文符号和空格，请重试！");location.href="/home/search";</script>';
                    die;
                }
                if (strlen($xzv_1) > 30) {
                    header('Content-type: text/html; charset=utf-8');
                    echo '<script>alert("搜索词不能超过10个字！");location.href="/home/search";</script>';
                    die;
                }
                if ($this->setting['seo']['searchlimit'] > 0) {
                    cookie('dosearch', NOW_TIME, $this->setting['seo']['searchlimit']);
                }
                $xzv_0 = M('searchlog');
                $xzv_16 = $xzv_0->where("searchword='{$xzv_1}'")->find();
                if ($xzv_16['id']) {
                    $xzv_12 = $xzv_16['id'];
                    $xzv_0->where("id='{$xzv_12}'")->setField(array('ip' => get_client_ip(), 'dateline' => NOW_TIME));
                    $xzv_0->where("id='{$xzv_12}'")->setInc('num', 1);
                } else {
                    $xzv_3 = array('searchword' => $xzv_1, 'ip' => get_client_ip(), 'dateline' => NOW_TIME);
                    $xzv_12 = $xzv_0->add($xzv_3);
                }
                $xzv_7 = M('articles');
                $xzv_2['_string'] = "(title like '%{$xzv_1}%') OR (author like '%{$xzv_1}%')";
                $xzv_17 = 'id desc';
                $xzv_4 = $xzv_7->where($xzv_2)->order($xzv_17)->limit($xzv_15)->select();
                $xzv_6 = $xzv_7->where($xzv_2)->Count();
                if ($xzv_6 > 0 && $xzv_12) {
                    $xzv_0->where("id='{$xzv_12}'")->setField('hasresult', 1);
                }
                G('end');
                $xzv_9['runtime'] = G('begin', 'end');
                if ($xzv_6 == 1) {
                    $xzv_11 = reurl('view', $xzv_4[0]);
                    $this->redirect($xzv_11);
                } elseif ($xzv_6 == 0) {
                    header('Content-type: text/html; charset=utf-8');
                    echo '<script>alert("没有搜到相关内容，我们已记录并将尽快添加相关内容，请收藏本站明天再来看哦！\\n搜索技巧：可少字不能错字不能多字，否则可能搜到不到哦");location.href="/home/search";</script>';
                    die;
                } else {
                    foreach ($xzv_4 as $xzv_8 => $xzv_10) {
                        $xzv_4[$xzv_8]['rewriteurl'] = reurl('view', $xzv_10);
                        $xzv_4[$xzv_8]['thumb'] = showcover($xzv_10['thumb']);
                        $xzv_4[$xzv_8]['cateurl'] = reurl('cate', $xzv_10['cate']);
                        $xzv_4[$xzv_8]['author'] = $xzv_10['author'] ? $xzv_10['author'] : '…';
                        $xzv_10['cates'] = $xzv_10['cate'] == $this->category['default']['dir'] ? 'default' : $xzv_10['cate'];
                        $xzv_4[$xzv_8]['catename'] = $this->category[$xzv_10['cates']]['name'];
                        $xzv_4[$xzv_8]['updatetime'] = date('Y-m-d H:i:s', $xzv_10['updatetime'] ? $xzv_10['updatetime'] : NOW_TIME);
                        $xzv_4[$xzv_8]['description'] = $xzv_10['info'] ? mb_substr($xzv_10['info'], 0, '50', 'utf-8') : $xzv_10['title'] . '是由作者' . $xzv_10['author'] . '创作的' . $xzv_4[$xzv_8]['catename'] . '，欢迎您来本站阅读' . $xzv_10['title'];
                        $xzv_4[$xzv_8]['status'] = $xzv_10['full'] > 0 ? '完成' : '连载';
                        $xzv_4[$xzv_8]['lastchapter'] = $xzv_10['lastchapter'] ? $xzv_10['lastchapter'] : '最近更新>>';
                        $xzv_4[$xzv_8]['lastchapterurl'] = $xzv_10['lastcid'] ? reurl('chapter', array('id' => $xzv_10['id'], 'cate' => $xzv_10['cate'], 'cid' => $xzv_10['lastcid'], 'pinyin' => $xzv_10['pinyin'])) : $xzv_4[$xzv_8]['rewriteurl'];
                    }
                    $this->assign('TDK', $xzv_9);
                    $this->assign('q', $xzv_1);
                    $this->assign('articlelist', $xzv_4);
                    $this->display('result');
                    die;
                }
            }
        }
        G('end');
        $xzv_9['runtime'] = G('begin', 'end');
        $this->assign('TDK', $xzv_9);
        $this->display();
    }
}