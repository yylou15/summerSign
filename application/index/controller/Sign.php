<?php
/**
 * Created by PhpStorm.
 * User: KIRITO
 * Date: 2018/6/27
 * Time: 10:33
 */

namespace app\index\controller;
use think\Controller;
use think\Request;
use app\index\model\Team as TeamModel;
use app\index\model\Sign as SignModel;
class Sign
{
    /**
     * @param $timeLong 直接用服务器的时间戳不就好
     * @param $taskID
     * @param $openId
     * @param $longitude
     * @param $latitude
     */
    public function sign(Request $request){
        //$data=$request->post();
        $data['taskID']=$request->post('taskID');
        $data['openId']=$request->post('openId');
        $sign = new SignModel();
        $data=$sign->checkTime($data);
        if($data['status']==false)
            return $data["message"];
        $data=$sign->checkPlace($data);
        if($data['message']==false)
            return $data["message"];
        $res = $sign->submit($data);
        return $res['message'];
    }

    public function getSign(Request $request){
        //$taskID,$openId,$startDate,$endDate,$type,$detail
        $data=$request->post();
        $sign = new SignModel();
        switch ($data['type']){
            case "daily": return $sign->getDaily($data); break; //
            case "all": return $sign->getAll($data);break;
            case "individual":
                switch($data['detail']){
                    case "simple": return $sign->getSimple($data);break;
                    case"late":return $sign->getLate($data);break;
                    case "leave":return $sign->getLeave($data);break;
                    case"ontime":return $sign->getOntime($data);break;
                    case"absent":return $sign->getAbsent($data);break;
                }
        }
    }
}