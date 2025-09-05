<nav class="navbar navbar-light bg-primary d-md-none">
    <div class="container-fluid">
        <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<div class="collapse d-md-block sidebar" id="sidebarMenu">
    <div class="sidebar-wrapper d-flex flex-column justify-content-center align-items-center py-4">
        <a href="/">
            <img src="https://avpn.asia/wp-content/uploads/2024/02/PN-Round-Logo1.png" alt="User"
                class="rounded-circle bg-secondary mb-3" width="80" height="80">
        </a>
        <ul class="nav nav-pills flex-column text-center justify-center w-100">

            @can('view grid')
                <li>
                    <a href="{{ route('grid-types.latest') }}"
                        class="nav-link text-white nav-link-hover {{ request()->routeIs('grid-types.index') ? 'active' : '' }}">
                        <i class="bi bi-grid me-2"></i> Assessment
                    </a>
                </li>
            @endcan
            @can('view evaluation')
                <li>
                    <a href="{{ route('evaluations.index') }}"
                        class="nav-link text-white nav-link-hover {{ request()->routeIs('evaluations.index') ? 'active' : '' }}">
                        <i class="bi bi-graph-up me-2"></i> Evaluation
                    </a>
                </li>
            @endcan
            {{-- @can('view grid') --}}
            <li>
                <a href="{{ route('transcript.index') }}"
                    class="nav-link text-white nav-link-hover {{ request()->routeIs('transcript.index') ? 'active' : '' }}">
                    <i class="bi bi-file-text me-2"></i> Transcript
                </a>
            </li>

            {{-- @endcan --}}
            @can('view generation')
                <li class="nav-item">
                    <a href="{{ route('generation') }}"
                        class="nav-link nav-link-hover text-white {{ request()->routeIs('generation') ? 'active' : '' }}">
                        <i class="bi bi-mortarboard-fill me-2"></i> Generation
                    </a>
                </li>
            @endcan

            @can('view term')
                <li>
                    <a href="{{ route('term.index') }}"
                        class="nav-link text-white nav-link-hover {{ request()->routeIs('term.index') ? 'active' : '' }}">
                        <i class="bi bi-calendar-event me-2"></i> Term
                    </a>
                </li>
            @endcan
            @can('view class')
                <li>
                    <a href="{{ route('class') }}"
                        class="nav-link text-white nav-link-hover {{ request()->routeIs('class') ? 'active' : '' }}">
                        <i class="bi bi-people-fill me-2"></i> Class
                    </a>
                </li>
            @endcan
            @can('view subject')
                <li>
                    <a href="{{ route('subject') }}"
                        class="nav-link text-white nav-link-hover {{ request()->routeIs('subject') ? 'active' : '' }}">
                        <i class="bi bi-book me-2"></i> Subject
                    </a>
                </li>
            @endcan


            @can('view student')
                <li>
                    <a href="{{ route('student') }}"
                        class="nav-link text-white nav-link-hover {{ request()->routeIs('student') ? 'active' : '' }}">
                        <i class="bi bi-person-circle me-2"></i> Student
                    </a>
                </li>
            @endcan
            @can('view teacher')
                <li>
                    <a href="{{ route('teacher') }}"
                        class="nav-link text-white nav-link-hover {{ request()->routeIs('teacher') ? 'active' : '' }}">
                        <i class="bi bi-person-workspace me-2"></i> Teacher
                    </a>
                </li>
            @endcan
            <!-- Report -->
            <hr class="sidebar-divider d-none d-md-block border-white">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin-report', 'teacher-report', 'student-report') ? '' : 'collapsed' }}"
                    data-bs-toggle="collapse" href="#collapseReport" role="button"
                    aria-expanded="{{ request()->routeIs('admin-report', 'teacher-report', 'student-report') ? 'true' : 'false' }}"
                    aria-controls="collapseReport">
                    <i class="bi bi-file-earmark-text"></i> Report
                </a>
                <ul class="list-unstyled collapse {{ request()->routeIs('admin-report', 'teacher-report', 'student-report') ? 'show' : '' }}"
                    id="collapseReport" data-bs-parent="#accordionSidebar">

                    @can('view admin_report')
                        <li>
                            <a href="{{ route('admin-report') }}"
                                class="nav-link {{ request()->routeIs('admin-report') ? 'active' : '' }}"
                                style="font-size:15px; padding-left:29px;">
                                <i class="bi bi-clipboard-data me-2"></i> Admin
                            </a>
                        </li>
                    @endcan

                    @can('view teacher_report')
                        <li>
                            <a href="{{ route('teacher-report') }}"
                                class="nav-link {{ request()->routeIs('teacher-report') ? 'active' : '' }}"
                                style="font-size:15px; padding-left:29px;">
                                <i class="bi bi-file-earmark-text me-2"></i> Teacher
                            </a>
                        </li>
                    @endcan

                    @can('view student_report')
                        <li>
                            <a href="{{ route('student-report') }}"
                                class="nav-link {{ request()->routeIs('student-report') ? 'active' : '' }}"
                                style="font-size:15px; padding-left:29px;">
                                <i class="bi bi-mortarboard me-2"></i> Student
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>


            <!-- Settings -->
            <hr class="sidebar-divider d-none d-md-block border-white">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('role-list', 'user-list', 'logHistory-list') ? '' : 'collapsed' }}"
                    data-bs-toggle="collapse" href="#collapseTwo" role="button"
                    aria-expanded="{{ request()->routeIs('role-list', 'user-list', 'logHistory-list') ? 'true' : 'false' }}"
                    aria-controls="collapseTwo">
                    <i class="bi bi-gear-fill"></i> Settings
                </a>
                <ul class="list-unstyled collapse {{ request()->routeIs('role-list', 'user-list', 'logHistory-list') ? 'show' : '' }}"
                    id="collapseTwo" data-bs-parent="#accordionSidebar">

                    @can('view role')
                        <li>
                            <a href="{{ route('role-list') }}"
                                class="nav-link {{ request()->routeIs('role-list') ? 'active' : '' }}"
                                style="font-size:15px; padding-left:29px;">
                                <i class="bi bi-shield-lock me-2"></i> Roles
                            </a>
                        </li>
                    @endcan

                    @can('view system_user')
                        <li>
                            <a href="{{ route('user-list') }}"
                                class="nav-link {{ request()->routeIs('user-list') ? 'active' : '' }}"
                                style="font-size:15px; padding-left:29px;">
                                <i class="bi bi-person"></i> User
                            </a>
                        </li>
                    @endcan

                    @can('view loghistory')
                        <li>
                            <a href="{{ route('logHistory-list') }}"
                                class="nav-link {{ request()->routeIs('logHistory-list') ? 'active' : '' }}"
                                style="font-size:15px; padding-left:29px;">
                                <i class="bi bi-journal-text me-2"></i> History
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        </ul>
    </div>
</div>
