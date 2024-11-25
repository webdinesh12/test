<?php

namespace App\Repositary\PusherKeyRepo;

class PusherKeyRepoRepoImpl implements  PusherKeyRepoRepo{
	function  get_app_key(){
		// return '18a73d95bd0eb4dedf89';
		return 'c047094e35b8bf272ade';
		// return '29cd7876495941ad69be';
	}
	
	function  get_app_secret(){
		// return '9332708c29485da8dc90';
		return 'b8b2523d9ecd4e1a9ffa';
		// return '3b4c965cfab9e0cd7e8a';
	}
	
	function  get_app_id(){
		// return '1884211';
		return '1899257';
		// return '1899267';
	}
	
	function  get_app_cluster(){
		return 'ap2';
	}
}