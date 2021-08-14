<?php

phpinfo();

$added_dt = '2013-03-31';
date('Y-m-d',strtotime(date('Y-m').'-'.date('d',strtotime($added_dt)))); 
#echo date('d',strtotime("2013-05-31"));
//phpinfo();
echo $nextDate = getNextBillDate("2011-01-13");


function getNextBillDate($start_date) {

  $date_array = explode("-",$start_date); // split the array

  $year = $date_array[0];
  $month = $date_array[1];
  $day = $date_array[2];

  if (date("d") <= $day) {
    $billMonth = (int)date("m");
  }else{
    $billMonth = date("m")+1;
  }
  $billMonthDays = cal_days_in_month(CAL_GREGORIAN, ($billMonth), date("Y"));

  if ($billMonthDays > $day) {
    $billDay = $day;
  }else{
    $billDay = $billMonthDays;
  }

  $nextBillDate = date("Y").'-'.$billMonth . "-" . $billDay ;

  return $nextBillDate;
}

 

?>