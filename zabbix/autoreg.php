<?php
$zPath='/usr/share/zabbix';
require_once $zPath.'/include/classes/core/APP.php';
require_once $zPath.'/include/classes/api/CApiService.php';
require_once $zPath.'/include/classes/api/services/CHostBase.php';
require_once $zPath.'/include/classes/api/services/CHostGeneral.php';
require_once $zPath.'/include/classes/api/services/CHost.php';
require_once $zPath.'/include/classes/api/services/CHostInterface.php';
require_once $zPath.'/include/classes/api/services/CHostGroup.php';

if (count($argv) !=4 ){
  throw new Exception('There shoud be 3 args. Usage: autoreg.php HOST_NAME TEMPLATE_NAME JMX_PORT');
}

$hName = $argv[1];
$templateGroupName = $argv[2];
preg_match('/jmx:(\d+)/',$argv[3],$parsedMeta);
$jmxPort = (isset($parsedMeta[1])) ? $parsedMeta[1] : $argv[3];
if ($jmxPort < 1 or $jmxPort > 65535){
  throw new Exception("JMX port is out of range");
}

echo $jmxPort;
$instance = APP::getInstance();
$instance->run(APP::EXEC_MODE_API);
API::setWrapper(null);
API::Host()::$userData=['type'=>USER_TYPE_SUPER_ADMIN];

$hosts = API::Host()->get(['filter'=>['host'=>[$hName]]]);
if (count($hosts)>0){
  $host=$hosts[0];
}else{
  throw new Exception('Failed to find host with given host: '.$hName);
}

$hostInterface = API::HostInterface()->get(['hostids'=>[$host{'hostid'}],'filter'=>['main'=>1,'type'=>1]]);
if (count($hostInterface)>0){
  $jmxInterface = $hostInterface[0];
  $jmxInterface{'type'} = 4;
  $jmxInterface{'port'} = $jmxPort;
  unset($jmxInterface{'interfaceid'});
}else{
  throw new Exception('Failed to find main interface for host: '.$hName);
}

$templateGrps = API::HostGroup()->get(['output'=>['groupid'],'filter'=>['name'=>$templateGroupName]]);
if (count($templateGrps)>0){
  $templates = API::Template()->get(['groupids'=>[$templateGrps[0]{'groupid'}],'nopermissions'=>'true']);
}else{
  throw new Exception('Failed to find given group: '.$templateGroupName);
}

API::Host()->massAdd(['hosts'=>[$host],'templates'=>$templates,'interfaces'=>[$jmxInterface]]);
