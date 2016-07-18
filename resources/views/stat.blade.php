@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Filter</div>
                  <div class="panel-body">
                    <form role="form" method="POST" action="{{ url('/stat') }}">
                      <fieldset>
                        <div class="form-group">
                          <label for="groups">Group</label>
                          <select name = "groups" id="groups" class="form-control">
                              <option>All groups</option>
                              <?php
                              foreach($groups as $key=>$value){
                                echo '<option>' . $value . '</option>';
                              }
                              ?>
                            <option>Disabled select</option>
                          </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Apply</button>
                      </fieldset>
                    </form>
                  </div>

        </div>            
            <div class="panel panel-default">
                <div class="panel-heading">Application statistics</div>
                <div class="panel-body">
                    <table class="table table-striped">
                      <col style="width: 25%;">
                      <col style="width: 25%;">
                      <col style="width: 25%;">
                      <col style="width: 25%;">
                      <?php
                       echo ( '<tr>
                                <th></th>
                                <th>hit</th>
                                <th>U ip</th>
                                <th>U cookie</th>
                              </tr>'
                              );
                      foreach($data as $key=>$value){
                        echo ( '<tr >
                                  <td colspan="4" class="h3">'.$key.'</td>
                                </tr>'
                              );
                        foreach($data[$key] as $elem=>$elem_data){
                          echo ( '<tr>
                                  <td >'.$elem.'</td>
                                  <td>'.$data[$key][$elem]['hit'].'</td>
                                  <td>'.$data[$key][$elem]['ip'].'</td>
                                  <td>'.$data[$key][$elem]['cookie'].'</td>
                                  </tr>'
                                );
                        }
                      }
                      ?>
                    </table>
                    

            </div>
        </div>
    </div>
</div>
@endsection
