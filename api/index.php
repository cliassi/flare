<?php error_reporting(E_ALL); ini_set('display_errors', 1);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
// header('Content-Type: application/json'); 
session_start();
// header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Methods: GET, POST');
$_SESSION['app'] = 'flare';
require "../safeboot.php";

$get = array(); foreach ($_GET as $key => $value) { $get[$key] = $value; } unset($_GET); $get = (object)$get;
$post = array(); foreach ($_POST as $key => $value) { $post[$key] = $value; } unset($_POST); $post = (object)$post;
$input = json_decode(file_get_contents('php://input'));
// require "phpqrcode/qrlib.php";
$action = trim(isset($get->q)?$get->q:'');
$actions = ['banks','cashout','changePassword','changeSecurityQuestions','changeSettings','createBankAccount','createEvent','editBankAccount','events','login','removeBankAccount','register','securityQuestions','transfer','updateMember', 'members'];

$output = ["response"=>"500", "responseMsg"=>"Unauthorized Access"];
switch ($action) {
	case 'publishers':{		
		$publishers = R::find("publisher");
		$output['response'] = 1;
		$output['responseMsg'] = 'Success';
		$output['publishers'] = [];
		foreach ($publishers as $key => $value) {
			$p = [];
			$p['id'] = $value->id;
			$p['name'] = $value->name;
			$p['logo'] = "http://w1.ossup.com/app/flare/uploads/publisher/$value->id/$value->logo";
			array_push($output['publishers'], $p);
		}
	} break;
	case 'publisher':{		
		$publisher = R::load("publisher", $input->id);
		$output['response'] = 1;
		$output['responseMsg'] = 'Success';
		$output['publishers'] = [];

		$p = [];
		$p['id'] = $publisher->id;
		$p['name'] = $publisher->name;
		$p['logo'] = "http://w1.ossup.com/app/flare/uploads/publisher/$publisher->id/$publisher->logo";
		array_push($output['publishers'], $p);
	} break;
	case 'services':{		
		$services = R::find("service", "publisher=?", [$input->publisher]);
		$output['response'] = 1;
		$output['responseMsg'] = 'Success';
		$output['services'] = [];
		foreach ($services as $key => $value) {
			$p = [];
			$p['id'] = $value->id;
			$p['name'] = $value->name;
			$p['publisher'] = $value->publisher;
			$p['pages'] = $value->number_of_pages;
			array_push($output['services'], $p);
		}
	} break;
	case 'service':{		
		$service = R::load("service", $input->id);
		$output['response'] = 1;
		$output['responseMsg'] = 'Success';
		$output['services'] = [];

		$p = [];
		$p['id'] = $service->id;
		$p['name'] = $value->name;
		$p['publisher'] = $service->publisher;
		$p['pages'] = $service->number_of_pages;
		array_push($output['services'], $p);
	} break;
	case 'categories':{
		// $categories = R::find("page_pricing", "service=? AND page=? AND ? BETWEEN start_date AND end_date", [$input->service, $input->page, substr($input->date, 0, 10)]);
		$categories = select("p.*", "page_pricing p, page_pricing_date d", "p.id=d.pricing AND p.service=$input->service AND p.page=$input->page AND d.date='".substr($input->date, 0, 10)."'");
		$output['response'] = 1;
		$output['responseMsg'] = 'Success';
		$output['categories'] = [];
		// foreach ($categories as $key => $value) {
		while($value = mysqli_fetch_object($categories)){
			$p = [];
			$p['id'] = $value->id;
			$p['name'] = $value->name;
			// $p['pages'] = $value->number_of_pages;
			$p['publisher'] = $value->publisher;
			$p['service'] = $value->service;
			$p['page'] = $value->page;
			$p['language'] = $value->language;
			$p['width'] = $value->width;
			$p['height'] = $value->height;
			$p['column_size'] = $value->column_size;
			$p['min_col'] = $value->min_col;
			$p['max_col'] = $value->max_col;
			$p['min_col_width'] = $value->min_col_width;
			$p['max_col_width'] = $value->max_col_width;
			$p['bw_unit_price'] = $value->bw_unit_price;
			$p['color_unit_price'] = $value->color_unit_price;
			$p['min_words'] = $value->min_words;
			$p['max_words'] = $value->max_words;
			$p['init_words'] = $value->init_words;
			$p['additional_word_price'] = $value->additional_word_price;
			$p['fixed_place_rate'] = $value->fixed_place_rate;
			$p['bold_text_rate'] = $value->bold_text_rate;
			$p['first_position_rate'] = $value->first_position_rate;
			$p['in_screen_rate'] = $value->in_screen_rate;
			$p['in_box_rate'] = $value->in_box_rate;
			$p['daily_approval_limit'] = $value->daily_approval_limit;
			$p['master_agent_commission_bw'] = $value->master_agent_commission_bw;
			$p['agent_commission_bw'] = $value->agent_commission_bw;
			$p['master_agent_commission_color'] = $value->master_agent_commission_color;
			$p['agent_commission_color'] = $value->agent_commission_color;
			$p['number_of_column'] = $value->number_of_column;
			$p['price'] = $value->price;
			$p['agent_payment_method'] = $value->agent_payment_method;
			$p['advertiser_payment_method'] = $value->advertiser_payment_method;
			array_push($output['categories'], $p);
		}
	} break;
	case 'submit_request': {
		$ad = R::load('ad_request', $input->advert);
		$ad->status = 'Pending';
		R::store($ad);
		$output['response'] = 1;
		$output['responseMsg'] = 'Success!';
	} break;
	case 'price': {
		$output['response'] = 1;
		$output['responseMsg'] = 'Success!';
		$font = 'TIMES.TTF';
		$text = trim($input->text);
		if($text){
			$pricing = R::load("page_pricing", $input->category);
			$img = calculateTextBox(10, 0, $font, $text);
			$output['height'] = $img['height'];
			$output['width'] = $img['width'];
			if($input->color == 'bw'){
				$price = ceil($output['width'] / 72 / $pricing->column_size) * $pricing->bw_unit_price;
			} else{
				$price = ceil($output['width'] / 72 / $pricing->column_size) * $pricing->color_unit_price;
			}
			$extra = 0;

			if($input->fixed_place){
				$extra += $price * $pricing->fixed_place_rate / 100;
			}
			if($input->first_ad){
				$extra += $price * $pricing->first_position_rate / 100;
			}
			if($input->text_in_box){
				$extra += $price * $pricing->in_box_rate / 100;
			}
			if($input->bold_text){
				$extra += $price * $pricing->bold_text_rate / 100;
			}
			if($input->text_in_screen){
				$extra += $price * $pricing->in_screen_rate / 100;
			}

			$output['price'] = $price + $extra;
			$tax_rate = 15;
			$tax = $price * 15 / 100;
			$service_charge = 35;
			$total = $price + $tax + $service_charge + $extra;

			// $output['price'] = $price;
			$output['tax_rate'] = $tax_rate;
			$output['tax'] = $tax;
			$output['service_charge'] = $service_charge;
			$output['total'] = $total;

			if(isset($input->advert) && $input->advert){
				$ad = R::load('ad_request', $input->advert);
			} else{
				$ad = R::dispense('ad_request');
			}
			$ad->member = $input->member;
			$ad->publisher = $input->publisher;
			$ad->service = $input->service;
			$ad->page = $input->page;
			$ad->date = $input->date;
			$ad->message = $text;
			$ad->price = $price;
			$ad->tax = $tax;
			$ad->service_charge = $service_charge;
			$ad->total = $total;
			$ad->category = $input->category;
			$ad->color = $input->color;
			$ad->columns = $input->columns;
			$ad->fixed_place = $input->fixed_place;
			$ad->first_ad = $input->first_ad;
			$ad->text_in_box = $input->text_in_box;
			$ad->bold_text = $input->bold_text;
			$ad->text_in_screen = $input->text_in_screen;
			R::store($ad);
			$output['advert'] = $ad->id;
		} else{
			$output['height'] = 0;
			$output['width'] = 0;
			$price = 0;
			$output['price'] = 0;
		}
	} break;
	case 'price_old': {
		$font = 'TIMES.TTF';
		$text = trim($input->text);
		if($text){
			$img = calculateTextBox(10, 0, $font, $text);
			$output['height'] = $img['height'];
			$output['width'] = $img['width'];
			$price = ceil($output['width'] / 72 / 2.5) * 1500;
			$output['price'] = $price;
			$tax_rate = 15;
			$tax = $price * 15 / 100;
			$service_charge = 35;
			$total = $price + $tax + $service_charge;

			$output['price'] = $price;
			$output['tax_rate'] = $tax_rate;
			$output['tax'] = $tax;
			$output['service_charge'] = $service_charge;
			$output['total'] = $total;

			if(isset($input->advert) && $input->advert){
				$ad = R::load('ad_request', $input->advert);
			} else{
				$ad = R::dispense('ad_request');
			}
			$ad->member = $input->member;
			$ad->publisher = $input->publisher;
			$ad->service = $input->service;
			$ad->page = $input->page;
			$ad->date = $input->date;
			$ad->message = $text;
			$ad->price = $price;
			$ad->tax = $tax;
			$ad->service_charge = $service_charge;
			$ad->total = $total;
			R::store($ad);
			$output['advert'] = $ad->id;
		} else{
			$output['height'] = 0;
			$output['width'] = 0;
			$price = 0;
			$output['price'] = 0;
		}
	} break;
	case 'history':{
		// $ads = R::find('ad_request', "member=? ORDER BY id DESC", [$input->member]);
		$ads = select('ad.*, p.name publisher_name, p.logo, s.name service_name, pp.name category_name', 'ad_request ad, publisher p, service s, page_pricing pp', "member=$input->member AND ad.publisher = p.id AND ad.service = s.id AND ad.category=pp.id ORDER BY id DESC");
		$adverts = [];
		$output = [];
		$output['response'] = 1;
		$output['responseMsg'] = 'Success!';
		// foreach ($ads as $key => $ad) {
		while($ad = mysqli_fetch_object($ads)){
			$advert = [];
			$advert['id'] = $ad->id;
			$advert['member'] = $ad->member;
			$advert['publisher_name'] = $ad->publisher_name;
			$advert['logo'] = "http://w1.ossup.com/app/flare/uploads/publisher/$ad->publisher/$ad->logo";
			$advert['service_name'] = $ad->service_name;
			$advert['category_name'] = $ad->category_name;
			$advert['publisher'] = $ad->publisher;
			$advert['service'] = $ad->service;
			$advert['page'] = $ad->page;
			$advert['category'] = $ad->category;
			$advert['date'] = $ad->date;
			$advert['approved_date'] = $ad->approved_date;
			$advert['status'] = $ad->status;
			$advert['publishing_status'] = $ad->publishing_status;
			$advert['payment_status'] = $ad->payment_status;
			$advert['updated_by'] = $ad->updated_by;
			$advert['message'] = $ad->message;
			$advert['price'] = $ad->price;
			$advert['tax'] = $ad->tax;
			$advert['service_charge'] = $ad->service_charge;
			$advert['total'] = $ad->total;
			$advert['approved_price'] = $ad->approved_price;
			$advert['created_at'] = $ad->created_at;
			$advert['fixed_place'] = $ad->fixed_place;
			$advert['first_ad'] = $ad->first_ad;
			$advert['bold_text'] = $ad->bold_text;
			$advert['text_in_screen'] = $ad->text_in_screen;
			$advert['text_in_box'] = $ad->text_in_box;
			$advert['color'] = $ad->color;
			$advert['columns'] = $ad->columns;
			array_push($adverts, $advert);
		}
		$output['adverts'] = $adverts;
	} break;

	case 'inbox_read':{
		$output = [];
		update("inbox", "`read`=1", "id=$input->id");
		$output['response'] = 1;
		$output['responseMsg'] = 'Success';
	} break;

	case 'inbox':{
		$output = [];
		$messages = [];
		$output['count'] = 0;
		$inbox_items = select("i.*", "inbox i, ad_request r", "r.id=i.ad_request_id AND r.member = $input->member", "ORDER BY `read`");
		while ($inbox = mysqli_fetch_object($inbox_items)) {
			$message['id'] = $inbox->id;
			$message['date'] = date("d M Y h:i A", strtotime($inbox->entry_time));
			$message['message'] = $inbox->message;
			if($inbox->read == 0){
				$output['count']++;
			}
			$ads = select('ad.*, p.name publisher_name, p.logo, s.name service_name, pp.name category_name', 'ad_request ad, publisher p, service s, page_pricing pp', "ad.id=$inbox->ad_request_id AND ad.publisher = p.id AND ad.service = s.id AND ad.category=pp.id");
			$ad = mysqli_fetch_object($ads);
			$advert = [];
			$advert['id'] = $ad->id;
			$advert['member'] = $ad->member;
			$advert['publisher_name'] = $ad->publisher_name;
			$advert['logo'] = "http://w1.ossup.com/app/flare/uploads/publisher/$ad->publisher/$ad->logo";
			$advert['service_name'] = $ad->service_name;
			$advert['category_name'] = $ad->category_name;
			$advert['publisher'] = $ad->publisher;
			$advert['service'] = $ad->service;
			$advert['page'] = $ad->page;
			$advert['category'] = $ad->category;
			$advert['date'] = $ad->date;
			$advert['approved_date'] = $ad->approved_date;
			$advert['status'] = $ad->status;
			$advert['publishing_status'] = $ad->publishing_status;
			$advert['payment_status'] = $ad->payment_status;
			$advert['updated_by'] = $ad->updated_by;
			$advert['message'] = $ad->message;
			$advert['price'] = $ad->price;
			$advert['tax'] = $ad->tax;
			$advert['service_charge'] = $ad->service_charge;
			$advert['total'] = $ad->total;
			$advert['approved_price'] = $ad->approved_price;
			$advert['created_at'] = $ad->created_at;
			$advert['fixed_place'] = $ad->fixed_place;
			$advert['first_ad'] = $ad->first_ad;
			$advert['bold_text'] = $ad->bold_text;
			$advert['text_in_screen'] = $ad->text_in_screen;
			$advert['text_in_box'] = $ad->text_in_box;
			$advert['color'] = $ad->color;
			$advert['columns'] = $ad->columns;
			$message['advert'] = $advert;
			array_push($messages, $message);
		}
		
		$output['messages'] = $messages;
		$output['response'] = 1;
		$output['responseMsg'] = 'Success';
	} break;
	case 'banks':{
		$banks = select("i.*, b.name bank_name", "bank_info i, banks b", "i.name = b.code AND member={$get->id}");
		//$banks = R::find("bank_info");
		$output = [];
		while ($bank = mysqli_fetch_object($banks)) {
			array_push($output, ['id'=> $bank->id, 'name'=>$bank->bank_name, 'code'=>$bank->name, 'account_no'=>$bank->account_no, 'fullname'=>$bank->fullname, 'payee'=>$bank->payee_id]);
		}
	} break;
	case 'transaction':{

	} break;
	case 'purchases':{
		$id = $get->id;
		$trans = select("SELECT *, e.description, ec.name event_category FROM `trans` t, `event` e, event_category ec WHERE t.event=e.id AND e.category=ec.id AND t.member=$id AND `type` IN('Payment')");
		$output = [];
		while($tran = mysqli_fetch_object($trans)){
			array_push($output, ['id'=> $tran->id, 'date'=>$tran->time, 'category'=>$tran->event_category, 'amount'=>$tran->amount]);
		}
	} break;
	case 'egifts':{
		$id = $get->id;
		$trans = select("SELECT t.*, e.description FROM `trans` t, event e WHERE t.event=e.id AND t.member=$id AND `type` IN('eGift')");
		$output = [];
		while($tran = mysqli_fetch_object($trans)){
			array_push($output, ['id'=>$tran->id, 'date'=>df($tran->time), 'category'=>'e-Gift', 'event'=>$tran->description, 'amount'=>$tran->amount]);
		}
	} break;
	case 'receives':{
		$id = $get->id;
		$trans = select("SELECT t.*, e.description FROM `trans` t, event e WHERE t.event=e.id AND t.member=$id AND `type` IN('eGift')");
		$output = [];
		while($tran = mysqli_fetch_object($trans)){
			array_push($output, ['id'=>$tran->id, 'date'=>df($tran->time), 'category'=>'e-Gift', 'event'=>$tran->description, 'amount'=>$tran->amount]);
		}
	} break;
	case 'cashout':{
		$id = $get->id;
		$bank = $get->bank;
		$token = $get->token;
		$amount = $get->amount;
		$member = R::load("member", $id);
		$bank_info = R::load("bank_info", $bank);

		$payee_bank_account_no = $bank_info->account_no;
		$payee_bank_name = $bank_info->name;
		$payee_identification_id = $bank_info->payee_id;
		$payee_name = $bank_info->fullname;
		$transfer_amount = $amount;
		$transfer_reason = 'Cashout from BigDay to Personnal Account';

		$response = cashout($token, $payee_bank_account_no, $payee_bank_name, $payee_identification_id, $payee_name, $transfer_amount, $transfer_reason);
		$webcash_response = json_decode($response);
		//var_dump($webcash_response);
		if($webcash_response->success){
			addTran($member->id, 'Cashout', $amount, $response);
			$output = [];
			$output['response'] = '100';
			$output['responseMsg'] = 'Success';
			$output['new_balance'] = $webcash_response->data->new_balance;
			$member->balance = $webcash_response->data->new_balance;
		} else{
			$output = [];
			$output['response'] = '0';
			$output['responseMsg'] = $webcash_response->message;
		}
	} break;
	case 'changePassword':{
		extract($_REQUEST);
		$user = R::load("member", $id);
		if($user){
			$webcash_response = json_decode(updateProfile($token, 
				[
					'password'=>$password, 
					'verify_password'=>$password
				]));
			if($webcash_response->success){
				$user->password = md5($password);
				R::store($user);
				$output = [];
				$output['response'] = '100';
				$output['responseMsg'] = 'Success';
			} else{
				$output = [];
				$output['response'] = '0';
				$output['responseMsg'] = 'Failed';
				$output['additionalInfo'] = 'Webcash Failed';
				$output['webcashData'] = $webcash_response->data;
			}			
		} else{
			$output = [];
			$output['response'] = '0';
			$output['responseMsg'] = 'Failed';
		}
	} break;
	case 'createBankAccount':{
		try{
			extract($_REQUEST);
			$bank_info = R::dispense("bank_info");
			if(isset($bank_id)){
				if($bank_id){
					$bank_info = R::load("bank_info", $bank_id);
				}
			}
			$bank_info->name = $bank;
			$bank_info->account_no = $bank_account_no;
			$bank_info->fullname = $fullname;
			$bank_info->payee_id = $payee_id;
			$bank_info->member = $member;

			R::store($bank_info);
			$output = [];
			$output['response'] = '100';
			$output['responseMsg'] = $bank_info->id;
		} catch(Exception $ex){
			$output = [];
			$output['response'] = '0';
			$output['responseMsg'] = $ex->getMessage();
		}
	} break;
	case 'createEvent':{
		try{
			extract($_REQUEST);
			$rows = select("SELECT id FROM `event` WHERE member='$member' AND category='$eventCategory' AND YEAR(DATE)=YEAR(CURDATE())");
			if($rows->num_rows > 0){
				$output = [];
				$output['response'] = '0';
				$output['responseMsg'] = 'You cannot create same event more than once a year.';
			} else{
				$payment_request = R::dispense("payment_request");
				$payment_request->member = $member;
			    $payment_request->amount = $amount;
			    R::store($payment_request);

			    $payment_request->ref = "P".str_pad($payment_request->id, 10, '0', STR_PAD_LEFT);

			    if($amount > 0){
			    	$response = payment($token, $amount, $payment_request->ref, $gst_amount = "0.00");
			    	$webcash_response = json_decode($response);
			    	$message = $webcash_response->message;
			    	if($webcash_response->code == "success"){
				    	$payment_request->status = 'Success';
				    	$output['new_balance'] = $webcash_response->data->account_balance;
				    } else{
				    	$payment_request->status = 'Failed';
				    }
			    } else{
			    	$response = "";
			    	$message = "Success";
			    	$payment_request->status = 'Success';
			    	$output['new_balance'] = '';
			    }		    
			    
			    $payment_request->response = $response;
			    R::store($payment_request);

			    if($payment_request->status == 'Success'){
			    	$event = R::dispense("event");
					$event->category = $eventCategory;
					$event->member = $member;
					$event->can_receive_egift = $canReceiveEgift;
					$event->template = $eventEcard;
					$event->photos = $images;
					$event->photo1 = $imageFileName1;
					$event->photo2 = $imageFileName2;
					$event->photo3 = $imageFileName3;
					$event->photo4 = $imageFileName4;
					$event->photo5 = $imageFileName5;
					$event->photo6 = $imageFileName6;
					$event->date = $eventDate;
					$event->time = $eventTime;
					$event->venue = $eventVenue;
					$event->description = $eventDescription;
					$event->payment_method = $eventPaymentMethod;
					$event->payment_ref = $payment_request->ref;
					$event->entry_by = 0;

					R::store($event);
			    	$event->qr = "E".str_pad($event->id, 5, '0', STR_PAD_LEFT);
					QRcode::png($event->qr, "../assets/event_qr/$event->qr.png", 'L');
					$event->qr = $event->qr.".png";
					R::store($event);

					$output = [];
					$output['response'] = '100';
					$output['responseMsg'] = $message;
					$output['id'] = $event->id;
			    } else{
			    	$output = [];
					$output['response'] = '0';
					$output['responseMsg'] = $message;
			    }
			}
		} catch(Exception $ex){
			$output = [];
			$output['response'] = '0';
			$output['responseMsg'] = "Error";
			//var_dump($ex);
		}
	} break;
	// END createEvent
	// CreateDraftEvent
	case 'createDraftEvent':{
		try{
			extract($_REQUEST);		
	    	$event = R::dispense("event_draft");
			$event->category = $eventCategory;
			$event->member = $member;
			$event->can_receive_egift = $canReceiveEgift;
			$event->template = $eventEcard;
			$event->photos = $images;
			$event->photo1 = $imageFileName1;
			$event->photo2 = $imageFileName2;
			$event->photo3 = $imageFileName3;
			$event->photo4 = $imageFileName4;
			$event->photo5 = $imageFileName5;
			$event->photo6 = $imageFileName6;
			$event->date = $eventDate;
			$event->time = $eventTime;
			$event->venue = $eventVenue;
			$event->description = $eventDescription;
			$event->payment_method = $eventPaymentMethod;
			$event->entry_by = 0;

			R::store($event);
			$output = [];
			$output['id'] = $event->id;
			$output['date'] = date("M d, Y", strtotime($event->date));
			$output['ion_date'] = date("Y-m-d", strtotime($event->date));
			$output['time'] = $event->time;
			$output['description'] = $event->description;
			$output['template'] = $event->template;
			$output['photos'] = explode(",", $event->photos);
			$output['photo1'] = $event->photo1;
			$output['photo2'] = $event->photo2;
			$output['photo3'] = $event->photo3;
			$output['photo4'] = $event->photo4;
			$output['photo5'] = $event->photo5;
			$output['photo6'] = $event->photo6;
			$output['can_receive_egift'] = $event->can_receive_egift;
			$output['category'] = $event->category;
			$output['owner'] = $event->member;
			$output['venue'] = $event->venue;
		} catch(Exception $ex){
			$output = [];
			$output['response'] = '0';
			$output['responseMsg'] = $ex->getMessage();
			//var_dump($ex);
		}
	} break;
	// END createDraftEvent
	case 'editEvent':{
		try{
			extract($_REQUEST);			
	    	$event = R::load("event", $id);
			$event->category = $eventCategory;
			$event->member = $member;
			$event->can_receive_egift = $canReceiveEgift;
			// $event->template = $eventEcard;
			$event->photo1 = $imageFileName1;
			$event->photo2 = $imageFileName2;
			$event->photo3 = $imageFileName3;
			$event->photo4 = $imageFileName4;
			$event->photo5 = $imageFileName5;
			$event->photo6 = $imageFileName6;
			$event->date = $eventDate;
			$event->time = $eventTime;
			$event->venue = $eventVenue;
			$event->description = $eventDescription;

			R::store($event);

			$output = [];
			$output['response'] = '100';
			$output['responseMsg'] = "Success!";
		} catch(Exception $ex){
			$output = [];
			$output['response'] = '0';
			$output['responseMsg'] = "Error";
			//var_dump($ex);
		}
	} break;
	case 'editBankAccount':{
		try{
			extract($_REQUEST);
			$bank_info = R::load("bank_info", $id);
			$bank_info->name = $bank;
			$bank_info->account_no = $bank_account_no;
			$bank_info->fullname = $fullname;
			$bank_info->payee_id = $payee_id;
			$bank_info->member = $member;

			R::store($bank_info);
			$output = [];
			$output['response'] = '100';
			$output['responseMsg'] = $bank_info->id;
		} catch(Exception $ex){
			$output = [];
			$output['response'] = '0';
			$output['responseMsg'] = $ex->Message;
		}
	} break;
	case 'removeBankAccount':{
		try{
			extract($_REQUEST);
			$bank_info = R::load("bank_info", $id);
			R::trash($bank_info);
			$output = [];
			$output['response'] = '100';
			$output['responseMsg'] = $bank_info->id;
		} catch(Exception $ex){
			$output = [];
			$output['response'] = '0';
			$output['responseMsg'] = $ex->Message;
		}
	} break;
	case 'events':{
		extract($_REQUEST);
		//$events = R::find("event", "`date` BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND DATE_ADD(CURDATE(), INTERVAL 60 DAY)");
		$events = select("SELECT *, 0 going FROM `event` WHERE member = $member AND `date` BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND DATE_ADD(CURDATE(), INTERVAL 60 DAY)
			UNION
			SELECT e.*, ea.going FROM `event` e, `event_attendee` ea WHERE e.id=ea.event AND ea.member=$member AND `date` BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND DATE_ADD(CURDATE(), INTERVAL 60 DAY)");
		$output = [];
		//foreach ($events as $event) {
		while($event = mysqli_fetch_object($events)){
			$ecard = R::load("ecard", $event->template);
			
			$d1 = date_create(date("Y-m-d",strtotime($event->date)));
			$d2 = date_create(date("Y-m-d",time()));
			$interval = date_diff($d1, $d2);
			$days = $interval->format('%a');
			if($d1<$d2){
				$days = 0 -$days;
			}
			$upcoming = ($d1>=$d2 && $days<=30)?1:0;

			array_push($output, [
									'id'=>$event->id, 
									'date'=>date("M d, Y", strtotime($event->date)), 
									'ion_date'=>date("Y-m-d", strtotime($event->date)), 
									'time'=>$event->time,
									'description'=>$event->description, 
									'template'=>$event->template,
									'photos'=>explode(",", $event->photos),
									'photo1'=>$event->photo1,
									'photo2'=>$event->photo2,
									'photo3'=>$event->photo3,
									'photo4'=>$event->photo4,
									'photo5'=>$event->photo5,
									'photo6'=>$event->photo6,
									'can_receive_egift'=>$event->can_receive_egift,
									'qr'=>"http://w1.ossup.com/app/bigday/assets/event_qr/$event->qr",
									'category'=>$event->category,
									'foreground'=>"http://203.115.215.19/bigday/uploads/ecards/$ecard->foreground",
									'owner'=>$event->member,
									'venue'=>$event->venue,
									'attendee'=>$event->going,
									'egift'=>1200.00,
									'days'=>$days,
									'upcoming'=>$upcoming
								]);
		}
		// $output['response'] = '1';
		// $output['responseMsg'] = 'Success';

	} break;	
	case 'going': {
		extract($_REQUEST);
		$event_attendee = R::dispense("event_attendee");
		$event_attendee->going = $going;
		$event_attendee->event = $event;
		$event_attendee->member = $member;
		$event_attendee->date_confirmed = date("Y-m-d H:i:s", time());

		R::store($event_attendee);
		$output = [];
		$output['response'] = "100";
		$output['responseCode'] = "100";
		$output['responseMsg'] = 'Success';
	} break;
	case 'myevents':{
		extract($_REQUEST);
		$events = R::find("event", "member=? AND `date` BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND DATE_ADD(CURDATE(), INTERVAL 60 DAY)", [$member]);
		$output = [];
		foreach ($events as $event) {
			$ecard = R::load("ecard", $event->template);
			array_push($output, [
									'id'=>$event->id, 
									'date'=>date("M d, Y", strtotime($event->date)), 
									'description'=>$event->description, 
									'photos'=>explode(",", $event->photos),
									'photo1'=>$event->photo1,
									'photo2'=>$event->photo2,
									'photo3'=>$event->photo3,
									'photo4'=>$event->photo4,
									'photo5'=>$event->photo5,
									'photo6'=>$event->photo6,
									'qr'=>"http://w1.ossup.com/app/bigday/assets/event_qr/$event->qr",
									'category'=>$event->category,
									'foreground'=>"http://203.115.215.19/bigday/uploads/ecards/$ecard->foreground",
									'owner'=>$event->member,
									'venue'=>$event->venue,
									'attendee'=>1,
									'egift'=>1200.00
								]);
		}
		// $output['response'] = '1';
		// $output['responseMsg'] = 'Success';

	} break;	
	case 'invite':{
		extract($_REQUEST);
		$members = explode(",", $members);

		foreach ($members as $member) {
			insert("event_attendee", "event, member", "$event, $member");
		}
		$output['response'] = '1';
		$output['responseMsg'] = 'Success';
	} break;
	case 'banners':{
		$banners = select("*", "banner", "CURDATE() BETWEEN start_date AND end_date");
		$output = [];
		while ($banner = mysqli_fetch_object($banners)) {
			array_push($output, [
				'id'=>$banner->id,
				'banner'=>"http://203.115.215.19/bigday/uploads/banner/$banner->banner", 
				'links_with'=>$banner->links_with,
				'reference_id'=>$banner->reference_id,
				'description'=>$banner->description
			]);
		}
		// $output['response'] = '1';
		// $output['responseMsg'] = 'Success';

	} break;
	case 'ecards':{
		$ecards = select("SELECT ecard.*, (SELECT COUNT(id) FROM `event` WHERE `event`.`template`=ecard.id) downloads FROM `ecard`");
		$output = [];
		$category_name = toA("event_category");
		while ($ecard = mysqli_fetch_object($ecards)) {
			// $output['event']['description'] = $event->description;
			// $output['event']['photo'] = $event->photo;
			$d1 = date("Y-m-d",strtotime($ecard->entry_time));
			$d2 = date("Y-m-d",time());
			$interval = date_diff(date_create($d1), date_create($d2));
			$latest = $interval->format('%a')<=30?1:0;
			$template_results = select("SELECT template, background, IFNULL(photo1,'') photo1, IFNULL(photo2,'') photo2, IFNULL(photo3,'') photo3, IFNULL(photo4,'') photo4, IFNULL(photo1_animation,'') photo1_animation, IFNULL(photo2_animation,'') photo2_animation, IFNULL(photo3_animation,'') photo3_animation, IFNULL(photo4_animation,'') photo4_animation, IFNULL(element1,'') element1, IFNULL(element1_animation,'') element1_animation, IFNULL(element2,'') element2, IFNULL(element2_animation,'') element2_animation, IFNULL(frame,'') frame FROM `ecard_templates` WHERE ecard=$ecard->id");

			$templates = [];
			$photo_animations = [];
			while ($template_result = mysqli_fetch_object($template_results)) {
				array_push($templates, [
					'template'=>$template_result->template,
					'background'=>$template_result->background,
					'photo1'=>$template_result->photo1,
					'photo2'=>$template_result->photo2,
					'photo3'=>$template_result->photo3,
					'photo4'=>$template_result->photo4,
					'photo1_animation'=>$template_result->photo1_animation,
					'photo2_animation'=>$template_result->photo2_animation,
					'photo3_animation'=>$template_result->photo3_animation,
					'photo4_animation'=>$template_result->photo4_animation,
					'element1'=>$template_result->element1,
					'element1_animation'=>$template_result->element1_animation,
					'element2'=>$template_result->element2,
					'element2_animation'=>$template_result->element2_animation,
					'frame'=>$template_result->frame
				]);
				if(nn($template_result->photo1_animation)){ array_push($photo_animations, $template_result->photo1_animation); }
				if(nn($template_result->photo2_animation)){ array_push($photo_animations, $template_result->photo2_animation); }
				if(nn($template_result->photo3_animation)){ array_push($photo_animations, $template_result->photo3_animation); }
				if(nn($template_result->photo4_animation)){ array_push($photo_animations, $template_result->photo4_animation); }
			}
			array_push($output, [
				'id'=>$ecard->id, 
				'category'=>$ecard->category,
				'sub_category'=>$ecard->sub_category, 
				'animation'=> explode(",", $ecard->animation), 
				'templates'=> $templates, 
				'thumbnail'=>$ecard->thumbnail,
				'background'=>"http://203.115.215.19/bigday/uploads/ecards/$ecard->thumbnail",
				'photos'=> explode(",", $ecard->photos),
				'photo_animations' => $photo_animations, 
				'number_of_photos'=> $ecard->number_of_photos,
				'downloaded'=>$ecard->downloads, 
				'most_downloaded'=>0,
				'latest'=>$latest, 
				'name'=>$ecard->name, 
				'category_name'=>$category_name[$ecard->category], 
				'price'=>($ecard->price>0?"RM $ecard->price":"Free"), 
				//'background'=>"http://203.115.215.19/bigday/uploads/ecards/$ecard->background", 
				'foreground'=>"http://203.115.215.19/bigday/uploads/ecards/$ecard->foreground", 
				'description'=>$ecard->description
			]);
		}
		// $output['response'] = '1';
		// $output['responseMsg'] = 'Success';

	} break;
	case 'ecard':{
		extract($_REQUEST);
		$ecard = R::load("ecard", $id);
		$output = [];
		$template_results = select("SELECT template, background, IFNULL(photo1,'') photo1, IFNULL(photo2,'') photo2, IFNULL(photo3,'') photo3, IFNULL(photo4,'') photo4, IFNULL(photo1_animation,'') photo1_animation, IFNULL(photo2_animation,'') photo2_animation, IFNULL(photo3_animation,'') photo3_animation, IFNULL(photo4_animation,'') photo4_animation, IFNULL(element1,'') element1, IFNULL(element1_animation,'') element1_animation, IFNULL(element2,'') element2, IFNULL(element2_animation,'') element2_animation, IFNULL(frame,'') frame FROM `ecard_templates` WHERE ecard=$ecard->id");

		$templates = [];
		$photo_animations = [];
		while ($template_result = mysqli_fetch_object($template_results)) {
			array_push($templates, [
				'template'=>$template_result->template,
				'background'=>$template_result->background,
				'photo1'=>$template_result->photo1,
				'photo2'=>$template_result->photo2,
				'photo3'=>$template_result->photo3,
				'photo4'=>$template_result->photo4,
				'photo1_animation'=>$template_result->photo1_animation,
				'photo2_animation'=>$template_result->photo2_animation,
				'photo3_animation'=>$template_result->photo3_animation,
				'photo4_animation'=>$template_result->photo4_animation,
				'element1'=>$template_result->element1,
				'element1_animation'=>$template_result->element1_animation,
				'element2'=>$template_result->element2,
				'element2_animation'=>$template_result->element2_animation,
				'frame'=>$template_result->frame
			]);
			if(nn($template_result->photo1_animation)){ array_push($photo_animations, $template_result->photo1_animation); }
			if(nn($template_result->photo2_animation)){ array_push($photo_animations, $template_result->photo2_animation); }
			if(nn($template_result->photo3_animation)){ array_push($photo_animations, $template_result->photo3_animation); }
			if(nn($template_result->photo4_animation)){ array_push($photo_animations, $template_result->photo4_animation); }
		}
		$category_name = toA("event_category");
		//foreach ($ecards as $ecard) {
			// $output['event']['description'] = $event->description;
			// $output['event']['photo'] = $event->photo;
			//array_push($output, [
				$output['id'] = $ecard->id;
				$output['category'] = $ecard->category;
				$output['animation'] = explode(",", $ecard->animation);
				$output['templates'] = $templates;
				$output['photos'] = explode(",", $ecard->photos);
				$output['photo_animations'] = $photo_animations;
				$output['name'] = $ecard->name;
				$output['category_name'] = $category_name[$ecard->category];
				$output['price'] = ($ecard->price>0?"RM $ecard->price":"Free");
				$output['background'] = "http://203.115.215.19/bigday/uploads/ecards/$ecard->background";
				$output['foreground'] = "http://203.115.215.19/bigday/uploads/ecards/$ecard->foreground";
				$output['thumbnail'] = "http://203.115.215.19/bigday/uploads/ecards/$ecard->thumbnail";
				$output['description'] = $ecard->description;
			//]);
		//}
		// $output['response'] = '1';
		// $output['responseMsg'] = 'Success';

	} break;

	/*old ecards
	
	case 'ecards':{
		$ecards = select("SELECT ecard.*, (SELECT COUNT(id) FROM `event` WHERE `event`.`template`=ecard.id) downloads FROM `ecard`");
		$output = [];
		$category_name = toA("event_category");
		while ($ecard = mysqli_fetch_object($ecards)) {
			// $output['event']['description'] = $event->description;
			// $output['event']['photo'] = $event->photo;
			$d1 = date("Y-m-d",strtotime($ecard->entry_time));
			$d2 = date("Y-m-d",time());
			$interval = date_diff(date_create($d1), date_create($d2));
			$latest = $interval->format('%a')<=30?1:0;
			array_push($output, [
				'id'=>$ecard->id, 
				'category'=>$ecard->category, 
				'animation'=> explode(",", $ecard->animation), 
				'photos'=> explode(",", $ecard->photos), 
				'number_of_photos'=> $ecard->number_of_photos,
				'downloaded'=>$ecard->downloads, 
				'latest'=>$latest, 
				'name'=>$ecard->name, 
				'category_name'=>$category_name[$ecard->category], 
				'price'=>($ecard->price>0?"RM $ecard->price":"Free"), 
				'background'=>"http://203.115.215.19/bigday/uploads/ecards/$ecard->background", 
				'foreground'=>"http://203.115.215.19/bigday/uploads/ecards/$ecard->foreground", 
				'description'=>$ecard->description
			]);
		}
		// $output['response'] = '1';
		// $output['responseMsg'] = 'Success';

	} break;
	case 'ecard':{
		extract($_REQUEST);
		$ecard = R::load("ecard", $id);
		$output = [];
		$category_name = toA("event_category");
		//foreach ($ecards as $ecard) {
			// $output['event']['description'] = $event->description;
			// $output['event']['photo'] = $event->photo;
			//array_push($output, [
				$output['id'] = $ecard->id;
				$output['category'] = $ecard->category;
				$output['animation'] = explode(",", $ecard->animation);
				$output['photos'] = explode(",", $ecard->photos);
				$output['name'] = $ecard->name;
				$output['category_name'] = $category_name[$ecard->category];
				$output['price'] = ($ecard->price>0?"RM $ecard->price":"Free");
				$output['background'] = "http://203.115.215.19/bigday/uploads/ecards/$ecard->background";
				$output['foreground'] = "http://203.115.215.19/bigday/uploads/ecards/$ecard->foreground";
				$output['description'] = $ecard->description;
			//]);
		//}
		// $output['response'] = '1';
		// $output['responseMsg'] = 'Success';

	} break;
	*/
	case 'forgot_password':{
		extract($_REQUEST);
		$user = R::findOne("member", "phone=? AND ((security_question_1=? AND security_question_1_answer=?) OR (security_question_2=? AND security_question_2_answer=?))", [$mobile, $securityQuestion, $securityAnswer, $securityQuestion, $securityAnswer]);
		if($user){
			if($user->status == 'Suspended'){
				$output = [];
				$output['response'] = '0';
				$output['responseMsg'] = 'Your account has been Suspended. Please contact admin.';
			} else{
				$output = [];
				$output['response'] = '1';
				$output['member'] = $user->id;
				$otp = otp($mobile);
				if($otp !== false){
					$user->otp = $otp;
					$user->otp_time = now();
					$output['token'] = $user->webcash_token;
					//$output['pin'] = $user->otp;
					R::store($user);
					//send_sms($mobile, $user->otp);
				} else{
					$output = [];
					$output['response'] = '0';
					$output['responseMsg'] = 'Failed! Please try again.';
				}				
			}
		} else{
			$output = [];
			$output['response'] = '0';
			$output['responseMsg'] = 'Account not found!';
		}

	} break;
	case 'otp': {
		extract($_REQUEST);
		$otp = otp($mobile);
		if($otp !== false){
			$output = [];
			//$output['otp'] = '1';
			$output['response'] = '1';
			$output['responseMsg'] = 'Success!';
		} else{
			$output = [];
			$output['response'] = '0';
			$output['responseMsg'] = 'Failed! Please try again.';
		}	
	} break;
	case 'reset_password':{
		extract($_REQUEST);
		$user = R::findOne("member", "id=? AND otp=? AND DATE(otp_time) = CURDATE()", [$id, $otp]);
		if($user){
			$webcash_response = json_decode(updateProfile($token, 
				[
					'password'=>$password, 
					'verify_password'=>$password
				]));
			if($webcash_response->success){
				$user->password = md5($password);
				$user->status = 'Active';
				R::store($user);
				$output = [];
				$output['response'] = '1';
				$output['responseMsg'] = 'Sucessfully Reset!';
			} else{
				$output = [];
				$output['response'] = '0';
				$output['responseMsg'] = 'Failed';
				$output['additionalInfo'] = 'Webcash Failed';
				$output['webcashData'] = $webcash_response->data;
			}
		} else{
			$output = [];
			$output['response'] = '0';
			$output['responseMsg'] = 'Account not found!';
		}
	} break;
	case 'lost_phone':{
		extract($_REQUEST);
		$user = R::findOne("member", "phone=? AND password=?", [$phone, $password]);
		if($user){
			$user->status = 'Suspended';
			R::store($user);
			$output = [];
			$output['response'] = '1';
			$output['responseMsg'] = 'Report Received! Your account has been temporarily Suspended, kindly reset password to activate it back from new phone. Thank You';
		} else{
			$output = [];
			$output['response'] = '0';
			$output['responseMsg'] = 'Account not found!';
		}
	} break;
	case 'login':{	
		$username = isset($input->username)?$input->username:false;
		$password = isset($input->password)?$input->password:false;

		//$user = R::findOne("member", "email=? AND password=?", [$username, md5($password)]);
		$user = R::findOne("member", "(email=? OR phone=?) AND password=?", [$username, $username, md5($password)]);
		if($user){
			if($user->status == 'Suspended'){
				$output = [];
				$output['response'] = '0';
				$output['responseMsg'] = 'Failed';
			} else{
				$output = [];
				$output['response'] = '1';
				$output['responseMsg'] = 'Success';
				$output['id'] = $user->id;
				$output['username'] = $username;
				$output['fullname'] = $user->name;
				$output['email'] = $user->email;
				$output['phone'] = $user->phone;
				$output['agent'] = $user->agent;
				// R::store($user);
			}
		} else{
			//failed_attempt
			$user = R::findOne("member", "email=? OR phone=?", [$username, $username]);
			if($user){
				if($user->failed_attempt >= 3){
					$user->status = 'Suspended';
					$user->failed_attempt = 0;
				} else{
					$user->failed_attempt = $user->failed_attempt + 1;
				}
				
				R::store($user);
			}
			$output = [];
			$output['response'] = '0';
			$output['responseMsg'] = 'Failed';
		}
	}	break;
	case 'sms':{
		extract($_REQUEST);
	} break;
	case 'member':{
		if(isset($get->id)){
			$member = R::load("member", $get->id);
			$output = [];
			foreach ($members as $member) {
				array_push($output, [
										'id'=>$member->id, 
										'name'=>$member->fullname, 
										'photo'=>$member->avatar,
										'phone'=>$member->phone
									]);
			}
		} else{
			$output = [];
			$output['response'] = '0';
			$output['responseMsg'] = 'No ID was passed';
		}
		
		// $output['response'] = '1';
		// $output['responseMsg'] = 'Success';

	} break;
	case 'members':{
		//$members = R::find("member");
		extract($_REQUEST);
		$members = select("*", "member", "id NOT IN (select member from event_attendee where `event`=$event)");
		$output = [];
		//foreach ($members as $member) {
		while($member = mysqli_fetch_object($members)){
			array_push($output, [
									'id'=>$member->id, 
									'name'=>$member->fullname, 
									'photo'=>$member->avatar,
									'phone'=>$member->phone
								]);
		}
		// $output['response'] = '1';
		// $output['responseMsg'] = 'Success';

	} break;
	case 'phones':{
		$member_rows = select("SELECT GROUP_CONCAT(phone) phones FROM `member` WHERE phone IS NOT NULL");
		$members = mysqli_fetch_object($member_rows);
		$output = [];
		$output['phones'] = "$members->phones";
		// $output['response'] = '1';
		// $output['responseMsg'] = 'Success';

	} break;
	case 'register': {
		$output = [];
		$email = isset($input->email)?$input->email:false;
		$phone = isset($input->phone)?$input->phone:false;
		$fullname = isset($input->fullname)?$input->fullname:false;
		$password = isset($input->password)?$input->password:false;

		$member = R::findOne("member", "email=?", [$email]);
		$valid = true;
		if($member){
			$error = "Email exists!";
			$valid = false;
			$output['response'] = '0';
			$output['responseMsg'] = $error;
		} else{
			$member = R::findOne("member", "phone=?", [$phone]);
			if($member){
				$error = "Sorry this phone number already linked with an account!";
				$valid = false;
				$output['response'] = '0';
				$output['responseMsg'] = $error;
			} else{
				$user = R::dispense("member");
				$user->name = $fullname;
				$user->email = $email;
				$user->phone = $phone;
				$user->password = md5($password);
				R::store($user);
				$output = [];
				$output['response'] = '1';
				$output['responseMsg'] = 'Success';
			}
		}				
	} break;
	case 'securityQuestions': {
		extract($_REQUEST);
		
		$security_questions = R::find("security_questions");
		//$banks = R::find("bank_info");
		$output = [];
		$output['security_questions'] = [];
		foreach ($security_questions as $security_question) {
			array_push($output['security_questions'], ['id'=> $security_question->id, 'name'=>$security_question->name]);
		}
		if(isset($id)){
			$user = R::load("member", $id);
			$output['security_question_1'] = $user->security_question_1;
			$output['security_question_1_answer'] = $user->security_question_1_answer;
			$output['security_question_2'] = $user->security_question_2;
			$output['security_question_2_answer'] = $user->security_question_2_answer;
		}
	} break;

	case 'transfer': {
		extract($_REQUEST);
		$recipient = R::load("member", $member);
		$response = transfer($token, $recipient->webcash_id, $amount);
		$webcash_response = json_decode($response);
		if($webcash_response->success){
			$output = [];
			$output['response'] = "100";
			$output['responseMsg'] = "Success";
		} else{
			$output = [];
			$output['response'] = "0";
			$output['responseMsg'] = "$recipient->id".$response;
		}
	} break;

	case 'updateMember': {
		extract($_REQUEST);
		$user = R::load("member", $id);

		if($user){
			if($fullname != "") $user->fullname = $fullname;
			if($email != "") $user->email = $email;
			if($phone != "") $user->phone = $phone;
			if($dob != "") $user->dob = $dob;
			if($marital_status != "") $user->marital_status = $marital_status;
			if(isset($icno)){
				if($icno != "") $user->icno = $icno;
				if(strlen($icno)==12 && substr($icno, 0, 6) != date("ymd", strtotime($user->dob))){
					$output = [];
					$output['response'] = '0';
					$output['responseMsg'] = 'Failed! Date of Birth or IC No. is wrong!';
					break;
				} elseif(strlen($icno)<8 && strlen($icno)>12){
					$output = [];
					$output['response'] = '0';
					$output['responseMsg'] = 'Failed! Wrong IC/Passport format!';
					break;
				}
			}
			if($avatar == "" && $user->avatar != "") {
				//unlink($user->avatar = $avatar);
				$user->avatar = "";
			} else{
				$user->avatar = $avatar;
			}
			if(isset($ic_image)) {
				//unlink($user->avatar = $avatar);
				$user->ic_image = $ic_image;
			}
			R::store($user);
			$user->log = updateProfile($token, ['full_name'=>$user->fullname, 'mobile_number'=>$user->phone]);
			R::store($user);
			$output = [];
			$output['response'] = '100';
			$output['responseMsg'] = 'Success';
		} else{
			$output = [];
			$output['response'] = '0';
			$output['responseMsg'] = 'Failed to update! Please contact us if you are having trouble to update your profile.';
		}
	} break;

	case 'updateSecurityQuestions': {
		extract($_REQUEST);
		$user = R::load("member", $id);
		if($user){
			$user = R::load("member", $id);
			$user->security_question_1 = trim($security_questions_1);
			$user->security_question_1_answer = trim($security_questions_1_answer);
			$user->security_question_2 = trim($security_questions_2);
			$user->security_question_2_answer = trim($security_questions_2_answer);
			R::store($user);
			$output = [];
			$output['response'] = '100';
			$output['responseMsg'] = 'Success';
		}
	} break;
}


$url = "https://staging.webcash.com.my/api/v2/thirdparty";
$merchant_id = "80000888";
$secret = "twcXULMRRtGrt6h2";

function addTran($member, $type, $amount, $response){
	//'Topup','Cashout','eGift','Payment','Receive'
	$tran = R::dispense("trans");
	$tran->member = $member;
    $tran->type = $type;
    $tran->amount = $amount;
    $tran->response = $response;
    R::store($tran);
}

function cashout($token, $payee_bank_account_no, $payee_bank_name, $payee_identification_id, $payee_name, $transfer_amount, $transfer_reason){
	$url = "https://staging.webcash.com.my/api/v2/thirdparty";
	$action = "withdrawal";
	$merchant_id = "80000888";
	$secret = "twcXULMRRtGrt6h2";
	$sub_merchant_id = "80000155";

	$before_hash = "$merchant_id$secret$action$payee_bank_account_no$payee_bank_name$payee_identification_id$payee_name$transfer_amount$transfer_reason";
	$after_hash = sha1($before_hash);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,"$url/$action.json?merchant_id={$merchant_id}&hash_key={$after_hash}&token={$token}");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,
	            [
					'payee_bank_account_no' => $payee_bank_account_no,
					'payee_bank_name' => $payee_bank_name,
					'payee_identification_id' => $payee_identification_id,
					'payee_name' => $payee_name,
					'transfer_amount' => $transfer_amount,
					'transfer_reason' => $transfer_reason
	            ]);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$server_output = curl_exec ($ch);
	curl_close ($ch);
	//var_dump($server_output);
	return $server_output;
}

