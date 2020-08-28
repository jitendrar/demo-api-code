<?php

function pr($arr){
  echo "<pre>";
  print_r($arr);
}

function prd($arr){
  echo "<pre>";
  print_r($arr);
  exit();
}

function _GetStatusName($statusid='') {
  if($statusid == 1) {
    return "Active";
  } else {
    return "Inactive";
  }
}