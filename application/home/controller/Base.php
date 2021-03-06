<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2018/5/21 0021
 * Time: 17:03
 */

namespace app\home\controller;
use app\lib\exception\UserException;
use app\lib\exception\WeChatException;
use think\Controller;
use app\service\Token;
use think\Exception;

class Base extends Controller
{
    public $order;
    public $device;
    public $charge;
    public $user;
    public $orderStatus;
    public $emergency;
    public $safelimit;
    public $deviceshow;
    public $rent;
    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        $this->order = model('app\admin\model\Order');
        $this->device = model('app\admin\model\Device');
        $this->charge = model('app\admin\model\Charge');
        $this->user = model('app\admin\model\User');
        $this->orderStatus = model('app\admin\model\OrderStatus');
        $this->emergency = model('app\admin\model\Emergency');
        $this->safelimit = model('app\admin\model\SafeLimit');
        $this->deviceshow = model('app\admin\model\Deviceshow');
        $this->rent = model('app\admin\model\Rent');
    }
    //微信我二维码配置
    public function wxConfig(){
        $access_token=cache("access_token");
        if(!$access_token) {
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . config('service.APPID'). "&secret=" .  config('service.APPSECRET');
            $result=doCurl($url);
            $result_arr = json_decode($result);
            try{
                cache("access_token", $result_arr->access_token, $result_arr->expires_in);
            }catch (Exception $e)
            {
                throw new WeChatException();
            }

        }
    }

    //创建订单号
    public function setOrderSn(){
        list($t1,$t2) = explode(' ',microtime());
        $t3 = explode('.',$t1*10000);
        return $t2.$t3[0].(rand(10000,99999));
    }
    //检查超级用户的权限
    protected function checkPrimaryScope(){
        Token::needPrimaryScope();
    }
    //检查普通用户的权限
    protected function checkExclusiveScope(){

        Token::needExclusiveScope();
    }

}