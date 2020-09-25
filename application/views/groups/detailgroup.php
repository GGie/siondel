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
                                <h4 class="col-auto mr-auto card-title">Group Info</h4>
                                <a class="col-auto btn btn-danger text-white" href="<?= base_url() ?>groups">
                                    <i class="mdi mdi-keyboard-backspace text-white"></i>Back</a>
                            </div>
                            <p class="name"><?= $user['group_name'] ?></p>
                            <span class="d-block text-center text-dark"><?= $user['desc'] ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-12 stretch-card">
                    <div class="card">
                        <div class="card-body overview">
                            <ul class="achivements">
                                <li>
                                    <p class="text-success">Id</p>
                                    <p><?= $user['group_id'] ?></p>
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
                        <h4 class="card-title mb-0">Group Detail</h4>
                        <ul class="nav nav-tabs tab-solid tab-solid-primary mb-0" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="info-tab" data-toggle="tab" href="#info" role="tab" aria-controls="info" aria-expanded="true">Info</a>
                            </li>
                        </ul>
                    </div>
                    <div class="wrapper">

                        <hr>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info">
                                <?= form_open_multipart('groups/ubahid'); ?>
                                <input type="hidden" name="id" value="<?= $user['group_id'] ?>">
                                <div class="form-group">
                                    <label for="group_name">Name</label>
                                    <input type="text" class="form-control" id="group_name" name="group_name" value="<?= $user['group_name'] ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="desc">Keterangan</label>
                                    <input type="text" class="form-control" id="desc" name="desc" value="<?= $user['desc'] ?>" required>
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
<script type="text/javascript">
    $(function() {
        var code = "<?= $user['countrycode'] ?>"; // Assigning value from model.
        $('#txtPhone').val(code);
        $('#txtPhone').intlTelInput({
            autoHideDialCode: true,
            autoPlaceholder: "ON",
            dropdownContainer: document.body,
            formatOnDisplay: true,
            hiddenInput: "full_number",
            initialCountry: "auto",
            nationalMode: true,
            placeholderNumberType: "MOBILE",
            preferredCountries: ['US'],
            separateDialCode: false
        });
        console.log(code)
    });
</script>