function payment($token, $amount, $merchant_reference, $gst_amount = "0.00"){
	$url = "https://staging.webcash.com.my/api/v2/thirdparty";
	$action = "payment";
	$merchant_id = "80000888";
	$secret = "twcXULMRRtGrt6h2";
	$sub_merchant_id = "80000155";

	$before_hash = "$merchant_id$secret$action$amount$gst_amount$merchant_id$merchant_reference";
	$after_hash = sha1($before_hash);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,"$url/$action.json?merchant_id={$merchant_id}&hash_key={$after_hash}&token={$token}");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,
	            [
					'amount' => $amount,
					'gst_amount' => $gst_amount,
					'merchant_id' => $merchant_id,
					'merchant_reference' => $merchant_reference
	            ]);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$server_output = curl_exec ($ch);
	curl_close ($ch);
	//var_dump($server_output);
	return $server_output;
}

function transfer($token, $recipient_id, $transfer_amount){
	$url = "https://staging.webcash.com.my/api/v2/thirdparty";
	$action = "wallet-transfer";
	$merchant_id = "80000888";
	$secret = "twcXULMRRtGrt6h2";
	$sub_merchant_id = "80000155";

	$before_hash = "$merchant_id$secret$action$recipient_id$transfer_amount";
	$after_hash = sha1($before_hash);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,"$url/$action.json?merchant_id={$merchant_id}&hash_key={$after_hash}&token={$token}");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,
	            [
					'recipient_id' => $recipient_id,
					'transfer_amount' => $transfer_amount
	            ]);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$server_output = curl_exec ($ch);
	curl_close ($ch);
	//var_dump($server_output);
	return $server_output;
}

