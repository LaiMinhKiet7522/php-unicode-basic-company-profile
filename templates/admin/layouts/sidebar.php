<?php
$userId = isLogin()['user_id'];
$userDetail = getUserInfo($userId);
?>
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?php echo _WEB_HOST_ROOT_ADMIN . '/?module=dashboard'; ?>" class="brand-link">
        <span class="brand-text font-weight-light text-uppercase">Radix Admin</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?php echo _WEB_HOST_ADMIN_TEMPLATE; ?>/assets/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="<?php echo getLinkAdmin('users', 'profile'); ?>" class="d-block"><?php echo $userDetail['fullname']; ?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Trang tổng quan - Begin -->
                <li class="nav-item">
                    <a href="<?php echo getLinkAdmin('dashboard'); ?>" class="nav-link <?php echo (activeMenuSidebar('dashboard')) ? 'active' : false; ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Tổng quan
                        </p>
                    </a>
                </li>
                <!-- Trang tổng quan - End -->

                <!-- Nhóm người dùng - Begin -->
                <li class="nav-item has-treeview <?php echo activeMenuSidebar('groups') ? 'menu-open' : false; ?>">
                    <a href="#" class="nav-link <?php echo activeMenuSidebar('groups') ? 'active' : false; ?>">
                        <i class="nav-icon fas fa-copy"></i>
                        <p>
                            Nhóm người dùng
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo getLinkAdmin('groups'); ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Danh sách</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo getLinkAdmin('groups', 'add'); ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Thêm mới</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- Nhóm người dùng - End -->

                <!-- Quản lý người dùng - Begin -->
                <li class="nav-item has-treeview <?php echo activeMenuSidebar('users') ? 'menu-open' : false; ?>">
                    <a href="#" class="nav-link <?php echo activeMenuSidebar('users') ? 'active' : false; ?>">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Quản lý người dùng
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo getLinkAdmin('users'); ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Danh sách</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo getLinkAdmin('users', 'add'); ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Thêm mới</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- Quản lý người dùng - End -->

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

<div class="content-wrapper">