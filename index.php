<?php

	// include tagihanpln.php
	require('tagihanpln.php');
	
	// data
	$id_pelanggan = '132000166606'; // (required)
	$tahun = '2016'; // (optional. Default: tahun sekarang)
	$bulan = '06'; // (optional. Default: bulan sekarang)
	
	// call function tagihan_pln()
	echo tagihanpln($id_pelanggan, $tahun, $bulan);