function getToken($email, $password){
	$url = "https://staging.webcash.com.my/api/v2/thirdparty";
	$merchant_id = "80000888";
	$secret = "twcXULMRRtGrt6h2";
	$action = "token";

	$before_hash = "$merchant_id$secret$action$password$email";
	$after_hash = sha1($before_hash);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,"$url/$action.json?merchant_id={$merchant_id}&hash_key={$after_hash}");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,
	            [
					'password' => $password,
					'username' => $email
	            ]);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$server_output = curl_exec ($ch);
	curl_close ($ch);
	//var_dump($server_output);
	return $server_output;
}

function updateProfile($token, $values = []){
	$url = "https://staging.webcash.com.my/api/v2/thirdparty";
	$merchant_id = "80000888";
	$secret = "twcXULMRRtGrt6h2";
	$action = "update-profile";

	$address = isset($values['address'])?$values['address']:'';
	$city = isset($values['city'])?$values['city']:'';
	$contact_number = isset($values['contact_number'])?$values['contact_number']:'';
	$country = isset($values['country'])?$values['country']:'';
	$date_of_birth = isset($values['date_of_birth'])?$values['date_of_birth']:'';
	$email = isset($values['email'])?$values['email']:'';
	$fax_number = isset($values['fax_number'])?$values['fax_number']:'';
	$full_name = isset($values['full_name'])?$values['full_name']:'';
	$mobile_number = isset($values['mobile_number'])?$values['mobile_number']:'';
	$password = isset($values['password'])?$values['password']:'';
	$postcode = isset($values['postcode'])?$values['postcode']:'';
	$state = isset($values['state'])?$values['state']:'';
	$user_race = isset($values['user_race'])?$values['user_race']:'';
	$verify_password = isset($values['verify_password'])?$values['verify_password']:'';

	$before_hash = "$merchant_id$secret$action$address$city$contact_number$country$date_of_birth$email$fax_number$full_name$mobile_number$password$postcode$state$user_race$verify_password";
	$after_hash = sha1($before_hash);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,"$url/$action.json?merchant_id={$merchant_id}&hash_key={$after_hash}&token={$token}");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,
	            [
					'address' => $address,
					'city' => $city,
					'contact_number' => $contact_number,
					'country' => $country,
					'date_of_birth' => $date_of_birth,
					'email' => $email,
					'fax_number' => $fax_number,
					'full_name' => $full_name,
					'mobile_number' => $mobile_number,
					'password' => $password,
					'postcode' => $postcode,
					'state' => $state,
					'user_race' => $user_race,
					'verify_password' => $verify_password
	            ]);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$server_output = curl_exec ($ch);
	curl_close ($ch);
	return $server_output;
}

