<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	START Core Helper
*/

# untuk print_f
function pre( $var, $exit = null )
{
	$CI = &get_instance();
	echo '<pre>';
	if ( $var == 'lastdb' ){
		print_r($CI->db->last_query());
	} else if ( $var == 'post' ){
		print_r($CI->input->post());
	} else if ( $var == 'get' ){
		print_r($CI->input->get());
	} else {
		print_r( $var );
	}
	echo '</pre>';

	if ( $exit )
	{
		exit();
	}
}

function md5_mod($str, $salt = '_wom_finance'){

	$str = md5(md5($str).$salt);
	return $str;
}

function strEncrypt($str, $forDB = FALSE){
	$CI =& get_instance();
	$key    = $CI->config->item('encryption_key');

	$str    = ($forDB) ? 'md5(concat(\'' . $key . '\',' . $str . '))' : md5($key . $str);
	return $str;
}

function uang( $var, $dec="0" )
{
	if ( empty($var) ) return 0;
	return 'Rp. ' . number_format(str_replace(',','.',$var), $dec,',','.').($dec=="0"?",00":'');
}

function uang2( $var, $dec="0" )
{
	if ( empty($var) ) return 0;
	return number_format(str_replace(',','.',$var),$dec,',','.');
}

function bulan($bulan)
{
	$aBulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

	return $aBulan[$bulan];
}

function hari($hari)
{
	$data  = date('N', strtotime($hari));
	$aHari = ['', 'Senin', 'Selasa','Rabu','Kamis',"Jum'at",'Sabtu','Minggu'];

	return $aHari[$data];
}

function tgl_format($tgl , $format = "")
{
	if ($format == "") {
		$tanggal    = date('d', strtotime($tgl));
		$bulan      = bulan( date('n', strtotime($tgl))-1 );
		$tahun      = date('Y', strtotime($tgl));
		return $tanggal.' '.$bulan.' '.$tahun;
	}elseif ($format == 'd/m/Y') {
		$tanggal    = date('d', strtotime($tgl));
		$bulan      = date('m',strtotime($tgl));
		$tahun      = date('Y', strtotime($tgl));
		return $tanggal.'-'.$bulan.'-'.$tahun;
	}

}

function date_format_indo($tgl)
{
	$exp = explode(' ', $tgl);
	$detik      = date('s', strtotime($tgl));
	$menit      = date('i', strtotime($tgl));
	$jam        = date('H', strtotime($tgl));
	$tanggal    = date('d', strtotime($tgl));
	$bulan      = bulan( date('n', strtotime($tgl))-1 );
	$tahun      = date('Y', strtotime($tgl));

	if( empty($exp[1]) ){
		return $tanggal.' '.$bulan.' '.$tahun;
	}else{
		return $tanggal.' '.$bulan.' '.$tahun.' '.$jam.':'.$menit.':'.$detik;
	}
}

function bulan_tahun($param){
	$bulan = bulan( date('n', strtotime($param))-1 );
	$tahun = date('Y', strtotime($param));
	return $bulan.' '.$tahun;
}

function date_value($tgl)
{
   $date        = date_format_indo($tgl);
   $date_indo   = explode(' ', $date);

   $hr  = hari($tgl);
   $tg  = Terbilang($date_indo[0]);
   $bln = $date_indo[1];
   $thn = Terbilang($date_indo[2]);

   return $hr . ' tanggal ' . $tg . ' bulan ' . $bln . ' tahun ' . $thn;
}

function bln_thn($tgl, $deli)
{
	$data   = explode($deli, $tgl);
	$x      = (intval($data[0])-1);

	return bulan($x) . ' ' . $data[1];
}

// function config( $val, $json = null )
// {
//     $CI =& get_instance();
//     $result = $CI->m_global->get_data_all('config', null, ['config_nama' => $val])[0];
//     if ( $result ) {
//       if ( $json ){
//         return json_decode( $result->config_value );
//       } else {
//         return $result->config_value;
//       }
//     } else {
//         return null;
//     }
// }

