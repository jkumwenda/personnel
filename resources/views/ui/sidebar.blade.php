<aside class="main-sidebar sidebar-light-primary">
    <!-- Brand Logo -->
<a href="#">
    <img src="{{asset('dist/img/Logo-1.png')}}" alt="PMRA Logo" style="width: 200px; display: block; margin: auto;">
</a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link {{ Request::is('home') ? 'brand-bg-color' : '' }}">
                        <i class="nav-icon fa fa-home"></i>
                        <p class="fw-bold">
                            Home
                        </p>
                    </a>
                </li>
                @if(Auth::user()->hasAnyRole(['superadmin', 'Chief Inspector','Inspector','PRO','DG','BC']))
                <li class="nav-item {{ Request::is('applications') || Request::is('applications/*')? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::is('applications') || Request::is('applications/*') ? 'brand-bg-color' : '' }}">
                        <i class="nav-icon fas fa-folder-open"></i>
                        <p class="fw-bold">
                            Applications
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if(Auth::user()->can('approve license applications') || Auth::user()->can('screen documents'))
                        <li class="nav-item">
                            <a href="{{ route('applications.listview') }}" class="nav-link {{ Request::is('applications') ? 'brand-active' : '' }}">
                                <p>Screen Applications</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('applications.screen.approvals') }}" class="nav-link {{ Request::is('applications/screen/approvals') ? 'brand-active' : '' }}">
                                <p>Approve Applications</p>
                            </a>
                        </li>
                        @endif
                        @if(Auth::user()->can('view all license applications'))
                        <li class="nav-item">
                            <a href="{{ route('applications.screen.approved') }}" class="nav-link {{ Request::is('applications/screen/approved') ? 'brand-active' : '' }}">
                                <p>Approved Applications </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('applications.screen.rejected') }}" class="nav-link {{ Request::is('applications/screen/rejected') ? 'brand-active' : '' }}">
                                <p>Rejected Applications <span class="badge badge-danger">{{ $rejectedApplicationsCount }}</span></p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
                @if(Auth::user()->hasAnyRole('personnel'))
                    @if(Auth::user()->application()->first() == null)
                        <li class="nav-item {{ Request::is('applications/index') || Request::is('applications/apply') || Request::is('applications/list')  || Request::is('applications/*')? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ Request::is('applications/index') || Request::is('applications/apply') || Request::is('applications/list')  || Request::is('applications/*')? 'brand-bg-color' : '' }}">
                            <i class="nav-icon fas fa-folder-open"></i>
                            <p class="fw-bold">
                                Applications
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('applications.apply') }}" class="nav-link {{ Request::is('applications/apply') ? 'brand-active' : '' }}">
                                    <p>New Application</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('applications.list') }}" class="nav-link {{ Request::is('applications/list') ? 'brand-active' : '' }}">
                                    <p>My Applications</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @else
                        @if(Auth::user()->application()->first()->application_status !== "approved")
                            <li class="nav-item {{ Request::is('applications/index') || Request::is('applications/apply') || Request::is('applications/list')  || Request::is('applications/*')? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ Request::is('applications/index') || Request::is('applications/apply') || Request::is('applications/list')  || Request::is('applications/*')? 'brand-bg-color' : '' }}">
                                <i class="nav-icon fas fa-folder-open"></i>
                                <p>
                                    Applications
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('applications.apply') }}" class="nav-link {{ Request::is('applications/apply') ? 'brand-active' : '' }}">
                                        <p>New Application</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('applications.list') }}" class="nav-link {{ Request::is('applications/list') ? 'brand-active' : '' }}">
                                        <p>My Applications</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                    @endif
                @endif
                @if(Auth::user()->hasAnyRole(['superadmin']))
                <li class="nav-item {{ Request::is('payments/*')? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::is('payments/*') ? 'brand-bg-color' : '' }}">
                        <i class="nav-icon fas fa-money-bill-wave"></i>
                        <p class="fw-bold">
                            Payments
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('payments.categories') }}" class="nav-link {{ Request::is('payments/categories') ? 'brand-active' : '' }}">
                                <p>Set Fee Category</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('payments.fees') }}" class="nav-link {{ Request::is('payments/fees') ? 'brand-active' : '' }}">
                                <p>Set Fee</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('payments.index') }}" class="nav-link {{ Request::is('payments') ? 'brand-active' : '' }}">
                                <p>View Payments</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <p>Receipts</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                @if(Auth::user()->hasAnyRole(['superadmin','Chief Inspector', 'Inspector', 'PRO', 'DG', 'BC']))
                <li class="nav-item {{ Request::is('license/*')? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::is('license/*') ? 'brand-bg-color' : '' }}">
                        <i class="nav-icon fas fa-copy"></i>
                        <p class="fw-bold">
                            Licenses
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('license.all-original') }}" class="nav-link {{ Request::is('/license/original/view/all') ? 'brand-active' : '' }}">
                                <p class="fw-bold">Practicing Licenses</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('license.all-provisional') }}" class="nav-link {{ Request::is('license/provisional/view/all') ? 'brand-active' : '' }}">
                                <p class="fw-bold">Notices</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                @if(Auth::user()->hasAnyRole(['personnel']))
                    <li class="nav-item {{ Request::is('license/*')? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ Request::is('license/*') ? 'brand-bg-color' : '' }}">
                            <i class="nav-icon fas fa-copy"></i>
                            <p class="fw-bold">
                                Licenses
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('license.mylicense', auth()->user()->id) }}" class="nav-link">
                                    <p>Practicing License</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <p>Notice</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                <li class="nav-item {{ Request::is('exams/*') || Request::is('personnel/register/exam/*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::is('exams/*') ? 'brand-bg-color' : '' }}">
                        <i class="nav-icon fas fa-user-graduate"></i>
                        <p class="fw-bold">
                            Exams
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if(Auth::user()->hasAnyRole(['superadmin', 'Chief Inspector', 'Inspector', 'PRO', 'DG', 'BC']))
{{--                            @if(Auth::user()->can('view subjects'))--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="{{ route('exams.courses') }}" class="nav-link">--}}
{{--                                    <p>Subjects</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            @endif--}}
                            @if(Auth::user()->can('view subjects') || Auth::user()->can('assign subjects'))
                            <li class="nav-item">
                                <a href="{{ route('exams.subjects') }}" class="nav-link">
                                    <p>Subjects</p>
                                </a>
                            </li>
                            @endif
                            @if(Auth::user()->can('view exams'))
                            <li class="nav-item">
                                <a href="{{ route('exams.list') }}" class="nav-link">
                                    <p>Exam List</p>
                                </a>
                            </li>
                            @endif
                            @if(Auth::user()->hasAnyRole(['superadmin', 'Chief Inspector']))
                            <li class="nav-item">
                                <a href="{{ route('exams.upload-view') }}" class="nav-link">
                                    <p>Upload Grades</p>
                                </a>
                            </li>
                            @endif
                        @endif
                        @if(Auth::user()->hasAnyRole(['personnel']))
                            <li class="nav-item">
                                <a href="{{ route('personnel.register_exam', auth()->user()->id) }}" class="nav-link">
                                    <p>Register for exam</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('mysubjects') }}" class="nav-link">
                                    <p>Subjects</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('results.myresults') }}" class="nav-link">
                                    <p>Results</p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>

                @if(Auth::user()->hasAnyRole(['superadmin']))
                    <li class="nav-header fs-5">System Management</li>
                    <li class="nav-item">
                        <a href="{{ route('users.index') }}" class="nav-link {{ Request::is('users') || Request::is('users/*') ? 'brand-bg-color' : '' }}">
                            <i class="nav-icon fa fa-users"></i>
                            <p class="fw-bold">
                                Users
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('trails.index') }}" class="nav-link {{ Request::is('trails') ? 'brand-bg-color' : '' }}">
                            <i class="nav-icon fa fa-history"></i>
                            <p class="fw-bold">
                                Audit Trails
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('configs.index') }}" class="nav-link {{Request::is('configs') || Request::is('configs/*') || Request::is('configs/roles/*') ? 'brand-bg-color' : '' }}">
                            <i class="nav-icon fa fa-cog"></i>
                            <p class="fw-bold">
                                Configurations
                            </p>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
