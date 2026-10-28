<li class="nav-item has-treeview {{ isActive(['admin.modules.index' ,'admin.modules.create' ,'admin.modules.edit' ],'menu-open') }}">
    <a href="#" class="nav-link {{ isActive(['admin.modules.index' ,'admin.modules.create' ,'admin.modules.edit' ],'active') }}">
        <i class="nav-icon fa fa-cubes"></i>
        <p>
            ماژول ها
            <i class="right fa fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{route('admin.modules.index')}}" class="nav-link {{ in_array(Route::currentRouteName(), ['admin.modules.index' ,'admin.modules.create' ,'admin.modules.edit' ]) ? 'active' : '' }}">
                <i class="fa fa-circle-o nav-icon"></i>
                <p>لیست ماژول ها</p>
            </a>
        </li>
    </ul>
</li>