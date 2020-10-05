<!-- partial -->
<div class="content-wrapper">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div>
                    <a class="btn btn-info" href="<?= base_url(); ?>bank/tambah">
                        <i class="mdi mdi-plus-circle-outline"></i>Add Bank</a>
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
                <h4 class="card-title">Bank</h4>
                <div class="tab-minimal tab-minimal-success">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="tab-2-1" data-toggle="tab" href="#allusers-2-1" role="tab" aria-controls="allusers-2-1" aria-selected="true">
                                <i class="mdi mdi-account"></i>All Bank</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-2-2" data-toggle="tab" href="#blocked-2-2" role="tab" aria-controls="blocked-2-2" aria-selected="false">
                                <i class="mdi mdi-account-off"></i>Nonaktif Bank</a>
                        </li>
                    </ul>
                    <div class="tab-content">

                        <!-- all users -->
                        <div class="tab-pane fade show active" id="allusers-2-1" role="tabpanel" aria-labelledby="tab-2-1">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">All Bank</h4>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table id="order-listing" class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th width='80px'>Bank Code</th>
                                                            <th width='300px'>Bank Name</th>
                                                            <th>Status</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $i = 1;
                                                        foreach ($bank as $sm) { ?>
                                                            <tr>
                                                                <td><?= $i ?></td>
                                                                <td><?= $sm['bank_code'] ?></td>
																<td><?= $sm['bank_name'] ?></td>
                                                                <td>
                                                                    <?php if ($sm['apply'] == 1) { ?>
                                                                        <label class="badge badge-success">Active</label>
                                                                    <?php } else { ?>
                                                                        <label class="badge badge-dark">Nonaktif</label>
                                                                    <?php } ?>
                                                                </td>
                                                                <td>
                                                                    <a href="<?= base_url(); ?>users/detail/<?= $sm['id'] ?>">
                                                                        <button class="btn btn-outline-primary m-2" style='width:80px'>View</button>
                                                                    </a>
                                                                    <?php if ($sm['apply'] == 0) { ?>
                                                                        <a href="<?= base_url(); ?>users/userunblock/<?= $sm['id'] ?>">
                                                                            <button class="btn btn-outline-success text-red m-2" style='width:80px'>Unblock</button>
                                                                        </a>
                                                                    <?php } else { ?>
                                                                        <a href="<?= base_url(); ?>users/userblock/<?= $sm['id'] ?>">
                                                                            <button class="btn btn-outline-dark text-dark m-2" style='width:80px'>Block</button>
                                                                        </a>
                                                                    <?php } ?>
                                                                    <a href="<?= base_url(); ?>users/hapususers/<?= $sm['id'] ?>">
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

                        <!-- blocked users -->
                        <div class="tab-pane fade" id="blocked-2-2" role="tabpanel" aria-labelledby="tab-2-2">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">All Bank</h4>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table id="order-listing" class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
															<th width='80px'>Bank Code</th>
                                                            <th width='300px'>Bank Name</th>
                                                            <th>Status</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $i = 1;
                                                        foreach ($bank as $us) {
                                                            if ($us['apply'] == 0) { ?>
                                                                <tr>
                                                                    <td><?= $i ?></td>
                                                                    <td><?= $us['bank_code'] ?></td>
                                                                    <td><?= $us['bank_name'] ?></td>
                                                                    <td>
                                                                        <?php if ($us['apply'] == 1) { ?>
                                                                            <label class="badge badge-success">Active</label>
                                                                        <?php } else { ?>
                                                                            <label class="badge badge-dark">Nonaktif</label>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td>
                                                                        <a href="<?= base_url(); ?>users/detail/<?= $us['id'] ?>">
                                                                            <button class="btn btn-outline-primary m-2" style='width:80px'>View</button>
                                                                        </a>
                                                                        <a href="<?= base_url(); ?>users/userunblock/<?= $us['id'] ?>">
                                                                            <button class="btn btn-outline-success text-red m-2" style='width:80px'>Unblock</button>
                                                                        </a>

                                                                        <a href="<?= base_url(); ?>users/hapususers/<?= $us['id'] ?>">
                                                                            <button class="btn btn-outline-danger text-red m-2" style='width:80px'>Delete</button>
                                                                        </a>
                                                                    </td>
                                                            <?php $i++;
                                                            }
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
                        <!-- end of blocked -->

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- content-wrapper ends -->