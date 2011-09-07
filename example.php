<?php

require_once("evergreen/fieldmapper.php");

#using a KCLS/IMLS demo server
#https://75.101.133.94/osrf-gateway-v1?service=open-ils.actor&method=open-ils.actor.org_unit.retrieve&param=&param=1
$json_string = '{"payload":[{"__c":"aou","__p":[null,1,1,1,1,1,"Demo Consortium",1,null,"CONS","","","t",1]}],"status":200}';

$json_obj = json_decode($json_string);

if (!$json_obj) {
    exit("json decode failure");
}

// test for success
if ($json_obj->{"status"} == "200") {
    print "Successful status -- continuing...\n";
    foreach($json_obj->{"payload"} as $payload_obj) {
        $object = JSON2obj($payload_obj);
        print_r($object);
    }
} else {
    exit("status is not successful");
}


