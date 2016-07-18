<?php

namespace App\Http\Controllers;
use Gate;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Helpers\Data;
use App\User;
use App\Repositories\PagePermission;

class StatController extends Controller
{
  
  
  public function index(Data $stat,Request $request ){
    
    if ($request->user()->cannot('statView')) {
      return view('errors.503');
    }
    
    $data = $stat->getStatistics(array('browser',  'os', 'referer', 'city', 'page'));
    $statistics = $data['statistics'];
    $filterGroup = $data['groups'];
   
    return view('stat', ['data'=>$statistics, 'groups' => $filterGroup]);
  }
  
  public function filters(Data $stat, Request $request){
    
    if($request->input('groups') !='All groups'){
      $groups = array();
      $groups[] = $request->input('groups');
    } else {
      $groups = array();
    }
    
   $data = $stat->getStatistics($groups);
   $statistics = $data['statistics'];
   $filterGroup = $data['groups'];
   
   return view('stat', ['data'=>$statistics, 'groups' => $filterGroup]);
  }
}
