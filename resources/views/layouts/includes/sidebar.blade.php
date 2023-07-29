<div class="left side-menu d-print-none">
    <button type="button" class="button-menu-mobile button-menu-mobile-topbar open-left waves-effect">
        <i class="ion-close"></i>
    </button>

    <div class="left-side-logo d-block d-lg-none">
        <div class="text-center">
            <a href="index.html" class="logo"><img src="{{ asset('admin/images/logo.png') }}" height="50" alt="logo"></a>
        </div>
    </div>

    <div class="sidebar-inner scroll-sidebar">
        
        <div id="sidebar-menu">
            <ul>
                <li>
                    <a href="{{url('/donotezzycaretouch')}}" class="waves-effect">
                        <i class="dripicons-home"></i>
                        <span> Dashboard </span>
                    </a>
                </li>
                @can('admin-list')
                <li>
                    <a href="{{url('/donotezzycaretouch/admin/users')}}" class="waves-effect">
                        <i class="dripicons-list"></i>
                        <span> Admin List </span>
                    </a>
                </li>
                @endcan
                @can('patients-list')
                <li>
                    <a href="{{url('/donotezzycaretouch/customer/patient')}}" class="waves-effect">
                        <i class="dripicons-document"></i>
                        <span> Patient List </span>
                    </a>
                </li>
                @endcan
                @if(!empty(Auth::user()) && Auth::user()->hasMultiplePermissionTo('healthcare-dashboard','healthcare-list'))
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect">
                        <i class="dripicons-heart"></i> <span>Healthcare Provider</span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span>
                        <span id="HealthcareProviderPendingCount" class="badge_count_side_menu_with_sub float-right">0</span>
                    </a>
                    <ul class="list-unstyled">
                        @can('healthcare-dashboard')
                        <li><a href="{{url('/donotezzycaretouch/healthcare/dashboard')}}">Dashboard</a></li>
                        @endcan
                        @can('healthcare-dashboard')
                        <li><a href="{{url('/donotezzycaretouch/healthcare/doctor/dashboard')}}">Doctor Dashboard</a></li>
                        @endcan
                        @can('healthcare-list')
                        <li><a href="{{url('/donotezzycaretouch/healthcare/user/pending')}}">Pending HCP</a></li>
                        @endcan
                        @can('healthcare-list')
                        <li><a href="{{url('/donotezzycaretouch/healthcare/user')}}">Approved HCP</a></li>
                        @endcan
                    </ul>
                </li>
                @endif
                @if(!empty(Auth::user()) && Auth::user()->hasMultiplePermissionTo('pharmacy-list','pharmacy-list','order-list','order-review'))
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect">
                        <i class="dripicons-box"></i> <span> Pharmacy </span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span>
                        <span id="PharmacyPendingCount" class="badge_count_side_menu_with_sub float-right">0</span>
                    </a>
                    <ul class="list-unstyled">
                        @can('pharmacy-dashboard')
                        <li><a href="{{url('/donotezzycaretouch/pharmacy/dashboard')}}">Dashboard</a></li>
                        @endcan
                        @can('pharmacy-list')
                        <li><a href="{{url('/donotezzycaretouch/pharmacy/user/pending')}}">Pending Pharmacist</a></li>
                        @endcan
                        @can('pharmacy-list')
                        <li><a href="{{url('/donotezzycaretouch/pharmacy/user')}}">Approved Pharmacist</a></li>
                        @endcan
                        @can('order-list')
                        <li><a href="{{url('/donotezzycaretouch/pharmacy/order')}}">Orders</a></li>
                        @endcan
                        @can('order-review')
                        <li><a href="{{url('/donotezzycaretouch/pharmacy/order/reviews')}}">Reviews</a></li>
                        @endcan
                    </ul>
                </li>
                @endif
                @if(!empty(Auth::user()) && Auth::user()->hasMultiplePermissionTo('laboratories-dashboard','laboratories-list'))
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect">
                        <i class="dripicons-medical"></i> <span> Laboratories </span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span>
                        <span id="LaboratoriesPendingCount" class="badge_count_side_menu_with_sub float-right">0</span>
                    </a>
                    <ul class="list-unstyled">
                        @can('laboratories-dashboard')
                        <li><a href="{{url('/donotezzycaretouch/laboratories/dashboard')}}">Dashboard</a></li>
                        @endcan
                        @can('laboratories-list')
                        <li><a href="{{url('/donotezzycaretouch/laboratories/user/pending')}}">Pending Laboratories</a></li>
                        @endcan
                        @can('laboratories-list')
                        <li><a href="{{url('/donotezzycaretouch/laboratories/user')}}">Approved Laboratories</a></li>
                        @endcan
                        <!-- @can('laboratories-list')
                        <li><a href="javascript:void(0);">Manage Lab Reports</a></li>
                        @endcan -->
                    </ul>
                </li>
                @endif
                @if(!empty(Auth::user()) && Auth::user()->hasMultiplePermissionTo('appointments-list','appointments-review'))
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-clipboard"></i> 
                        <span> Appointments </span> 
                        <span class="menu-arrow float-right">
                            <i class="mdi mdi-chevron-right"></i>
                        </span>                        
                        <span id="AppointmentPendingCount" class="badge_count_side_menu_with_sub float-right">0</span>
                    </a>
                    <ul class="list-unstyled">
                        @can('appointments-list')
                        <li class="d-flex">
                            <a href="{{url('/donotezzycaretouch/appointment/upcoming')}}">
                                Upcoming
                            </a>                            
                            <!-- <span id="AppointmentPendingCount" class="badge_count_side_menu_child">0</span> -->
                        </li>
                        @endcan
                        @can('appointments-list')
                        <li><a href="{{url('/donotezzycaretouch/appointment')}}">Completed</a></li>
                        @endcan
                        @can('appointments-list')
                        <li><a href="{{url('/donotezzycaretouch/appointment/cancel')}}">Cancel</a></li>
                        @endcan
                        @can('appointments-review')
                        <li><a href="{{url('/donotezzycaretouch/appointment/reviews')}}">Reviews</a></li>
                        @endcan
                    </ul>
                </li>
                @endif
                @if(!empty(Auth::user()) && Auth::user()->hasMultiplePermissionTo('payout-list'))
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-card"></i> <span> Payout </span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                    <ul class="list-unstyled">
                        @can('payout-list')
                        <li><a href="{{url('/donotezzycaretouch/payout/pending')}}">Pending Payout</a></li>
                        @endcan
                        @can('payout-list')
                        <li><a href="{{url('/donotezzycaretouch/payout')}}">Approved Payout</a></li>
                        @endcan
                        @can('payout-list')
                        <li><a href="{{url('/donotezzycaretouch/transaction/list')}}">Transaction List</a></li>
                        @endcan
                    </ul>
                </li>
                @endif
                @if(!empty(Auth::user()) && Auth::user()->hasMultiplePermissionTo('medicine_category-list','medicine_subcategory-list','medicine_details-list'))
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-duplicate"></i> <span> Manage Pharmacy </span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                    <ul class="list-unstyled">
                        @can('medicine_category-list')
                        <li><a href="{{url('/donotezzycaretouch/medicine/categories')}}">Medicine Categories</a></li>
                        @endcan
                        <!-- @can('medicine_subcategory-list')
                        <li><a href="{{url('/donotezzycaretouch/medicine/subcategories')}}">Medicine Subcategories</a></li>
                        @endcan -->
                        @can('medicine_details-list')
                        <li><a href="{{url('/donotezzycaretouch/medicine/details')}}">Medicine Details</a></li>
                        @endcan
                    </ul>
                </li>
                @endif
                @if(!empty(Auth::user()) && Auth::user()->hasMultiplePermissionTo('static_page-list','hcp_type-list','services-list','fees-list','voucher_code-list'))
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-gear"></i> <span> Setting </span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                    <ul class="list-unstyled">
                        @can('static_page-list')
                        <li><a href="{{url('/donotezzycaretouch/static_pages')}}">Static Pages</a></li>
                        @endcan
                        @can('hcp_type-list')
                        <li><a href="{{url('/donotezzycaretouch/category')}}">Manage HCP</a></li>
                        @endcan
                        <!-- <li><a href="{{url('/donotezzycaretouch/service_usage')}}">Services Usage</a></li> -->
                        @can('services-list')
                        <li><a href="{{url('/donotezzycaretouch/services')}}">Manage Services</a></li>
                        @endcan
                        @can('fees-list')
                        <li><a href="{{url('/donotezzycaretouch/manage_fees')}}">Manage Fees</a></li>
                        @endcan
                        @can('voucher_code-list')
                        <li><a href="{{url('/donotezzycaretouch/voucher_code')}}">Voucher Code</a></li>
                        @endcan
                        @can('medical_category-list')
                        <li><a href="{{url('/donotezzycaretouch/medical_category')}}">Medical Category</a></li>
                        @endcan
                        @can('medical_item-list')
                        <li><a href="{{url('/donotezzycaretouch/medical_item')}}">Medical Item</a></li>
                        @endcan
                    </ul>
                </li>
                @endif
                @if(!empty(Auth::user()) && Auth::user()->hasMultiplePermissionTo('role-list'))
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-gear"></i> <span> Admin Setting </span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                    <ul class="list-unstyled">
                        @can('permission_category-list')
                        <!-- <li><a href="{{url('/donotezzycaretouch/permission_category')}}">Permission Category</a></li> -->
                        @endcan
                        @can('permission-list')
                        <!-- <li><a href="{{url('/donotezzycaretouch/permission')}}">Permission</a></li> -->
                        @endcan
                        @can('role-list')
                        <li><a href="{{url('/donotezzycaretouch/role')}}">Role</a></li>
                        @endcan                        
                        @can('app_version-list')
                        <li><a href="{{url('/donotezzycaretouch/app_version')}}">App Version</a></li>
                        @endcan
                        @can('app_setting-list')
                        <li><a href="{{url('/donotezzycaretouch/app_setting')}}">App Setting</a></li>
                        @endcan
                        @if(!empty(Auth::user()->role_id == '1'))
                        <li><a href="{{url('/donotezzycaretouch/user_trackings')}}">User Trackings</a></li>
                        @endif
                    </ul>
                </li>
                @endif
                @can('notification-list')
                <li>
                    <a href="{{url('/donotezzycaretouch/notifications')}}" class="waves-effect">
                        <i class="dripicons-bell"></i>
                        <span> Notifications </span>
                    </a>
                </li>
                @endcan
                @can('support_ticket-list')
                <li>
                    <a href="{{url('/donotezzycaretouch/support_request')}}" class="waves-effect d-flex">
                        <i class="dripicons-headset"></i>
                        <span> Support Ticket </span>
                        <span id="SupportPendingTicketCount" class="badge_count_side_menu">0</span>
                    </a>
                </li>
                @endcan
                <li>
                    <a href="{{url('/donotezzycaretouch/contact_form')}}" class="waves-effect">
                        <i class="dripicons-list"></i>
                        <span> Contact Form </span>
                    </a>
                </li>
                @can('admin_activity-list')
                <li>
                    <a href="{{url('/donotezzycaretouch/admin_activity')}}" class="waves-effect">
                        <i class="dripicons-list"></i>
                        <span> Admin Activity </span>
                    </a>
                </li>
                @endif
            </ul>
        </div>
        <div class="clearfix"></div>
    </div> <!-- end sidebarinner -->
</div>