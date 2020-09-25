<!-- partial -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<div class="content-wrapper">
    <div class="row user-profile">
        <div class="col-lg-4 side-left d-flex align-items-stretch">
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body avatar">
                            <div class="row">
                                <h4 class="col-auto mr-auto card-title">Admin Info</h4>
                                <a class="col-auto btn btn-danger text-white" href="<?= base_url() ?>admin">
                                    <i class="mdi mdi-keyboard-backspace text-white"></i>Back</a>
                            </div>
                            <img src="<?= base_url('images/admin/') . $user['image']; ?>">
                            <p class="name"><?= $user['user_name'] ?></p>
                            <span class="d-block text-center text-dark"><?= $user['email'] ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-12 stretch-card">
                    <div class="card">
                        <div class="card-body overview">
                            <ul class="achivements">
                                <li>
                                    <p class="text-success">Id</p>
                                    <p><?= $user['id'] ?></p>
                                </li>
                                <li>
                                    <p class="text-success">Status</p>
                                    <p>
                                        <?php if ($user['status'] == 1) {
                                            echo 'active';
                                        } else {
                                            echo 'Blocked';
                                        } ?>
                                    </p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 side-right stretch-card">
            <div class="card">

                <div class="card-body">
                    <?php if ($this->session->flashdata('ubah')) : ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo $this->session->flashdata('ubah'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('demo')) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $this->session->flashdata('demo'); ?>
                        </div>
                    <?php endif; ?>
                    <div class="wrapper d-block d-sm-flex align-items-center justify-content-between">
                        <h4 class="card-title mb-0">Admin Detail</h4>
                        <ul class="nav nav-tabs tab-solid tab-solid-primary mb-0" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="info-tab" data-toggle="tab" href="#info" role="tab" aria-controls="info" aria-expanded="true">Info</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="avatar-tab" data-toggle="tab" href="#avatar" role="tab" aria-controls="avatar">Profile Picture</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="security-tab" data-toggle="tab" href="#security" role="tab" aria-controls="security">Password</a>
                            </li>
                        </ul>
                    </div>
                    <div class="wrapper">

                        <hr>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info">
                                <?= form_open_multipart('admin/ubahid'); ?>
                                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="user_name" value="<?= $user['user_name'] ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?= $user['email'] ?>" placeholder="Change email address" required readonly>
                                </div>
								
								<div id="pelanggancheck" style="display:block;" class="form-group">
									<label for="id_Pelanggan">Users</label>
									<select class="js-example-basic-single" style="width:100%" name="group_id">
										<?php foreach ($group as $gr) { ?>
												<option value="<?= $gr['group_id'] ?>" <?php if( $gr['group_id'] == $user['group_id'] ) { echo "selected"; } ?>><?= $gr['group_name'] ?> </option>
										<?php } ?>
									</select>
								</div>
								
                                <div class="form-group mt-5">
                                    <button type="submit" class="btn btn-success mr-2">Update</button>
                                    <button class="btn btn-outline-danger">Cancel</button>
                                </div>
                                <?= form_close(); ?>
                            </div>
                            <div class="tab-pane fade" id="avatar" role="tabpanel" aria-labelledby="avatar-tab">
                                <?= form_open_multipart('admin/ubahfoto'); ?>
                                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                <input type="file" name="fotopelanggan" class="dropify" data-max-file-size="1mb" data-default-file="<?= base_url('images/admin/') . $user['image'] ?>" />
                                <div class="form-group mt-5">
                                    <button type="submit" class="btn btn-success mr-2">Update</button>
                                    <button class="btn btn-outline-danger">Cancel</button>
                                </div>
                                <?= form_close(); ?>
                            </div>
                            <div class="tab-pane fade" id="security" role="tabpanel" aria-labelledby="security-tab">
                                <?= form_open_multipart('admin/ubahpass'); ?>
                                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                <div class="form-group">
                                    <input type="password" class="form-control" id="new-password" name="password" placeholder="Enter you new password" required>
                                </div>
                                <div class="form-group mt-5">
                                    <button type="submit" class="btn btn-success mr-2">Update</button>
                                    <button class="btn btn-outline-danger">Cancel</button>
                                </div>
                                <?= form_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- content-wrapper ends -->