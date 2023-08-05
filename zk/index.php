<?php
    ini_set('display_errors',1);

    require 'lib/TADFactory.php';
    require 'lib/TAD.php';
    require 'lib/TADResponse.php';
    require 'lib/Providers/TADSoap.php';
    require 'lib/Providers/TADZKLib.php';
    require 'lib/Exceptions/ConnectionError.php';
    require 'lib/Exceptions/FilterArgumentError.php';
    require 'lib/Exceptions/UnrecognizedArgument.php';
    require 'lib/Exceptions/UnrecognizedCommand.php';
    use TADPHP\TADFactory;

    // $tad = (new TADFactory(['ip'=>'172.17.56.230', 'com_key'=>0]))->get_instance();
    
    $tad = (new TADFactory(['ip'=>'172.17.56.230', 'com_key'=>0]))->get_instance();
    

    $logs = $tad->get_att_log()->to_array();

    // $att_logs = $tad->get_att_log();

    // $filtered_att_logs = $att_logs->filter_by_date(
    //     ['start' => '2023-08-01','end' => '2023-08-30']
    // );


    // var_dump($logs->get_response_body());
    // $tad = (new TADFactory(['ip'=>'172.17.38.89', 'com_key'=>0]))->get_instance();
    // $logs = $tad->get_att_log(['pin'=>847])->to_array();

    // $logs = $tad->get_att_log();
   
    // var_dump($logs);

    echo json_encode($logs);

?>
