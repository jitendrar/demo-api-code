<?php 
$toggleFlag = \session()->get('toggleFlag');
$toggleClickFlag2   = ($toggleFlag == 1)?'page-sidebar-menu-closed':'';
?>
<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <div class="page-logo">
        </div>
        <ul class="page-sidebar-menu page-header-fixed {{ $toggleClickFlag2 }}" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px" id="menu-list-ul">
            <li class="sidebar-toggler-wrapper hide">
                <div class="sidebar-toggler">
                    <span></span>
                </div>
            </li>
            <li class="nav-item active">
                <a href="{{ route('admin-dashboard')}}" class="nav-link nav-toggle">
                    <i class=""></i>
                    <span class="title">
                       dashboard
                    </span>
                </a>
            </li>
            <li class="nav-item active">
                <a href="{{ route('admin-profile')}}" class="nav-link nav-toggle">
                    <i class=""></i>
                    <span class="title">
                       My Profile
                    </span>
                </a>
            </li>
            <li class="nav-item active">
                <a href="{{ route('users.index')}}" class="nav-link nav-toggle">
                    <i class=""></i>
                    <span class="title">
                       Users
                    </span>
                </a>
            </li>
            <li class="nav-item active">
                <a href="{{ route('orders.index')}}" class="nav-link nav-toggle">
                    <i class=""></i>
                    <span class="title">
                       Orders
                    </span>
                </a>
            </li>
            <li class="nav-item active">
                <a href="{{ route('products.index')}}" class="nav-link nav-toggle">
                    <i class=""></i>
                    <span class="title">
                       Products
                    </span>
                </a>
            </li> 
            <li class="nav-item active">
                <a href="{{ route('categories.index')}}" class="nav-link nav-toggle">
                    <i class=""></i>
                    <span class="title">
                       Category List
                    </span>
                </a>
            </li>
        </ul>
    </div>
</div>
 