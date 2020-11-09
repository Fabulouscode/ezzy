<div class="left side-menu d-print-none">
    <button type="button" class="button-menu-mobile button-menu-mobile-topbar open-left waves-effect">
        <i class="ion-close"></i>
    </button>

    <div class="left-side-logo d-block d-lg-none">
        <div class="text-center">
            
            <a href="index.html" class="logo"><img src="{{ asset('admin/images/logo-dark.png') }}" height="20" alt="logo"></a>
        </div>
    </div>

    <div class="sidebar-inner scroll-sidebar">
        
        <div id="sidebar-menu">
            <ul>
                <li>
                    <a href="{{url('/')}}" class="waves-effect">
                        <i class="dripicons-home"></i>
                        <span> Dashboard </span>
                    </a>
                </li>
                <li>
                    <a href="{{url('/admin/user')}}" class="waves-effect">
                        <i class="dripicons-list"></i>
                        <span> Admin List </span>
                    </a>
                </li>
                <li>
                    <a href="{{url('/patients/user')}}" class="waves-effect">
                        <i class="dripicons-document"></i>
                        <span> Patient List </span>
                    </a>
                </li>
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-heart"></i> <span>Health Care Provider</span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{url('/healthcare/dashboard')}}">Dashboard</a></li>
                        <li><a href="{{url('/healthcare/user/pending')}}">Pending HCP</a></li>
                        <li><a href="{{url('/healthcare/user')}}">Approved HCP</a></li>
                    </ul>
                </li>
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-box"></i> <span> Pharmacy </span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{url('/pharmacy/dashboard')}}">Dashboard</a></li>
                        <li><a href="{{url('/pharmacy/user/pending')}}">Pending Pharmacist</a></li>
                        <li><a href="{{url('/pharmacy/user')}}">Pharmacist List</a></li>
                        <li><a href="{{url('/pharmacy/order')}}">Orders</a></li>
                        <li><a href="{{url('/pharmacy/order/reviews')}}">Reviews</a></li>
                    </ul>
                </li>
                
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-medical"></i> <span> Laboratories </span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{url('/laboratories/dashboard')}}">Dashboard</a></li>
                        <li><a href="{{url('/laboratories/user/pending')}}">Pending Laboratories</a></li>
                        <li><a href="{{url('/laboratories/user')}}">Laboratories List</a></li>
                        <li><a href="javascript:void(0);">Manage Lab Reports</a></li>
                    </ul>
                </li>
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-clipboard"></i> <span> Appointments </span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{url('/appointment')}}">Upcoming</a></li>
                        <li><a href="{{url('/appointment/completed')}}">Completed</a></li>
                        <li><a href="{{url('/appointment/cancel')}}">Cancel</a></li>
                        <li><a href="{{url('/appointment/reviews')}}">Reviews</a></li>
                    </ul>
                </li>
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-card"></i> <span> Payout </span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                    <ul class="list-unstyled">
                        <li><a href="javascript:void(0);">Pending Payout</a></li>
                        <li><a href="javascript:void(0);">Approved Payout</a></li>
                    </ul>
                </li>
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-duplicate"></i> <span> Manage Pharmacy </span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{url('/medicine/categories')}}">Medicine Categories</a></li>
                        <li><a href="{{url('/medicine/subcategories')}}">Medicine Subcategories</a></li>
                        <li><a href="{{url('/medicine/details')}}">Medicine Details</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-gear"></i> <span> Setting </span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{url('/static_pages')}}">Static Pages</a></li>
                        <li><a href="{{url('/category')}}">Manage HCP</a></li>
                        <li><a href="{{url('/services')}}">Manage Services</a></li>
                        <li><a href="javascript:void(0);">Manage Fees</a></li>
                        <li><a href="javascript:void(0);">Voucher Code</a></li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:void(0);" class="waves-effect">
                        <i class="dripicons-bell"></i>
                        <span> Notifications </span>
                    </a>
                </li>
                <li>
                    <a href="{{url('/support_request')}}" class="waves-effect">
                        <i class="dripicons-headset"></i>
                        <span> Support Ticket </span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="clearfix"></div>
    </div> <!-- end sidebarinner -->
</div>