function cashout2($id, $bank, $token, $amount){
	$url = "https://staging.webcash.com.my/api/v2/thirdparty";
	$merchant_id = "80000888";
	$secret = "twcXULMRRtGrt6h2";
	$action = "withdrawal";
	$member = R::load("member", $id);
	$bank_info = R::load("bank_info", $bank);

	$payee_bank_account_no = $bank_info->account_no;
	$payee_bank_name = $bank_info->name;
	$payee_identification_id = $bank_info->payee_id;
	$payee_name = $bank_info->fullname;
	$transfer_amount = $amount;
	$transfer_reason = 'Cashout from BigDay to Personnal Account';

	$before_hash = "$merchant_id$secret$action$payee_bank_account_no$payee_bank_name$payee_identification_id$payee_name$transfer_amount$transfer_reason";

	$after_hash = sha1($before_hash);
	return [
		'url'=>"$url/$action.json?merchant_id={$merchant_id}&hash_key={$after_hash}&token=$token",
		'token' => $token,
		'payee_bank_account_no' => $payee_bank_account_no,
		'payee_bank_name' => $payee_bank_name,
		'payee_identification_id' => $payee_identification_id,
		'payee_name' => $payee_name,
		'transfer_amount' => $transfer_amount,
		'transfer_reason' => $transfer_reason
	];

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,"$url/$action.json?merchant_id={$merchant_id}&hash_key={$after_hash}&token=$token");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,
	            [
					'payee_bank_account_no' => $payee_bank_account_no,
					'payee_bank_name' => $payee_bank_name,
					'payee_identification_id' => $payee_identification_id,
					'payee_name' => $payee_name,
					'transfer_amount' => $transfer_amount,
					'transfer_reason' => $transfer_reason
	            ]);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$server_output = curl_exec ($ch);
	curl_close ($ch);
	return $server_output;
}

