<?php

namespace Home\Controller;

use Home\Controller\BaseController;
use Think\Controller;
class IndexController extends BaseController
{
    public function index()
    {
        $xzv_0 = S(array('type' => 'file', 'expire' => 3600, 'prefix' => $xzv_150, 'length' => 500, 'temp' => $this->temppath . 'index/'));
        $xzv_90 = C('NOWHOST') == C('WAPHOST') ? explode('[line]', F('flink_wap')) : explode('[line]', F('flink'));
        foreach ($xzv_90 as $xzv_147 => $xzv_29) {
            if (strexists($xzv_29, '|')) {
                list($xzv_53, $xzv_151) = explode('|', $xzv_29);
                $xzv_123[] = array('name' => $xzv_53, 'url' => $xzv_151);
            }
        }
        $xzv_23 = array('weburl' => C('NOWHOST') . C('HOME_URL'), 'canonicalurl' => C('NOWHOST') . C('HOME_URL'), 'canonicalurl_m' => C('WAPHOST') . C('HOME_URL'), 'webname' => $this->setting['seo']['webname'], 'huandeng1' => $this->setting['seo']['huandeng1'], 'huandeng2' => $this->setting['seo']['huandeng2'], 'huandeng3' => $this->setting['seo']['huandeng3'],'huandeng4' => $this->setting['seo']['huandeng4'],'huandeng5' => $this->setting['seo']['huandeng5'],'gonggao1' => $this->setting['seo']['gonggao1'],'gonggao2' => $this->setting['seo']['gonggao2'],'gonggao3' => $this->setting['seo']['gonggao3'],'gonggao4' => $this->setting['seo']['gonggao4'],'gonggao5' => $this->setting['seo']['gonggao5'],'title' => $this->setting['seo']['indextitle'], 'keyword' => $this->setting['seo']['indexkw'], 'description' => $this->setting['seo']['indexdes'], 'flink' => $xzv_123);
        $xzv_22 = C('NOWHOST') == C('WAPHOST') ? 10 : 20;
        $xzv_21 = 'updatelist_' . $xzv_22;
        $xzv_88 = $xzv_0->{$xzv_21};
        if (!$xzv_88) {
            $xzv_91 = M('articles');
            $xzv_92 = 'lastchapter is not null and thumb is not null';
            $xzv_88 = $xzv_91->where($xzv_92)->order('updatetime desc')->limit($xzv_22)->select();
            foreach ($xzv_88 as $xzv_147 => $xzv_29) {
                if ($xzv_29['cate'] == $this->defaultdir || !$xzv_29['cate']) {
                    $xzv_94 = 'default';
                } else {
                    $xzv_94 = $xzv_29['cate'];
                }
                $xzv_88[$xzv_147]['thumb'] = showcover($xzv_29['thumb']);
                $xzv_88[$xzv_147]['rewriteurl'] = reurl('view', $xzv_29);
                $xzv_88[$xzv_147]['catename'] = $this->category[$xzv_94]['name'];
                $xzv_88[$xzv_147]['cateurl'] = reurl('cate', $xzv_29['cate']);
                $xzv_88[$xzv_147]['updatetime'] = date('m-d', $xzv_29['updatetime']);
                $xzv_88[$xzv_147]['lastchapterurl'] = $xzv_29['lastcid'] ? reurl('chapter', array('id' => $xzv_29['id'], 'cate' => $xzv_29['cate'], 'cid' => $xzv_29['lastcid'])) : $xzv_88[$xzv_147]['rewriteurl'];
            }
            $xzv_0->{$xzv_21} = $xzv_88;
        }
        $xzv_21 = 'newestlist';
        $xzv_93 = $xzv_0->{$xzv_21};
        if (!$xzv_93) {
            $xzv_91 = M('articles');
            $xzv_93 = $xzv_91->order('id desc')->limit(20)->select();
            foreach ($xzv_93 as $xzv_147 => $xzv_29) {
                if ($xzv_29['cate'] == $this->defaultdir || !$xzv_29['cate']) {
                    $xzv_94 = 'default';
                } else {
                    $xzv_94 = $xzv_29['cate'];
                }
                $xzv_93[$xzv_147]['rewriteurl'] = reurl('view', $xzv_29);
                $xzv_93[$xzv_147]['posttime'] = date('m-d', $xzv_29['posttime']);
                $xzv_93[$xzv_147]['catename'] = $this->category[$xzv_94]['name'];
                $xzv_93[$xzv_147]['cateurl'] = reurl('cate', $xzv_94);
                $xzv_93[$xzv_147]['catename_short'] = mb_substr($xzv_93[$xzv_147]['catename'], 0, 2, 'utf-8');
            }
            $xzv_0->{$xzv_21} = $xzv_93;
        }
        G('end');
        $xzv_23['runtime'] = G('begin', 'end');
        $this->assign('TDK', $xzv_23);
        $this->assign('updatelist', $xzv_88);
        $this->assign('newestlist', $xzv_93);
        $this->display();
    }
    public function showlist()
    {
        $xzv_122 = S(array('type' => 'file', 'expire' => $this->setting['extra']['listcachetime'], 'prefix' => $xzv_156, 'length' => 500, 'temp' => $this->temppath . 'cate/'));
        $xzv_155 = I('get.page', '1', 'intval');
        $xzv_46 = I('get.cate', $this->defaultdir);
        $xzv_80 = 30;
        if ($xzv_46 == 'all' || $xzv_46 == 'top' || $xzv_46 == 'full') {
            $xzv_80 = 60;
            $xzv_79 = $xzv_46 == 'all' ? '书库' : ($xzv_46 == 'full' ? '全本小说' : '排行榜');
        } else {
            $xzv_79 = $xzv_46 == $this->defaultdir ? $this->category['default']['name'] : $this->category[$xzv_46]['name'];
            $xzv_82 = mb_substr($xzv_79, 0, 2, 'utf-8');
        }
        $xzv_83 = $xzv_46 == $this->defaultdir ? 'default' : $xzv_46;
        if ($this->category[$xzv_83]['listtitle']) {
            $this->setting['seo']['listtitle'] = $this->category[$xzv_83]['listtitle'];
            $this->setting['seo']['listkw'] = $this->category[$xzv_83]['listkw'];
            $this->setting['seo']['listdes'] = $this->category[$xzv_83]['listdes'];
        }
        $xzv_48 = array('weburl' => C('NOWHOST') . C('HOME_URL'), 'webname' => $this->setting['seo']['webname'], 'catename' => $xzv_79, 'catename_short' => $xzv_82, 'canonicalurl' => C('NOWHOST') . reurl('cate', $xzv_46), 'canonicalurl_m' => C('WAPHOST') . reurl('cate', $xzv_46), 'title' => str_replace(array('{cate}', '{webname}'), array($xzv_79, $this->setting['seo']['webname']), $xzv_153['listtitle'] ? $xzv_153['listtitle'] : $this->setting['seo']['listtitle']), 'keyword' => str_replace(array('{cate}', '{webname}'), array($xzv_79, $this->setting['seo']['webname']), $xzv_153['listkw'] ? $xzv_153['listkw'] : $this->setting['seo']['listkw']), 'description' => str_replace(array('{cate}', '{webname}'), array($xzv_79, $this->setting['seo']['webname']), $xzv_153['listdes'] ? $xzv_153['listdes'] : $this->setting['seo']['listdes']));
        if ($xzv_46 == 'all' || $xzv_46 == 'top' || $xzv_46 == 'full') {
            $xzv_48['keyword'] = $xzv_48['description'] = $xzv_79 . $xzv_48['webname'];
            $xzv_48['title'] = str_replace('{webname}', $this->setting['seo']['webname'], $this->setting['seo'][$xzv_46 . 'title']);
        }
        if ($xzv_46 != 'top' && $xzv_46 != 'full') {
            $xzv_128 = $xzv_46 . '_' . $xzv_155;
            $xzv_127 = $xzv_122->{$xzv_128};
            if ($xzv_46 == 'all') {
                $xzv_63 = '1>0';
            } else {
                $xzv_63 = "a.cate='{$xzv_46}'";
            }
            $xzv_126 = 'a.updatetime desc';
            $xzv_57 = M('articles');
            if (!$xzv_127) {
                $xzv_127 = $xzv_57->alias('a')->join(C('DB_PREFIX') . 'article_views av ON a.id=av.aid')->where($xzv_63)->order($xzv_126)->limit($xzv_80 * ($xzv_155 - 1), $xzv_80)->select();
                foreach ($xzv_127 as $xzv_144 => $xzv_15) {
                    if ($xzv_15['cate'] == $this->defaultdir || !$xzv_15['cate']) {
                        $xzv_129 = 'default';
                    } else {
                        $xzv_129 = $xzv_15['cate'];
                    }
                    $xzv_127[$xzv_144]['rewriteurl'] = reurl('view', $xzv_15);
                    $xzv_127[$xzv_144]['thumb'] = showcover($xzv_15['thumb']);
                    $xzv_127[$xzv_144]['cateurl'] = reurl('cate', $xzv_15['cate']);
                    $xzv_127[$xzv_144]['author'] = $xzv_15['author'] ? $xzv_15['author'] : '…';
                    $xzv_127[$xzv_144]['catename'] = $this->category[$xzv_129]['name'];
                    $xzv_127[$xzv_144]['catename_short'] = mb_substr($xzv_127[$xzv_144]['catename'], 0, 2, 'utf-8');
                    $xzv_127[$xzv_144]['updatetime'] = date('Y-m-d H:i:s', $xzv_15['updatetime'] ? $xzv_15['updatetime'] : NOW_TIME);
                    $xzv_127[$xzv_144]['description'] = $xzv_15['info'] ? mb_substr($xzv_15['info'], 0, '50', 'utf-8') : $xzv_15['title'] . '是由作者' . $xzv_15['author'] . '创作的' . $xzv_127[$xzv_144]['catename'] . '，欢迎您来本站阅读' . $xzv_15['title'];
                    $xzv_127[$xzv_144]['status'] = $xzv_15['full'] > 0 ? '完成' : '连载';
                    $xzv_127[$xzv_144]['lastchapter'] = $xzv_15['lastchapter'] ? $xzv_15['lastchapter'] : '最近更新>>';
                    $xzv_127[$xzv_144]['lastchapterurl'] = $xzv_15['lastcid'] ? reurl('chapter', array('id' => $xzv_15['id'], 'cate' => $xzv_15['cate'], 'cid' => $xzv_15['lastcid'])) : $xzv_127[$xzv_144]['rewriteurl'];
                }
                $xzv_122->{$xzv_128} = $xzv_127;
            }
            $xzv_124 = $xzv_57->alias('a')->where($xzv_63)->Count();
            $xzv_16 = ceil($xzv_124 / $xzv_80);
            $xzv_124 > $xzv_80 && ($xzv_141 = pagelist('list', $xzv_155, 2, $xzv_16, $xzv_46));
            $xzv_128 = $xzv_46 . '_newest';
            $xzv_143 = $xzv_122->{$xzv_128};
            if (!$xzv_143) {
                $xzv_57 = M('articles');
                $xzv_126 = 'id desc';
                $xzv_143 = $xzv_57->alias('a')->where($xzv_63)->order($xzv_126)->limit($xzv_80)->select();
                foreach ($xzv_143 as $xzv_144 => $xzv_15) {
                    if ($xzv_15['cate'] == $this->defaultdir || !$xzv_15['cate']) {
                        $xzv_129 = 'default';
                    } else {
                        $xzv_129 = $xzv_15['cate'];
                    }
                    $xzv_143[$xzv_144]['rewriteurl'] = reurl('view', $xzv_15);
                    $xzv_143[$xzv_144]['thumb'] = showcover($xzv_15['thumb']);
                    $xzv_143[$xzv_144]['cateurl'] = reurl('cate', $xzv_15['cate']);
                    $xzv_143[$xzv_144]['author'] = $xzv_15['author'] ? $xzv_15['author'] : '…';
                    $xzv_143[$xzv_144]['catename'] = $this->category[$xzv_129]['name'];
                    $xzv_143[$xzv_144]['catename_short'] = mb_substr($xzv_143[$xzv_144]['catename'], 0, 2, 'utf-8');
                    $xzv_143[$xzv_144]['updatetime'] = date('Y-m-d H:i:s', $xzv_15['updatetime'] ? $xzv_15['updatetime'] : NOW_TIME);
                    $xzv_143[$xzv_144]['description'] = $xzv_15['info'] ? mb_substr($xzv_15['info'], 0, '50', 'utf-8') : $xzv_15['title'] . '是由作者' . $xzv_15['author'] . '创作的' . $xzv_143[$xzv_144]['catename'] . '，欢迎您来本站阅读' . $xzv_15['title'];
                    $xzv_143[$xzv_144]['status'] = $xzv_15['full'] > 0 ? '完成' : '连载';
                    $xzv_143[$xzv_144]['lastchapter'] = $xzv_15['lastchapter'] ? $xzv_15['lastchapter'] : '最近更新>>';
                    $xzv_143[$xzv_144]['lastchapterurl'] = $xzv_15['lastcid'] ? reurl('chapter', array('id' => $xzv_15['id'], 'cate' => $xzv_15['cate'], 'cid' => $xzv_15['lastcid'])) : $xzv_143[$xzv_144]['rewriteurl'];
                }
                $xzv_122->{$xzv_128} = $xzv_143;
            }
        }
        $xzv_128 = 'hot_' . $xzv_46;
        $xzv_44 = $xzv_122->{$xzv_128};
        if (!$xzv_44) {
            $xzv_57 = M('articles');
            if ($xzv_46 == 'all' || $xzv_46 == 'top' || $xzv_46 == 'full') {
                $xzv_63 = 'a.thumb is not null and a.info is not null';
            } else {
                $xzv_63 = "a.thumb is not null and a.info is not null and cate='{$xzv_46}'";
            }
            $xzv_44 = $xzv_57->alias('a')->join('LEFT JOIN ' . C('DB_PREFIX') . 'article_views av ON a.id=av.aid')->where($xzv_63)->order('av.monthviews desc')->limit(6)->select();
            foreach ($xzv_44 as $xzv_144 => $xzv_15) {
                $xzv_44[$xzv_144]['rewriteurl'] = reurl('view', $xzv_15);
                $xzv_44[$xzv_144]['thumb'] = showcover($xzv_15['thumb']);
                $xzv_44[$xzv_144]['description'] = mb_substr($xzv_15['info'], 0, 70, 'utf-8');
            }
            $xzv_122->{$xzv_128} = $xzv_44;
        }
        G('end');
        $xzv_48['runtime'] = G('begin', 'end');
        $this->assign('hotlist', $xzv_44);
        $this->assign('articlelist', $xzv_127);
        $this->assign('newestlist', $xzv_143);
        $this->assign('TDK', $xzv_48);
        $this->assign('pagehtml', $xzv_141);
        $this->assign('cate', $xzv_46);
        $this->assign('page', $xzv_155);
        if ($xzv_46 == 'all' || $xzv_46 == 'top' || $xzv_46 == 'full') {
            $this->display('showlist_' . $xzv_46);
        } else {
            $this->display();
        }
    }
    public function view()
    {
        $xzv_62 = I('get.id');
        $xzv_84 = I('get.cate');
        if (is_numeric($xzv_62)) {
            $xzv_96 = $xzv_62 - $this->setting['seo']['idrule'];
            $xzv_142['id'] = array('eq', $xzv_96);
        } else {
            $this->error('文章不存在！', U('/home/index'));
        }
        $xzv_12 = M('articles');
        $xzv_7 = $xzv_12->where($xzv_142)->find();
        $xzv_96 = $xzv_7['id'];
        if (!isset($xzv_7)) {
            $this->error('文章不存在！', U('/home/index'));
        }
        if ($this->setting['seo']['blackbooklist']) {
            $xzv_120 = explode('|', $this->setting['seo']['blackbooklist']);
            foreach ($xzv_120 as $xzv_104 => $xzv_135) {
                if (strexists($xzv_7['title'], $xzv_135)) {
                    $this->error('文章不存在！', U('/home/index'));
                }
            }
        }
        if (strexists(C('VIEW_RULE'), '{subid}')) {
            $xzv_106 = floor($xzv_96 / 1000);
            if ($xzv_84 != $xzv_106) {
                $this->error('文章不存在！', U('/home/index'));
            }
        } elseif (strexists(C('VIEW_RULE'), '{dir}')) {
            if ($xzv_84 != $xzv_7['cate'] && $xzv_7['cate']) {
                $xzv_41 = reurl('view', array('id' => $xzv_96, 'cate' => $xzv_7['cate'], 'posttime' => $xzv_7['posttime']));
                http_301($xzv_41);
            }
        }
        $xzv_42 = floor($xzv_96 / 1000);
        $xzv_107 = F('view/book/' . $xzv_42 . '/' . $xzv_96);
        $xzv_35 = $xzv_7['cate'] ? $xzv_7['cate'] : $this->defaultdir;
        if (!isset($xzv_107['content']) || !$xzv_107['thumb'] || $xzv_7['update'] > 0) {
            $xzv_107 = $xzv_7;
            $xzv_108 = pickrun('content', $xzv_107['pid'], $xzv_107['url']);
            $xzv_182 = F('pick');
            $xzv_137 = $xzv_182[$xzv_107['pid']];
            if (!$xzv_107['thumb'] && $xzv_7['thumb']) {
                $xzv_107['thumb'] = $xzv_7['thumb'];
            }
            $xzv_175 = $xzv_108['thumb'] ? $xzv_108['thumb'] : $xzv_7['thumb'];
            if (strexists($xzv_175, $xzv_137['nothumb_sign']) || !$xzv_175 && !$xzv_107['thumb']) {
                $xzv_107['thumb'] = '/Public/images/nocover.jpg';
            } else {
                $xzv_138 = $xzv_137['piclocal'] == 'yes' ? true : false;
                $xzv_107['thumb'] = deimg($xzv_175, $xzv_96, $xzv_107['url'], true, $xzv_138);
            }
            $xzv_107['thumb'] = $xzv_107['thumb'] ? $xzv_107['thumb'] : '/Public/images/nocover.jpg';
            $xzv_109['thumb'] = $xzv_107['thumb'];
            if (strexists($xzv_107['thumb'], '/Public') && $xzv_7['thumb'] && !strexists($xzv_7['thumb'], 'http')) {
                $xzv_109['thumb'] = $xzv_107['thumb'] = $xzv_7['thumb'];
            }
            if ($xzv_108['cate']) {
                $xzv_109['cate'] = $xzv_35 = $xzv_108['cate'];
            }
            $xzv_107['content'] = $xzv_108['content'];
            $xzv_109['full'] = $xzv_107['full'] = $xzv_108['isfull'] == 'full' ? 1 : 0;
            $xzv_109['info'] = $xzv_107['description'] = mb_substr(cleanHtml($xzv_107['content']), 0, 120, 'utf-8');
            $xzv_107['keyword'] = $xzv_108['keyword'];
            $xzv_107['cate'] = $xzv_35;
            $xzv_107['catename'] = $xzv_35 == $this->defaultdir ? $this->category['default']['name'] : $this->category[$xzv_35]['name'];
            $xzv_107['time'] = date('Y-m-d H:i:s', $xzv_107['updatetime'] ? $xzv_107['updatetime'] : NOW_TIME);
            if ($xzv_108['author']) {
                $xzv_109['author'] = $xzv_107['author'] = $xzv_108['author'];
            }
            $xzv_107['chapterurl'] = $xzv_108['chapterurl'];
            if ($xzv_108['chapterdb']) {
                $xzv_130 = $xzv_108['chapterdb'];
                $xzv_3 = $xzv_130['chapterlist'];
                $xzv_109['lastchapter'] = $xzv_107['lastchapter'] = $xzv_130['lastchapter']['title'];
                $xzv_109['lastcid'] = $xzv_107['lastcid'] = $xzv_130['lastchapter']['cid'];
                foreach ($xzv_3 as $xzv_104 => $xzv_135) {
                    $xzv_3[$xzv_104]['id'] = $xzv_96;
                    $xzv_3[$xzv_104]['cid'] = $xzv_104;
                    $xzv_3[$xzv_104]['cate'] = $xzv_107['cate'];
                }
                $xzv_158 = 0;
                foreach (array_reverse($xzv_3, true) as $xzv_104 => $xzv_135) {
                    $xzv_125[$xzv_158] = $xzv_135;
                    $xzv_158++;
                    if ($xzv_158 > 11) {
                        break;
                    }
                }
                F('view/chapter/' . $xzv_42 . '/' . $xzv_96, $xzv_3);
                F('view/newchapter/' . $xzv_42 . '/' . $xzv_96, $xzv_125);
                S('chaptercache_' . $xzv_96, NOW_TIME, array('temp' => TEMP_PATH . 'chaptercache/' . $xzv_42 . '/', 'expire' => $this->setting['extra']['chaptercachetime']));
            }
            $xzv_109['update'] = 0;
            F('view/book/' . $xzv_42 . '/' . $xzv_96, $xzv_107);
            pushapi($xzv_96);
        } else {
            if (!$xzv_107['tags']) {
                $xzv_172 = new \Org\Util\Tag();
                $xzv_17 = preg_replace('/<\\/?[^>]+>/i', '', $xzv_107['content']);
                $xzv_17 = preg_replace('/\\s{2,}/i', '', $xzv_17);
                $xzv_17 = str_replace(array('', '
', '&nbsp;'), '', $xzv_17);
                $xzv_17 = mb_strcut($xzv_17, 0, 480, 'utf-8');
                $xzv_17 = rawurldecode(str_replace('%C2%A0', '', rawurlencode($xzv_17)));
                $xzv_107['tags'] = $xzv_172->relatekw($xzv_107['title'], $xzv_17, $xzv_96);
                F('view/book/' . $xzv_42 . '/' . $xzv_96, $xzv_107);
                delhtml($xzv_96);
            }
            $xzv_107['thumb'] = $xzv_7['thumb'];
            $xzv_3 = F('view/chapter/' . $xzv_42 . '/' . $xzv_96);
            $xzv_125 = F('view/newchapter/' . $xzv_42 . '/' . $xzv_96);
        }
        if (!$xzv_107['catename']) {
            $xzv_107['catename'] = $xzv_35 == $this->defaultdir ? $this->category['default']['name'] : $this->category[$xzv_35]['name'];
        }
        if ($xzv_107['tags']) {
            $xzv_99 = explode('	', $xzv_107['tags']);
            if (count($xzv_99) > 0) {
                foreach ($xzv_99 as $xzv_104 => $xzv_98) {
                    if (!strexists($xzv_98, ',')) {
                        continue;
                    }
                    $xzv_97 = explode(',', $xzv_98);
                    if (count($xzv_97) == 3) {
                        $xzv_107['taglist'][$xzv_104] = array('id' => $xzv_97[0], 'tagname' => $xzv_97[1], 'ename' => $xzv_97[2]);
                    }
                    $xzv_107['taglist'][$xzv_104]['tagurl'] = reurl('tag', $xzv_107['taglist'][$xzv_104]);
                }
            }
        }
        if (!$xzv_35) {
            $xzv_35 = $xzv_109['cate'] = $xzv_107['cate'] = $this->defaultdir;
            $xzv_107['catename'] = $this->category['default']['name'];
        }
        $xzv_176 = M('article_views')->where('aid = %d', $xzv_96)->find();
        list($xzv_19, $xzv_100) = explode(',', $this->setting['seo']['virtviews']);
        if (!$xzv_19 || !$xzv_100 || $xzv_100 < $xzv_19) {
            $xzv_19 = 1;
            $xzv_100 = 3;
        }
        $xzv_101 = mt_rand($xzv_19, $xzv_100);
        if (!$xzv_176) {
            $xzv_107['weekviews'] = $xzv_107['monthviews'] = $xzv_107['views'] = $xzv_101;
            $xzv_177 = array('aid' => $xzv_96, 'weekviews' => $xzv_101, 'monthviews' => $xzv_101, 'views' => $xzv_101, 'weekkey' => date('W', NOW_TIME), 'monthkey' => date('n', NOW_TIME));
            M('article_views')->add($xzv_177);
        } else {
            $xzv_177 = array();
            $xzv_107['views'] = $xzv_177['views'] = $xzv_176['views'] + $xzv_101;
            if ($xzv_176['weekkey'] != date('W', NOW_TIME)) {
                $xzv_107['weekviews'] = $xzv_177['weekviews'] = $xzv_101;
                $xzv_177['weekkey'] = date('W', NOW_TIME);
            } else {
                $xzv_107['weekviews'] = $xzv_177['weekviews'] = $xzv_176['weekviews'] + $xzv_101;
            }
            if ($xzv_176['monthkey'] != date('n', NOW_TIME)) {
                $xzv_107['monthviews'] = $xzv_177['monthviews'] = $xzv_101;
                $xzv_177['monthkey'] = date('n', NOW_TIME);
            } else {
                $xzv_107['monthviews'] = $xzv_177['monthviews'] = $xzv_176['monthviews'] + $xzv_101;
            }
            if (count($xzv_177) > 0) {
                M('article_views')->where('aid = %d', $xzv_96)->save($xzv_177);
            }
        }
        if ($xzv_109['lastchapter']) {
            $xzv_107['updatetime'] = $xzv_109['updatetime'] = NOW_TIME;
            $xzv_107['time'] = date('Y-m-d H:i:s', NOW_TIME);
        }
        $xzv_12->where("id = '{$xzv_96}'")->save($xzv_109);
        F('view/book/' . $xzv_42 . '/' . $xzv_96, $xzv_107);
        $xzv_107['thumb'] = showcover($xzv_107['thumb']);
        $xzv_107['lastchapterurl'] = reurl('chapter', array('id' => $xzv_96, 'cate' => $xzv_35, 'cid' => $xzv_107['lastcid']));
        $xzv_107['firstchapterurl'] = reurl('chapter', array('id' => $xzv_96, 'cate' => $xzv_35, 'cid' => 0));
        if ($xzv_109['cate'] && $xzv_109['cate'] != $this->defaultdir && strexists(C('VIEW_RULE'), '{dir}')) {
            $xzv_41 = reurl('view', array('id' => $xzv_96, 'cate' => $xzv_109['cate'], 'posttime' => $xzv_107['posttime']));
            http_301($xzv_41);
        }
        $xzv_103 = $xzv_35 == $this->defaultdir ? 'default' : $xzv_35;
        if ($this->category[$xzv_35]['viewtitle']) {
            $this->setting['seo']['viewtitle'] = $this->category[$xzv_35]['viewtitle'];
            $this->setting['seo']['viewkw'] = $this->category[$xzv_35]['viewkw'];
            $this->setting['seo']['viewdes'] = $this->category[$xzv_35]['viewdes'];
        }
        $xzv_181 = array('weburl' => C('NOWHOST') . C('HOME_URL'), 'webname' => $this->setting['seo']['webname'], 'title' => $xzv_107['seotitle'] ? $xzv_107['seotitle'] : str_replace(array('{cate}', '{webname}', '{title}', '{author}', '{keyword}'), array($xzv_107['catename'], $this->setting['seo']['webname'], $xzv_107['title'], $xzv_107['author'], $xzv_107['keyword']), $xzv_180['viewtitle'] ? $xzv_180['viewtitle'] : $this->setting['seo']['viewtitle']), 'keyword' => $xzv_107['seokeyword'] ? $xzv_107['seokeyword'] : str_replace(array('{cate}', '{webname}', '{title}', '{author}', '{keyword}'), array($xzv_107['catename'], $this->setting['seo']['webname'], $xzv_107['title'], $xzv_107['author'], $xzv_107['keyword']), $xzv_180['viewkw'] ? $xzv_180['viewkw'] : $this->setting['seo']['viewkw']), 'description' => $xzv_107['seodescription'] ? $xzv_107['seodescription'] : str_replace(array('{cate}', '{webname}', '{title}', '{author}', '{intro}', '{keyword}'), array($xzv_107['catename'], $this->setting['seo']['webname'], $xzv_107['title'], $xzv_107['author'], $xzv_107['description'], $xzv_107['keyword']), $xzv_180['viewdes'] ? $xzv_180['viewdes'] : $this->setting['seo']['viewdes']), 'canonicalurl' => C('NOWHOST') . reurl('view', $xzv_107), 'canonicalurl_m' => C('WAPHOST') . reurl('view', $xzv_107), 'oid' => $xzv_62, 'subid' => strexists(C('CHAPTER_RULE'), '{dir}') ? $xzv_35 : $xzv_42, 'hash' => encodekey('book_' . $xzv_62), 'cateurl' => reurl('cate', $xzv_35));
        $xzv_102 = F('seowords');
        $xzv_179 = unique_array($xzv_102, 5, false);
        foreach ($xzv_179 as $xzv_104 => $xzv_135) {
            $xzv_179[$xzv_104]['rewriteurl'] = reurl('seoword', $xzv_135);
        }
        $this->assign('randseolist', $xzv_179);
        G('end');
        $xzv_181['runtime'] = G('begin', 'end');
        $this->assign('articledb', $xzv_107);
        $this->assign('TDK', $xzv_181);
        $this->assign('cate', $xzv_35);
        $this->assign('chapterdb', $xzv_3);
        $this->assign('newchaperlist', $xzv_125);
        $this->display();
    }
    public function showchapter()
    {
        $xzv_68 = I('get.id');
        $xzv_178 = I('get.cid');
        if (strexists($xzv_178, '_')) {
            $xzv_66 = explode('_', $xzv_178);
            $xzv_43 = $xzv_66[0];
            $xzv_162 = $xzv_66[1];
        } else {
            $xzv_43 = intval($xzv_178);
            $xzv_162 = 0;
        }
        $xzv_65 = I('get.cate');
        if (is_numeric($xzv_68)) {
            $xzv_121 = $xzv_68 - $this->setting['seo']['idrule'];
            $xzv_159['id'] = array('eq', $xzv_121);
        } else {
            $this->error('文章不存在！', U('/home/index'));
        }
        $xzv_160 = $xzv_43 - $this->setting['seo']['cidrule'];
        $xzv_36 = M('articles');
        $xzv_38 = $xzv_36->where($xzv_159)->find();
        $xzv_121 = $xzv_38['id'];
        if (!isset($xzv_38)) {
            $this->error('文章不存在！', U('/home/index'));
        }
        if (strexists(C('CHAPTER_RULE'), '{subid}')) {
            $xzv_4 = floor($xzv_121 / 1000);
            if ($xzv_65 != $xzv_4) {
                $this->error('文章不存在！', U('/home/index'));
            }
        }
        $xzv_131 = floor($xzv_121 / 1000);
        $xzv_119 = F('view/chapter/' . $xzv_131 . '/' . $xzv_121);
        if (!$xzv_119) {
            $xzv_169 = reurl('view', array('id' => $xzv_121, 'cate' => $xzv_38['cate'], 'posttime' => $xzv_38['posttime']));
            http_301($xzv_169);
        }
        $xzv_9 = $xzv_38['cate'];
        $xzv_140 = F('view/book/' . $xzv_131 . '/' . $xzv_121);
        $xzv_139 = M('article_views')->where('aid = %d', $xzv_121)->find();
        list($xzv_136, $xzv_33) = explode(',', $this->setting['seo']['virtviews']);
        if (!$xzv_136 || !$xzv_33 || $xzv_33 < $xzv_136) {
            $xzv_136 = 1;
            $xzv_33 = 3;
        }
        $xzv_70 = mt_rand($xzv_136, $xzv_33);
        $xzv_10['views'] = $xzv_140['views'] = $xzv_139['views'] + $xzv_70;
        $xzv_10['weekviews'] = $xzv_140['weekviews'] = $xzv_139['weekviews'] + $xzv_70;
        $xzv_10['monthviews'] = $xzv_140['monthviews'] = $xzv_139['monthviews'] + $xzv_70;
        if ($xzv_10 && count($xzv_10) > 0) {
            M('article_views')->where("aid = '{$xzv_121}'")->save($xzv_10);
            F('view/book/' . $xzv_131 . '/' . $xzv_121, $xzv_140);
        }
        $xzv_58 = $xzv_119[$xzv_160];
        $xzv_72 = array('id' => $xzv_121, 'cate' => $xzv_9, 'cid' => $xzv_160);
        $xzv_71 = $xzv_9 == $this->defaultdir ? 'default' : $xzv_9;
        if ($this->category[$xzv_9]['chaptertitle']) {
            $this->setting['seo']['chaptertitle'] = $this->category[$xzv_9]['chaptertitle'];
            $this->setting['seo']['chapterkw'] = $this->category[$xzv_9]['chapterkw'];
            $this->setting['seo']['chapterdes'] = $this->category[$xzv_9]['chapterdes'];
        }
        $xzv_56 = array('weburl' => C('NOWHOST') . C('HOME_URL'), 'webname' => $this->setting['seo']['webname'], 'title' => str_replace(array('{cate}', '{webname}', '{title}', '{author}', '{ctitle}'), array($xzv_140['catename'], $this->setting['seo']['webname'], $xzv_140['title'], $xzv_140['author'], $xzv_58['title']), $this->setting['seo']['chaptertitle']), 'keyword' => str_replace(array('{cate}', '{webname}', '{title}', '{author}', '{ctitle}'), array($xzv_140['catename'], $this->setting['seo']['webname'], $xzv_140['title'], $xzv_140['author'], $xzv_58['title']), $this->setting['seo']['chapterkw']), 'description' => str_replace(array('{cate}', '{webname}', '{title}', '{author}', '{ctitle}'), array($xzv_140['catename'], $this->setting['seo']['webname'], $xzv_140['title'], $xzv_140['author'], $xzv_58['title']), $this->setting['seo']['chapterdes']), 'canonicalurl' => C('NOWHOST') . reurl('chapter', $xzv_72), 'canonicalurl_m' => C('WAPHOST') . reurl('chapter', $xzv_72), 'oid' => $xzv_68, 'ocid' => $xzv_43, 'subid' => strexists(C('CHAPTER_RULE'), '{dir}') ? $xzv_65 : $xzv_131, 'articletitle' => $xzv_38['title'], 'articleurl' => reurl('view', $xzv_38), 'author' => $xzv_38['author'], 'catename' => $xzv_140['catename'], 'cateurl' => reurl('cate', $xzv_9), 'cate' => $xzv_9, 'eKey' => encodekey('id-' . $xzv_121 . '+cid-' . $xzv_160));
        $xzv_47 = F('view/chaptercont/' . $xzv_131 . '/' . $xzv_121 . '/' . $xzv_160);
        $xzv_132 = explode('[line]', htmlspecialchars_trans($this->setting['seo']['repick_sign'], 'pick'));
        foreach ($xzv_132 as $xzv_6 => $xzv_73) {
            if (strexists($xzv_47['content'], $xzv_73)) {
                $xzv_47['content'] = null;
                break;
            }
        }
        if ($this->setting['seo']['chapterload'] > 0 && $xzv_38['original'] != 1) {
            $xzv_47['content'] = null;
        }
        if (strlen($xzv_47['content']) < 3 || !strexists($xzv_47['link'], $xzv_58['link']) && $xzv_38['original'] == 0) {
            $xzv_47 = null;
        } else {
            if ($this->setting['seo']['core_filter']) {
                $xzv_30 = explode('[line]', htmlspecialchars_trans($this->setting['seo']['core_filter'], 'pick'));
                foreach ($xzv_30 as $xzv_26 => $xzv_45) {
                    preg_match("#^\\{filter\\s+replace\\s*=\\s*'([^']*)'\\s*\\}(.*)\\{/filter\\}#", $xzv_45, $xzv_74);
                    if (isset($xzv_74[2]) && !empty($xzv_74[2])) {
                        $xzv_74[2] = str_replace('~', '\\~', $xzv_74[2]);
                        $xzv_74[2] = str_replace('"', '\\"', $xzv_74[2]);
                        $xzv_47['content'] = preg_replace('~' . $xzv_74[2] . '~iUs', $xzv_74[1], $xzv_47['content']);
                    } else {
                        $xzv_47['content'] = str_replace($xzv_45, '', $xzv_47['content']);
                    }
                }
            }
            $xzv_133 = $this->setting['seo']['chapterlimit'];
            if ($xzv_133 > 0 && ($xzv_162 > 0 || mb_strlen($xzv_47['content']) > ($xzv_162 + 1) * $xzv_133)) {
                $xzv_134 = ceil(mb_strlen($xzv_47['content']) / $xzv_133);
                $xzv_47['content'] = mb_substr($xzv_47['content'], $xzv_162 * $xzv_133, $xzv_133);
                $xzv_167 = $xzv_162 + 1 >= $xzv_134 ? NULL : $xzv_162 + 1;
            }
        }
        $xzv_50 = array('title' => $xzv_58['title'], 'rewriteurl' => $xzv_58['rewriteurl'], 'sourceurl' => fillurl($xzv_38['url'], $xzv_58['link']), 'prev' => $xzv_160 > 0 ? $xzv_119[$xzv_160 - 1] : null, 'prevcid' => $xzv_160 > 0 ? $xzv_160 - 1 + $this->setting['seo']['cidrule'] : -1, 'next' => $xzv_160 < count($xzv_119) - 1 ? $xzv_119[$xzv_160 + 1] : null, 'nextcid' => $xzv_160 < count($xzv_119) - 1 ? $xzv_160 + 1 + $this->setting['seo']['cidrule'] : -1, 'cache' => $xzv_47, 'nextpage' => $xzv_47['nextpage'], 'hash' => encodekey('id-' . $xzv_121 . '+cid-' . $xzv_160 . '*page-' . $xzv_47['nextpage']), 'allsub' => $xzv_134, 'nowsub' => $xzv_162 + 1);
        if (isset($xzv_162) && $xzv_47) {
            if (!is_null($xzv_50['prev'])) {
                $xzv_50['prev']['cid'] = $xzv_162 > 0 ? $xzv_50['prev']['cid'] + 1 : $xzv_50['prev']['cid'];
                $xzv_50['prev']['sub'] = $xzv_162 > 0 ? $xzv_162 - 1 : NULL;
            }
            if (($xzv_162 != 0 || $xzv_134 > 0) && !is_null($xzv_50['next'])) {
                $xzv_50['next']['cid'] = $xzv_162 + 1 == $xzv_134 ? $xzv_50['next']['cid'] : $xzv_50['next']['cid'] - 1;
                $xzv_50['next']['sub'] = $xzv_167;
            }
            if ($xzv_162 + 1 < $xzv_134 && !$xzv_50['next']) {
                $xzv_50['next'] = $xzv_119[$xzv_160];
                $xzv_50['next']['sub'] = $xzv_167;
            }
        }
        G('end');
        $xzv_56['runtime'] = G('begin', 'end');
        $this->assign('TDK', $xzv_56);
        $this->assign('cate', $xzv_9);
        $this->assign('mychapterdb', $xzv_50);
        $this->display();
    }
    public function bookcase()
    {
        $xzv_54 = array('weburl' => C('NOWHOST') . C('HOME_URL'), 'webname' => $this->setting['seo']['webname']);
        $this->assign('TDK', $xzv_54);
        $this->display();
    }
    public function updatecache()
    {
        $xzv_166 = I('get.id');
        $xzv_28 = I('get.hash');
        if (is_numeric($xzv_166)) {
            $xzv_174 = $xzv_166 - $this->setting['seo']['idrule'];
            $xzv_39['id'] = array('eq', $xzv_174);
        } else {
            $xzv_111['status'] = 'error';
            $xzv_111['content'] = 'unavailable id';
            echo json_encode($xzv_111);
            die;
        }
        if (!$xzv_174 || !$xzv_28 || $xzv_28 !== encodekey('book_' . $xzv_166)) {
            $xzv_111['status'] = 'error';
            $xzv_111['content'] = 'not match data';
            die(json_encode($xzv_111));
        }
        $xzv_49 = M('articles');
        $xzv_75 = $xzv_49->where($xzv_39)->find();
        $xzv_174 = $xzv_75['id'];
        if (!isset($xzv_75)) {
            $xzv_111['status'] = 'error';
            $xzv_111['content'] = 'unavailable id';
            echo json_encode($xzv_111);
            die;
        }
        $xzv_25 = floor($xzv_174 / 1000);
        $xzv_18 = S('chaptercache_' . $xzv_174, '', array('temp' => TEMP_PATH . 'chaptercache/' . $xzv_25 . '/'));
        $xzv_32 = F('view/book/' . $xzv_25 . '/' . $xzv_174);
        $xzv_168 = M('article_views')->where('aid = %d', $xzv_174)->find();
        list($xzv_67, $xzv_77) = explode(',', $this->setting['seo']['virtviews']);
        if (!$xzv_67 || !$xzv_77 || $xzv_77 < $xzv_67) {
            $xzv_67 = 1;
            $xzv_77 = 3;
        }
        $xzv_76 = mt_rand($xzv_67, $xzv_77);
        $xzv_165['views'] = $xzv_32['views'] = $xzv_168['views'] + $xzv_76;
        $xzv_165['weekviews'] = $xzv_32['weekviews'] = $xzv_168['weekviews'] + $xzv_76;
        $xzv_165['monthviews'] = $xzv_32['monthviews'] = $xzv_168['monthviews'] + $xzv_76;
        M('article_views')->where("aid = '{$xzv_174}'")->save($xzv_165);
        F('view/book/' . $xzv_25 . '/' . $xzv_174, $xzv_32);
        $xzv_117 = true;
        if ($xzv_75['full'] == 1 || $xzv_18 || $xzv_75['original'] == 1) {
            $xzv_117 = false;
        }
        if (F('view/chapter/' . $xzv_25 . '/' . $xzv_174) && !$xzv_117) {
            $xzv_111['status'] = 'error';
            $xzv_111['content'] = 'the cache dont need update';
            die(json_encode($xzv_111));
        } else {
            unset($xzv_18);
            $xzv_18 = pickrun('chapter', $xzv_32['pid'], $xzv_32['chapterurl']);
            $xzv_164 = $xzv_18['chapterlist'];
            if ($xzv_18['lastchapter']['title'] && $xzv_18['lastchapter']['title'] != $xzv_32['lastchapter']) {
                $xzv_69['lastchapter'] = $xzv_32['lastchapter'] = $xzv_18['lastchapter']['title'];
                $xzv_69['lastcid'] = $xzv_32['lastcid'] = $xzv_18['lastchapter']['cid'];
                $xzv_49 = M('articles');
                $xzv_32['updatetime'] = $xzv_69['updatetime'] = NOW_TIME;
                $xzv_32['time'] = date('Y-m-d H:i:s', NOW_TIME);
                $xzv_49->where("id = '{$xzv_174}'")->save($xzv_69);
                F('view/book/' . $xzv_25 . '/' . $xzv_174, $xzv_32);
            }
            foreach ($xzv_164 as $xzv_24 => $xzv_114) {
                $xzv_164[$xzv_24]['id'] = $xzv_174;
                $xzv_164[$xzv_24]['cid'] = $xzv_24;
                $xzv_164[$xzv_24]['cate'] = $xzv_32['cate'];
                $xzv_164[$xzv_24]['title'] = trim($xzv_114['title']);
            }
            $xzv_161 = 0;
            foreach (array_reverse($xzv_164, true) as $xzv_24 => $xzv_114) {
                $xzv_64[$xzv_24] = $xzv_163[$xzv_161] = $xzv_114;
                $xzv_161++;
                if ($xzv_161 > 11) {
                    break;
                }
            }
            if (count($xzv_164) > 0 && count($xzv_64) > 0) {
                $xzv_18 = 'view/chapter/' . $xzv_25 . '/' . $xzv_174;
                $xzv_170 = 'view/newchapter/' . $xzv_25 . '/' . $xzv_174;
                F($xzv_18, $xzv_164);
                F($xzv_170, $xzv_64);
                S('chaptercache_' . $xzv_174, NOW_TIME, array('temp' => TEMP_PATH . 'chaptercache/' . $xzv_25 . '/', 'expire' => $this->setting['extra']['chaptercachetime']));
                delhtml($xzv_174);
            }
            $xzv_111['status'] = 'success';
            $xzv_111['content'] = $xzv_163;
            die(json_encode($xzv_111));
        }
    }
    public function ajaxchapter()
    {
        $xzv_171 = I('param.id');
        $xzv_112 = I('param.nextpage');
        $xzv_27 = I('param.pagehash');
        $xzv_34 = I('param.cid', '', 'intval');
        $xzv_20 = I('param.basecid', '', 'intval');
        $xzv_113 = I('param.eKey');
        if (is_numeric($xzv_171)) {
            $xzv_40 = $xzv_171 - $this->setting['seo']['idrule'];
            $xzv_5['id'] = array('eq', $xzv_40);
        } else {
            $xzv_37['status'] = 'unavailable id';
            echo json_encode($xzv_37);
            die;
        }
        $xzv_173 = $xzv_34 - $this->setting['seo']['cidrule'];
        $xzv_105 = M('articles');
        $xzv_78 = $xzv_105->where($xzv_5)->find();
        $xzv_40 = $xzv_78['id'];
        if (!isset($xzv_78)) {
            $xzv_37['status'] = 'unavailable id';
            echo json_encode($xzv_37);
            die;
        } else {
            if (!$xzv_113 || $xzv_113 != encodekey('id-' . $xzv_40 . '+cid-' . $xzv_173) && $xzv_113 != encodekey('id-' . $xzv_40 . '+cid-' . $xzv_20)) {
                $xzv_37['status'] = 'unavailable hash';
                echo json_encode($xzv_37);
                die;
            }
        }
        if ($xzv_78['original'] == 1) {
            $xzv_37['status'] = 'original book';
            echo json_encode($xzv_37);
            die;
        }
        $xzv_1 = floor($xzv_40 / 1000);
        $xzv_115 = F('view/chaptercont/' . $xzv_1 . '/' . $xzv_40 . '/' . $xzv_173);
        $xzv_2 = explode('[line]', htmlspecialchars_trans($this->setting['seo']['repick_sign'], 'pick'));
        foreach ($xzv_2 as $xzv_8 => $xzv_31) {
            if (strexists($xzv_115['content'], $xzv_31)) {
                $xzv_115 = null;
                break;
            }
        }
        if (strlen($xzv_112) > 3) {
            if ($xzv_27 != encodekey('id-' . $xzv_40 . '+cid-' . $xzv_173 . '*page-' . $xzv_112)) {
                $xzv_37['status'] = 'unavailable pagehash';
                echo json_encode($xzv_37);
                die;
            } else {
                $xzv_95 = $xzv_112;
            }
        }
        $xzv_11 = F('view/chapter/' . $xzv_1 . '/' . $xzv_40);
        if (!strexists($xzv_115['link'], $xzv_11[$xzv_173]['link'])) {
            $xzv_115 = null;
        }
        if (!$xzv_11[$xzv_173]['link'] && !$xzv_115) {
            die;
        }
        if (!$xzv_115 || strlen($xzv_115['content']) <= 3 || $xzv_78['update'] > 0 || $xzv_95) {
            $xzv_13 = F('view/book/' . $xzv_1 . '/' . $xzv_40);
            if ($xzv_95) {
                $xzv_14 = fillurl(fillurl($xzv_13['chapterurl'], $xzv_11[$xzv_173]['link']), $xzv_95);
                $xzv_59 = $xzv_115['content'];
            } else {
                $xzv_14 = fillurl($xzv_13['chapterurl'], $xzv_11[$xzv_173]['link']);
            }
            $xzv_115 = pickrun('chaptercontent', $xzv_78['pid'], $xzv_14);
            $xzv_115['title'] = $xzv_11[$xzv_173]['title'];
            $xzv_115['link'] = $xzv_11[$xzv_173]['link'];
            $xzv_115['rewriteurl'] = reurl('chapter', array('id' => $xzv_40, 'cate' => $xzv_78['cate'], 'cid' => $xzv_173));
            $xzv_115['nextcid'] = $xzv_173 < count($xzv_11) - 1 ? $xzv_173 + 1 : -1;
            $xzv_115['prevcid'] = $xzv_173 > 0 ? $xzv_173 - 1 : -1;
            foreach ($xzv_2 as $xzv_8 => $xzv_31) {
                if (strexists($xzv_115['content'], $xzv_31)) {
                    $xzv_60 = true;
                    break;
                }
            }
            if ($xzv_95) {
                $xzv_115['content'] = $xzv_59 . $xzv_115['content'];
            }
            !$xzv_60 && F('view/chaptercont/' . $xzv_1 . '/' . $xzv_40 . '/' . $xzv_173, $xzv_115);
        }
        if (strlen($xzv_115['content']) > 3) {
            if ($this->setting['seo']['core_filter']) {
                $xzv_118 = explode('[line]', htmlspecialchars_trans($this->setting['seo']['core_filter'], 'pick'));
                foreach ($xzv_118 as $xzv_145 => $xzv_61) {
                    preg_match("#^\\{filter\\s+replace\\s*=\\s*'([^']*)'\\s*\\}(.*)\\{/filter\\}#", $xzv_61, $xzv_55);
                    if (isset($xzv_55[2]) && !empty($xzv_55[2])) {
                        $xzv_55[2] = str_replace('~', '\\~', $xzv_55[2]);
                        $xzv_55[2] = str_replace('"', '\\"', $xzv_55[2]);
                        $xzv_115['content'] = preg_replace('~' . $xzv_55[2] . '~iUs', $xzv_55[1], $xzv_115['content']);
                    } else {
                        $xzv_115['content'] = str_replace($xzv_61, '', $xzv_115['content']);
                    }
                }
            }
            $xzv_37['status'] = 'success';
            $xzv_37['info'] = $xzv_115;
            if ($xzv_115['nextpage']) {
                $xzv_37['nextpage'] = $xzv_115['nextpage'];
                $xzv_37['hash'] = encodekey('id-' . $xzv_40 . '+cid-' . $xzv_173 . '*page-' . $xzv_115['nextpage']);
            }
            delchapter($xzv_40, $xzv_34);
            echo json_encode($xzv_37);
            die;
        } else {
            $xzv_37['status'] = 'network error';
            echo json_encode($xzv_37);
            die;
        }
    }
    public function sitemapfix()
    {
        ob_end_clean();
        header('Content-type:text/xml');
        $xzv_116 = I('get.index', '', 'intval');
        $xzv_116 > '1000' && die;
        if ($xzv_116 > 0) {
            $xzv_157 = 'sitemapfix_' . $xzv_116;
        } else {
            $xzv_157 = 'sitemapfix_index';
        }
        $xzv_85 = S($xzv_157, '', array('temp' => $this->temppath));
        if (!$xzv_85) {
            $xzv_154 = M('articles');
            $xzv_85['dateline'] = date('Y-m-d');
            $xzv_85['znsid'] = $this->setting['seo']['znsid'];
            $xzv_81 = 1000;
            if ($xzv_116 > 0) {
                $xzv_146 = ($xzv_116 - 1) * $xzv_81;
                $xzv_86 = $xzv_116 * $xzv_81 + 1;
                $xzv_87 = $xzv_154->where("thumb is not null and info is not null and id > '{$xzv_146}' and id < '{$xzv_86}'")->order('id desc')->select();
                foreach ($xzv_87 as $xzv_110 => $xzv_152) {
                    $xzv_89[$xzv_110]['rewriteurl'] = reurl('view', $xzv_152);
                    $xzv_89[$xzv_110]['updatetime'] = date('Y-m-d', $xzv_152['updatetime'] ? $xzv_152['updatetime'] : NOW_TIME);
                    $xzv_89[$xzv_110]['title'] = utf8_for_xml($xzv_152['title']);
                    $xzv_89[$xzv_110]['author'] = utf8_for_xml($xzv_152['author']);
                    $xzv_89[$xzv_110]['author_encode'] = urlencode($xzv_152['author']);
                    $xzv_89[$xzv_110]['thumb'] = showcover($xzv_152['thumb']);
                    if (substr($xzv_89[$xzv_110]['thumb'], 0, 4) != 'http') {
                        $xzv_89[$xzv_110]['thumb'] = C('NOWHOST') . C('HOME_URL') . $xzv_89[$xzv_110]['thumb'];
                    }
                    $xzv_89[$xzv_110]['description'] = utf8_for_xml(cleanHtml($xzv_152['info']));
                    $xzv_152['cates'] = $xzv_152['cate'] == $this->category['default']['dir'] ? 'default' : $xzv_152['cate'];
                    $xzv_89[$xzv_110]['catename'] = $this->category[$xzv_152['cates']]['name'];
                    $xzv_89[$xzv_110]['status'] = $xzv_152['full'] > 0 ? '已完成' : '连载中';
                    $xzv_89[$xzv_110]['views'] = $xzv_152['views'];
                    $xzv_89[$xzv_110]['lastchapter'] = $xzv_152['lastchapter'];
                    $xzv_89[$xzv_110]['lastchapterurl'] = $xzv_152['lastcid'] ? reurl('chapter', array('id' => $xzv_152['id'], 'cate' => $xzv_152['cate'], 'cid' => $xzv_152['lastcid'])) : $xzv_89[$xzv_110]['rewriteurl'];
                }
                $xzv_85['novellist'] = $xzv_89;
                S($xzv_157, $xzv_85, array('temp' => $this->temppath, 'expire' => 3600 * 5));
            } else {
                $xzv_52 = $xzv_154->Count();
                $xzv_148 = ceil($xzv_52 / $xzv_81);
                for ($xzv_149 = 1; $xzv_149 <= $xzv_148; $xzv_149++) {
                    $xzv_51[]['listurl'] = '/home/index/sitemapfix' . '?index=' . $xzv_149;
                }
                $xzv_85['maplist'] = $xzv_51;
                S($xzv_157, $xzv_85, array('temp' => $this->temppath, 'expire' => 3600 * 12));
            }
        }
        $this->assign('sitemapdata', $xzv_85);
        $this->assign('index', $xzv_116);
        $this->display();
    }
}