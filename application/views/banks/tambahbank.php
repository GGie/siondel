<!-- partial -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<div class="content-wrapper">
    <div class="row ">
        <div class="col-md-8 offset-md-2 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Add Bank</h4>
                    <?php if ($this->session->flashdata() or validation_errors()) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?= validation_errors() ?>
                            <?php echo $this->session->flashdata('invalid'); ?>
                            <?php echo $this->session->flashdata('demo'); ?>
                        </div>
                    <?php endif; ?>
                    <?= form_open_multipart(base_url('bank/tambah')); ?>

                    <div class="form-group">
                        <label for="fullnama">Bank Code</label>
                        <input type="text" class="form-control" id="bank_code" name="bank_code" placeholder="Bank Code" <?php if ($_POST != NULL) { ?> value="<?= $_POST['bank_code']; ?>" <?php } ?> required>
                    </div>
					
					<div class="form-group">
                        <label for="fullnama">Bank Name</label>
                        <input type="text" class="form-control" id="bank_name" name="bank_name" placeholder="Bank Name" <?php if ($_POST != NULL) { ?> value="<?= $_POST['bank_name']; ?>" <?php } ?> required>
                    </div>
					
					<div class="form-group">
                        <label for="fullnama">Apply</label>
                        <select class="js-example-basic-single" style="width:100%" name="apply">
							<option value="1" <?php //if ($_POST['apply'] == 1) { echo "selected"; } ?>>Aktif</option>
							<option value="0" <?php //if ($_POST['apply'] == 0) { echo "selected"; } ?>>Nonaktif</option>
						</select>
                    </div>
					
					
                    <button type="submit" class="btn btn-success mr-2">Submit</button>
                    <button class="btn btn-light">Cancel</button>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function() {
        var code = "+62"; // Assigning value from model.
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
<!-- end of content wrapper -->