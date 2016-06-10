<?php

/* Just for Fun by iBacor */

function tagihanpln($idp, $thn='', $bln=''){

	// array untuk output
	$result = array();
	
	// id pelanggan (required)
	if(!empty($idp)){

		// data
		$thn = (!empty($thn) ? $thn : date("Y")); // tahun (optional. Default: tahun sekarang)
		$bln = (!empty($bln) ? $bln : date("m")); // bulan (optional. Default: bulan sekarang)

		// curl
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, 'http://layanan.pln.co.id/ebill/FormInfoRekening/trans');
		curl_setopt($ch, CURLOPT_REFERER, 'http://layanan.pln.co.id/ebill/');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
			'Content-Type: text/x-gwt-rpc; charset=utf-8',
			'X-GWT-Permutation: C6BB3F692785D0860C3C38B6C5A8FB24',
			'X-GWT-Module-Base: http://layanan.pln.co.id/ebill/FormInfoRekening/'
		));
		curl_setopt($ch, CURLOPT_POSTFIELDS, '7|0|7|http://layanan.pln.co.id/ebill/FormInfoRekening/|31FCED6DBB5E158989E9AD8E99085D6D|com.iconplus.client.services.TransService|getInvoiceByIdpelThblrek|java.lang.String/2004016611|'.$idp.'|'.$thn.$bln.'|1|2|3|4|2|5|5|6|7|');   
		$data = curl_exec($ch);
		curl_close($ch);
		
		// manipulasi dom
		if(preg_match('/ketlunas","fakmkvam/', $data)){
			$data = str_replace(array('//OK', '"', '],0,7]', 'rp', 'frt,', 'ketlunas,'), '', $data);
		}else{
			$data = str_replace(array('//OK', '"', '],0,7]', 'rp', 'frt,'), '', $data);
		}
		$data = str_replace('tag', 'tagihan', $data);
		$data = str_replace(array('   ', '  '), ' ', $data);
		$data = preg_replace("/\[[^>]+\[/i", "", $data);
		
		// create array
		$array = explode(',', $data);
		
		// data ada
		if(count($array) > 5){
			
			// array to object
			$object = new stdClass();
			foreach ($array as $key => $value)
			{
				if($key > 2 && $key % 2 != 0){
					$object->$value = $array[$key + 1];
				}
			}
			$result['status'] = 'success';
			$result['query'] = array(
				'id_pelanggan' => $idp,
				'tahun' => $thn,
				'bulan' => $bln
			);
			$result['data'] = $object;
		}else{
		
			// data tidak ada
			$result['status'] = 'error';
			$result['pesan'] = 'data tidak ada';
		}
	}else{
		
		// id pelanggan belum di isi
		$result['status'] = 'error';
		$result['pesan'] = 'id pelanggan belum di isi';
	}
	
	// object to json
	header('Content-Type: application/json');
	header('Access-Control-Allow-Origin: *');
	return json_encode($result, JSON_PRETTY_PRINT);

}
	
?>
