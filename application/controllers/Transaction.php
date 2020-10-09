<?php
defined('BASEPATH') or exit('No direct script access allowed');

class transaction extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ci_ext_model', 'ci_ext');
        $ci_ext = $this->ci_ext->ciext();
        if (!$ci_ext) {
            redirect(gagal);
        }
        if ($this->session->userdata('user_name') == NULL && $this->session->userdata('password') == NULL) {
            redirect(base_url() . "login");
        }
        $this->load->model('Appsettings_model', 'app');
        $this->load->model('Dashboard_model', 'dashboard');
        $this->load->model('Voucher_model', 'voucher');
        // $this->load->library('form_validation');
    }

    public function index()
    {

        $data['currency'] = $this->app->getappbyid();
        $data['transaksi'] = $this->dashboard->getAlltransaksi();
        $data['fitur'] = $this->dashboard->getAllfitur();
        $data['saldo'] = $this->dashboard->getallsaldo();

        $this->headers();
        $this->load->view('transaction/index', $data);
        $this->load->view('includes/footer');
    }
	
	public function excel()
    {

		header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment; filename=Transaction" . time() . ".xls");  //File name extension was wrong
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false);

        $transaksi = $this->dashboard->getAlltransaksi();
		$currency = $this->app->getappbyid();
		
        echo '<table id="order-listing" class="table" border="1">
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
                                </tr>
                            </thead>
                            <tbody>';
                                $i = 1;
                                foreach ($transaksi as $tr) {
									$kdvoucher = explode('.', base64_decode($tr['qrstring']));
									
											if ($tr['pakai_wallet'] == '0') {
                                                $payment = 'CASH';
                                            } else {
                                                $payment =  'WALLET';
                                            }
											
                                    echo '<tr>
                                        <td>' . $i . '</td>
                                        <td>' . $tr['fullnama'] . '</td>
                                        <td>' . $tr['nama_driver'] . '</td>
                                        <td>' . $tr['fitur'] . '</td>
                                        <td style="max-width:300px;">' . $tr['alamat_tujuan'] . '</td>
                                        <td style="max-width:300px;">' . $tr['alamat_asal'] . '</td>
										<td>' . date('Y-m-d H:i:s', strtotime($tr['waktu'])) . '</td>
										<td>' . date('Y-m-d H:i:s', strtotime($tr['waktu_order'])) . '</td>
										<td>' . date('Y-m-d H:i:s', strtotime($tr['waktu_selesai'])) . '</td>
                                        <td>' . $kdvoucher[0] . '</td>
                                        <td>' . $currency['app_currency'] . '
                                            ' . number_format($tr['biaya_akhir'], 0, ".", ".") . '</td>
										 <td>
                                            ' . $payment . '
                                        </td>
                                        <td>
                                            ' . $tr['status_transaksi'] . '
                                        </td>
                                    </tr>';
									$i++;
                                }
                            echo '</tbody></table>';
    }
	
	public function xls_reportmeter()
    {
		
		$transaksi = $this->dashboard->getAlltransaksi();
		$currency = $this->app->getappbyid();
		
		// $query	= $this->db->query($sql);

		// * Create Comment 30 Sept 2020
		// Starting the PHPExcel library
        $this->load->library('Excel/PHPExcel');
        $this->load->library('Excel/PHPExcel/IOFactory');
 
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
 
        $objPHPExcel->setActiveSheetIndex(0);
 
        // Field names in the first row
        // $fields = $query->list_fields();
		$col = 0;
		
		
		// merubah style border pada cell yang aktif (cell yang terisi)
		$styleArray = array( 'borders' => 
			array( 'allborders' => 
				array( 'style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => '00000000'), 
					), 
				),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
             	'rotation'   => 0,
			),
			);
		// melakukan pengaturan pada header kolom
		$fontHeader = array( 
			'font' => array(
				'bold' => true
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
             	'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
             	'rotation'   => 0,
			),
			'fill' => array(
            	'type' => PHPExcel_Style_Fill::FILL_SOLID,
            	'color' => array('rgb' => 'b0c4de')
        	)
		);
		
		//Header Excel
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'No');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, 'Customer');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, 'Driver');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, 'Service');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, 'Samsat Location');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, 'Customer Location');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, 'Date');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 1, 'Start date');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 1, 'End Date');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, 1, 'Kode Voucher');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, 1, 'Price');
			// $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, 1, 'Payment Method');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, 1, 'Status');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, 1, 'NIK');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, 1, 'Nopolisi');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, 1, 'Nohp');
		//Header Excel EOF
				
 
        // Fetching the table data
        $row = 2;
        foreach($transaksi as $data)
        {
            $col = 0;
			$total_price = 0;
				$kdvoucher = explode('.', base64_decode($data['qrstring']));
				
				// if ($data['pakai_wallet'] == '0') {
					// $payment = 'CASH';
				// } else {
					// $payment = 'WALLET';
				// }
									
				$voucher = $this->voucher->getvoucher($kdvoucher[0]);
				$result = json_decode($voucher->row('json'), true);
					
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $row-1, PHPExcel_Cell_DataType::TYPE_STRING);

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+1, $row, $data['fullnama'], PHPExcel_Cell_DataType::TYPE_STRING);	
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+2, $row, $data['nama_driver'], PHPExcel_Cell_DataType::TYPE_STRING);	
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+3, $row, $data['fitur'], PHPExcel_Cell_DataType::TYPE_STRING);	
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+4, $row, $data['alamat_tujuan'], PHPExcel_Cell_DataType::TYPE_STRING);	
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+5, $row, $data['alamat_asal'], PHPExcel_Cell_DataType::TYPE_STRING);	
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+6, $row, date('Y-m-d H:i:s', strtotime($data['waktu'])), PHPExcel_Cell_DataType::TYPE_STRING);	
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+7, $row, date('Y-m-d H:i:s', strtotime($data['waktu_order'])), PHPExcel_Cell_DataType::TYPE_STRING);	
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+8, $row, date('Y-m-d H:i:s', strtotime($data['waktu_selesai'])), PHPExcel_Cell_DataType::TYPE_STRING);	
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+9, $row, $kdvoucher[0], PHPExcel_Cell_DataType::TYPE_STRING);	
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+10, $row, $currency['app_currency'] . " " . $data['biaya_akhir'], PHPExcel_Cell_DataType::TYPE_STRING);	
				// $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+11, $row, $payment, PHPExcel_Cell_DataType::TYPE_STRING);	
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+11, $row, $data['status_transaksi'], PHPExcel_Cell_DataType::TYPE_STRING);	
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+12, $row, @$result['nik'], PHPExcel_Cell_DataType::TYPE_NUMERIC);	
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+13, $row, $result['nopol'], PHPExcel_Cell_DataType::TYPE_NUMERIC);	
				// $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+14, $row, $result['nohp'], PHPExcel_Cell_DataType::TYPE_NUMERIC);	
				$objPHPExcel->setActiveSheetIndex(0)-> setCellValueExplicit ( 'O'.$row, $result['nohp']);
            $row++;
			$total_price = $total_price + $data['biaya_akhir'];
        }
		
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $currency['app_currency'] . " " . $total_price, PHPExcel_Cell_DataType::TYPE_NUMERIC);	
 
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
		// $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setQuotePrefix(true);
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
		// $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
		
		// $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25); //Meter Foto
		// $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(25); //Unit Foto

        $objPHPExcel->setActiveSheetIndex(0);
 
		$objWorksheet = $objPHPExcel->getActiveSheet();
		$objPHPExcel->getActiveSheet()->getStyle('M1:O'.$row)->getNumberFormat()->setFormatCode('#');
		// $objPHPExcel->getActiveSheet()->getStyle('N1:P'.$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		
		$objWorksheet->getStyle('A'.$row.':O'.$row)->applyFromArray($fontHeader);
		
		$objWorksheet->getStyle('A1:O1')->applyFromArray($fontHeader);
		$objWorksheet->getStyle('A1:O'.$row)->applyFromArray($styleArray);
		$objWorksheet->getStyle('A1:O'.$row)->getFont()
                                ->setName('Arial')
                                ->setSize(9);
		$objWorksheet->getStyle('A1:O'.$row)
								->getAlignment()
								->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		
  
        $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
 
        // Sending headers to force the user to download the file
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="REPORT_TRANSACTION_'.date('dmY').'.xls"');
        header('Cache-Control: max-age=0');
 
        $objWriter->save('php://output');
		

			// $objWriter->save('upload/REPORT_METER_'.date('dmY').'.xls');


		
	}
	
}
