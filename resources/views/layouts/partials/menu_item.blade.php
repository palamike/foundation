@if(array_key_exists('permissions',$item))
@if(has_permissions($item['permissions']))
<li class="{{ array_key_exists('children',$item) ? 'treeview' : '' }} {{ (($item['type'] == 'internal') && ((request()->is($item['link'].'*')) || (request()->url() ==  $item['link']) )) ? 'active' : '' }}"><!--
 --><a href="{{ ($item['type'] == 'placeholder')?'#': url($item['link']) }}"><!--
     --><i class="fa fa-{{ $item['icon'] }}"></i> <span>{{ trans($item['label']) }}</span><!--
     -->@if(array_key_exists('children',$item)) <i class="fa fa-angle-left pull-right"></i>@endif<!--
 --></a><!--
 -->@if(array_key_exists('children',$item))<!--
 --><ul class="treeview-menu"><!--
      -->@each('foundation::layouts.partials.menu_item',$item['children'],'item')<!--
 --></ul><!--
 -->@endif<!--
--></li>
@endif
@else
<li>No Permission Set for This Item!!</li>
@endif