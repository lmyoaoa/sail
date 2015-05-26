<?php

/**
 * @desc 分页基类
 * @author lmyoaoa
 * @since 2015-01-28
 * */
class Page {

    //当前页
    protected $currPage;

    //每页条数
    protected $perPage;

    //最大页码
    protected $maxPage;

    //数据总条数
    protected $totalNum;

    //页面参数
    protected $param;

    //锚点名称
    protected $anc;

    /**
     * @desc 组织分页数据
     * @param $data 需要分页的数据
     <code>
     array(
        'curr' => 当前页
        'perPage' => 每页条数
        'total' => 数据总条数
        'param' => 页面参数数组array('kk'=>1, 'nn'=>2)
        'url' => 需要跳转的分页地址，非必须
        'anc' => 锚点，非必须
     )
     </code>
     * @author limy
     * @since 2014-01-28
     */
    public function __construct(array $data) {
        $this->currPage = isset($data['curr']) ? $data['curr'] : 1;
        $this->perPage = isset($data['perPage']) ? $data['perPage'] : 10;
        $this->totalNum = isset($data['total']) ? $data['total'] : 0;
        $this->param = isset($data['param']) ? $data['param'] : array();
        $this->anc = isset($data['anc']) ? $data['anc'] : '';
        $this->url = isset($data['url']) ? $data['url'] : '';
        $this->maxPage = ceil($this->totalNum / $this->perPage);
    }

    /**
     * @desc 返回一个分页数据的数组，不包含模板
     * @author limy
     * @since 2015-01-28
     * @return array
     */
    public function toData() {
        $queryString = array();
        foreach( $this->param as $key => $val ) {
            if( $key == 'page' ) {
                continue;
            }

            if ( strlen(trim($val)) > 0 ) {
                $queryString[] = $key .'=' . $val;
            }
        }
        $queryString = '/?' . implode('&', $queryString);
        //$baseUrl = '/' . CONTROLLER_NAME . '/' . ACTION_NAME . '/';
        $baseUrl = $this->url;
        $url = $baseUrl . $queryString;

        //组织分页列表
        $showNum = 10; //显示10个分页项
        $data = array();
        $neig = array(
            'prev' => array(),
            'next' => array(),
        );
        for( $i=0; $i<5; $i++ ) {
            $toPage = $this->currPage - $i - 1;
            if( $this->currPage <= 1 || $toPage < 1 ) {
                break;
            }
            $data[$toPage] = $this->_pageData($url, $toPage);
            $this->_pageNeighbor($neig, $url, $toPage);
            $showNum--;
        }

        $leftNum = $showNum - count($data);
        for( $i=0; $i<$showNum; $i++ ) {
            $toPage = $this->currPage + $i;
            if( $toPage > $this->maxPage ) {
                break;
            }
            $isCurr = $this->currPage == $toPage ? 1 : 0;
            $data[$toPage] = $this->_pageData($url, $toPage, $isCurr);
            $this->_pageNeighbor($neig, $url, $toPage);
        }
        ksort($data);

        $return = array(
            'curr' => $this->currPage,
            'perPage' => $this->perPage,
            'totalNum' => $this->totalNum,
            'data' => $data,
            'neig' => $neig,
        );

        return $return;
    }

    /**
     * @desc 获取上一页/下一页的数据数组
     * @param array $neig 数组
     * @param string $url 页面基础url（即只包含module/controller/action
     * @param string $toPage 第几页
     * @return void
     */
    protected function _pageNeighbor(&$neig, $url, $toPage) {
        $prevPage = $this->currPage - 1;
        $nextPage = $this->currPage + 1;
        if( $prevPage == $toPage ) {
            $neig['prev'] = $this->_pageData($url, $toPage);
        }
        if( $nextPage == $toPage ) {
            $neig['next'] = $this->_pageData($url, $toPage);
        }
    }

    /**
     * @desc 组合分页数据
     * @param string $url 页面基础url（即只包含module/controller/action
     * @param string $toPage 第几页
     * @param boolen $isCurr 是否是当前页
     * @return array
     */
    protected function _pageData($url, $toPage, $isCurr=0) {
        if( strpos($url, '?') === false ) {
            $url = rtrim($url, '/') . '/'; 
            $url = $url . "?page={$toPage}";
        }else{
            $url = $url . "&page={$toPage}";
        }
        return array(
            'curr' => $isCurr,
            'page' => $toPage,
            'url' => $url,
        );
    }

    /**
     * @desc 将分页数据转换为html字符串
     * @author limy
     * @since 2015-01-28
     */
    public function html() {
        $data = $this->toData();

        $str = '';
        foreach( $data['data'] as $v ) {
            $act = $v['curr'] ? 'active' : '';
            $str .= '<li class="' . $act . '"><a href="' . $v['url'] . '">' . $v['page'] . '</a></li>';
        }

        if ($str) {
            $prevUrl = isset($data['neig']['prev']['url']) ? $data['neig']['prev']['url'] : '#';
            $nextUrl = isset($data['neig']['next']['url']) ? $data['neig']['next']['url'] : '#';
            $strPrefix = '<ul class="pagination">';
            $strPrefix .= '<li class="prev disabled"><a href="' . $prevUrl . '">← 上一页</a></li>';

            $strSuffix = '<li class="next"><a href="' . $nextUrl . '">下一页 → </a></li>';
            $strSuffix .= '</ul>';

            $str = $strPrefix . $str . $strSuffix;
        }

        return $str;
    }


}