function Terbilang($x) {
	$x = abs($x);
	$angka = array("", "satu ", "dua ", "tiga ", "empat ", "lima ","enam ", "tujuh ", "delapan ", "sembilan ", "sepuluh ", "sebelas ");
	$temp = "";
	if ($x <12) {
		$temp = "". $angka[$x];
	} else if ($x <20) {
		$temp = Terbilang($x - 10). "belas ";
	} else if ($x <100) {
		$temp = Terbilang($x/10)."puluh ". Terbilang($x % 10);
	} else if ($x <200) {
		$temp = "seratus " . Terbilang($x - 100);
	} else if ($x <1000) {
		$temp = Terbilang($x/100) . "ratus " . Terbilang($x % 100);
	} else if ($x <2000) {
		$temp = " seribu" . Terbilang($x - 1000);
	} else if ($x <1000000) {
		$temp = Terbilang($x/1000) . "ribu " . Terbilang($x % 1000);
	} else if ($x <1000000000) {
		$temp = Terbilang($x/1000000) . "juta " . Terbilang($x % 1000000);
	} else if ($x <1000000000000) {
		$temp = Terbilang($x/1000000000) . "milyar " . Terbilang(fmod($x,1000000000));
	} else if ($x <1000000000000000) {
		$temp = Terbilang($x/1000000000000) . "trilyun " . Terbilang(fmod($x,1000000000000));
	}
		return $temp;
}

function list_name($array)
{
	$data   = '';
	$count  = count($array);

	if($count == 1) {
		$data = $array[0];
	} else if ($count == 2) {
		$data = $array[0] . ' dan ' . $array[1];
	} else if ($count > 2) {
		foreach ($array as $key => $val) {
			($key == ($count - 1)) ?
			$data .= ' dan ' . $val :
			$data .= $val . ', ';
		}
	}

	return $data;
}

function list_name2($array, $glue = ',', $index = 0, $quote = null)
{
	$data   = '';
	$count  = count($array);

	if($count == 1) {
		$data = !is_null($quote) ? $quote . current($array) . $quote : current($array);
	} else if ($count > 1) {
		$q = ($count - 1);

		if($index == 1) {
			$q = $count;
		} else if ($index > 1) {
			$q = ($count + ($index - 1));
		}

		foreach ($array as $key => $val) {
			($key == $q) ?
			$data .= !is_null($quote) ? $quote . $val . $quote : $val :
			$data .= !is_null($quote) ? $quote . $val . $quote . $glue : $val . $glue;
		}
	}

	return $data;
}

function color( $key )
{
	// $arr = ['white', 'default', 'dark', 'blue', 'blue-madison', 'blue-ebonyclay', 'blue-hoki', 'blue-steel', 'blue-soft', 'blue-dark', 'blue-sharp', 'green', 'green-dark', 'green-sharp', 'grey', 'grey-steel', 'grey-cararra' ];
	$arr = ['blue', 'green', 'red', 'grey-gallery', 'yellow-lemon', 'purple'];
	return $arr[$key];
}

function user_data() {
	$CI = &get_instance();

	$data = $CI->session->userdata('wom_finance');

	return $data;
}

function user_role() {
	// $CI = &get_instance();

	// $sess = $CI->session->userdata('wom_finance');

	// $data['role'] 		= $CI->m_global->get_data_all('roles', null, ['role_id' => $sess->user_role], 'role_type')[0]->role_type;
	// $data['position'] 	= $sess->user_position;

	$data['role'] 		= 'admin';
	$data['position'] 	= '';

	return $data;
}

function dataHelper($search, $id = null)
{
	$CI 	= &get_instance();
	$data 	= [];

	if($search == 'roles') {
		$data = $CI->m_global->get_data_all('roles', null, ['role_status' => '1'], 'role_id, role_name');
	} else if ($search == 'prop') {
		$data = $CI->m_global->get_data_all('propinsi');
	} else if ($search == 'kab') {
		$data = $CI->m_global->get_data_all('kabupaten', null, ['id_prop' => $id]);
	} else if ($search == 'kec') {
		$data = $CI->m_global->get_data_all('kecamatan', null, ['id_kab' => $id]);
	} else if ($search == 'kel') {
		$data = $CI->m_global->get_data_all('kelurahan', null, ['id_kec' => $id]);
	}

	return $data;
}

