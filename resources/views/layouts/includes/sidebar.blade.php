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

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-briefcase"></i> <span>Health Care Provider</span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{url('/healthcare/dashboard')}}">Dashboard</a></li>
                        <li><a href="{{url('/healthcare/users/pending')}}">Pending HCP</a></li>
                        <li><a href="{{url('/healthcare/users')}}">Approved HCP</a></li>
                    </ul>
                </li>
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-briefcase"></i> <span> Pharmacy </span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{url('/pharmacy/dashboard')}}">Dashboard</a></li>
                        <li><a href="{{url('/pharmacy/users/pending')}}">Pending Pharmacist</a></li>
                        <li><a href="{{url('/pharmacy/users')}}">Pharmacist List</a></li>
                        <li><a href="javascript:void(0);">Orders</a></li>
                        <li><a href="javascript:void(0);">Manage Pharmacy</a></li>
                    </ul>
                </li>
                
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-briefcase"></i> <span> Laboratories </span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{url('/laboratories/dashboard')}}">Dashboard</a></li>
                        <li><a href="{{url('/laboratories/users/pending')}}">Pending Laboratories</a></li>
                        <li><a href="{{url('/laboratories/users')}}">Laboratories List</a></li>
                        <li><a href="javascript:void(0);">Orders</a></li>
                        <li><a href="javascript:void(0);">Manage Lab Reports</a></li>
                    </ul>
                </li>
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-briefcase"></i> <span> Appointments </span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{url('/appointment')}}">Upcoming</a></li>
                        <li><a href="{{url('/appointments/completed')}}">Completed</a></li>
                        <li><a href="{{url('/appointments/cancel')}}">Cancel</a></li>
                    </ul>
                </li>
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-briefcase"></i> <span> Payout </span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                    <ul class="list-unstyled">
                        <li><a href="javascript:void(0);">Pending Payout</a></li>
                        <li><a href="javascript:void(0);">Approved Payout</a></li>
                    </ul>
                </li>
                <li>
                    <a href="{{url('/users/patients')}}" class="waves-effect">
                        <i class="dripicons-meter"></i>
                        <span> Patients </span>
                    </a>
                </li>
                <li>
                    <a href="javascript:void(0);" class="waves-effect">
                        <i class="dripicons-meter"></i>
                        <span> Static Pages </span>
                    </a>
                </li>
                <li>
                    <a href="{{url('/category')}}" class="waves-effect">
                        <i class="dripicons-meter"></i>
                        <span> HCP Types </span>
                    </a>
                </li>
                <li>
                    <a href="javascript:void(0);" class="waves-effect">
                        <i class="dripicons-meter"></i>
                        <span> Manage Fees </span>
                    </a>
                </li>
                <li>
                    <a href="javascript:void(0);" class="waves-effect">
                        <i class="dripicons-meter"></i>
                        <span> Discount Code </span>
                    </a>
                </li>
                <li>
                    <a href="javascript:void(0);" class="waves-effect">
                        <i class="dripicons-meter"></i>
                        <span> Notifications </span>
                    </a>
                </li>
                <li>
                    <a href="{{url('/support_request')}}" class="waves-effect">
                        <i class="dripicons-meter"></i>
                        <span> Support Request </span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="clearfix"></div>
    </div> <!-- end sidebarinner -->
</div>