<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Anterin Si-OnDel Panel</title>
    <!-- plugins:css -->


    <!-- endinject -->
    <!-- plugin css for this page -->
    <link rel="stylesheet" href="<?= base_url(); ?>asset/node_modules/font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" href="<?= base_url(); ?>asset/node_modules/jquery-bar-rating/dist/themes/fontawesome-stars.css">
    <link rel="stylesheet" href="<?= base_url(); ?>asset/node_modules/datatables.net-bs4/css/dataTables.bootstrap4.css" />
    <link rel="stylesheet" href="<?= base_url(); ?>asset/node_modules/dropify/dist/css/dropify.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>asset/node_modules/pwstabs/assets/jquery.pwstabs.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>asset/node_modules/icheck/skins/all.css" />
    <link rel="stylesheet" href="<?= base_url(); ?>asset/node_modules/select2/dist/css/select2.min.css" />
    <link rel="stylesheet" href="<?= base_url(); ?>asset/node_modules/select2-bootstrap-theme/dist/select2-bootstrap.min.css" />
    <link rel="stylesheet" href="<?= base_url(); ?>asset/node_modules/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>asset/node_modules/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="<?= base_url(); ?>asset/node_modules/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>asset/node_modules/perfect-scrollbar/dist/css/perfect-scrollbar.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>asset/node_modules/dragula/dist/dragula.min.css" />
    <link rel="stylesheet" href="<?= base_url(); ?>asset/node_modules/summernote/dist/summernote-bs4.css">
    <link rel="stylesheet" href="<?= base_url(); ?>asset/node_modules/quill/dist/quill.snow.css">
    <link rel="stylesheet" href="<?= base_url(); ?>asset/node_modules/simplemde/dist/simplemde.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/16.0.8/css/intlTelInput.css" />
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="<?= base_url(); ?>asset/css/style.css">

    <!-- endinject -->
    <link rel="shortcut icon" href="<?= base_url(); ?>asset/images/logo.png" />

    <style type="text/css">
        @media screen and (max-width: 500px) {
            #mobileshow {
                display: none;
            }
        }
    </style>

</head>

<body>

    <div class="container-scroller">
        <!-- partial:../../partials/_navbar.html -->
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center">
                <a class="navbar-brand brand-logo" href="<?= base_url(); ?>"><img src="<?= base_url(); ?>asset/images/logo_dashboard.png" alt="logo" /></a>
                <a class="navbar-brand brand-logo-mini" href="<?= base_url(); ?>"><img src="<?= base_url(); ?>asset/images/logo.png" alt="logo" /></a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center">
                <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                    <span class="icon-menu"></span>
                </button>

                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                    <span class="icon-menu"></span>
                </button>
            </div>
        </nav>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <div class="row row-offcanvas row-offcanvas-right">
                <!-- partial:../../partials/_sidebar.html -->
                <nav class="sidebar sidebar-offcanvas" id="sidebar">
                    <ul class="nav">
                        <li class="nav-item nav-profile">
                            <div class="nav-link">
                                <div class="profile-image">
                                    <img src="<?= base_url(); ?>images/admin/<?= $this->session->userdata('image') ?>" onerror="this.onerror=null;this.src='<?= base_url(); ?>asset/images/logo.png';" />
                                    <span class="online-status online"></span>
                                    <!--change class online to offline or busy as needed-->
                                </div>
                                <div class="profile-name">
                                    <p class="name">
                                        <?= $this->session->userdata('user_name') ?>
                                    </p>
                                    <p class="designation">
                                        Super Admin
                                    </p>
                                </div>
                            </div>
                        </li>
						
        <?php foreach ($menu->result() as $tab) { ?>
        <?php //if ( $this->permissions->menu($tab->menu_id, 'view') ) { ?>
		<?php if ( menu($tab->menu_id, 'view') ) { ?>
		<?php $menu_child = $this->db->query("SELECT * FROM menu WHERE menu_pid='" . $tab->menu_id . "' AND status_id=1 ORDER BY menu_order"); ?>
              <li class="nav-item">
								<?php if ( $menu_child->num_rows() > 0 ) { ?>
									<?php $link		= "#" . $tab->menu_id; ?>
									<?php $collapse = "collapse"; ?>
								<?php } else { ?>
									<?php $link		= base_url() . $tab->url; ?>
									<?php $collapse = "false"; ?>
								<?php } ?>
								
                            <a class="nav-link" data-toggle="<?php echo $collapse; ?>" href="<?php echo $link; ?>" aria-expanded="false" aria-controls="<?php echo $tab->menu_id ?>">
                                <i class="<?php echo $tab->icon; ?>"></i>
                                <span class="menu-title"><?php echo ucwords(strtolower($tab->menu)) ?></span>
								
								<?php if ( $menu_child->num_rows() > 0 ) { ?>
									<span class="badge badge-white"><i class="mdi mdi-menu-down mdi-24px text-primary"></i></span>
								<?php } ?>
                            </a>
				
				
				
				
				<div class="collapse" id="<?php echo $tab->menu_id ?>">
                <ul class="nav flex-column sub-menu">
				<?php foreach ($menu_child->result() as $child) { ?>
					
					<?php if ( menu($child->menu_id, 'view') ) { ?>
						<li class="nav-item"> <a class="nav-link" href="<?php echo base_url($child->url); ?>"><?php echo ucwords(strtolower($child->menu)) ?></a></li>
					<?php } ?>
					
				 <?php } ?>
				 </ul>
				 </div>
				 
				 
				 
				 
			   </li>
		<?php } ?>
		<?php } ?>
                    </ul>
                </nav>
