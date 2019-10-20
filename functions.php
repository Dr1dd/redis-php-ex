<?php
require('main.php');
	if(isset($_POST['key'])){
		$id = $_POST['key'];
		$redis->DEL($id);
		$iter = NULL;
		$redis->setOption(Redis::OPT_SCAN, Redis::SCAN_RETRY);
		while($arr_del = $redis->scan($iter, 'account:'.str_replace("user:", "", $id).':*'))
		{
			foreach ($arr_del as $accountkey) {
				$redis->DEL($accountkey);
			}
		}
	}
	if(isset($_POST['accId'])){
		$accId = $_POST['accId'];
		$validatedId =str_replace("user:", "", $accId);
		//$accArray = $redis->keys('account:'.$validatedId.':*');
		$iterator = NULL;
		$accLength = 0;
		$redis->setOption(Redis::OPT_SCAN, Redis::SCAN_RETRY);
		while($accArray = $redis->scan($iterator, 'account:'.$validatedId.':*')){
			foreach($accArray as $key1){
				$accLength +=1;
			}
		}
		$accLength = count($accArray)+1;
		if($accLength >2) echo "error";
		else{
		$cash = $_POST['cash'];
		$pointAcc = 0;

		if(empty($redis->keys('account:'.$validatedId.':'.$accLength) && isset($validatedId))){

			$redis->hmset('account:'.$validatedId.':'.$accLength, [
	  		 'money' => $cash,
	  		 'points'=> $pointAcc,

	  		 ]);
			$redis->expire('account:'.$validatedId.':'.$accLength, $redis->ttl($accId));
			}
			//$redis->sAdd('Narys:'.$validatedId, 'user:'.$validatedId, 'account:'.$validatedId.':'.$accLength);
		}
	}

 	if(isset($_POST['id'])){
 		$user = filter_input(INPUT_POST, 'firstname');
    	 $lname = filter_input(INPUT_POST, 'lastname');
    	 $id = filter_input(INPUT_POST, 'id');
    	 $money = filter_input(INPUT_POST, 'money');
    	 $releaseTime = filter_input(INPUT_POST, 'releaseTime');
    	 $points =0;
    	   if(isset($id)){
    	   	if(empty($redis->keys('user:'.$id))){
			  		 $redis->HMSET('user:'.$id, [
			   		'fname' => $user,
			   		'lname' => $lname,
			   ]); 
			  		 $redis->hmset('account:'.$id.':1', [
			  		 'money' => $money,
			  		 'points'=> $points,
			  		 ]);
			  		 

			  		 switch($_POST['releaseTime']){
			  		 	case "30s":
			  		 		$redis->hset('user:'.$id, 'time', time()+(30)+3600);
			  		 		$redis->expire('user:'.$id, 30);
			  		 		$redis->expire('account:'.$id.':1', 30);
			  		 		break;
			  			case "30min":
			  		 		$redis->hset('user:'.$id, 'time', time()+(30*60)+3600);
			  		 		$redis->expire('user:'.$id, 30*60);
			  		 		$redis->expire('account:'.$id.':1', 30*60);
			  		 		break;
			  		 	case "30h":
			  		 		$redis->hset('user:'.$id, 'time', time()+(30*60*60)+3600);
			  		 		$redis->expire('user:'.$id, 30*60*60);
			  		 		$redis->expire('account:'.$id.':1', 30*60*60);
			  		 		break;
			  		 	case "30d":
			  		 		$redis->hset('user:'.$id, 'time', time()+(30*24*60*60)+3600);
			  		 		$redis->expire('user:'.$id, 30*24*60*60);
			  		 		$redis->expire('account:'.$id.':1', 30*24*60*60);
			  		 		break;
			  		 	case "1y":
			  		 		$redis->hset('user:'.$id, 'time', time()+(365*24*60*60)+3600);
			  		 		$redis->expire('user:'.$id, 365*24*60*60);
			  		 		$redis->expire('account:'.$id.':1', 365*24*60*60);
			  		 		break;
			  		 	case "2y":
			  		 		$redis->hset('user:'.$id, 'time', time()+(2*365*24*60*60)+3600);
			  		 		$redis->expire('user:'.$id, 2*365*24*60*60);
			  		 		$redis->expire('account:'.$id.':1', 2*365*24*60*60);
			  		 		break;
			  		 	case "3y":
			  		 		$redis->hset('user:'.$id, 'time', time()+(3*365*24*60*60)+3600);
			  		 		$redis->expire('user:'.$id, 3*365*24*60*60);
			  		 		$redis->expire('account:'.$id.':1', 3*365*24*60*60);
			  		 		break;
			  		 	case "4y":
			  		 		$redis->hset('user:'.$id, 'time', time()+(4*365*24*60*60)+3600);
			  		 		$redis->expire('user:'.$id, 4*365*24*60*60);
			  		 		$redis->expire('account:'.$id.':1', 4*365*24*60*60);
			  		 		break;
			  		 	case "5y":
			  		 		$redis->hset('user:'.$id, 'time', time()+(5*365*24*60*60)+3600);
			  		 		$redis->expire('user:'.$id, 5*365*24*60*60);
			  		 		$redis->expire('account:'.$id.':1', 5*365*24*60*60);
			  		 		break;
			  		 	default:
			  		 		echo "error";
			  		 		break;

			  		 }
			  		//  $redis->sAdd('Narys:'.$id, 'user:'.$id, 'account:'.$id.':1');

			  		}
    	   	}
    	  
 }
 	if(isset($_POST['idFrom'])){
 		$firstID ="";
 		$secondID="";
 		$money="";
 		$firstID=$_POST['idFrom'];
		$secondID=$_POST['idTo'];
		$accountNumber = $_POST['accountId'];
		$transactionTimer = 0;
		$Error = false;
		if(isset($_POST['transactionTimer'])) $transactionTimer = $_POST['transactionTimer'];

		if(!empty($redis->keys('account:'.$firstID.':'.$accountNumber)) && $_POST['idFrom'] >= 0){
			$temp = $redis->keys('account:'.$firstID.':'.$accountNumber);
		}

		if(!empty($redis->keys('account:'.$secondID.':1')) && $_POST['idTo'] >=0){
			$tempTo = $redis->keys('account:'.$secondID.':1');
		}
		if($_POST['moneySend'] >0) $money = $_POST['moneySend'];

 		if(isset($temp) && isset($tempTo) && isset($money)){
 			$condition = true;
	 		$redis->watch('account:'.$firstID.':'.$accountNumber);
	 		while($condition == true){
	 			$currentMoney = $redis->hget('account:'.$firstID.':'.$accountNumber, 'money');
	 			$redis->multi();
		 			if(($currentMoney - $money) < 0) {
		 				$redis->discard();
		 				$condition = false;
		 				echo "Per mažai pinigų balanse";
		 			}
		 			else{
		 				$redis->hincrby('account:'.$firstID.':'.$accountNumber, 'money', -$money);
			 			$redis->hincrby('account:'.$secondID.':1', 'money', $money);
			 			$redis->hincrby('account:'.$firstID.':'.$accountNumber, 'points', 10);
			 			$redis->hincrby('user:'.$firstID, 'time', -60);
		 				$ret = $redis->exec(); 

		 				$condition = false;
		 				if($ret == false) $condition = true;
		 				else echo "Transakcija sėkmingai įvykdyta";
		 			}
		 			
	 		} 
	 		if(!isset($temp) ||!isset($tempTo)) $Error =true;
	 		unset($firstID);
	 		unset($secondID);
	 		unset($money);
	 		unset($accountNumber);
 	}
 }
?>
