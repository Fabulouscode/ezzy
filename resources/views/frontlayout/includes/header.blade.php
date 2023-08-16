<nav class="navbar navbar-expand-md navbar-light navbar-area">
    <div class="container">
        <a class="navbar-brand" href="{{route('home')}}">
            <img src="{{ asset('frontend/img/logo.png') }}" class="img-fluid" alt="logo" />
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{route('home')}}#home">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('home')}}#aboutus">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('home')}}#features">Features</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('home')}}#screenshots">Screenshots</a>
                </li>
                <li class="nav-item">
                    <a href="{{route('doctors')}}" class="nav-link" >Our Care Providers</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('happy_clients')}}">Happy Clients</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('home')}}#contactus">Contact Us</a>
                </li>
            </ul>
            <div class="navbar-btn">
                <a href="#apps" class="download-appbtn">Download App</a>
            </div>
        </div>
    </div>
</nav>