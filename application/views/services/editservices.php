<!-- partial -->
<div class="content-wrapper">
    <div class="row ">
        <div class="col-md-8 offset-md-2 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">
                        <?php if ($this->session->flashdata()) : ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $this->session->flashdata('demo'); ?>
                            </div>
                        <?php endif; ?>
                        Service</h4>
                    <?= form_open_multipart('services/ubah/' . $id_fitur); ?>
                    <input type="hidden" name="id_fitur" value='<?= $id_fitur ?>'>
                    <div class="form-group">
                        <input type="file" class="dropify" name="icon" data-max-file-size="3mb" data-default-file="<?= base_url('images/fitur/') . $icon ?>" />
                    </div>
                    <div class="form-group">
                        <label for="newstitle">Name</label>
                        <input type="text" class="form-control" id="newstitle" name="fitur" value="<?= $fitur ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="service_tipe">Service Type</label>
                        <select class="js-example-basic-single" name="home" style="width:100%">
                            <option value="1" <?php if ($home == '1') { ?>selected<?php } ?>>Passenger Transportation</option>
                            <option value="2" <?php if ($home == '2') { ?>selected<?php } ?>>Shipment</option>
                            <option value="3" <?php if ($home == '3') { ?>selected<?php } ?>>Rental Service</option>
                            <option value="4" <?php if ($home == '4') { ?>selected<?php } ?>>Purchasing Service</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="newstitle">Price</label>
                        <!--<input type="text" pattern="^\d+(\.|\,)\d{2}$" data-type="currency" class="form-control" id="newstitle" name="biaya" value="<?= number_format($biaya, 0, ".", ".") ?>" required>-->
                        <input type="text" class="form-control" id="newstitle" name="biaya" value="<?= number_format($biaya, 0, ".", "") ?>" required>
                    </div>
					<div class="form-group">
                        <label for="newscategory">Fixed Price</label>
                        <select class="js-example-basic-single" name="fixed" style="width:100%">
                            <option value="1" <?php if ($fixed == 1) { ?>selected<?php } ?>>Fixed</option>
                            <option value="0" <?php if ($fixed == 0) { ?>selected<?php } ?>>NonFixed</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="newstitle">Discount (%)</label>
                        <input type="text" class="form-control" id="newstitle" name="nilai" value="<?= $nilai ?>" placeholder="ex 10%">
                    </div>
                    <div class="form-group">
                        <label for="newscategory">Unit</label>
                        <select class="js-example-basic-single" name="keterangan_biaya" style="width:100%">
                            <!-- <option value="KM">Kilometers</option> -->
                            <option value="KM" <?php if ($keterangan_biaya == 'KM') { ?>selected<?php } ?>>Kilometers</option>
                            <option value="Hr" <?php if ($keterangan_biaya == 'Hr') { ?>selected<?php } ?>>An Hour</option>
                            <!-- <option value="Hr">An Hour</option> -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="newstitle">Commission (%)</label>
                        <input type="text" class="form-control" id="newstitle" name="komisi" value="<?= $komisi ?>" placeholder="ex 10%" required>
                    </div>
                    <div class="form-group">
                        <label for="newscategory">vechile</label>
                        <select class="js-example-basic-single" name="driver_job" style="width:100%">
                            <?php foreach ($driverjob as $drj) { ?>
                                <option value="<?= $drj['id'] ?>" <?php if ($driver_job == $drj['id']) { ?>selected<?php } ?>><?= $drj['driver_job'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="newstitle">Minimum Price</label>
                        <input type="text" class="form-control" id="newstitle" name="biaya_minimum" value="<?= number_format($biaya_minimum, 0, ".", "") ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="newstitle">Driver Radius</label>
                        <input type="text" class="form-control" id="newstitle" name="jarak_minimum" value="<?= $jarak_minimum ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="newstitle">Max Distance Order</label>
                        <input type="text" class="form-control" id="newstitle" name="maks_distance" value="<?= $maks_distance ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="newstitle">Minimum Saldo</label>
                        <input type="text" class="form-control" id="newstitle" name="wallet_minimum" value="<?= number_format($wallet_minimum, 0, ".", "") ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="newstitle">Description</label>
                        <input type="text" class="form-control" id="newstitle" name="keterangan" value="<?= $keterangan ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="newscategory">Status</label>
                        <select class="js-example-basic-single" name="active" style="width:100%">
                            <option value="0" <?php if ($active == 0) { ?>selected<?php } ?>>Nonactive</option>
                            <option value="1" <?php if ($active == 1) { ?>selected<?php } ?>>Active</option>
                        </select>
                    </div>
					
					<style>
					fieldset.scheduler-border {
						border: 1px groove #aaa !important;
						padding: 0 1.4em 1.4em 1.4em !important;
						margin: 0 0 1.5em 0 !important;
						-webkit-box-shadow:  0px 0px 0px 0px #000;
								box-shadow:  0px 0px 0px 0px #000;
					}

					legend.scheduler-border {
						font-size: 1.2em !important;
						font-weight: bold !important;
						text-align: left !important;
					}
					</style>
					
					
					<?php $sch = json_decode($schedule, true); ?>
					<fieldset class="scheduler-border">
						<legend class="scheduler-border">Schedule Operational</legend>
						<div class="control-group">
							<label for="newscategory">Monday</label>
							<div class="row">
								<div class="col-6 grid-margin stretch-card">
									<input type="time" class="form-control" name="mon_time_1" value="<?php echo @$sch['monday']['start'] ?>" required>
								</div>
								<div class="col-6 grid-margin stretch-card">
									<input type="time" class="form-control" name="mon_time_2" value="<?php echo @$sch['monday']['end'] ?>" required>
								</div>
							</div>
						</div>
						
						<div class="control-group">
							<label for="newscategory">Tuesday</label>
							<div class="row">
								<div class="col-6 grid-margin stretch-card">
									<input type="time" class="form-control" name="tue_time_1" value="<?php echo @$sch['tuesday']['start'] ?>" required>
								</div>
								<div class="col-6 grid-margin stretch-card">
									<input type="time" class="form-control" name="tue_time_2" value="<?php echo @$sch['tuesday']['end'] ?>" required>
								</div>
							</div>
						</div>
						
						<div class="control-group">
							<label for="newscategory">Wednesday</label>
							<div class="row">
								<div class="col-6 grid-margin stretch-card">
									<input type="time" class="form-control" name="wed_time_1" value="<?php echo @$sch['wednesday']['start'] ?>" required>
								</div>
								<div class="col-6 grid-margin stretch-card">
									<input type="time" class="form-control" name="wed_time_2" value="<?php echo @$sch['wednesday']['end'] ?>" required>
								</div>
							</div>
						</div>
						
						<div class="control-group">
							<label for="newscategory">Thursday</label>
							<div class="row">
								<div class="col-6 grid-margin stretch-card">
									<input type="time" class="form-control" name="thu_time_1" value="<?php echo @$sch['thursday']['start'] ?>" required>
								</div>
								<div class="col-6 grid-margin stretch-card">
									<input type="time" class="form-control" name="thu_time_2" value="<?php echo @$sch['thursday']['end'] ?>" required>
								</div>
							</div>
						</div>
						
						<div class="control-group">
							<label for="newscategory">Friday</label>
							<div class="row">
								<div class="col-6 grid-margin stretch-card">
									<input type="time" class="form-control" name="fri_time_1" value="<?php echo @$sch['friday']['start'] ?>" required>
								</div>
								<div class="col-6 grid-margin stretch-card">
									<input type="time" class="form-control" name="fri_time_2" value="<?php echo @$sch['friday']['end'] ?>" required>
								</div>
							</div>
						</div>
						
						<div class="control-group">
							<label for="newscategory">Saturday</label>
							<div class="row">
								<div class="col-6 grid-margin stretch-card">
									<input type="time" class="form-control" name="sat_time_1" value="<?php echo @$sch['saturday']['start'] ?>" required>
								</div>
								<div class="col-6 grid-margin stretch-card">
									<input type="time" class="form-control" name="sat_time_2" value="<?php echo @$sch['saturday']['end'] ?>" required>
								</div>
							</div>
						</div>
						
						<div class="control-group">
							<label for="newscategory">Sunday</label>
							<div class="row">
								<div class="col-6 grid-margin stretch-card">
									<input type="time" class="form-control" name="sun_time_1" value="<?php echo @$sch['sunday']['start'] ?>" required>
								</div>
								<div class="col-6 grid-margin stretch-card">
									<input type="time" class="form-control" name="sun_time_2" value="<?php echo @$sch['sunday']['end'] ?>" required>
								</div>
							</div>
						</div>
					</fieldset>

                    <button type="submit" class="btn btn-success mr-2">Submit</button>
                    <a href="<?= base_url() ?>services" class="btn btn-danger">Cancel</a>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end of content wrapper -->