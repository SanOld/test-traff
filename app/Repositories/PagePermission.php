<?php

namespace App\Repositories;

use App\User;
use App\Page;

class PagePermission
{
  
  protected $canView = false;
  protected $canEdit = false;
  
  public function __construct(){
      
  }
    /**
     * Get all of the tasks for a given user.
     *
     * @param  User  $user
     * @return Collection
     */
  protected function permission($name, User $user)
  {
   DB::table('users_pages')->select(['canView', 'canEdit'])
                  ->join('pages', 'users_pages.page_id', '=', 'pages.id')
                  ->where(
                          ['user_type', User::type]
                        , ['pages.name', $name]
                          )
                  ->first();
    if($result){
      $this->canView = $result['canView'];
      $this->canEdit = $result['canEdit'];
    }
  }
    
  public function canView($name){
    $this ->permission($name);
    return $this->canView;
  }
    
    
}
