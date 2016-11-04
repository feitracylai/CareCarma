<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 4/15/2016
 * Time: 4:16 PM
 */

namespace humhub\libs;


class Push
{
    private $title;

    // push message payload
    private $data;

    //sender in message
    private $sender;

    //AndroidId in contact
    private $aID;

    // flag indicating background task on push received
    private $is_background;

    // flag to indicate the type of notification
    private $flag;

    function __construct() {

    }

    public function setTitle($title){
        $this->title = $title;
    }

    public function setData($data){
        $this->data = $data;
    }

    public function setSender($sender){
        $this->sender = $sender;
    }

    public function setAID($aID){
        $this->aID = $aID;
    }

    public function setIsBackground($is_background){
        $this->is_background = $is_background;
    }

    public function setFlag($flag){
        $this->flag = $flag;
    }

    public function getPush(){
        $res = array();

        $res['is_background'] = $this->is_background;
        $res['flag'] = $this->flag;
        $res['data'] = $this->data;
        $res['title'] = $this->title;
        $res['sender'] = $this->sender;
        $res['aID'] = $this->aID;

        return $res;
    }
}