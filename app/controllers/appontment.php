<?php

/**
 * @file: appontment.php
 * @package:    e:\openserver7\OpenServer\domains\localhost\f3-url-shortener\app\controllers
 * @created:    Mon Feb 03 2020
 * @author:     oppo
 * @version:    1.0.0
 * @modified:   Monday February 3rd 2020 4:14:13 pm
 * @copyright   (c) 2008-2020 1plus GmbH, DE All rights reserved.
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

//PM (11.03.2019) Bloom´s // Webseite // Terminbuchung // Einführung Kundenkonto/ReconnectID >> Produktion
if (!defined('APPOINTMENT_RECONNECT_ID')) {
	define('APPOINTMENT_RECONNECT_ID', 'pagecon_appointment_reconnect_id');
}

class appontment extends Controller
{
	public function index()
	{
		$this->f3->set('isHomePage', false);
		$this->f3->set('title', 'appontment');
		$this->f3->set('view', 'appontment.html');
		$this->f3->set('classfoot', 'appontment');
		// ADD JS
	}

	public function createAppontmentCodeWithoutAccount()
	{
		$kliz_sel = [
			'success' => false,
			'appointment_created' => false,
			'appointment_id' => false,
			'sms_sent' => false,
			'error_code' => 64
			// 'error_code' => 0
		];

		$ip = $this->f3->get('IP');
		$local = $ip == '127.0.0.1' ? true : false;

		$test = (int) helperblooms::inGet('test', 0);
		// log for localhost
		if ($local) {
			$logger = new \Log($this->f3->get('LOGS') . 'createAppontment.log');
			$logger->write(var_export($_POST, true));
		}

		$this->f3->set('SESSION.pdf_sess', null);

		if ($test || $this->f3->get('AJAX')) {
			if ($test || ($this->f3->exists('POST.user_data_tab_three') && $this->f3->exists('POST.available_termine'))) {
				$data = $this->f3->get('POST');

				$this->f3->set('SESSION.pdf_sess', $data);

				// $this->f3->set('SESSION.pdf_sess', array());
				// $this->f3->push('SESSION.pdf_sess', array('type' => 'warning', 'msg' => 'Please wait for your account to be approved before submitting forms!'));

				// mode: "createAppontmentCodeWithoutAccount",
				// vorname: vorname,
				// nachname: nachname,
				// email: email,
				// mobilenumber: mobilenumber,
				// salonId: salonId,
				// mitarbeiterId: mitarbeiterId,
				// date: date,
				// time: time,
				// dienstleistungId: dienstleistungId,
				// vertretung: vertretung,
				// pagecon_appointment_reconnect_id: true

				// array (
				// 	'user_data_tab_three' =>
				// 	array (
				// 	'vertretung' => 'null',
				// 	'vorname' => 'Alexopopo',
				// 	'nachname' => 'ONEPLUS',
				// 	'email' => 'info@1plus-agency.com',
				// 	'mobilenumber' => '01645854290',
				// 	),
				// 	'available_termine' =>
				// 	array (
				// 	'salonId' => '25',
				// 	'termineDate' => '2020-02-10',
				// 	'termineTime' => '9:45 AM',
				// 	'dienstleistungId' => '4',
				// 	'dienstleistungName' => 'Schnitt + Finish',
				// 	'dienstleistungDescription' => '• Kompetente Beratung mit mehreren Vorschlägen
				// 	• Individuell auf ihre Haarstruktur abgestimmter Haarschnitt
				// 	• Professionelles Finish mit Rundbürste, Lockenstab oder Glätteisen',
				// 	'wochenTagFertig' => 'понеділок 10 лютий 2020',
				// 	'mitarbeiterId' => '912',
				// 	'mitarbeiterName' => 'Lisa Täubl',
				// 	'mitarbeiterFile' => 'http://localhost/f3-url-shortener/assets/images/employeeimage/emp_image.jpg',
				// 	'salonAddress' => 'Elisabethenstr. 8, Darmstadt ',
				// 	),
				// 	'pagecon_appointment_reconnect_id' => 'true',
				// 	)

				$reconnect = null;
				$comfort = helperblooms::inPOST(APPOINTMENT_RECONNECT_ID);
				if (isset($_COOKIE[APPOINTMENT_RECONNECT_ID]) && ($reconnectId = $_COOKIE[APPOINTMENT_RECONNECT_ID])) {
					//DO NOT MERGE
					if ($comfort) {
						$reconnect = $reconnectId;
						$response['comfort'] = true;
					} else {
						$now = time();
						setcookie(APPOINTMENT_RECONNECT_ID, '', $now - 3600); //destroy the cookie
						#$now += 1800; //limit the validity of the remaining data to 30min
					}
				}

				$user_data_tab_three = bloomArrayHelper::getValueJoom($data, 'user_data_tab_three', null, 'ARRAY');
				$available_termine = bloomArrayHelper::getValueJoom($data, 'available_termine', null, 'ARRAY');

				//available_termine
				$salonId = bloomArrayHelper::getValueJoom($available_termine, 'salonId', null, 'INT');
				$mitarbeiterId = bloomArrayHelper::getValueJoom($available_termine, 'mitarbeiterId', null, 'INT');
				$datum = bloomArrayHelper::getValueJoom($available_termine, 'termineDate', null, 'STRING');
				$zeit = bloomArrayHelper::getValueJoom($available_termine, 'termineTime', null, 'STRING');
				$dienstleistungId = bloomArrayHelper::getValueJoom($available_termine, 'dienstleistungId', null, 'INT');
				// user_data_tab_three
				$mobilenumber = bloomArrayHelper::getValueJoom($user_data_tab_three, 'mobilenumber', null, 'STRING');
				$email = bloomArrayHelper::getValueJoom($user_data_tab_three, 'email', null, 'STRING');

				$vorname = bloomArrayHelper::getValueJoom($user_data_tab_three, 'vorname', null, 'STRING');
				$nachname = bloomArrayHelper::getValueJoom($user_data_tab_three, 'nachname', null, 'STRING');
				$name = $vorname . ' ' . $nachname;
				//'vertretung' => 'null',
				$vertretung = bloomArrayHelper::getValueJoom($user_data_tab_three, 'vertretung', false, 'STRING');
				$employee = $vertretung ? 'true' : 'false';

				if ($test > 0 || $local) {
					//$salonId, $mitarbeiterId, $datum, $zeit, $dienstleistungId, $mobilenumber, $email, $name, $employee, $reconnect
					$salonId = 24;
					$mitarbeiterId = '912';
					$datum = '2020-02-10';
					$zeit = '9:45 AM';
					$dienstleistungId = '4';
					$mobilenumber = '01645854290';
					$email = 'info@1plus-agency.com';
					$name = 'Alex ONEPLUS';
					// $employee = $_POST["vertretung"];
					$employee = 'true';
					$reconnect = null;
				}

				$appontmentModel = new appontmentModel();
				$appointmentCreated = $appontmentModel->createAppontmentCodeWithoutAccount($salonId, $mitarbeiterId, $datum, $zeit, $dienstleistungId, $mobilenumber, $email, $name, $employee, $reconnect);

				if (!empty($appointmentCreated) && is_object($appointmentCreated)) {
					// stdClass::__set_state(array(
					// 	'AppointmentCreated' => false,
					// 	'AppointmentId' => NULL,
					// 	'ConfirmationCodeExpiryDate' => '0001-01-01T00:00:00',
					// 	'ErrorCode' => 256,
					// 	'ReconnectId' => NULL,
					// 	'ReturnValueText' => 'NoOnlineAppointmentSalon',
					// 	'SMSSent' => false,
					//  ))

					//PM (11.03.2019) Bloom´s // Webseite // Terminbuchung // Einführung Kundenkonto/ReconnectID >> Produktion :: update the reconnect id
					$success = (int) $appointmentCreated->ErrorCode === 0;
					$reconnectId = (string) $appointmentCreated->ReconnectId;
					if ($comfort && $success && $reconnectId) {
						setcookie(
							//update the cookie lifespan
							APPOINTMENT_RECONNECT_ID,
							$reconnectId,
							time() + 86400 * 365, //expires in 365 days
							'/',
							$_SERVER['SERVER_NAME'],
							true,
							true
						);
					}

					$kliz_sel = [
						'success' => $success,
						'appointment_created' => (bool) $appointmentCreated->AppointmentCreated,
						'appointment_id' => (string) $appointmentCreated->AppointmentId,
						'sms_sent' => (bool) $appointmentCreated->SMSSent,
						'error_code' => (int) $appointmentCreated->ErrorCode
					];
				} elseif ($local) {
					$kliz_sel = [
						'success' => false,
						'appointment_created' => false,
						'appointment_id' => false,
						'sms_sent' => false,
						// 'error_code' => 64
						'error_code' => 0
					];
				}
			}
		}
		/** @FIX by oppo (webiprog.de), @Date: 2020-11-03 12:37:50
		 * @Desc: add tool for  appointment
		 */
		$this->f3->set(
			"SESSION.pdf_sess",
			array_merge(
				$this->f3->get('SESSION.pdf_sess'),
				['appointment'=>$kliz_sel]
			)
		);


		/** @FIX by oppo (webiprog.de), @Date: 2020-11-03 12:11:47
		 * @Desc: fixed schmutzige indische code
		 * $kliz_sel['plusdata'] = $appointmentCreated;
		 * $appointmentCreated may not be defined or not installed and cause an error
		 */
		// $kliz_sel['plusdata'] = $appointmentCreated; // fix
		$kliz_sel['plusdata'] = (isset($appointmentCreated) && is_object($appointmentCreated)) ?$appointmentCreated:null;
		header('Content-Type: application/json');
		print json_encode($kliz_sel);
		exit();
	}

	public function confirmAppontmentCode()
	{
		$kliz_sel = [
			'success' => false,
			'returncodevalue' => '0:0'
		];

		$ip = $this->f3->get('IP');
		$local = $ip == '127.0.0.1' ? true : false;

		$test = (int) helperblooms::inGet('test', 0);
		// log for localhost
		if ($local) {
			$logger = new \Log($this->f3->get('LOGS') . 'confirmAppontmentCode.log');
			$logger->write(var_export($_POST, true));
		}

		if ($test || $this->f3->get('AJAX')) {
			if ($test || $this->f3->exists('POST.code')) {
				$data = $this->f3->get('POST');
				$code = $data['code'];

				if ($test) $code = 'WSPN';
				// if ($local) {
				// 	$logger = new \Log($this->f3->get('LOGS') . 'code.log');
				// 	$logger->write($code);
				// }
				$appontmentModel = new appontmentModel();
				$codeConfirmed = null;

				try {
					$codeConfirmed = $appontmentModel->confirmAppontmentCode($code);
				} catch (Exception $e) {

					$logger = new \Log($this->f3->get('LOGS') . 'error_sms_code.log');
					$sms_error = ['code' => $code, 'error' => $e->getMessage()];
					$logger->write(json_encode($sms_error));
				}

				if (!empty($codeConfirmed) && is_object($codeConfirmed)) {

					// $logger = new \Log($this->f3->get('LOGS') . 'codeConfirmed_sms.log');
					// $logger->write(json_encode($codeConfirmed));

					// Fri, 19 Jun 2020 12:13:34 +0200 [178.151.40.213] {"Appointment":{"AppointmentId":"012ecbd8-d5d2-4c16-8363-9efb48a7f19d","AppointmentServiceFormula":null,"ArticleServiceIds":["650c49f5-4bf0-11db-bede-000b6a26b60d","9cee89c4-ec6c-4df8-a55f-687d1b42b199"],"CustomerEmail":"svizina@gmail.com","CustomerName":"oppo demo","CustomerPhone":"01703225110","EmployeeId":214,"IsCheckedIn":false,"IsPaid":false,"SalonId":2,"ServicePackageIds":[2],"SpecificEmployee":false,"StartDate":"2020-06-29T13:00:00"},"AdditionalInfo":null,"ReturnCodeValue":1,"ReturnValueText":"AllFine"}

					// {"Appointment":null,"AdditionalInfo":null,"ReturnCodeValue":4,"ReturnValueText":"AppointmentAlreadyConfirmed"}

					// $returncodevalue = $codeConfirmed->ReturnCodeValue . ':' . $codeConfirmed->Appointment;
					$return_codevalue = isset($codeConfirmed->ReturnCodeValue)?$codeConfirmed->ReturnCodeValue:'';
					$return_valuetext = isset($codeConfirmed->ReturnValueText)?$codeConfirmed->ReturnValueText:'';

					$returncodevalue = $return_codevalue . ':' . $return_valuetext;

					$kliz_sel = [
						'success' => true,
						'returncodevalue' => $returncodevalue
					];
				} elseif ($local) {
					// test
					$kliz_sel = [
						'success' => false,
						'returncodevalue' => '1:0'
					];
				}
			} else {
				$kliz_sel = [
					'success' => false,
					'returncodevalue' => '2:0'
				];
			}
		}

		header('Content-Type: application/json');
		print json_encode($kliz_sel);
		exit();
	}
	/**
	 * Appointment resendConfirmationCode
	 * A controller for handling appointments.
	 *
	 * @return void
	 */
	public function resendConfirmationCode()
	{
		$kliz_sel = [
			'success' => false,
			'returncodevalue' => '0:0'
		];

		$ip = $this->f3->get('IP');
		$local = $ip == '127.0.0.1' ? true : false;

		$test = (int) helperblooms::inGet('test', 0);
		// log for localhost
		if ($local) {
			$logger = new \Log($this->f3->get('LOGS') . 'resendConfirmationCode.log');
			$logger->write(var_export($_POST, true));
		}

		if ($test || $this->f3->get('AJAX')) {
			if ($test || ($this->f3->exists('POST.appointmentId') && $this->f3->exists('POST.mobilenumber'))) {
				$data = $this->f3->get('POST');
				$appointmentId = $data['appointmentId'];
				$mobilenumber = $data['mobilenumber'];

				$appontmentModel = new appontmentModel();
				$resendConfirm = $appontmentModel->resendConfirmationCode($appointmentId, $mobilenumber);
				if (!empty($resendConfirm) && is_object($resendConfirm)) {
					$returncodevalue = $resendConfirm->ErrorCode;
					$kliz_sel = [
						'success' => true,
						'returncodevalue' => $returncodevalue
					];
				} elseif ($local) {
					// test
					$kliz_sel = [
						'success' => true,
						'returncodevalue' => '1:0'
					];
				}
			} else {
				$kliz_sel = [
					'success' => false,
					'returncodevalue' => '2:0'
				];
			}
		}

		header('Content-Type: application/json');
		print json_encode($kliz_sel);
		exit();
	}

	public function testupdate()
	{
		// $employee = new Employee( $this->db );
		// $user     = null;
		// if ( $this->f3->exists( 'POST.update' ) ) {
		//     $this->f3->set( 'POST.updated_by', $this->f3->get( 'SESSION.id' ) );
		//     $data  = $this->f3->get( 'POST' );
		//     $valid = Validate::is_valid( $data, array(
		//         'first_name'            => 'required|valid_name',
		//         'last_name'             => 'valid_name',
		//         'mobile'                => 'phone_number',
		//         'email'                 => 'valid_email',
		//         'permenant_address'     => 'required|street_address',
		//         'communication_address' => 'street_address',
		//         'parent_name'           => 'valid_name',
		//         'reference_name'        => 'valid_name',
		//         'reference_number'      => 'phone_number'
		//     ) );
		//     if ( $valid === true ) {
		//         $employee->edit( $this->f3->get( 'POST.id' ) );
		//         $this->f3->reroute( '/employee' );
		//     } else {
		//         $error = implode( '. ', $valid );
		//         \Flash::instance()->addMessage( $error, 'warning' );
		//         $this->f3->set( 'page_head', 'Create' );
		//         $this->f3->set( 'view', 'employee/form.htm' );
		//         echo Template::instance()->render( 'layout.htm' );
		//     }
		// } else {
		//     $employee->getByid( $this->f3->get( 'PARAMS.id' ) );
		//     $this->f3->set( 'employee', $user );
		//     $this->f3->set( 'page_head', 'Update' );
		//     $this->f3->set( 'view', 'employee/form.htm' );
		//     echo Template::instance()->render( 'layout.htm' );
		// }
	}
}
