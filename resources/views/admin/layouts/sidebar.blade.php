<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">پنل مدیریت</span>
    </a>

    <div class="sidebar" style="direction: ltr">
        <div style="direction: rtl">
            @if (auth()->check())
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="info">
                        <span class="d-block text-white">{{ auth()->user()->name }}</span>
                    </div>
                </div>
            @endif

            <nav class="mt-2" aria-label="منوی مدیریت">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ isActive('admin.dashboard', 'active') }}">
                            <i class="nav-icon fa fa-dashboard" aria-hidden="true"></i>
                            <p>داشبورد</p>
                        </a>
                    </li>

                    @can('show-users')
                        <li class="nav-item">
                            <a href="{{ route('admin.users.index') }}" class="nav-link {{ isActive(['admin.users.index', 'admin.users.create', 'admin.users.edit'], 'active') }}">
                                <i class="nav-icon fa fa-users" aria-hidden="true"></i>
                                <p>کاربران</p>
                            </a>
                        </li>
                    @endcan

                    @can('show-messages')
                        <li class="nav-item">
                            <a href="{{ route('admin.messages.index') }}" class="nav-link {{ isActive(['admin.messages.index', 'admin.messages.show'], 'active') }}">
                                <i class="nav-icon fa fa-envelope-o" aria-hidden="true"></i>
                                <p>پیام‌های فرم تماس</p>
                            </a>
                        </li>
                    @endcan

                    @canany(['show-permissions', 'show-roles'])
                        <li class="nav-header">مدیریت دسترسی‌ها</li>
                        @can('show-roles')
                            <li class="nav-item">
                                <a href="{{ route('admin.roles.index') }}" class="nav-link {{ isActive(['admin.roles.index', 'admin.roles.create', 'admin.roles.edit'], 'active') }}">
                                    <i class="nav-icon fa fa-id-card-o" aria-hidden="true"></i>
                                    <p>نقش‌ها</p>
                                </a>
                            </li>
                        @endcan
                        @can('show-permissions')
                            <li class="nav-item">
                                <a href="{{ route('admin.permissions.index') }}" class="nav-link {{ isActive(['admin.permissions.index', 'admin.permissions.create', 'admin.permissions.edit'], 'active') }}">
                                    <i class="nav-icon fa fa-key" aria-hidden="true"></i>
                                    <p>دسترسی‌ها</p>
                                </a>
                            </li>
                        @endcan
                    @endcanany

                    @can('show-products')
                        <li class="nav-header">فروشگاه</li>
                        <li class="nav-item">
                            <a href="{{ route('admin.products.index') }}" class="nav-link {{ isActive(['admin.products.index', 'admin.products.create', 'admin.products.edit'], 'active') }}">
                                <i class="nav-icon fa fa-shopping-cart" aria-hidden="true"></i>
                                <p>محصولات</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.attributes.index') }}" class="nav-link {{ isActive(['admin.attributes.index', 'admin.attributes.create', 'admin.attributes.edit'], 'active') }}">
                                <i class="nav-icon fa fa-list-alt" aria-hidden="true"></i>
                                <p>ویژگی‌ها</p>
                            </a>
                        </li>
                    @endcan

                    @can('show-categories')
                        <li class="nav-item">
                            <a href="{{ route('admin.categories.index') }}" class="nav-link {{ isActive(['admin.categories.index', 'admin.categories.create', 'admin.categories.edit'], 'active') }}">
                                <i class="nav-icon fa fa-folder-open-o" aria-hidden="true"></i>
                                <p>دسته‌بندی‌ها</p>
                            </a>
                        </li>
                    @endcan

                    @can('show-tags')
                        <li class="nav-item">
                            <a href="{{ route('admin.tags.index') }}" class="nav-link {{ isActive(['admin.tags.index', 'admin.tags.create', 'admin.tags.edit'], 'active') }}">
                                <i class="nav-icon fa fa-tags" aria-hidden="true"></i>
                                <p>برچسب‌ها</p>
                            </a>
                        </li>
                    @endcan

                    @can('show-approved-comments')
                        <li class="nav-item">
                            <a href="{{ route('admin.comments.index') }}" class="nav-link {{ isActive(['admin.comments.index', 'admin.comments.unapproved', 'admin.comments.edit'], 'active') }}">
                                <i class="nav-icon fa fa-comments" aria-hidden="true"></i>
                                <p>نظرات</p>
                            </a>
                        </li>
                    @endcan

                    @can('show-badges')
                        <li class="nav-item">
                            <a href="{{ route('admin.badges.index') }}" class="nav-link {{ isActive(['admin.badges.index', 'admin.badges.create', 'admin.badges.edit'], 'active') }}">
                                <i class="nav-icon fa fa-certificate" aria-hidden="true"></i>
                                <p>نشان‌ها</p>
                            </a>
                        </li>
                    @endcan

                    @can('show-orders')
                        <li class="nav-header">سفارش‌ها</li>
                        @php
                            // شمارش همه وضعیت‌ها در یک پرس‌وجو انجام می‌شود.
                            $orderCounts = \App\Models\Order::query()
                                ->selectRaw('status, COUNT(*) as aggregate')
                                ->groupBy('status')
                                ->pluck('aggregate', 'status');
                        @endphp
                        @foreach ([
                            'unpaid' => ['پرداخت‌نشده', 'warning'],
                            'paid' => ['پرداخت‌شده', 'info'],
                            'preparation' => ['در حال پردازش', 'primary'],
                            'posted' => ['ارسال‌شده', 'light'],
                            'received' => ['دریافت‌شده', 'success'],
                            'canceled' => ['لغوشده', 'danger'],
                        ] as $status => $item)
                            <li class="nav-item">
                                <a href="{{ route('admin.orders.index', ['type' => $status]) }}"
                                   class="nav-link {{ request('type') === $status && request()->routeIs('admin.orders.index') ? 'active' : '' }}">
                                    <i class="nav-icon fa fa-circle-o text-{{ $item[1] }}" aria-hidden="true"></i>
                                    <p>
                                        {{ $item[0] }}
                                        <span class="badge badge-{{ $item[1] }} right">{{ number_format((int) ($orderCounts[$status] ?? 0)) }}</span>
                                    </p>
                                </a>
                            </li>
                        @endforeach
                    @endcan

                    @can('show-settings')
                        <li class="nav-header">تنظیمات</li>
                        <li class="nav-item">
                            <a href="{{ route('admin.settings.index') }}" class="nav-link {{ isActive(['admin.settings.index', 'admin.settings.update'], 'active') }}">
                                <i class="nav-icon fa fa-cog" aria-hidden="true"></i>
                                <p>تنظیمات سایت</p>
                            </a>
                        </li>
                    @endcan

                    @canany(['show-modules', 'show-discounts'])
                        @foreach (Module::collections() as $module)
                            @if (View::exists("{$module->getLowerName()}::admin.sidebar-item"))
                                @include("{$module->getLowerName()}::admin.sidebar-item")
                            @endif
                        @endforeach
                    @endcanany
                </ul>
            </nav>
        </div>
    </div>
</aside>