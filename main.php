<?php
  
   $redis = new Redis(); 
   $redis->connect('192.168.0.106', 6379); 
   $redis->set('Miestas', 'Vilnius');
?>
<!doctype html>
<html lang="lt">
  <head>
    <link rel="stylesheet" href="css.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="height=device-height, initial-scale=1">
    <meta charset="UTF-8">
    <script type="text/javascript" src="js/main.js">
    </script>
    <title>Kalėjimas</title>
  </head>
  <body>
    <div class ="mainWin">
    	<div class="jailBlock">
    	  <?php 
         $it = NULL;
          $list = $redis->KEYS('user:*');
    	    //$list = $redis->lRange($redis->KEYS('user:*'), 0, -1);
  				foreach ($list as $key)
				{
          if($key != 'user:'){
          //  $accountList = $redis->lRange($redis->KEYS('user:*'), 0, -1);
				$vardas = $redis->hget($key, 'fname');
				$pavarde = $redis->hget($key, 'lname');
        $sask = $redis->keys('account:'.str_replace("user:", "", $key).':*');
        $points =$redis->hget($key, 'points');
        $time = $redis->hget($key, 'time');
				echo '<div class="jailCells">';
          echo '<div>';
          echo '<span> ID: <b>'.str_replace("user:", "", $key).'</b> </span></br>';
			  	echo '<span> Vardas: <b>'. $vardas.'</b> </span></br>';
			  	echo '<span> Pavardė: <b>'.$pavarde.'</b> </span></br>';
          foreach($sask as $acc){
            echo '<span> <font color="red"> Sąskaita: </font> <b>'.str_replace('account:', "", $acc).' </b> </span> </br>';
            echo ' <div class ="jailAccountText"> Pinigai: <b>'.$redis->hget($acc, 'money').'</b> </div>';
            echo '<div class ="jailAccountText"> Taškai: <b>'.$redis->hget($acc, 'points').'</b> </div>';
          }
          echo '</br><span> Išleidimo data: <b>'.date('Y/m/d H:i:s',$time).'</b> </span> </br>';
			  	echo '<div> <a class="delBtn" data-value="'.$key.'">X</a> <a class="addBtn" href="#addAcc" data-value="'.$key.'" id="addingAccount" >+</a> </br> </div>';
			  	echo '</div>';
          echo '</div>';
        }
				}

		  unset($list);
			?>
          <a href="#Prideti" class ="inputBlock" style="align-items: center;" onclick="rmRequiredTransaction()">
        <img class="imgClass" src="https://image.flaticon.com/icons/png/512/19/19725.png">
       </a>
		</div>

      <div class="inputBlock">
        <a class ="button1" href="#Transakcija" onclick="rmRequiredAddUser()"> Transakcija </a>
      </div>
    </div>
      <div id="Prideti" class="overlaypop">
          <div class="popup">
              <a class="close" href="#">&times;</a>
               <div class="contentpop">
                <h1>Pridėti kalinį</h1>
                <div class="inputBlock">
                    <form action="main.php" id ="addUserForm" method="post" >
                    ID:<br>
                    <input type="number" min="1" name="id" step="1" max="99999" autocomplete="off" required><br>
                    Vardas:<br>
                    <input type="text" name="firstname" autocomplete="off" maxlength="20" required><br>
                    Pavardė:<br>
                    <input type="text" name="lastname" autocomplete="off" maxlength="20" required><br>
                    Pinigai:<br>
                    <input type="number" min="1" step="1" name="money" maxlength="9999999"required><br><br> 
                    <select name="releaseTime">
                      <option value="30s">30 sekundžių</option>
                      <option value="30min">30 minučių</option>
                      <option value="30h">30 valandų</option>
                      <option value="30d">30 dienų</option>
                      <option value="1y">1 metai</option>
                      <option value="2y">2 metai</option>
                      <option value="3y">3 metai</option>
                      <option value="4y">4 metai</option>
                      <option value="5y">5 metai</option>
                    </select>                   
                    <input type="submit" value="Pridėti" id="addUser" class="buttonSubmit">
                  </form>
                  </div>
               </div>
          </div>
      </div> 
         <div id="Transakcija" class="overlaypop">
          <div class="popup">
              <a class="close" href="#">&times;</a>
               <div class="contentpop">
                <h1>Transakcija</h1>
                <div class="transactionBlock">
                 <form id="transactionForm" method="post" >
                    <input type="number" class="inputTransaction" min ="1" maxlength="99999" name="idFrom" placeholder="Įveskite kalinio ID" autocomplete="off" required> <br>
                    <input type="number" class="inputTransaction" min ="1" max = "2" maxlength="2"  name="accountId" placeholder="Įveskite sąskaitos numerį" autocomplete="off" required><br>
                    <input type="number" class="inputTransaction"  name="moneySend" min ="1" maxlength="9999999" placeholder="Įveskite pinigų sumą" autocomplete="off" required><br>
                    <input type="number" class="inputTransaction" min ="1" maxlength="99999"  name="idTo" placeholder="Įveskite gavėjo ID" autocomplete="off" required><br>
                    <input type="submit" value="OK" id="transactionSubmit" class="buttonSubmit">
                  </form>
              </div>
              
          </div>
         </div>
      </div> 
      <div id="addAcc" class="overlaypop">
          <div class="popup">
              <a class="close" href="#">&times;</a>
               <div class="contentpop">
                <h1>Pridėti sąskaitą</h1>
                <div class="transactionBlock">
                 <form id="accountForm" method="post" >
                    <input type="number" class="inputTransaction"  name="cash" min ="1" maxlength="9999999" placeholder="Įveskite pinigų sumą" autocomplete="off" required><br>
                    <input type="submit" value="OK" id="accountSubmit" data-value="" class="buttonSubmit">
                  </form>
              </div>
              
          </div>
         </div>
      </div> 


  </body>
  </html>