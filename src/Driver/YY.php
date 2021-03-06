<?php
namespace Itxiao6\Live\Driver;
use Itxiao6\Live\Bridge\HTTP;
use Itxiao6\Live\Driver;

/**
 * YY 直播抓取
 * Class YY
 * @package Itxiao6\Live\Driver
 */
class YY extends Driver
{
    /**
     * 获取video source
     * @return string
     */
    public function get_source()
    {
        return '<source src="'.$this -> get_hls().'" type="application/x-mpegURL">';
    }

    /**
     * 解析直播
     * @return mixed|void
     * @throws \Throwable
     */
    public function analysis()
    {
        /**
         * 获取内容
         */
        preg_match("!http://www\.yy\.com/(\d+)/\d+!is", $this -> live_url, $matchs);
        /**
         * 判断url 是否合法
         */
        if(!empty($matchs) && isset($matchs[1])){
            /**
             * 获取房间id
             */
            $room_id = $matchs[1];
        }else{
            /**
             * url 格式不正确
             */
            throw new \Exception('视频地址参数错误或所选来源错误');
        }
        /**
         * 抓取流信息
         */
        $res = json_decode(trim(HTTP::request('http://interface.yy.com/hls/new/get/'.$room_id.'/'.$room_id.'/1200?source=wapyy&callback=','POST',[
            'Referer'=>'http://wap.yy.com/mobileweb/'.$room_id.'/'.$room_id,
            'User-Agent'=>'Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/604.1.38 (KHTML, like Gecko) Version/11.0 Mobile/15A372 Safari/604.1']),"() \t\n\r \v"),true);
        /**
         * 判断是否抓取成功
         */
        if(isset($res['hls']) && $res['hls'] != ''){
            /**
             * 获取hls 播放地址
             */
            $this -> hls_url = $res['hls'];
        }
        /**
         * 获取状态
         */
        $this -> status = 1;
        /**
         * 获取直播封面
         */
        $this -> poster = '';
    }

}