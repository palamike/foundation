<ul class="sidebar-menu">
    @set($items,menu('menu'))
    @each('foundation::layouts.partials.menu_item',$items,'item')
</ul>