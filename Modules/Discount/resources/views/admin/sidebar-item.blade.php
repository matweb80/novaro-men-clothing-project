<li class="nav-item has-treeview {{ isActive(['admin.discount.index' ,'admin.discount.create' ,'admin.discount.edit' ],'menu-open') }}">
    <a href="#" class="nav-link {{ isActive(['admin.discount.index' ,'admin.discount.create' ,'admin.discount.edit' ],'active') }}">
        <i class="nav-icon fa fa-percent"></i>
        <p>
            تخفیف ها
            <i class="right fa fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{route('admin.discount.index')}}" class="nav-link {{ in_array(Route::currentRouteName(), ['admin.discount.index' ,'admin.discount.create' ,'admin.discount.edit' ]) ? 'active' : '' }}">
                <i class="fa fa-circle-o nav-icon"></i>
                <p>لیست تخفیف ها</p>
            </a>
        </li>
    </ul>
</li>