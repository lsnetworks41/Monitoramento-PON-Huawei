#!/usr/bin/php -q
<?php

$comm = $argv[1];
$ip = $argv[2];

$int  = "/([0-9]{0,}).=.*[A-Z]:.(GPON) (.*)$/m"; // :regex para filtrar index, type_int, name_int
$on   = "/:.(1)$/m"; // filtra satus ont online
$off  = "/:.(2)$/m"; // filtra status ont offline
 
$total  = null;
$qt_on  = null;
$qt_off = null;

$snmp_int = shell_exec("snmpwalk -v 2c -c $comm $ip ifname"); // executa consulta snmp em ifname olt

if(preg_match_all($int, $snmp_int, $saida_int)){
	for($i=0;$i<count($saida_int[1]);$i++){

		$snmp_onts  = shell_exec("snmpwalk -v 2c -c $comm $ip .1.3.6.1.4.1.2011.6.128.1.1.2.46.1.15.".$saida_int[1][$i]); //executa consulta há status ont

		// filtra onts online
		if(preg_match_all($on, $snmp_onts, $ont_on)){
			$qt_on = count($ont_on[1]);
		}

		//filtra onts offline
		if(preg_match_all($off, $snmp_onts, $ont_off)){
			$qt_off = count($ont_off[1]);
		}

		//calcula qt total onts
		$qt_total = $qt_on+$qt_off;

		//create array
		$data[] = [
			"{#IFINDEX}"  => $saida_int[1][$i],
			"{#PON}"      => $saida_int[3][$i],
			"{#ONTTOTAL}" => $qt_total,
			"{#ONTON}"    => $qt_on,
			"{#ONTOFF}"   => $qt_off
		];
	}
	//encode array to json
	echo json_encode($data);
}

//Desenvolvido por Lsnetworks Solutions
