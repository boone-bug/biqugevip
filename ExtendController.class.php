<?php

namespace Home\Controller;

use Home\Controller\BaseController;
use Think\Controller;
class ExtendController extends BaseController
{
    public function seoword()
    {
        $xzv_67 = I('get.id');
        if (is_numeric($xzv_67)) {
            $xzv_66['id'] = array('eq', $xzv_67);
        } else {
            $xzv_66['ename'] = array('eq', $xzv_67);
        }
        $xzv_65 = M('seowords');
        $xzv_61 = $xzv_65->where($xzv_66)->find();
        if (!$xzv_61) {
            $this->error('出错啦！', '/');
        }
        $xzv_65->where($xzv_66)->setInc('views', 1);
        $xzv_62 = S(array('type' => 'file', 'expire' => 3600, 'prefix' => $xzv_64, 'length' => 500, 'temp' => $this->temppath . 'index/'));
        $xzv_63 = reurl('seoword', $xzv_61);
        $xzv_54 = array('weburl' => C('NOWHOST') . $xzv_63, 'canonicalurl' => C('NOWHOST') . $xzv_63, 'canonicalurl_m' => C('WAPHOST') . $xzv_63, 'webname' => $xzv_61['sitename'], 'title' => $xzv_61['title'], 'keyword' => $xzv_61['keywords'], 'description' => $xzv_61['description']);
        $xzv_56 = F('seowords');
        if (!$xzv_56) {
            $xzv_56 = $xzv_65->limit('500')->select();
            F('seowords', $xzv_56);
        }
        $xzv_53 = unique_array($xzv_56, 10, false);
        foreach ($xzv_53 as $xzv_55 => $xzv_58) {
            $xzv_53[$xzv_55]['rewriteurl'] = C('NOWHOST') . reurl('seoword', $xzv_58);
        }
        $xzv_53[] = array('rewriteurl' => C('NOWHOST') . C('HOME_URL'), 'sitename' => $this->setting['seo']['webname']);
        $this->assign('randseolist', $xzv_53);
        $xzv_57 = C('NOWHOST') == C('WAPHOST') ? 10 : 20;
        $xzv_60 = 'updatelist_' . $xzv_57;
        $xzv_59 = $xzv_62->{$xzv_60};
        $xzv_41 = $xzv_62->newestlist;
        G('end');
        $xzv_54['runtime'] = G('begin', 'end');
        $this->assign('TDK', $xzv_54);
        $this->assign('updatelist', $xzv_59);
        $this->assign('newestlist', $xzv_41);
        $this->display();
    }
    public function taglist()
    {
        $xzv_43 = S(array('type' => 'file', 'expire' => $this->setting['extra']['listcachetime'], 'prefix' => $xzv_45, 'length' => 500, 'temp' => $this->temppath . 'tags/'));
        $xzv_44 = I('param.id');
        if (!preg_match('/^([0-9A-Za-z_])+$/', $xzv_44) || strlen($xzv_44) > 30) {
            $this->error('出错啦！', U('/home/index'));
        }
        $xzv_40 = 'taglist_' . $xzv_44;
        $xzv_38 = $xzv_43->{$xzv_40};
        $xzv_39 = 'tagdb_' . $xzv_44;
        $xzv_42 = $xzv_43->{$xzv_39};
        if (!$xzv_38 || !$xzv_42) {
            $xzv_46 = M('tags');
            $xzv_51 = M('tagdatas');
            $xzv_52 = M('articles');
            if (is_numeric($xzv_44)) {
                $xzv_42 = $xzv_46->where("id='{$xzv_44}'")->find();
            } else {
                $xzv_42 = $xzv_46->where("ename='{$xzv_44}'")->find();
            }
            $xzv_50 = $xzv_42['id'];
            if (!$xzv_50) {
                $this->error('出错啦！', U('/home/index'));
            }
            $xzv_49 = $xzv_51->where("tid='{$xzv_50}'")->select();
            foreach ($xzv_49 as $xzv_47 => $xzv_48) {
                $xzv_35 = $xzv_35 ? $xzv_35 . ',' . $xzv_48['aid'] : $xzv_48['aid'];
            }
            $xzv_36['id'] = array('in', $xzv_35);
            $xzv_38 = $xzv_52->where($xzv_36)->order('id desc')->limit(60)->select();
            foreach ($xzv_38 as $xzv_47 => $xzv_48) {
                if ($xzv_48['cate'] == $this->defaultdir || !$xzv_48['cate']) {
                    $xzv_37 = 'default';
                } else {
                    $xzv_37 = $xzv_48['cate'];
                }
                $xzv_38[$xzv_47]['rewriteurl'] = reurl('view', $xzv_48);
                $xzv_38[$xzv_47]['thumb'] = showcover($xzv_48['thumb']);
                $xzv_38[$xzv_47]['cateurl'] = reurl('cate', $xzv_48['cate']);
                $xzv_38[$xzv_47]['author'] = $xzv_48['author'] ? $xzv_48['author'] : '…';
                $xzv_38[$xzv_47]['catename'] = $this->category[$xzv_37]['name'];
                $xzv_38[$xzv_47]['catename_short'] = mb_substr($xzv_38[$xzv_47]['catename'], 0, 2, 'utf-8');
                $xzv_38[$xzv_47]['updatetime'] = date('Y-m-d H:i:s', $xzv_48['updatetime'] ? $xzv_48['updatetime'] : NOW_TIME);
                $xzv_38[$xzv_47]['description'] = $xzv_48['info'] ? mb_substr($xzv_48['info'], 0, '50', 'utf-8') : $xzv_48['title'] . '是由作者' . $xzv_48['author'] . '创作的' . $xzv_38[$xzv_47]['catename'] . '，欢迎您来本站阅读' . $xzv_48['title'];
                $xzv_38[$xzv_47]['status'] = $xzv_48['full'] > 0 ? '完成' : '连载';
                $xzv_38[$xzv_47]['lastchapter'] = $xzv_48['lastchapter'] ? $xzv_48['lastchapter'] : '最近更新>>';
                $xzv_38[$xzv_47]['lastchapterurl'] = $xzv_48['lastcid'] ? reurl('chapter', array('id' => $xzv_48['id'], 'cate' => $xzv_48['cate'], 'cid' => $xzv_48['lastcid'])) : $xzv_38[$xzv_47]['rewriteurl'];
            }
            $xzv_43->{$xzv_40} = $xzv_38;
            $xzv_43->{$xzv_39} = $xzv_42;
        }
        $xzv_34 = $xzv_43->hot;
        if (!$xzv_34) {
            $xzv_52 = M('articles');
            $xzv_32 = NOW_TIME - 7 * 24 * 3600;
            $xzv_34 = $xzv_52->alias('a')->join(C('DB_PREFIX') . 'article_views av ON a.id=av.aid')->order('av.weekviews desc')->limit(20)->select();
            foreach ($xzv_34 as $xzv_47 => $xzv_48) {
                $xzv_34[$xzv_47]['rewriteurl'] = reurl('view', $xzv_48);
            }
            $xzv_43->hot = $xzv_34;
        }
        $xzv_16 = $xzv_43->moretag;
        if (!$xzv_16) {
            $xzv_46 = $xzv_46 ? $xzv_46 : M('tags');
            $xzv_14 = $xzv_46->Count();
            $xzv_17 = mt_rand(0, $xzv_14 > 200 ? $xzv_14 - 200 : 0) . ', 200';
            $xzv_16 = $xzv_46->order('num desc')->limit($xzv_17)->select();
            foreach ($xzv_16 as $xzv_47 => $xzv_48) {
                $xzv_16[$xzv_47]['tagurl'] = reurl('tag', $xzv_48);
            }
            $xzv_43->moretag = $xzv_16;
        }
        $xzv_18 = array('weburl' => C('NOWHOST') . C('HOME_URL'), 'webname' => $this->setting['seo']['webname'], 'title' => str_replace(array('{tagname}', '{webname}'), array($xzv_42['tagname'], $this->setting['seo']['webname']), $this->setting['seo']['tagtitle']), 'keyword' => str_replace(array('{tagname}', '{webname}'), array($xzv_42['tagname'], $this->setting['seo']['webname']), $this->setting['seo']['tagkw']), 'description' => str_replace(array('{tagname}', '{webname}'), array($xzv_42['tagname'], $this->setting['seo']['webname']), $this->setting['seo']['tagdes']), 'canonicalurl' => C('NOWHOST') . reurl('tag', $xzv_42), 'canonicalurl_m' => C('WAPHOST') . reurl('tag', $xzv_42));
        G('end');
        $xzv_18['runtime'] = G('begin', 'end');
        $this->assign('tagdbs', $xzv_42);
        $this->assign('moretag', $xzv_16);
        $this->assign('TDK', $xzv_18);
        $this->assign('taglist', $xzv_38);
        $this->assign('hotarticle', $xzv_34);
        $this->display();
    }
    public function author()
    {
        $xzv_19 = I('get.author', '', 'urldecode,g2u');
        if (strlen($xzv_19) > 30) {
            $this->error('作者不存在');
        }
        $xzv_20 = S(array('type' => 'file', 'expire' => $this->setting['extra']['listcachetime'], 'prefix' => $xzv_12, 'length' => 500, 'temp' => $this->temppath . 'author/'));
        $xzv_9 = md5($xzv_19);
        $xzv_8 = $xzv_20->{$xzv_9};
        if (!$xzv_8) {
            $xzv_10 = M('articles')->where("author = '{$xzv_19}'")->count();
            if ($xzv_10 < 1) {
                $this->error('作者不存在');
            }
            $xzv_13 = M('articles')->alias('a')->join('LEFT JOIN ' . C('DB_PREFIX') . 'article_views av ON a.id=av.aid')->where("a.author = '{$xzv_19}'")->field('a.id,a.title,a.pinyin,a.thumb,a.cate,a.info,a.posttime,a.updatetime,a.lastchapter,a.lastcid,a.author,a.full,av.views,av.weekviews,av.monthviews')->select();
            foreach ($xzv_13 as $xzv_11 => $xzv_15) {
                $xzv_15['subid'] = floor($xzv_15['id'] / 1000);
                $xzv_8[$xzv_11]['title'] = $xzv_15['title'];
                $xzv_8[$xzv_11]['rewriteurl'] = reurl('view', $xzv_15);
                $xzv_8[$xzv_11]['cateurl'] = reurl('cate', $xzv_15['cate']);
                if ($xzv_15['cate'] == $this->defaultdir || !$xzv_15['cate']) {
                    $xzv_15['cates'] = 'default';
                } else {
                    $xzv_15['cates'] = $xzv_15['cate'];
                }
                $xzv_8[$xzv_11]['description'] = $xzv_15['info'] ? mb_substr($xzv_15['info'], 0, 40, 'utf-8') : $xzv_15['title'];
                $xzv_8[$xzv_11]['catename'] = $xzv_33[$xzv_15['cates']]['name'];
                $xzv_8[$xzv_11]['catename_short'] = mb_substr($xzv_8[$xzv_11]['catename'], 0, 2, 'utf-8');
                $xzv_8[$xzv_11]['thumb'] = showcover($xzv_15['thumb']);
                $xzv_8[$xzv_11]['posttime'] = date($xzv_15['posttime'], 'Y-m-d H:i');
                $xzv_8[$xzv_11]['updatetime'] = date($xzv_15['updatetime'], 'Y-m-d H:i');
                $xzv_8[$xzv_11]['lastchapter'] = $xzv_15['lastchapter'] ? $xzv_15['lastchapter'] : '最新一章';
                $xzv_8[$xzv_11]['lastchapterurl'] = $xzv_15['lastcid'] ? reurl('chapter', array('id' => $xzv_15['id'], 'cate' => $xzv_15['cate'], 'cid' => $xzv_15['lastcid'], 'pinyin' => $xzv_15['pinyin'])) : $xzv_8[$xzv_11]['rewriteurl'];
                $xzv_8[$xzv_11]['author'] = $xzv_15['author'];
                $xzv_8[$xzv_11]['views'] = intval($xzv_15['views']);
                $xzv_8[$xzv_11]['weekviews'] = intval($xzv_15['weekviews']);
                $xzv_8[$xzv_11]['monthviews'] = intval($xzv_15['monthviews']);
                $xzv_8[$xzv_11]['status'] = $xzv_15['full'] > 0 ? '完成' : '连载';
            }
            $xzv_20->{$xzv_9} = $xzv_8;
        }
        $xzv_29 = $xzv_20->authorlist;
        if (!$xzv_29) {
            $xzv_30 = date('W', NOW_TIME);
            $xzv_29 = M('articles')->alias('a')->join('LEFT JOIN ' . C('DB_PREFIX') . 'article_views av ON a.id=av.aid')->where("av.weekkey = '%d'", $xzv_30)->order('av.weekviews desc')->field('a.author')->limit(50)->select();
            foreach ($xzv_29 as $xzv_11 => $xzv_15) {
                $xzv_29[$xzv_11]['rewriteurl'] = reurl('author', $xzv_15);
            }
            $xzv_20->authorlist = $xzv_29;
        }
        $xzv_31 = array('weburl' => C('NOWHOST') . C('HOME_URL'), 'webname' => $this->setting['seo']['webname'], 'title' => str_replace(array('{author}', '{webname}'), array($xzv_19, $this->setting['seo']['webname']), $this->setting['seo']['authortitle']), 'keyword' => str_replace(array('{author}', '{webname}'), array($xzv_19, $this->setting['seo']['webname']), $this->setting['seo']['authorkw']), 'description' => str_replace(array('{author}', '{webname}'), array($xzv_19, $this->setting['seo']['webname']), $this->setting['seo']['authordes']), 'canonicalurl' => C('NOWHOST') . reurl('author', array('author' => $xzv_19)), 'canonicalurl_m' => C('WAPHOST') . reurl('author', array('author' => $xzv_19)));
        G('end');
        $xzv_31['runtime'] = G('begin', 'end');
        $this->assign('TDK', $xzv_31);
        $this->assign('articlelist', $xzv_8);
        $this->assign('authorlist', $xzv_29);
        $this->assign('author', $xzv_19);
        $this->display();
    }
    public function sitemapxml()
    {
        ob_end_clean();
        header('Content-type:text/xml');
        $xzv_21 = I('get.index', 0, 'intval');
        $xzv_21 > '100' && die;
        $xzv_28 = $_SERVER['HTTP_HOST'];
        $xzv_27 = 9900;
        if ($xzv_21 > 0) {
            $xzv_23 = 'sitemap_' . $xzv_28 . '_' . $xzv_21;
        } else {
            $xzv_23 = 'sitemap_' . $xzv_28 . '_index';
        }
        $xzv_22 = S($xzv_23, '', array('temp' => $this->temppath));
        if (!$xzv_22) {
            $xzv_24 = M('articles');
            if ($xzv_21 == 0) {
                $xzv_25 = $xzv_24->Count();
                $xzv_26 = ceil($xzv_25 / $xzv_27);
                for ($xzv_2 = 1; $xzv_2 <= $xzv_26 + 1; $xzv_2++) {
                    $xzv_1[]['listurl'] = C('HOME_URL') . $this->setting['seo']['sitemap_url'] . '?index=' . $xzv_2;
                }
                $xzv_22['maplist'] = $xzv_1;
                $xzv_22['mapinfo']['dateline'] = date('Y-m-d');
                S($xzv_23, $xzv_22, array('temp' => $this->temppath, 'expire' => 3600 * 12));
            } elseif ($xzv_21 == 1) {
                $xzv_4 = M('tags');
                $xzv_0 = $xzv_4->order('num desc')->limit(5000)->select();
                foreach ($xzv_0 as $xzv_3 => $xzv_5) {
                    $xzv_0[$xzv_3]['rewriteurl'] = C('NOWHOST') . reurl('tag', $xzv_5);
                }
                $xzv_22['taginfo'] = $xzv_0;
                foreach ($this->category as $xzv_3 => $xzv_5) {
                    $this->category[$xzv_3]['url'] = C('NOWHOST') . $xzv_5['url'];
                }
                $xzv_22['category'] = $this->category;
                $xzv_7['weburl'] = C('NOWHOST') . C('HOME_URL');
                $xzv_7['dateline'] = date('Y-m-d');
                $xzv_22['mapinfo'] = $xzv_7;
                S($xzv_23, $xzv_22, array('temp' => $this->temppath, 'expire' => 3600 * 5));
            } else {
                $xzv_6 = ($xzv_21 - 2) * $xzv_27 . ', ' . $xzv_27;
                $xzv_1 = $xzv_24->order('id desc')->limit($xzv_6)->select();
                foreach ($xzv_1 as $xzv_3 => $xzv_5) {
                    $xzv_1[$xzv_3]['rewriteurl'] = C('NOWHOST') . reurl('view', $xzv_5);
                }
                $xzv_7['maplist'] = $xzv_1;
                $xzv_7['weburl'] = C('NOWHOST') . C('HOME_URL');
                $xzv_7['dateline'] = date('Y-m-d');
                $xzv_22['mapinfo'] = $xzv_7;
                S($xzv_23, $xzv_22, array('temp' => $this->temppath, 'expire' => 3600 * 5));
            }
        }
        $this->assign('sitemapdata', $xzv_22);
        $this->assign('index', $xzv_21);
        $this->display();
    }
}