function registerWebcash($email, $full_name, $password){
	$url = "https://staging.webcash.com.my/api/v2/thirdparty";
	$merchant_id = "80000888";
	$secret = "twcXULMRRtGrt6h2";
	$action = "register";
	$verify_password = $password;
	$before_hash = "$merchant_id$secret$action$email$full_name$password$verify_password";
	$after_hash = sha1($before_hash);

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL,"$url/$action.json?merchant_id={$merchant_id}&hash_key={$after_hash}");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,
		[
			'email' => $email,
			'full_name' => $full_name,
			'password' => $password,
			'verify_password' => $verify_password
		]);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$server_output = curl_exec ($ch);
	//print $server_output;

	//var_dump($server_output);

	curl_close ($ch);
	return $server_output;
}

function otp($phone){
	$date = today();
	for($i=0;$i<10;$i++){
		$rand = rand(100000, 999999);
		$otp = R::findOne("otp_requests", "otp=? AND date=?", [$rand, $date]);
		if(!$otp){
			$otp = R::dispense("otp_requests");
			$otp->date = $date;
			$otp->otp = $rand;
			$otp->sent_to = $phone;
			$otp->status = send_sms($phone, $rand);
			R::store($otp);
			return $rand;
		}
	}
	return false;	
}

function send_sms($phone, $msg){
	$url = "http://cloudsms.trio-mobile.com/index.php/api/bulk_mt?api_key=NUC1301010000565910f572341f04f332e77ca907e5015c1f&action=send&to=$phone&msg=$msg&sender_id=CLOUDSMS&content_type=1&mode=shortcode&campaign=";
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$server_output = curl_exec ($ch);
	//print $server_output;

	//var_dump($server_output);

	curl_close ($ch);
	return $server_output;
}

