<?php

if ( ! defined( 'ABSPATH' ) )
	exit;

if ( ! class_exists( 'PWM_Pdf' ) ) {
	class PWM_Pdf {
		public static function create( $data, $action = 'save' ) {
			$logo_1_src = plugins_url( 'images/pdf-logo-1.jpg', PWM_FILE );
			$logo_2_src = plugins_url( 'images/PathwayMIS_Logo_Colour-1.jpg', PWM_FILE );
			$date = $data['date_submitted'];
			$date_from = date_i18n( 'd F Y', strtotime( $date ) );
			$date_to = date_i18n( 'd F Y', strtotime( $date . ' + 1 year - 1 day' ) );
			$date_month_1 = date_i18n( 'd F Y', strtotime( $date . ' + 1 month - 1 day' ) );
			$date_month_2 = date_i18n( 'd F Y', strtotime( $date . ' + 1 month' ) );
			$contact_name = 'Chris Langley Pathway MIS';
			$company_address = $data['company_address_line1'] . ' ' . $data['company_address_line2'] . ' ' . $data['company_address_line3'];

			$customer_number = get_option( 'pwm_sites_customer_number' ) ? get_option( 'pwm_sites_customer_number' ) + 1 : '0100';
			if ( $customer_number > 100 && $customer_number < 1000 ) {
				$customer_number = '0' . $customer_number;
			}
			if ( '0100' === $customer_number ) {
				add_option( 'pwm_sites_customer_number', $customer_number );
			} else {
				update_option( 'pwm_sites_customer_number', $customer_number );
			}

			$html = '
				<html>
					<head>
						<style>
							body {
								font-family: arial;
								font-size: 12px;
								color: #142344;
							}

							table td {
								vertical-align: top;
							}
						</style>
					</head>
					<body>
						<div class="page">
							<table style="width: 100%;">
								<tbody>
									<tr>
										<td style="width: 72%">
											<div style="float: left;">
												<div style="font-weight: bold;">We Know Print Ltd</div>
												<div style="font-weight: bold;">T/A Pathway MIS.</div>
												<div>19-21 Swan Street</div>
												<div>West Malling</div>
												<div>Kent</div>
												<div>ME19 6JU</div>
												<br>
												<div>Tel: 0800 107 0722</div>
												<div>www.pathwaymis.co.uk</div>
											</div>
										</td>
										<td style="width: 28%;">
											<img style="float: right; width: 300px;" src="' . $logo_1_src . '">
										</td>
									</tr>
								</tbody>
							</table>
							<br>
							<table style="width: 100%;">
								<tbody>
									<tr>
										<td style="width: 72%">
											<div>
												<div style="font-size: 14px; font-weight: bold;">' . $data['company_name'] . '</div>
												<div style="font-weight: bold;">' . $data['company_address_line1'] . '</div>';
												if ( ! empty( $data['company_address_line2'] ) ) {
													$html .= '<div style="font-weight: bold;">' . $data['company_address_line2'] . '</div>';
												}
												if ( ! empty( $data['company_address_line3'] ) ) {
													$html .= '<div style="font-weight: bold;">' . $data['company_address_line3'] . '</div>';
												}
												$html .= '<div style="font-weight: bold;">' . $data['registered_address_city'] . '</div>
												<div style="font-weight: bold;">' . $data['registered_address_postal_code'] . '</div>
											</div>
										</td>
										<td style="width: 28%">
											<div style="font-size: 10px; float: right;">
												<div>Customer Number: ' . $customer_number . '</div>
												<div>Date: ' . $date_from . '</div>
												<div>Contact: ' . $contact_name . '</div>
												<div>Tel: ' . $data['company_phone'] . '</div>
												<div>Email: ' . $data['company_email'] . '</div>
											</div>
										</td>
									</tr>
								</tbody>
							</table>
							<br>
							<br>
							<div>This agreement is made on ' . $date_from . ' between:</div>
							<br>
							<div style="font-weight: bold;">' . $data['company_name'] . ' (The Customer)</div>
							<div>Company Number: ' . $data['registration_number'] . ', Registered Address: ' . $data['registered_address_line1'] . ( ! empty( $data['registered_address_line2'] ) ? ', ' . $data['registered_address_line2'] : '' ) . ', ' . $data['registered_address_city'] . ', ' . $data['registered_address_postal_code'] . '</div>
							<br>
							<div style="font-weight: bold;">and</div>
							<br>
							<div>Company Number 10160343, Registered Address: 19-21 Swan Street, West Malling, Kent, ME19 6JU</div>
							<br>
							<br>
							<table>
								<tbody>
									<tr>
										<td style="width: 170px; padding-top: 3px; padding-bottom: 3px; font-weight: bold;">Length of Agreement:</td>
										<td style="padding-top: 3px; padding-bottom: 3px;">12 months ( rolling monthly )</td>
									</tr>
									<tr>
										<td style="width: 170px; padding-top: 3px; padding-bottom: 3px; font-weight: bold;">Details of Service:</td>
										<td style="padding-top: 3px; padding-bottom: 3px;">
											<ol>
												<li>Project Tracker</li>
												<li>Calendar View</li>
												<li>Quote Calculator</li>
											</ol>
										</td>
									</tr>
									<tr>
										<td style="width: 170px; padding-top: 3px; padding-bottom: 3px; font-weight: bold;">Service Charges:</td>
										<td>£49.00 (£58.80 incl VAT)</td>
									</tr>
									<tr>
										<td style="width: 170px; padding-top: 3px; padding-bottom: 3px; font-weight: bold;">Billing:</td>
										<td>Monthly</td>
									</tr>
									<tr>
										<td style="width: 170px; padding-top: 3px; padding-bottom: 3px; font-weight: bold;">Payment Type:</td>
										<td>Direct Debit</td>
									</tr>
									<tr>
										<td style="width: 170px; padding-top: 3px; padding-bottom: 3px; font-weight: bold;">Terms:</td>
										<td>Due on order</td>
									</tr>
								</tbody>
							</table>
							<br>
							<br>
							<br>
							<div style="padding: 40px 20px 20px; border: 2px solid #142344;">
								<table style="width: 100%;">
									<tbody>
										<tr>
											<td style="width: 70%;">
												<div style="font-size: 14px; font-weight: bold; color: #ffffff;">nbsp;</div>
												<div style="font-weight: bold;">..........................................................................................</div>
												<div style="font-size: 14px; font-weight: bold;">Signed on behalf of Pathway</div>
											</td>
											<td style="width: 30%;">
												<div style="font-size: 14px; font-weight: bold;">' . $date_from .  '</div>
												<div style="font-weight: bold;">..............................................</div>
												<div style="font-size: 14px; font-weight: bold;">Date</div>
											</td>
										</tr>
									</tbody>
								</table>
								<br>
								<br>
								<table style="width: 100%;">
									<tbody>
										<tr>
											<td>
												<div style="font-size: 14px; font-style: italic;">' . strtoupper( $contact_name ) . '</div>
												<div style="font-weight: bold;">.........................................................................................................................................................</div>
												<div style="font-size: 14px; font-weight: bold;">Print Name</div>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<br>
							<br>
							<br>
							<p style="font-weight: bold; text-align: center;"><span style="font-size: 8px;">We Know Print Ltd </span>T/A Pathway MIS.</p>
							<div style="text-align: center;">
								<span style="font-size: 8px;">0800 107 0722</span>
								<span style="color: #ffffff;">nbsp;</span>
								<span style="font-size: 8px;">sales@pathwaymis.co.uk</span>
								<span style="color: #ffffff;">nbsp;</span>
								<span style="font-size: 8px;">www.pathwaymis.co.uk</span>
							</div>
							<div style="font-size: 8px; text-align: center;">Company Address: 9 - 10 Starnes Court Union Street Maidstone Kent ME14 1EB</div>
							<div style="text-align: center;">
								<span style="font-size: 8px;">Company Registration Number: 10160343</span>
								<span style="color: #ffffff;">nbsp;</span>
								<span style="font-size: 8px;">Registered in England and Wales.</span>
								<span style="color: #ffffff;">nbsp;</span>
								<span style="font-size: 8px;">VAT No: 241061454</span>
							</div>
						</div>
						<!-- 1st page -->
						<div class="page">
							<div style="text-align: center;">
								<img style="width: 400px;" src="' . $logo_2_src . '">
							</div>
							<br>
							<br>
							<div style="text-align: center;">
								<div style="font:size: 16px !important; font-weight: bold;">Pathway Contract</div>
								<br>
								<div style="font:size: 14px !important;">This agreement is made on ' . $date_from .  ' between:</div>
								<br>
								<div style="font:size: 16px !important; font-weight: bold;">' . $data['company_name'] . ' (The Customer)</div>
								<div style="font:size: 14px !important;">Company Number ' . $data['registration_number'] . '</div>
								<div style="font:size: 14px !important;">Registered Address: ' . $data['registered_address_line1'] . ( ! empty( $data['registered_address_line2'] ) ? ', ' . $data['registered_address_line2'] : '' ) . ', ' . $data['registered_address_city'] . ', ' . $data['registered_address_postal_code'] . '</div>
								<br>
								<div style="font:size: 16px !important; font-weight: bold;">and</div>
								<br>
								<div style="font:size: 16px !important; font-weight: bold;">Pathway</div>
								<div style="font:size: 14px !important;">Company Number 10160343</div>
								<div style="font:size: 14px !important;">Registered Address: 19-21 Swan Street, West Malling, Kent, ME19 6JU</div>
							</div>
							<br>
							<br>
							<p style="font-weight: bold;">Length of the agreement;</p>
							<ol style="padding: 0 0 0 17px;">
								<li style="margin: 0 0 10px !important;">This contract commences on ' . $date_from . ' and concludes on ' . $date_to . '. Rolling monthly contract which can be cancelled at anytime during the agreement with one calendar month notice given.</li>
								<li>A period commencing ' . $date_from . ' to ' . $date_month_1 . ' is a free of charge period within the contract, if the customer continues after the trail period they are obliged to see out the entirety of this contract in line with clause 1 of Length of Agreement.</li>
							</ol>
							<p style="font-weight: bold;">Terms and Conditions;</p>
							<ol style="padding: 0 0 0 17px;">
								<li style="margin: 0 0 10px !important;">Payment by the Customer is required in full by monthly direct debit. Invoices are prepared and dispatched by Pathway the month the service is given.</li>
								<li style="margin: 0 0 10px !important;">Should for any reason an invoice becomes overdue, Pathway reserves the right to instigate legal proceedings or instruct recovery agents to recover overdue debt and claim back extra costs incurred by this action from the customer. If such action is taken, Pathway will invoice for the remaining contracted period to include in their claim. If the Customer fails to pay any of the feed and charges due hereunder (which are not in dispute) and fails to cure such delinquency within thirty (30) days following notice of nonpayment, Pathway shall have the right, in its sole and absolute discretion, to immediately suspend all or a part of the Services. The Customer shall remain liable for all unpaid fees and charges incurred during any period of suspension, notwithstanding that all or a part of the Services may not have been provided by Pathway.</li>
								<li style="margin: 0 0 10px !important;">Pathway reserves the right to terminate this Agreement immediately if the Customer ceases to carry on business, is wound up or is dissolved.</li>
								<li style="margin: 0 0 10px !important;">The Customer will not copy, translate, adapt or modify any of Pathway’s code or analytics which shall remain the property of Pathway absolutely at all times. For the avoidance of doubt all materials, processes, initiatives, data systems or software, code, specifications, designs, database rights, and rights in designs or inventions shall belong to Pathway absolutely.</li>
								<li style="margin: 0 0 10px !important;">The Customer hereby agrees to keep in strict confidence all technical or commercial know how, specifications, inventions, processes or initiatives which are of a confidential nature which are the property of Pathway but which have been disclosed to you by Pathway.</li>
								<li style="margin: 0 0 10px !important;">
									Nothing in this document shall limit or exclude Pathway’s liability for:
									<ul style="padding: 0; list-style: none;">
										<li>a) death or personal injury caused by its negligence, or the negligence of its employees, agents or subcontractors;</li>
										<li>b) fraud or fraudulent misrepresentation;</li>
										<li>c) breach of the terms implied by section 2 of the Supply of Goods and Services Act 1982;</li>
										<li>OR</li>
										<li>d) any other liability which cannot be limited by law.</li>
									</ul>
									<br>
									Subject to 6(a)-(d) above:
									<ul style="margin: 0 0 10px !important; padding: 0; list-style: none;">
										<li>a) Pathway shall not be liable to the Customer, whether in contract, tort (including negligence), breach of statutory duty, or otherwise, for any loss of profit, or any special, indirect or consequential loss arising under or in connection with this contract; and</li>
										<li>b) Pathway’s total liability to the Customer in respect of all other losses arising under or in connection with this contract, whether in contract, tort (including negligence), breach of statutory duty, or otherwise, shall not exceed the sums paid by the Customer to Pathway in the 12 months preceding the date of commencement of such claims.</li>
										<li></li>
										<li></li>
										<li></li>
									</ul>
									Except as set out in this document, all warranties, conditions and other terms implied by statute or common law are, to the fullest extent permitted by law, excluded from this contract.
								</li>
								<li>No amendments to this document will be accepted unless in writing and signed and initialled by an authorised representative of Pathway and the Customer. No other representations or conversations form part of this contract.</li>
								<li>
									<p>Should the Customer wish to terminate this agreement at the end of the contracted period stated in point 1 of length of agreement, confirmation must be written on headed paper, signed and emailed to sales@pathwaymis. co.uk to reach Pathway by ' . $date_month_1 . ' whereupon this agreement will cease at the end of the contracted period but without prejudice to any rights that Pathway may have against the Customer under this agreement.</p>
									<p>If no written notice is provided by the Customer to Pathway this Agreement shall continue for a further period of 12 months from ' . $date_month_2 . ' and shall continue to do so every 12 months thereafter.</p>
								</li>
								<li>a) For the purposes of this contract, Force Majeure event means an event beyond the reasonable control of Pathway including but limited to strikes, lock-outs or other industrial disputes (whether involving the workforce of Pathway or any other party), failure of a utility service or transport network, act of God, war, riot, civil commotion, malicious damage, compliance with any law or governmental order, rule, regulation or direction, accident, breakdown of plant or machinery, fire, flood, storm or default of suppliers or subcontractors.<br>
								b) Pathway shall not be liable to the Customer as a result of any delay or failure to perform its obligations under this contract as a result of a Force Majeure event.<br>
								c) If the Force Majeure event prevents Pathway from providing any of the services under this contract for more than 8 weeks, Pathway shall, without limiting its other rights or remedies, have the right to terminate this contract immediately by giving written notice to the Customer.</li>
								<li>The laws of England and Wales shall govern the performance of the agreement and the English courts will have exclusive jurisdiction thereon.</li>
							</ol>
							<br>
							<div style="padding: 40px 20px 20px; border: 2px solid #142344;">
								<table style="width: 100%;">
									<tbody>
										<tr>
											<td style="width: 70%;">
												<div style="font-size: 14px; font-weight: bold; color: #ffffff;">nbsp;</div>
												<div style="font-weight: bold;">..........................................................................................</div>
												<div style="font-size: 14px; font-weight: bold;">Signed on behalf of Creative Gifts</div>
											</td>
											<td style="width: 30%;">
												<div style="font-size: 14px; font-weight: bold; color: #ffffff;">nbsp;</div>
												<div style="font-weight: bold;">..............................................</div>
												<div style="font-size: 14px; font-weight: bold;">Date</div>
											</td>
										</tr>
									</tbody>
								</table>
								<br>
								<br>
								<table style="width: 100%;">
									<tbody>
										<tr>
											<td>
												<div style="font-size: 14px; font-weight: bold; color: #ffffff;">nbsp;</div>
												<div style="font-weight: bold;">.........................................................................................................................................................</div>
												<div style="font-size: 14px; font-weight: bold;">Print Name</div>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<br>
							<br>
							<div style="padding: 40px 20px 20px; border: 2px solid #142344;">
								<table style="width: 100%;">
									<tbody>
										<tr>
											<td style="width: 70%;">
												<div style="font-size: 14px; font-weight: bold; color: #ffffff;">nbsp;</div>
												<div style="font-weight: bold;">..........................................................................................</div>
												<div style="font-size: 14px; font-weight: bold;">Signed on behalf of Pathway</div>
											</td>
											<td style="width: 30%;">
												<div style="font-size: 14px; font-weight: bold; color: #ffffff;">nbsp;</div>
												<div style="font-weight: bold;">..............................................</div>
												<div style="font-size: 14px; font-weight: bold;">Date</div>
											</td>
										</tr>
									</tbody>
								</table>
								<br>
								<br> <table style="width: 100%;">
									<tbody>
										<tr>
											<td>
												<div style="font-size: 14px; font-style: italic;">' . strtoupper( $contact_name ) . '</div>
												<div style="font-weight: bold;">.........................................................................................................................................................</div>
												<div style="font-size: 14px; font-weight: bold;">Print Name</div>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</body>
				</html>
			';

			require_once ( plugin_dir_path( PWM_FILE ) . 'inc/mpdf/mpdf.php' );

			$filename = sanitize_file_name( 'Pathway-Contract-' . $data['company_name'] . '.pdf' );

			if ( 'download' == $action ) {
				$flag = 'D';
				$result = '';
			} else {
				$flag = 'F';
				$upload_dir = wp_upload_dir();
				$filename = $upload_dir['path'] . '/' . $filename;
				$result = $filename;
			}

			$mpdf = new mPDF();
			$mpdf->WriteHTML( $html );
			$mpdf->Output( $filename, $flag );

			return $result;
		}
	}
}