<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-absolute navbar-transparent">
    <div class="container-fluid">
        <div class="navbar-wrapper">
            <!-- Optional logo/title -->
        </div>

        <div class="collapse navbar-collapse justify-content-end" id="navigation">
            <ul class="navbar-nav align-items-center">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ Auth::user()->profile 
                                    ? asset('storage/' . Auth::user()->profile) 
                                    : asset('dashboard/img/anime3.png') }}" 
                            alt="Profile Photo"
                            class="rounded-circle me-2" width="40" height="40">
                        <span class="d-none d-lg-inline">{{ Auth::user()->username ?? 'Guest' }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li>
                            <a class="dropdown-item" href="">
                                <i class="bi bi-person me-2"></i> Profile
                            </a>
                        </li>
                        <li>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                        {{-- <li>
                            <form id="update-profile-form" 
                                action="{{ route('update-profile', Auth::user()->id) }}" 
                                method="POST" 
                                enctype="multipart/form-data" 
                                class="d-flex align-items-center gap-2 p-2">
                                @csrf
                                @method('patch')

                                <!-- File Input -->
                                <input type="file" 
                                    name="profile" 
                                    class="form-control form-control-sm" 
                                    accept="image/*">

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-sm btn-primary d-flex align-items-center">
                                    <i class="bi bi-camera me-1"></i> Update
                                </button>
                            </form>
                        </li> --}}
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
