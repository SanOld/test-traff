<?php

namespace App\Helpers;

use App\Helpers\Data;
use LRedis;
use Request;
use Agent;
use GeoIP;

class DataController implements Data
{
  public $browser;
  public $platform;
  public $city;
  public $referer;
  public $hit;
  public $ip;
  public $cookie;
  public $time;
  
  public $statistics = array();
  
  public $deltaTime = 60*60;
  public $fingerprint;

  public $redis;
  
  
  public function __construct() {
    $this->redis = LRedis::connection();
    $browser = Agent::browser();
    $this->browser = $browser;
    $this->browser = $browser.' '.Agent::version($browser);
    
    $platform = Agent::platform();
    $this->platform = $platform;
    $this->platform = $platform.' '.Agent::version($platform);
    
    $ref = explode('/', Request::header('referer'));
    $this->referer = (is_array($ref) && count($ref) >= 2)?$ref[2]:'unknow';
    $this->ip= Request::ip();
    $this->cookie= md5(implode(',', Request::cookie()));
    $this->page = Request::segment(1);
    
    $location = GeoIP::getLocation($this->ip);
    $this->city = $location['city'];
    
    $this->fingerprint = Request::fingerprint();
    $this->time = time();
    
    $this->statistics = array(
                                'browser'   => $this->browser
                              , 'os'      => $this->platform
                              , 'city'    => $this->city
                              , 'referer' => $this->referer
                              , 'page'    => $this->page
                              );
    $this->statistics_unic = array(
                                'ip'      => $this->ip
                              , 'cookie'  => $this->cookie
                              );

      
    
  }
  public function getStat(){
    
  }
  public function flushAll(){
    $redis = LRedis::connection();
    $redis->flushAll();
  }


  public function getAllGroups(){
    return array_keys($this->statistics);
  }
  
  public function getUnicGroups(){
    return array_keys($this->statistics_unic);
  }


  public function getStatistics($groups = array(),$objects = array(), $start = 0, $end = -1){

    $redis = $this->redis;
    $periodArray = $this->getPeriodArray($start, $end);
    $result['statistics'] = $this->getStatBy($groups,$objects ,$periodArray);
    $result['groups'] = $this->getAllGroups();
    return $result;
  }
  public function getPeriodArray($start = 0, $end = -1){
     $redis = LRedis::connection();
     if (!$start){
       $result = $redis->zRange('times', 0, -1);
     } else {
       $result = $redis->zRangeByScore('times', (int)$start, (int)$end);
     }
    return $result;
  }
  public function getStatBy($groups = array() , $objects = array(), $periodArray){

    if(count($groups) == 0){
      $groups = $this->getAllGroups();
    }

    $result = array();
    foreach ($groups as $group) {
      $groupMembers = $this->redis->zRange($group, 0, -1);
     
      foreach($groupMembers as $elem){
       
        if (!array_key_exists($group,$objects)){
          $result[$group][$elem] = $this->getData($periodArray, $group, $elem);
        } else {
          foreach($objects[$group] as $object){
            if ($elem = $object){
              $result[$group][$elem] = $this->getData($periodArray, $group, $elem);
            }
          }
        }
      }
    }
    return $result;
  }
  public function getData($periodArray, $stat, $element){
    $redis = $this->redis;
    $result = array();

      $keysHitArray = array();
      foreach($periodArray as $time){
        $keysHitArray[] = 'time_hit:'.$time.':'.$stat.':'.$element ;
      }
      $keysHitArray[] = $stat.':'.$element;
      $redis->delete('temp2');
      $redis->zUnion('temp2', $keysHitArray);
      $result['hit'] = $redis->zScore( 'temp2', 'hit' ); //hit
      
      
    foreach($this->statistics_unic as $stat_unic=>$value_unic){
      $keysArray = array();
      foreach($periodArray as $time){
        $keysArray[]  = 'time:'.$time.':'.$stat.':'.$element.':'.$stat_unic ;
      }
      //data current time
      $keysArray[] = $stat.':'.$element.':'.$stat_unic;
      $redis->delete('temp');
      $redis->zUnion('temp', $keysArray);
      $result[$stat_unic] = $redis->zSize('temp'); //unic
    }
    
    return $result;
  }
  
  public function setStat(){
      $redis = LRedis::connection();

      foreach ($this->statistics as $stat => $value) {
        $redis->zIncrBy($stat, 1, $value);
        
        $key_hit = $stat.':'.$value ;
        $redis->zIncrBy($key_hit, 1, 'hit');
      }

      if (!$redis->exists('times')){
        $redis->zAdd("times", $this->time, $this->time);
      }

      //время последнего сохранения статистики
      $last_time = $redis->zRange('times', -1, -1)[0];

      if(($this->time - (int) $last_time ) > $this->deltaTime){

        $redis->zAdd("times", $this->time, $this->time);

        $this->copyInfo($last_time);
        //add key
        $this->incrInfo();
      } else {
        $this->incrInfo();
      }
    }
  public function incrInfo(){
      $redis = LRedis::connection();
      //add key
      foreach ($this->statistics_unic as $stat_unic => $value_unic) {
        $redis->zIncrBy($stat_unic, 1, $value_unic);
        foreach ($this->statistics as $stat => $value) {
          $key = $stat.':'.$value.':'.$stat_unic ;
          $redis->zIncrBy($key, 1, $value_unic);
        }
      }
    }
  public function copyInfo($last_time){
      $redis = LRedis::connection();
        //copy key to time_key
        foreach ($this->statistics_unic as $stat_unic => $value_unic) {
          $elements_unic = $redis->zRange($stat_unic, 0, -1);

          foreach ($this->statistics as $stat => $value) {
            $elements = $redis->zRange($stat, 0, -1);

            foreach ($elements as $element) {

              $key = $stat.':'.$element.':'.$stat_unic ;
              $key_time ='time:'.$last_time.':'.$stat.':'.$element.':'.$stat_unic ;
              $redis->rename($key, $key_time);
              $redis->delete($key);

              $key_hit = $stat.':'.$element ;
              $key_time_hit ='time_hit:'.$last_time.':'.$stat.':'.$element ;
              $redis->rename($key_hit, $key_time_hit);
              $redis->delete($key_hit);
            }
            
          }
        }
    }
}