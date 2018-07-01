<?php
/**
 * Created by PhpStorm.
 * User: KIRITO
 * Date: 2018/6/27
 * Time: 10:10
 */

namespace app\index\model;
use think\Db;

class Sign
{
    public function checkTime($data){
        $res = Db::table('zt_shsj_teamtask')->where(['taskid'=>$data['taskID']])->select("taskid,starttime,endtime")[0];
        if($res==null){
            $data['status']=false;
            $data['message']="无此任务";
        }
        else{
            $now=time();
            $start = strtotime($res['starttime']);
            $end = strtotime($res['endtime']);
            if($now<$start) {
                $data['status'] = false;
                $data['message'] = "打卡过早";
            }
            elseif ($now<$end){
                $data['time']=date("Y-m-d H:i:s");
                $data["state"]="ontime";
                $data['status']=true;
                $data['message']= "打卡准点";
            } else{
                $data['state'] = "late";//这里超出十点端还能打卡，但是要不要只能当天打卡呢？
                $data['status'] = true;
                $data['message']= "打卡迟到";
            }
        }
        return $data;
    }

    public function checkPlace($data){
        $res = Db::table('zt_shsj_teamtask')->where(['taskid'=>$data['taskID']])->select("taskid,address")[0];
        if($res==null) {
            $data['status'] = false;
            $data['message']="无此任务";
        } else{
            //$place = json_decode($res['address']); //数据库中的 还有就是任务没了，sql语句都要改，新的表长啥样
            //$address = json_decode($data['address']); //传过来的
            if($res['address']==$data['address']){ //这里验证可能有问题
                $data['status'] = true;
                $data['message']="地点正确";
            }else{
                $data['status'] = false;
                $data['message']="地点错误";
            }
        }
        return $data;
    }

    public function submit($data){
        $res = Db::table("zt_shsj_qd")->insert([
            "openid"=>$data['openId'],
            "taskid"=>$data['taskID'],
            "time"=>$data['time'],
            "address"=>$data['address'],
            "state"=>$data['state']
        ]);
        if($res==null)
            $res['message']="打卡出现错误";
        else
            $res['message']="打卡成功";
        return $res;
    }
    /**
     * 某天某任务的最新打卡情况
     */
    public function getDaily($data){

    }

    /**
     * 某个时间段某个任务所有成员的签到情况统计
     */
    public function getAll($data){

    }

    /**
     * 某个时间段某个任务某个成员的签到情况统计
     */
    public function getSimple($data){

    }

    /**
     * 某个时间段某个任务某个成员的迟到情况统计
     */
    public function getLate($data){

    }

    /**
     *  某个时间段某个任务某个成员的请假情况统计
     */
    public function getLeave($data){

    }

    /**
     * 某个时间段某个任务某个成员的按时签到的情况统计
     */
    public function getOntime($data){

    }

    /**
     *  某个时间段某个任务某个成员未签到的情况统计
     */
    public function getAbsent($data){

    }
}