// $action => 0 = check, 1 = update
function check_update_password($id, $pass, $encrypt = false)
{
	$CI 		= &get_instance();
	$arr 		= [];
	$password   = hash('sha512', $pass);
	$x 			= get_config_password('PASSWORD_HISTORY')[0]->pass_value;

	if ($encrypt == false) {
		$where['user_id'] = $id;
	} else {
		$where[strEncrypt('user_id', TRUE)] = $id;
	}

	$get_data = $CI->m_global->get_data_all('users', null, $where, 'user_id, user_password_backup');

	if ($get_data) {
		$get_arr = json_decode($get_data[0]->user_password_backup);

		if (in_array($password, $get_arr)) {
			return false;
		} else {
			array_push($get_arr, $password);

			$res_pass = (count($get_arr) >= $x) ? json_encode(array_splice($get_arr, 1, $x)) : json_encode($get_arr) ;

			$data = ['user_password_backup' => $res_pass];

			$result = $CI->m_global->update('users', $data, $where);

			if ($result) {
				return true;
			} else {
				return false;
			}
		}
	} else {
		return false;
	}
}

// $type => 0 = logout, 1 = login
function log_user($id, $desc) {
	$CI 	= &get_instance();

	$data = ['lu_date' => date('Y-m-d H:i:s'), 'lu_user_id' => $id, 'lu_desc' => $desc, 'lu_address' => $CI->input->ip_address()];

	if (!empty($data)) {
		$CI->m_global->insert('log_user', $data);
	} else {
		return true;
	}
}

function session_data($out = false, $user_id = null, $id = null) {
	$CI 	= &get_instance();

	if ($out) {
		$session_data = [
			'session_status' => '0',
		];

		$return = $CI->m_global->update('session', $session_data, ['session_id' => $id]);

		return $return;
	} else {
		$session_data = [
			'session_user_id' 	=> $user_id,
			'session_token' 	=> token($user_id),
			'session_time' 		=> time()
		];

		$CI->m_global->insert('session', $session_data);

		return $CI->db->insert_id();
	}
}

function get_access($roleID) {
	$CI 	= &get_instance();
	
	$result = $CI->m_global->get_data_all('roles', null, ['role_id' => $roleID], 'role_access')[0]->role_access;

	return $result;
}

function flag_status($id) {
	$arr = [
		'0' => '<span class="uk-badge uk-badge-info">Upload</span>',
		'1' => '<span class="uk-badge uk-badge-info">Terkirim</span>',
		'2' => '<span class="uk-badge uk-badge-info">Diterima</span>',
		'3' => '<span class="uk-badge uk-badge-info">Proses AHU</span>',
		'4' => '<span class="uk-badge uk-badge-info">Proses Sertifikat Fidusia</span>',
		'5' => '<span class="uk-badge uk-badge-info">Terdaftar</span>',
		'6' => '<span class="uk-badge uk-badge-success">Invoice</span>',
		'7' => '<span class="uk-badge uk-badge-primary">Terbayar</span>',
		'8' => '<span class="uk-badge uk-badge-primary">Siap Kirim</span>',
		'9' => '<span class="uk-badge uk-badge-danger">Reject</span>',
	];

	return $arr[$id];
}
function flag_status_name($id) {
	$arr = [
		'0' => 'Upload',
		'1' => 'Terkirim',
		'2' => 'Diterima',
		'3' => 'Proses AHU',
		'4' => 'Proses Sertifikat Fidusia',
		'5' => 'Terdaftar',
		'6' => 'Invoice',
		'7' => 'Terbayar',
		'8' => 'Siap Kirim',
		'9' => 'Reject',
	];

	return $arr[$id];
}

function get_config_password($filter = null) {
	$CI 		= &get_instance();
	$where_e 	= null;

	if ($filter !== null && !is_array($filter)) {
		$where['pass_name'] = $filter;
	} else if ($filter !== null && is_array($filter)) {
		$list = '';
		
		foreach ($filter as $key => $val) {
			if ($key == 0) {
				$list .= '"'.$val.'"';
			} else {
				$list .= ',"'.$val.'"';
			}
		}
		
		$where_e = 'pass_name IN (' . $list . ')';
	}

	$where['pass_status'] 	= '1';
	$where['pass_type'] 	= '1';

	$data 	= $CI->m_global->get_data_all('password', null, $where, 'pass_id, pass_name, pass_value', $where_e);

	return $data;
}

function token($data = "", $width=192, $rounds = 3) {
	return substr(
		implode(
			array_map(
				function ($h) {
					return str_pad(bin2hex(strrev($h)), 16, "0");
				},
				str_split(hash("tiger192,$rounds", $data, true), 8)
			)
		),
		0, 48-(192-$width)/4
	);
}

?>