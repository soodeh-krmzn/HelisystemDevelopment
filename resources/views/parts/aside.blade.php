<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('dashboard') }}" class="brand-link">
        <span class="brand-text font-weight-light">هلی سیستم</span>
    </a>
    <div class="sidebar">
        <div>
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <!--div class="image">
                    <img src="" class="img-circle elevation-2" alt="User Image">
                </div-->
                <div class="info">
                    <a href="{{ route('dashboard') }}" class="d-block">{{ auth()->user()->getFullName() }}</a>
                </div>
            </div>
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                    data-accordion="false">
                    <li
                        class="nav-item has-treeview {{ activeDropdown(['account.create', 'account.index', 'account.edit', 'user.create', 'user.index', 'user.edit']) }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fa fa-users"></i>
                            <p>
                                اشتراک
                                <i class="right fa fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('account.create') }}"
                                    class="nav-link {{ activeMenu('account.create') }}">
                                    <i class="fa fa-circle-o nav-icon"></i>
                                    <p>ایجاد اشتراک جدید</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('account.index') }}"
                                    class="nav-link {{ activeMenu(['account.index', 'account.edit', 'user.create', 'user.index', 'user.edit']) }}">
                                    <i class="fa fa-circle-o nav-icon"></i>
                                    <p>لیست اشتراک</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item has-treeview {{ activeDropdown(['menu.create', 'menu.index', 'menu.edit']) }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fa fa-th"></i>
                            <p>
                                منو
                                <i class="right fa fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('menu.create') }}" class="nav-link {{ activeMenu('menu.create') }}">
                                    <i class="fa fa-circle-o nav-icon"></i>
                                    <p>ایجاد منو جدید</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('menu.index') }}"
                                    class="nav-link {{ activeMenu(['menu.index', 'menu.edit']) }}">
                                    <i class="fa fa-circle-o nav-icon"></i>
                                    <p>لیست منو</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li
                        class="nav-item {{ activeDropdown(['package.create', 'package.index', 'package.edit', 'package.menu', 'package-price.index', 'package-price.edit']) }}">
                        <a href="{{ route('package.index') }}"
                            class="nav-link {{ activeMenu(['package.create', 'package.index', 'package.edit', 'package.menu', 'package-price.index', 'package-price.edit']) }}">
                            <i class="nav-icon fa fa-th"></i>
                            <p>بسته ها</p>
                        </a>
                    </li>
                    <li class="nav-item {{ activeDropdown(['question.create', 'question.index', 'question.edit']) }}">
                        <a href="{{ route('question.index') }}"
                            class="nav-link {{ activeMenu(['question.create', 'question.index', 'question.edit']) }}">
                            <i class="nav-icon fa fa-th"></i>
                            <p>سوالات متداول</p>
                        </a>
                    </li>
                    <li class="nav-item {{ activeDropdown(['offer.index', 'offer.edit']) }}">
                        <a href="{{ route('offer.index') }}"
                            class="nav-link {{ activeMenu(['offer.index', 'offer.edit']) }}">
                            <i class="nav-icon fa fa-dollar"></i>
                            <p>کد تخفیف</p>
                        </a>
                    </li>
                    <li
                        class="nav-item has-treeview {{ activeDropdown(['payment', 'user.loginRecord', 'user.visitRecord', 'report.payment']) }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fa fa-file-archive-o"></i>
                            <p>
                                گزارشات
                                <i class="right fa fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item {{ activeMenu('payment') }}">
                                <a href="{{ route('payment') }}" class="nav-link">
                                    <i class="fa fa-circle-o nav-icon"></i>
                                    <p>پرداخت</p>
                                </a>
                            </li>
                            <li class="nav-item {{ activeMenu('user.loginRecord') }}">
                                <a href="{{ route('user.loginRecord') }}" class="nav-link">
                                    <i class="fa fa-circle-o nav-icon"></i>
                                    <p>سوابق ورود</p>
                                </a>
                            </li>
                            <li class="nav-item {{ activeMenu('user.visitRecord') }}">
                                <a href="{{ route('user.visitRecord') }}" class="nav-link">
                                    <i class="fa fa-circle-o nav-icon"></i>
                                    <p>سوابق بازدید</p>
                                </a>
                            </li>
                            <li class="nav-item {{ activeMenu('user.activity') }}">
                                <a href="{{ route('user.activity', ['days' => 3]) }}" class="nav-link">
                                    <i class="fa fa-circle-o nav-icon"></i>
                                    <p>کاربران غیرفعال</p>
                                </a>
                            </li>
                            <li class="nav-item {{ activeMenu('phonebook') }}">
                                <a href="{{ route('phonebook', ['days' => 3]) }}" class="nav-link">
                                    <i class="fa fa-circle-o nav-icon"></i>
                                    <p>دفترچه تلفن</p>
                                </a>
                            </li>
                            <li class="nav-item {{ activeMenu('report.payment') }}">
                                <a href="{{ route('report.payment') }}" class="nav-link">
                                    <i class="fa fa-circle-o nav-icon"></i>
                                    <p>خرید تعرفه و پیامک</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li
                        class="nav-item has-treeview {{ activeDropdown(['sms-pattern-category.index', 'sms-pattern-category.edit', 'sms-pattern.index', 'sms-pattern.create', 'sms-pattern.edit']) }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fa fa-envelope"></i>
                            <p>
                                پیامک Helionline
                                <i class="right fa fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('sms-pattern-category.index') }}"
                                    class="nav-link {{ activeMenu(['sms-pattern-category.index', 'sms-pattern-category.edit']) }}">
                                    <i class="fa fa-circle-o nav-icon"></i>
                                    <p>دسته های پیام</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('sms-pattern.index') }}"
                                    class="nav-link {{ activeMenu(['sms-pattern.index', 'sms-pattern.edit', 'sms-pattern.create']) }}">
                                    <i class="fa fa-circle-o nav-icon"></i>
                                    <p>الگوهای پیام</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li
                        class="nav-item has-treeview {{ activeDropdown(['message.create', 'message.index', 'message-text.index', 'message-text.edit']) }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fa fa-envelope"></i>
                            <p>
                                پیامک
                                <i class="right fa fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('message.create') }}"
                                    class="nav-link {{ activeMenu('message.create') }}">
                                    <i class="fa fa-circle-o nav-icon"></i>
                                    <p>ارسال پیامک</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('message.index') }}"
                                    class="nav-link {{ activeMenu('message.index') }}">
                                    <i class="fa fa-circle-o nav-icon"></i>
                                    <p>تاریخچه</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('message-text.index') }}"
                                    class="nav-link {{ activeMenu(['message-text.index', 'message-text.edit']) }}">
                                    <i class="fa fa-circle-o nav-icon"></i>
                                    <p>متون آماده</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item {{ activeDropdown(['tickets']) }}">
                        <a href="{{ route('tickets') }}" class="nav-link {{ activeMenu(['tickets']) }}">
                            <i class="nav-icon fa fa-gears"></i>
                            <p>تیکت ها</p>
                        </a>
                    </li>
                    <li class="nav-item {{ activeDropdown(['changeLogIndex']) }}">
                        <a href="{{ route('changeLogIndex') }}"
                            class="nav-link {{ activeMenu(['changeLogIndex']) }}">
                            <i class="nav-icon fa fa-gears"></i>
                            <p>تغییرات نرم افزار</p>
                        </a>
                    </li>
                    <li class="nav-item {{ activeDropdown(['option.index']) }}">
                        <a href="{{ route('option.index') }}" class="nav-link {{ activeMenu(['option.index']) }}">
                            <i class="nav-icon fa fa-gears"></i>
                            <p>تنظیمات</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" id="logout-form">
                            @csrf
                        </form>
                        <a href="#" class="nav-link" onclick="$('#logout-form').submit()">
                            <i class="nav-icon fa fa-power-off"></i>
                            <p>خروج</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="?setLang={{ app()->getLocale() == 'en' ? 'fa' : 'en' }}"
                            class="btn btn-block btn-warning text-dark">{{ app()->getLocale() == 'en' ? 'english' : 'فارسی' }}</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</aside>
