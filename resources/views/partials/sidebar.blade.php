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
            <li class="nav-item">
                <a href="{{ route('generation') }}" class="nav-link text-white hover-active">
                    <i class="bi bi-clock-history me-2"></i> Generation
                </a>
            </li>
            <li>
                <a href="{{ route('student') }}" class="nav-link text-white hover-active">
                    <i class="bi bi-person-lines-fill me-2"></i> Student
                </a>
            </li>
            <li>
                <a href="#" class="nav-link text-white hover-active">
                    <i class="bi bi-calendar-week me-2"></i> Term
                </a>
            </li>
            <li>
                <a href="#" class="nav-link text-white hover-active">
                    <i class="bi bi-table me-2"></i> Grid
                </a>
            </li>
            <li>
                <a href="{{ route('class') }}" class="nav-link text-white hover-active">
                    <i class="bi bi-door-closed me-2"></i> Class
                </a>
            </li>
            <li>
                <a href="{{ route('subject') }}" class="nav-link text-white hover-active">
                    <i class="bi bi-person-badge me-2"></i> Subject
                </a>
            </li>
            <li>
                <a href="{{ route('teacher') }}" class="nav-link text-white hover-active">
                    <i class="bi bi-person-badge me-2"></i> Teacher
                </a>
            </li>
        </ul>
    </div>
</div>
