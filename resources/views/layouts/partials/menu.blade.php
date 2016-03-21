<ul class="sidebar-menu">
    @set($items,menu('menu'))
    @each('layouts.partials.menu_item',$items,'item')
</ul>