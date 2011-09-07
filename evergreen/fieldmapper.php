<?php

// turn a json string with class hint into a fieldmapper object
// decode the raw json

function JSON2obj($string_or_obj) {
    $host = "75.101.133.94"; // XXX: hardcoded -- bad

    // if we get a string, decode it here
    // $json_obj = json_decode($string);
    // otherwise, the string_or_obj becomes out json_obj
    $json_obj = $string_or_obj;

    if (!$json_obj) {
        return false;
    }

    // do we have a class hint?
    if ($json_obj->{"__c"}) {
        $class_hint = $json_obj->{"__c"};
        print "Found class hint: " . $class_hint . "\n";
    } else {
        exit("Found no class key");
    }

    // request IDL definition for this class
    // TODO: cache!
    $curl = curl_init();
    #curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    #curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_URL, "https://" . $host . "/reports/fm_IDL.xml?class=" . $class_hint);
    $idl_chunk = curl_exec($curl);
    print curl_error($curl);
    print $idl_chunk . "\n\n";

    $dom = new DOMDocument();
    $dom->loadXML($idl_chunk);
    $classes = $dom->getElementsByTagName("class");
    $class = $classes->item(0);
    $fields = $class->getElementsByTagName("fields");
    $all_fields = $fields->item(0)->getElementsByTagName("field");
    $field_total = $all_fields->length;
    $new_obj = new stdClass();
    for ($f = 0; $f<$field_total; $f++) {
        $fieldname = $all_fields->item($f)->getAttribute("name");
        $fieldvalue = $json_obj->{"__p"}[$f];
        print $f . " : " . $fieldname . " = " . $fieldvalue;
        print "\n";
        $new_obj->{$fieldname} = $fieldvalue;
    }
    return $new_obj;
}

