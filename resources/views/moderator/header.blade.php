<header class="py-3 mb-3 border-bottom bg-light position-sticky top-0 z-3">
    <div class="container-fluid d-grid gap-3 align-items-center" style="grid-template-columns: 1fr 2fr;">
        <h5 class="text-nowrap"><?= $pageTitle ?></h5>
        <div class="d-flex align-items-center justify-content-end">
            <div class="me-3 d-none d-md-block">
                @if($userName)
                    Welcome <b><?= $userName ?></b>
                @endif
            </div>
            <div class="flex-shrink-0 dropdown">
                <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <img src="https://github.com/mdo.png" alt="mdo" width="25" height="25" class="rounded-circle">
                </a>
                <ul class="dropdown-menu text-small shadow">
                    <li><a class="dropdown-item" href="<?= $navLink['dasboard'] ?>">Dasboard</a></li>
                    <li><a class="dropdown-item" href="<?= $navLink['settings'] ?>">Settings</a></li>
                    <li><a class="dropdown-item" href="<?= $navLink['profile'] ?>">Profile</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="/logout">Sign out</a></li>
                </ul>
            </div>
        </div>
    </div>
</header>