<div class="left side-menu">
    <button type="button" class="button-menu-mobile button-menu-mobile-topbar open-left waves-effect">
        <i class="ion-close"></i>
    </button>

    <div class="left-side-logo d-block d-lg-none">
        <div class="text-center">
            
            <a href="index.html" class="logo"><img src="{{ asset('admin/images/logo-dark.png') }}" height="20" alt="logo"></a>
        </div>
    </div>

    <div class="sidebar-inner slimscrollleft">
        
        <div id="sidebar-menu">
            <ul>
                <li class="menu-title">Main</li>

                <li>
                    <a href="{{url('/')}}" class="waves-effect">
                        <i class="dripicons-meter"></i>
                        <span> Dashboard <span class="badge badge-success badge-pill float-right">3</span></span>
                    </a>
                </li>
                <li>
                    <a href="{{url('/category')}}" class="waves-effect">
                        <i class="dripicons-meter"></i>
                        <span> Category <span class="badge badge-success badge-pill float-right">3</span></span>
                    </a>
                </li>
                <li>
                    <a href="{{url('/user')}}" class="waves-effect">
                        <i class="dripicons-meter"></i>
                        <span> User <span class="badge badge-success badge-pill float-right">3</span></span>
                    </a>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-briefcase"></i> <span>Health Care Provider</span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{url('/')}}">Dashboard</a></li>
                        <li><a href="{{url('/user/healthcare/pending')}}">Pending HCP</a></li>
                        <li><a href="{{url('/user/healthcare')}}">Approved HCP</a></li>
                        <li><a href="">Patients</a></li>
                    </ul>
                </li>
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-briefcase"></i> <span> Pharmacy </span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{url('/')}}">Dashboard</a></li>
                        <li><a href="{{url('/user/pharmacy/pending')}}">Pending Pharmacist</a></li>
                        <li><a href="{{url('/user/pharmacy')}}">Pharmacist List</a></li>
                        <li><a href="">Orders</a></li>
                        <li><a href="">Manage Pharmacy</a></li>
                    </ul>
                </li>
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-briefcase"></i> <span> Laboratories </span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{url('/')}}">Dashboard</a></li>
                        <li><a href="{{url('/user/laboratories/pending')}}">Pending Laboratories</a></li>
                        <li><a href="{{url('/user/laboratories')}}">Laboratories List</a></li>
                        <li><a href="">Orders</a></li>
                        <li><a href="">Manage Lab Reports</a></li>
                    </ul>
                </li>
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-briefcase"></i> <span> Appointments </span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                    <ul class="list-unstyled">
                        <li><a href="">Upcoming</a></li>
                        <li><a href="">Completed</a></li>
                    </ul>
                </li>
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-briefcase"></i> <span> Payout </span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                    <ul class="list-unstyled">
                        <li><a href="">Pending Payout</a></li>
                        <li><a href="">Approved Payout</a></li>
                    </ul>
                </li>
                <li>
                    <a href="" class="waves-effect">
                        <i class="dripicons-meter"></i>
                        <span> Static Pages </span>
                    </a>
                </li>
                <li>
                    <a href="" class="waves-effect">
                        <i class="dripicons-meter"></i>
                        <span> HCP Types </span>
                    </a>
                </li>
                <li>
                    <a href="" class="waves-effect">
                        <i class="dripicons-meter"></i>
                        <span> Manage Fees </span>
                    </a>
                </li>
                <li>
                    <a href="" class="waves-effect">
                        <i class="dripicons-meter"></i>
                        <span> Discount Code </span>
                    </a>
                </li>
                <li>
                    <a href="" class="waves-effect">
                        <i class="dripicons-meter"></i>
                        <span> Notifications </span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="clearfix"></div>
    </div> <!-- end sidebarinner -->
</div>