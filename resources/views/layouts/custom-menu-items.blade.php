
@foreach($items as $item)
  <li @if($item->hasChildren()) class="dropdown" @endif>
       @if($item->hasChildren())
          <a  class="dropdown-toggle" data-toggle="dropdown"  href="{!! $item->url() !!}">
           {!! $item->title !!} 
           <b class="caret"></b>
          </a>
          <ul class="dropdown-menu"  >
                 @include('layouts.custom-menu-items', array('items' => $item->children()))
          </ul> 
       @else
          <a    href="{!! $item->url() !!}">
           {!! $item->title !!} 
         </a>       
       @endif
  </li>
@endforeach