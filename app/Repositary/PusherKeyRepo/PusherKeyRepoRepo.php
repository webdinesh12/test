<?php

namespace App\Repositary\PusherKeyRepo;

interface  PusherKeyRepoRepo{
	function  get_app_key();
	function  get_app_secret();
	function  get_app_id();
	function  get_app_cluster();
}