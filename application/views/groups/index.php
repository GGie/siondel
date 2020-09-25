<!-- partial -->
<div class="content-wrapper">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div>
                    <a class="btn btn-info" href="<?= base_url(); ?>groups/tambah">
                        <i class="mdi mdi-plus-circle-outline"></i>Add Group</a>
                </div>
                <br>
                <?php if ($this->session->flashdata('demo') or $this->session->flashdata('hapus')) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $this->session->flashdata('demo'); ?>
                        <?php echo $this->session->flashdata('hapus'); ?>
                    </div>
                <?php endif; ?>
                <?php if ($this->session->flashdata('ubah') or $this->session->flashdata('tambah')) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $this->session->flashdata('ubah'); ?>
                        <?php echo $this->session->flashdata('tambah'); ?>
                    </div>
                <?php endif; ?>
                <h4 class="card-title">Group</h4>
                <div class="tab-minimal tab-minimal-success">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="tab-2-1" data-toggle="tab" href="#allusers-2-1" role="tab" aria-controls="allusers-2-1" aria-selected="true">
                                <i class="mdi mdi-account"></i>All Group</a>
                        </li>
                        <!--<li class="nav-item">
                            <a class="nav-link" id="tab-2-2" data-toggle="tab" href="#blocked-2-2" role="tab" aria-controls="blocked-2-2" aria-selected="false">
                                <i class="mdi mdi-account-off"></i>Blocked Group</a>
                        </li>-->
                    </ul>
                    <div class="tab-content">

                        <!-- all users -->
                        <div class="tab-pane fade show active" id="allusers-2-1" role="tabpanel" aria-labelledby="tab-2-1">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">All Group</h4>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table id="order-listing" class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Name</th>
                                                            <th width='550px'>Keterangan</th>
                                                            <th>Status</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $i = 1;
                                                        foreach ($group as $sm) { ?>
                                                            <tr>
                                                                <td><?= $i ?></td>
                                                                <td style='width:380px'><?= $sm['group_name'] ?></td>
                                                                <td style='width:380px'><?= $sm['desc'] ?></td>
                                                                <td>
                                                                    <?php if ($sm['status'] == 1) { ?>
                                                                        <label class="badge badge-success">Active</label>
                                                                    <?php } else { ?>
                                                                        <label class="badge badge-dark">Blocked</label>
                                                                    <?php } ?>
                                                                </td>
                                                                <td>
                                                                    <a href="<?= base_url(); ?>groups/detail/<?= $sm['group_id'] ?>">
                                                                        <button class="btn btn-outline-primary m-2" style='width:80px'>View</button>
                                                                    </a>
                                                                    <?php if ($sm['status'] == 0) { ?>
                                                                        <a href="<?= base_url(); ?>groups/groupunblock/<?= $sm['group_id'] ?>">
                                                                            <button class="btn btn-outline-success text-red m-2" style='width:80px'>Unblock</button>
                                                                        </a>
                                                                    <?php } else { ?>
																	<!--
                                                                        <a href="<?= base_url(); ?>groups/groupblock/<?= $sm['group_id'] ?>">
                                                                            <button class="btn btn-outline-dark text-dark m-2" style='width:80px'>Block</button>
                                                                        </a>
																	-->
                                                                    <?php } ?>
                                                                    <a href="<?= base_url(); ?>groups/hapusgroups/<?= $sm['group_id'] ?>">
                                                                        <button onclick="return confirm ('Are You Sure?')" class="btn btn-outline-danger text-red m-2" style='width:80px'>Delete</button>
                                                                    </a>
                                                                </td>
                                                            <?php $i++;
                                                        } ?>
                                                            </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end of all users -->

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- content-wrapper ends -->