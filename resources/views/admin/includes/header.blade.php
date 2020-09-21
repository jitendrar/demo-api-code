<?php
$authUser = \Auth::guard('admins')->user(); 
$authName = '';
$formObj = $authUser->id;
if($authUser)
{
    if(!empty(($authUser->first_name)))
    {
        $authName =$authUser->first_name .' '.$authUser->last_name;          
    }
}
?>
<div class="page-wrapper-row">
    <div class="page-wrapper-top">
        <!-- BEGIN HEADER -->
        <div class="page-header">
            <!-- BEGIN HEADER TOP -->
            <div class="page-header-top">
                <div class="container">
                    <!-- BEGIN LOGO -->
                    <div class="page-logo">
                        <br>
                       <h5><b>Vegetable App</b></h5>
                    </div>
                    <!-- END LOGO -->
                    <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                    <a href="javascript:;" class="menu-toggler"></a>
                    <!-- END RESPONSIVE MENU TOGGLER -->
                    <!-- BEGIN TOP NAVIGATION MENU -->
                    <div class="top-menu">
                        <ul class="nav navbar-nav pull-right">
                            <li class="dropdown dropdown-user dropdown-dark">
                                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                    <img alt="{{ $authName }}" class="img-circle" src="{{ asset('images/default-medium.png') }}" />
                                    <span class="username username-hide-on-mobile"> {{ ucfirst($authName) }} </span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-default">
                                    <li>
                                        <a href="{{ route('admin-profile') }}">
                                            <i class="icon-user"></i> My Profile </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('logout') }}">
                                            <i class="icon-key"></i> Log Out </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="dropdown dropdown-quick-sidebar-toggler">
                                    <a href="{{ route('logout') }}" class="dropdown-toggle">
                                      <i class="icon-logout"></i>
                                          </a>
                            </li>
                        </ul>
                    </div>
                    <!-- END TOP NAVIGATION MENU -->
                </div>
            </div>
            <!-- END HEADER TOP -->
            <!-- BEGIN HEADER MENU -->
            <div class="page-header-menu">
                <div class="container">
                    <div class="hor-menu  ">
                        <ul class="nav navbar-nav">
                            <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown ">
                                <a href="{{ route('admin-dashboard')}}"> Dashboard
                                    <span class="arrow"></span>
                                </a>
                            </li>
                            <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown ">
                            <a href="{{ route('users.index')}}"> Users
                                <span class="arrow"></span>
                            </a>
                            </li>
                            <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown ">
                            <a href="{{ route('orders.index')}}"> Orders
                                <span class="arrow"></span>
                            </a>
                            </li> 
                            <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown ">
                            <a href="{{ route('categories.index')}}"> Category
                                <span class="arrow"></span>
                            </a>
                            </li>
                            <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown ">
                            <a href="{{ route('products.index')}}"> Products
                                <span class="arrow"></span>
                            </a>
                            </li>
                        </ul>
                    </div>
                    <!-- END MEGA MENU -->
                </div>
            </div>
            <!-- END HEADER MENU -->
        </div>
        <!-- END HEADER -->
    </div>
</div>