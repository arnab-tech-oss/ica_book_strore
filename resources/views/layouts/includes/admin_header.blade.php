<?php

$site_title = 'Indian Chamber Of Commerce - Trade Desk Portal';
$site_blogo = 'https://www.indianchamber.org/wp-content/themes/icc/images/logo.png';
$site_slogo = 'https://www.indianchamber.org/wp-content/uploads/2022/07/fav_logo.png';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title><?php echo $site_title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- Favicon -->
    <link href="{{ asset('assets/img/fav_logo.png') }}" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('assets/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css') }}" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('assets/css/admin-style.css') }}" rel="stylesheet">

</head>

<body>
    <div class="container position-relative d-flex p-0">


        <!-- Spinner Start -->
        <div id="spinner"
            class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-grow text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->


        <!-- Sidebar Start -->
        <div class="sidebar pb-3">
            <nav class="navbar navbar-light">
                <a href="{{ url('/home') }}" class="navbar-brand mx-4 mb-3">

                    <!-- <h3 class="text-primary"><img class="img-fluid" src="<?php echo $site_slogo; ?>" /></h3> -->
                    <h3 class="text-primary"><img class="img-fluid" src="{{ asset('img/fav_logo.png') }}" /></h3>

                </a>
                <div class="w-100 text-center ms-4 mb-4">
                    <div class="">
                        <h6 class="mb-0 text-primary"><a href="#">ICC Trade Desk V1.0</a></h6>
                    </div>
                </div>
                <div class="navbar-nav w-100">
                    <div class="navbar-nav w-100">
                        <!-- @can('dashboard_access') -->
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.home') }}"
                                class="nav-link {{ request()->is('admin') || request()->is('admin') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-fw fa-tachometer-alt">

                                </i>
                                {{ trans('global.dashboard') }}
                            </a>
                            @endif
                        <!-- @endcan
                        @can('user_dashboard_access') -->
                        @if(!auth()->user()->isAdmin())
                            <a href="{{ route('admin.user') }}"
                                class="nav-link {{ request()->is('admin') || request()->is('admin') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-fw fa-tachometer-alt">

                                </i>
                                {{ trans('global.dashboard') }}
                            </a>
                            @endif
                        <!-- @endcan -->
                        <!--                        || \Gate::check('media_access')-->
                        @if (\Gate::check('cms_access') || \Gate::check('pages_access'))
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle
                                {{ Request::routeIs('admin.faq.index') ? 'show' : '' }}
                                {{ Request::routeIs('admin.partners.index') ? 'show' : '' }}
                                {{ Request::routeIs('admin.pages.index') ? 'show' : '' }}
                                 {{ Request::routeIs('admin.testimonials.index') ? 'show' : '' }}
                                {{ Request::routeIs('contact.index') ? 'active' : '' }}
                                " data-bs-toggle="dropdown"><i
                                        class="far fa-file-alt me-2"></i>CMS</a>
                                <div
                                    class="dropdown-menu bg-transparent border-0  {{ Request::routeIs('admin.caseStudies.index') ? 'show' : '' }}
                                    {{ Request::routeIs('admin.faq.index') ? 'show' : '' }}
                                   {{ Request::routeIs('admin.partners.index') ? 'show' : '' }}
                                   {{ Request::routeIs('admin.pages.index') ? 'show' : '' }}
                                    {{ Request::routeIs('admin.testimonials.index') ? 'show' : '' }}
                                   {{ Request::routeIs('contact.index') ? 'active' : '' }}">
                                    @can('pages_show')
                                        <a href="{{ route('admin.caseStudies.index') }}"
                                            class="dropdown-item nav-link {{ Request::routeIs('admin.caseStudies.index') ? 'active' : '' }}"><i class="fas fa-suitcase"></i> Case
                                            Studies</a>
                                        <a href="{{ route('admin.faq.index') }}"
                                            class="dropdown-item nav-link {{ Request::routeIs('admin.faq.index') ? 'active' : '' }}"><i class="fas fa-exclamation-circle"></i> Faqs</a>
                                        <a href="{{ route('admin.partners.index') }}"
                                            class="dropdown-item nav-link {{ Request::routeIs('admin.partners.index') ? 'active' : '' }}"> <i class="fas fa-handshake"></i> Partners</a>
                                        <a href="{{ route('admin.pages.index') }}"
                                            class="dropdown-item nav-link {{ Request::routeIs('admin.pages.index') ? 'active' : '' }}"><i class="fas fa-file-alt"></i> Pages</a>
                                        <a href="{{ route('admin.testimonials.index') }}"
                                            class="dropdown-item nav-link {{ Request::routeIs('admin.testimonials.index') ? 'active' : '' }}"> <i class="fas fa-users"></i> Testimonials</a>
                                        <a href="{{ route('admin.whyChooseUs.index') }}" class="dropdown-item nav-link"> <i class="fas fa-question-circle"></i> Why
                                            Choose
                                            Us</a>
                                        <a href="{{ route('contact.index') }}"
                                            class="dropdown-item nav-link {{ Request::routeIs('contact.index') ? 'active' : '' }}"><i class="fas fa-id-card"></i> Enquiry
                                            </a>
                                    @endcan
                                    {{-- @can('media_show')
                                     <a href="#" class="dropdown-item">Media</a>
                                 @endcan --}}

                                </div>
                            </div>
                        @endif

                        <!--                        || \Gate::check('priority_access')-->
                        @if (\Gate::check('service_access') || \Gate::check('status_access') || \Gate::check('category_access'))
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle
                                {{ request()->is('admin/services') ? 'show' : '' }}
                                {{ request()->is('admin/statuses') ? 'show' : '' }}
                                {{ request()->is('admin/categories') ? 'show' : '' }}
                                " data-bs-toggle="dropdown"><i
                                        class="fa fa-laptop me-2"></i>Masters</a>
                                <div class="dropdown-menu bg-transparent border-0
                                {{ request()->is('admin/services') ? 'show' : '' }}
                                {{ request()->is('admin/statuses') ? 'show' : '' }}
                                {{ request()->is('admin/categories') ? 'show' : '' }}
                                ">
                                    @can('service_access')
                                        <a href="{{ route('admin.services.index') }}"
                                            class="nav-link  nav-item {{ request()->is('admin/services') || request()->is('admin/services/*') ? 'active' : '' }}">
                                            <i class="fas fa-weight-hanging"></i>  {{ trans('cruds.services.title') }}
                                        </a>
                                    @endcan
                                    @can('status_access')
                                        <a href="{{ route('admin.statuses.index') }}"
                                            class="nav-link nav-item {{ request()->is('admin/statuses') || request()->is('admin/statuses/*') ? 'active' : '' }}">
                                            <i class="fas fa-toggle-on"></i> {{ trans('cruds.status.title') }}
                                            {{ trans('cruds.status.title') }}
                                        </a>
                                    @endcan
                                    {{-- @can('priority_access')
                                    <a href="{{ route("admin.priorities.index") }}"
                                       class="nav-link nav-item {{ request()->is('admin/priorities') || request()->is('admin/priorities/*') ? 'active' : '' }}">
                                        {{ trans('cruds.priority.title') }}
                                    </a>
                                @endcan --}}
                                    @can('category_access')
                                        <a href="{{ route('admin.categories.index') }}"
                                            class="nav-link nav-item {{ request()->is('admin/categories') || request()->is('admin/categories/*') ? 'active' : '' }}">
                                            <i class="fas fa-edit"></i> {{ trans('cruds.category.title') }}
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        @endif

                        @can('ticket_access')
                            <a href="{{ route('admin.tickets.index') }}"
                                class="nav-link nav-item {{ request()->is('admin/tickets') || request()->is('admin/tickets/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-question-circle nav-icon">

                                </i>
                                {{ trans('cruds.ticket.title') }}
                            </a>
                        @endcan

                        @can('comment_access')

                            <a href="{{ route('admin.comments.index') }}"
                                class="nav-link nav-item {{ request()->is('admin/comments') || request()->is('admin/comments/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-comment nav-icon">

                                </i>
                                {{ trans('cruds.comment.title') }}
                            </a>

                        @endcan

                        @if (\Gate::check('bill_generate_access'))
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle
                                {{ request()->is('admin/bills') ? 'show' : '' }}
                                {{ request()->is('admin/customer') ? 'show' : '' }}
                                " data-bs-toggle="dropdown"><i class="fas fa-user-circle me-2"></i>Accounts</a>
                                <div class="dropdown-menu bg-transparent border-0

                                {{ request()->is('admin/bills') ? 'show' : '' }}
                                {{ request()->is('admin/customer') ? 'show' : '' }}
                                ">
                                    @can('bill_generate_access')
                                        <a href="{{ route('admin.bills.index') }}"
                                            class="nav-item nav-link {{ request()->is('admin/bills') || request()->is('admin/bills/*') ? 'active' : '' }}">
                                            <i class="fa-fw fas fa-comment nav-icon">

                                            </i>
                                            {{ trans('cruds.bills.title') }}
                                        </a>
                                    @endcan
                                    @if (!\Auth::user()->isUser())
                                        <a href="{{ route('admin.customer.list') }}"
                                            class="nav-item nav-link {{ request()->is('admin/customer') || request()->is('admin/customer/*') ? 'active' : '' }}">
                                            <i class="fas fa-user-tag"></i>
                                            </i>
                                            {{ trans('cruds.customer.title') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif


                        @if (\Gate::check('reports_access') || \Gate::check('link_generate_access') || \Gate::check('payment_access'))
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle
                                {{ request()->is('admin/payment') ? 'show' : '' }}
                                {{ request()->is('admin/links') ? 'show' : '' }}
                                " data-bs-toggle="dropdown"><i
                                        class="far fa-chart-bar me-2"></i>Payments</a>
                                <div class="dropdown-menu bg-transparent border-0
                                {{ request()->is('admin/payment') ? 'show' : '' }}
                                {{ request()->is('admin/links') ? 'show' : '' }}
                                ">
                                    @can('payment_access')
                                        <a href="{{ route('admin.payment.index') }}"
                                            class="nav-link nav-item {{ request()->is('admin/payment') || request()->is('admin/payment/*') ? 'active' : '' }}">
                                            <i class="fa fa-credit-card nav-icon"></i>
                                            {{ trans('cruds.payment.title') }}
                                        </a>

                                    @endcan

                                    @can('link_generate_access')
                                        <a href="{{ route('admin.links.index') }}"
                                            class="nav-link nav-item {{ request()->is('admin/links') || request()->is('admin/links/*') ? 'active' : '' }}">
                                            <i class="fas fa-link"></i>
                                            </i>
                                            {{ trans('cruds.links.title') }}
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        @endcan


                        @if (
                            \Gate::check('permission_access') ||
                                \Gate::check('role_access') ||
                                \Gate::check('user_access') ||
                                \Gate::check('audit_log_access'))
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle
                                {{ request()->is('admin/permissions') ? 'show' : '' }}
                                {{ request()->is('admin/roles') ? 'show' : '' }}
                                {{ request()->is('admin/users') ? 'show' : '' }}
                                {{ request()->is('admin/audit-logs') ? 'show' : '' }}
                                {{ request()->is('admin/emailLog') ? 'show' : '' }}
                                {{ request()->is('admin/setting') ? 'show' : '' }}
                                {{ request()->is('file/manage') ? 'show' : '' }}
                                " data-bs-toggle="dropdown"><i
                                        class="far fa-sun me-2"></i>Settings</a>
                                <div class="dropdown-menu bg-transparent border-0
                                {{ request()->is('admin/permissions') ? 'show' : '' }}
                                {{ request()->is('admin/roles') ? 'show' : '' }}
                                {{ request()->is('admin/users') ? 'show' : '' }}
                                {{ request()->is('admin/audit-logs') ? 'show' : '' }}
                                {{ request()->is('admin/emailLog') ? 'show' : '' }}
                                {{ request()->is('admin/setting') ? 'show' : '' }}
                                {{ request()->is('file/manage') ? 'show' : '' }}
                                ">
                                    @can('permission_access')

                                        <a href="{{ route('admin.permissions.index') }}"
                                            class="nav-link {{ request()->is('admin/permissions') || request()->is('admin/permissions/*') ? 'active' : '' }}">
                                            <i class="fas fa-drum-steelpan"></i>  {{ trans('cruds.permission.title') }}
                                        </a>

                                    @endcan
                                    @can('role_access')

                                        <a href="{{ route('admin.roles.index') }}"
                                            class="nav-link {{ request()->is('admin/roles') || request()->is('admin/roles/*') ? 'active' : '' }}">
                                            <i class="fas fa-user-tag"></i> {{ trans('cruds.role.title') }}
                                        </a>

                                    @endcan
                                    @can('user_access')

                                        <a href="{{ route('admin.users.index') }}"
                                            class="nav-link {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'active' : '' }}">
                                            <i class="fas fa-user-cog"></i> {{ trans('cruds.user.title') }}
                                        </a>

                                    @endcan
                                    @can('audit_log_access')

                                        <a href="{{ route('admin.audit-logs.index') }}"
                                            class="nav-link {{ request()->is('admin/audit-logs') || request()->is('admin/audit-logs/*') ? 'active' : '' }}">
                                            <i class="fas fa-tasks"></i>  {{ trans('cruds.auditLog.title') }}
                                        </a>
                                        <a href="{{ route('admin.emailLog.index') }}"
                                            class="nav-link {{ request()->is('admin/emailLog') || request()->is('admin/emailLog/*') ? 'active' : '' }}">
                                            <i class="fas fa-envelope-open-text"></i>  {{ trans('cruds.emailLog.title') }}
                                        </a>
                                    @endcan
                                    @can('setting_access')

                                        <a href="{{ route('admin.setting.index') }}"
                                            class="nav-link {{ request()->is('admin/setting') || request()->is('admin/setting/*') ? 'active' : '' }}">
                                            <i class="fas fa-cog"></i>   Settings
                                        </a>


                                    @endcan
                                </div>
                                @can('setting_access')
                                    <a href="{{ route('file.manager') }}"
                                        class="nav-link nav-item {{ request()->is('file/manage') || request()->is('file/manage*') ? 'active' : '' }}">
                                        <i class="fa fa-folder" aria-hidden="true"></i>
                                        File Manager
                                    </a>
                                @endcan
                            </div>
                        @endif
                </div>
            </div>
        </nav>
    </div>
    <!-- Sidebar End -->


    <!-- Content Start -->
    <div class="content">
        <!-- Navbar Start -->
        <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
            <a href="index.html" class="navbar-brand d-flex d-lg-none me-4">
                <h2 class="text-primary mb-0"><i class="fa fa-hashtag"></i></h2>
            </a>
            <a href="#" class="sidebar-toggler flex-shrink-0">
                <i class="fa fa-bars"></i>
            </a>
            <div class="d-none d-md-flex ms-4">
                <input class="form-control border-0 adminSearch" name="search" id="adminSearch" type="text"
                    placeholder="Search">
                <div id="admin-search-content" class="admin-search-content position-absolute p-1 overflow-auto">
                </div>
            </div>
            <div class="navbar-nav align-items-center ms-auto">
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        <img class="rounded-circle me-lg-2 bg-light" src="{{ asset('assets/img/user.jpg') }}"
                            alt="" style="width: 40px; height: 40px;">
                        @if (\Illuminate\Support\Facades\Auth::user()->name != null && isset(\Illuminate\Support\Facades\Auth::user()->roles[0]))
                            <span class="d-none d-lg-inline-flex">
                                <div>
                                    {{ \Illuminate\Support\Facades\Auth::user()->name }}<small>({{ \Illuminate\Support\Facades\Auth::user()->roles[0]->title }})</small>
                                </div>
                            </span>
                        @else
                            <span class="d-none d-lg-inline-flex">
                                <div>User</div>
                            </span>
                        @endif

                    </a>
                    <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                        <a href="{{ route('getProfile') }}" class="dropdown-item">My Profile</a>
                        <a href="#" class="dropdown-item"
                            onclick="event.preventDefault(); document.getElementById('logoutform').submit();">{{ trans('global.logout') }}</a>
                    </div>
                </div>
            </div>
        </nav>
        <!-- Navbar End -->
