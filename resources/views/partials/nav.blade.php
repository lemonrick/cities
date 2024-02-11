<div class="container">
    <nav class="navbar bg-body-tertiary d-none d-sm-none d-md-none d-xl-block d-xxl-block">
        <div class="container-fluid">
            <a class="navbar-brand" href="/"><img class="logo-ui" src="{{ asset('img/logo.png') }}"></a>
            <form class="d-flex" role="search">
                <div class="nav-text-ui">Kontakty&nbsp;a&nbsp;čísla&nbsp;na&nbsp;oddelenia&nbsp;</div>
                <div class="dropdown">
                    <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        SK
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">SK</a></li>
                        <li><a class="dropdown-item" href="#">EN</a></li>
                    </ul>
                </div>
                <input class="form-control me-2" type="search" placeholder="Hľadať..." aria-label="Search">
                <button class="btn btn-success" type="submit">Prihlásenie</button>
            </form>
        </div>
    </nav>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand d-block d-sm-block d-md-block d-xl-none d-xxl-none" href="/"><img class="logo-ui" src="{{ asset('img/logo.png') }}"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link nav-title-ui" href="#">O nás</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-title-ui" href="#">Zoznam miest</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-title-ui" href="#">Inšpekcia</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-title-ui" href="#">Kontakt</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>
