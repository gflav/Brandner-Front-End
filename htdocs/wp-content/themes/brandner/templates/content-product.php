<?php

$print = isset($_GET['print']) && (bool)$_GET['print'];
if($print) {
  echo tbo()->view->load('product-detail--print', ['post' => $post]);
} else {
  echo tbo()->view->load('product-detail', ['post' => $post]);
}