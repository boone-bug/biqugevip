<?php

namespace Home\Controller;

use Think\Controller;
class BaseController extends Controller
{
    public $setting;
    public $category;
    public $temppath;
    public $defaultdir;
    public function __construct()
    {
        parent::__construct();
        if (!home_check()) {
            die('&#116;&#104;&#105;&#115;&#32;&#100;&#111;&#109;&#97;&#105;&#110;&#32;&#105;&#115;&#32;&#110;&#111;&#116;&#32;&#97;&#108;&#108;&#111;&#119;&#101;&#100;&#33;');
        }
        if (!file_exists(CONF_PATH . 'install.lock')) {
            redirect(U('/install'));
            die;
        }
        G('begin');
        spiderlog();
        $this->setting = F('setting');
        $this->category = F('category');
        $this->temppath = TEMP_PATH;
        $this->redomain = C('DATADOMAIN');
        $xzv_9 = $xzv_7 = C('DATADOMAIN');
        if (C('NOWHOST') == C('WAPHOST')) {
            $this->temppath .= 'wap/';
            $this->setting['seo']['lazyload'] == 1 && ($this->setting['seo']['lazyload'] = 0);
        }
        $xzv_8 = F('dataarea/' . $xzv_9);
        foreach ($xzv_8 as $xzv_6 => $xzv_5) {
            if ($xzv_5['open'] == 'yes') {
                $xzv_4[$xzv_6] = F('dataarea/' . $xzv_9 . '/dataarea_' . $xzv_5['did']);
            }
        }
        $xzv_3 = array('top' => C('HOME_URL') . $this->setting['seo']['topurl'], 'full' => C('HOME_URL') . $this->setting['seo']['fullurl'], 'all' => C('HOME_URL') . substr($this->setting['seo']['allurl'], 0, strpos($this->setting['seo']['allurl'], '{ell')));
        $xzv_0 = array('znsid' => $this->setting['seo']['znsid'] && $this->setting['seo']['znsearch'] > 0 ? $this->setting['seo']['znsid'] : null, 'src' => $this->setting['seo']['lazyload'] > 0 ? 'rel-class="lazyload" src="/Public/images/nocover.jpg" data-original' : 'src', 'http' => is_HTTPS() ? 'https://' : 'http://');
        if ($this->setting['seo']['advertise'] > 0) {
            if (F('advertise_extend')) {
                $xzv_1 = array_merge(F('advertise'), F('advertise_extend'));
            } else {
                $xzv_1 = F('advertise');
            }
        } else {
            $xzv_1 = null;
        }
        $xzv_2 = file_get_contents(CONF_PATH . 'ver.txt');
        $this->defaultdir = $this->category['default']['dir'];
        foreach ($this->category as $xzv_6 => $xzv_5) {
            $this->category[$xzv_6]['url'] = reurl('cate', $xzv_5['dir']);
        }
        $this->assign('dataarea_list', $xzv_4);
        $this->assign('category', $this->category);
        $this->assign('statcode', $this->setting['seo']['statcode']);
        $this->assign('maindomain', $this->redomain);
        $this->assign('theme', C('DEFAULT_THEME'));
        $this->assign('catelist', $xzv_3);
        $this->assign('comset', $xzv_0);
        $this->assign('version', $xzv_2);
        $this->assign('advcode', $xzv_1);
        $this->assign('nowsetting', $this->setting['seo']);
        S(array('expire' => 3600, 'prefix' => $xzv_9, 'length' => 500, 'temp' => $this->temppath));
    }
}