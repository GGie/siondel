<!--
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.4/css/buttons.dataTables.min.css" />
-->
<div class="content-wrapper">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Data Transaction</h4>
            
			<div style="margin-bottom: 15px">
			<form action="" method="GET" accept-charset="utf-8">
			<div class="row">
				<?php
				
				if ( !empty($_GET['date_first']) AND !empty($_GET['date_end']) ) {
					$date_first = date('Y-m-d', strtotime($_GET['date_first']));
					$date_end = date('Y-m-d', strtotime($_GET['date_end']));
				} else {
					$date_first = date('Y-m-d', strtotime(date('Y-m-d') . ' - 7 days'));
					$date_end = date('Y-m-d');
				}
				
				?>
                <div class="col-3">
					<input type="date" class="form-control" name="date_first" value="<?php echo $date_first; ?>">
				</div>
				s/d
				<div class="col-3">
					<input type="date" class="form-control" name="date_end" value="<?php echo $date_end; ?>">
				</div>
				<button type="submit" class="btn btn-success mr-2">Search</button>
				<a class="btn btn-primary" href="<?php echo base_url('transaction/xls_reportmeter?date_first=' . $date_first . '&date_end=' . $date_end) ?>">Export</a>
			
			</div>
			</form>
			</div>
			
            <div class="row">
                <div class="col-12">
                    <?php if ($this->session->flashdata()) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $this->session->flashdata('demo'); ?>
                            <?php echo $this->session->flashdata('cancel'); ?>
                            <?php echo $this->session->flashdata('hapus'); ?>
                        </div>
                    <?php endif; ?>
                    <div class="table-responsive">
                        <table id="order-listing" class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Customer</th>
                                    <th>Driver</th>
                                    <th>Service</th>
                                    <th style="min-width:300px">Samsat Location</th>
                                    <th style="min-width:300px">Customer Location</th>
                                    <th style="min-width:90px">Date</th>
                                    <th style="min-width:90px">Start date</th>
                                    <th style="min-width:90px">End Date</th>
                                    <th>Kode Voucher</th>
                                    <th>Price</th>
                                    <th>Payment Method</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;
                                foreach ($transaksi as $tr) { ?>

                                    <tr>
                                        <td><?= $i; ?></td>
                                        <td><?= $tr['fullnama'] ?></td>
                                        <td><?= $tr['nama_driver'] ?></td>
                                        <td><?= $tr['fitur'] ?></td>
                                        <td style="max-width:300px;"><?= $tr['alamat_tujuan'] ?></td>
										<td style="max-width:300px;"><?= $tr['alamat_asal'] ?></td>
										<td><?= date('Y-m-d H:i:s', strtotime($tr['waktu'])) ?></td>
										<td><?= date('Y-m-d H:i:s', strtotime($tr['waktu_order'])) ?></td>
										<td><?= date('Y-m-d H:i:s', strtotime($tr['waktu_selesai'])) ?></td>
										<?php $kdvoucher = explode('.', base64_decode($tr['qrstring'])); ?>
                                        <td><?= $kdvoucher[0] ?></td>
                                        <td><?= $currency['app_currency'] ?>
                                            <?= number_format($tr['biaya_akhir'], 0, ".", ".") ?></td>
                                        <td>
                                            <?php if ($tr['pakai_wallet'] == '0') {
                                                echo 'CASH';
                                            } else {
                                                echo 'WALLET';
                                            } ?>
                                        </td>
                                        <td>
                                            <?php if ($tr['status'] == '2') { ?>
                                                <label class="badge badge-primary"><?= $tr['status_transaksi']; ?></label>
                                            <?php } ?>
                                            <?php if ($tr['status'] == '3') { ?>
                                                <label class="badge badge-success"><?= $tr['status_transaksi']; ?></label>
                                            <?php } ?>
                                            <?php if ($tr['status'] == '5') { ?>
                                                <label class="badge badge-danger"><?= $tr['status_transaksi']; ?></label>
                                            <?php } ?>
                                            <?php if ($tr['status'] == '4') { ?>
                                                <label class="badge badge-info"><?= $tr['status_transaksi']; ?></label>
                                            <?php } ?>
											<?php if ($tr['status'] == '6') { ?>
                                                <label class="badge badge-info"><?= $tr['status_transaksi']; ?></label>
                                            <?php } ?>
											<?php if ($tr['status'] == '7') { ?>
                                                <label class="badge badge-info"><?= $tr['status_transaksi']; ?></label>
                                            <?php } ?>
											<?php if ($tr['status'] == '8') { ?>
                                                <label class="badge badge-info"><?= $tr['status_transaksi']; ?></label>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <a href="<?= base_url(); ?>dashboard/detail/<?= $tr['id_transaksi']; ?>" class="btn btn-outline-primary m-2" style='width:80px'>View</a>
                                            <a onclick="return confirm ('Are You Sure?')" href="<?= base_url(); ?>dashboard/delete/<?= $tr['id_transaksi']; ?>" class="btn btn-outline-danger m-2" style='width:80px'>Delete</a>
                                        </td>
                                    </tr>
                                <?php $i++;
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>