function calculateTextBox($font_size, $font_angle, $font_file, $text) { 
	$box   = imagettfbbox($font_size, $font_angle, $font_file, $text); 
	if( !$box ) return false; 
	$min_x = min( array($box[0], $box[2], $box[4], $box[6]) ); 
	$max_x = max( array($box[0], $box[2], $box[4], $box[6]) ); 
	$min_y = min( array($box[1], $box[3], $box[5], $box[7]) ); 
	$max_y = max( array($box[1], $box[3], $box[5], $box[7]) ); 
	$width  = ( $max_x - $min_x ); 
	$height = ( $max_y - $min_y ); 
	$left   = abs( $min_x ) + $width; 
	$top    = abs( $min_y ) + $height; 
  // to calculate the exact bounding box i write the text in a large image 
	$img     = @imagecreatetruecolor( $width << 2, $height << 2 ); 
	$white   =  imagecolorallocate( $img, 255, 255, 255 ); 
	$black   =  imagecolorallocate( $img, 0, 0, 0 ); 
	imagefilledrectangle($img, 0, 0, imagesx($img), imagesy($img), $black); 
  // for sure the text is completely in the image! 
	imagettftext( $img, $font_size, 
		$font_angle, $left, $top, 
		$white, $font_file, $text); 
  // start scanning (0=> black => empty) 
	$rleft  = $w4 = $width<<2; 
	$rright = 0; 
	$rbottom   = 0; 
	$rtop = $h4 = $height<<2; 
	for( $x = 0; $x < $w4; $x++ ) {
		for( $y = 0; $y < $h4; $y++ ) {
			if( imagecolorat( $img, $x, $y ) ){ 
				$rleft   = min( $rleft, $x ); 
				$rright  = max( $rright, $x ); 
				$rtop    = min( $rtop, $y ); 
				$rbottom = max( $rbottom, $y ); 
			} 
		}
	}
	// imagedestroy( $img ); 
	return array( "left"   => $left - $rleft, "top"    => $top  - $rtop, "width"  => $rright - $rleft + 1, "height" => $rbottom - $rtop + 1, "img" =>$img ); 
} 

echo json_encode($output);