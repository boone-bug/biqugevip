<?php

namespace Home\Controller;

use Home\Controller\BaseController;
use Think\Controller;
class SitemapController extends BaseController
{
    public $temppath;
    public function sitemap_extend()
    {
        ob_end_clean();
        header('Content-type:text/xml');
        $this->temppath = TEMP_PATH;
        if (C('NOWHOST') == C('WAPHOST')) {
            $this->temppath .= 'wap/';
        }
        $xzv_24 = $_SERVER['HTTP_HOST'];
        $xzv_22 = I('get.limit', '', 'intval') ? I('get.limit', '', 'intval') : 1000;
        $xzv_12 = '99';
        $xzv_4 = 'sitemap_extend_' . $_SERVER['HTTP_HOST'] . '_' . $xzv_22;
        $xzv_19 = S($xzv_4, '', array('temp' => $this->temppath));
        if (!$xzv_19) {
            $xzv_3 = M('articles');
            $xzv_13 = $xzv_3->alias('a')->join(C('DB_PREFIX') . 'article_views av ON a.id=av.aid')->order('av.monthviews desc,a.id desc')->limit($xzv_22)->select();
            foreach ($xzv_13 as $xzv_8 => $xzv_18) {
                $xzv_13[$xzv_8]['rewriteurl'] = C('NOWHOST') . reurl('view', $xzv_18);
            }
            $xzv_7['maplist'] = $xzv_13;
            $xzv_7['weburl'] = C('NOWHOST') . C('HOME_URL');
            $xzv_7['dateline'] = date('Y-m-d');
            $xzv_19['mapinfo'] = $xzv_7;
            S($xzv_4, $xzv_19, array('temp' => $this->temppath, 'expire' => 3600 * 5));
        }
        $this->assign('sitemapdata', $xzv_19);
        $this->assign('index', $xzv_12);
        $this->display('Extend/sitemapxml');
    }
    public function sitemap_sm()
    {
        ob_end_clean();
        header('Content-type:text/xml');
        $xzv_25 = I('get.index', '', 'intval');
        if ($xzv_25 > 0) {
            $xzv_9 = 'sitemap_sm_' . $xzv_25;
        } else {
            $xzv_9 = 'sitemap_sm_index';
        }
        $xzv_11 = S($xzv_9, '', array('temp' => $this->temppath));
        $xzv_1 = F('setting');
        if (!$xzv_11) {
            $xzv_10 = M('articles');
            $xzv_11['dateline'] = date('Y-m-d');
            $xzv_23 = 1000;
            if ($xzv_25 > 0) {
                $xzv_20 = ($xzv_25 - 1) * $xzv_23;
                $xzv_21 = $xzv_25 * $xzv_23 + 1;
                $xzv_0 = $xzv_10->where("thumb is not null and info is not null and id > '{$xzv_20}' and id < '{$xzv_21}'")->order('id desc')->select();
                foreach ($xzv_0 as $xzv_2 => $xzv_5) {
                    $xzv_14[$xzv_2]['rewriteurl'] = reurl('view', $xzv_5);
                    $xzv_14[$xzv_2]['updatetime'] = date('Y-m-d', $xzv_5['updatetime'] ? $xzv_5['updatetime'] : NOW_TIME);
                    $xzv_14[$xzv_2]['title'] = utf8_for_xml($xzv_5['title']);
                    $xzv_14[$xzv_2]['author'] = utf8_for_xml($xzv_5['author']);
                    $xzv_14[$xzv_2]['author_encode'] = urlencode($xzv_5['author']);
                    $xzv_14[$xzv_2]['thumb'] = showcover($xzv_5['thumb']);
                    if (substr($xzv_14[$xzv_2]['thumb'], 0, 4) != 'http') {
                        $xzv_14[$xzv_2]['thumb'] = C('NOWHOST') . $xzv_14[$xzv_2]['thumb'];
                    }
                    $xzv_14[$xzv_2]['description'] = utf8_for_xml(cleanHtml($xzv_5['info']));
                    $xzv_5['cates'] = $xzv_5['cate'] == $this->category['default']['dir'] ? 'default' : $xzv_5['cate'];
                    $xzv_14[$xzv_2]['catename'] = $this->category[$xzv_5['cates']]['name'];
                    $xzv_14[$xzv_2]['status'] = $xzv_5['full'] > 0 ? 2 : 1;
                    $xzv_14[$xzv_2]['views'] = $xzv_5['views'];
                    $xzv_14[$xzv_2]['lastchapter'] = $xzv_5['lastchapter'];
                    $xzv_14[$xzv_2]['lastchapterurl'] = $xzv_5['lastcid'] ? reurl('chapter', array('id' => $xzv_5['id'], 'cate' => $xzv_5['cate'], 'cid' => $xzv_5['lastcid'])) : $xzv_14[$xzv_2]['rewriteurl'];
                    $xzv_14[$xzv_2]['webtitle'] = str_replace(array('{cate}', '{webname}', '{title}', '{author}', '{keyword}'), array($xzv_14[$xzv_2]['catename'], $xzv_1['seo']['webname'], $xzv_14[$xzv_2]['title'], $xzv_14[$xzv_2]['author'], ''), $xzv_1['seo']['viewtitle']);
                }
                $xzv_11['novellist'] = $xzv_14;
                S($xzv_9, $xzv_11, array('temp' => $this->temppath, 'expire' => 3600 * 5));
            } else {
                $xzv_15 = $xzv_10->Count();
                $xzv_16 = ceil($xzv_15 / $xzv_23);
                for ($xzv_17 = 1; $xzv_17 <= $xzv_16; $xzv_17++) {
                    $xzv_6[]['listurl'] = '/home/sitemap/sitemap_sm' . '?index=' . $xzv_17;
                }
                $xzv_11['maplist'] = $xzv_6;
                S($xzv_9, $xzv_11, array('temp' => $this->temppath, 'expire' => 3600 * 12));
            }
        }
        $this->assign('setting', $xzv_1);
        $this->assign('sitemapdata', $xzv_11);
        $this->assign('index', $xzv_25);
        $this->display('Extend/sitemap_sm');
    }
}