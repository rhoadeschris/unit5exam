<?php

    header('Access-Control-Allow-Origin: *');

    if ($_SERVER['REQUEST_METHOD'] != 'POST')
    {
        echo "POST request expected";
        return;
    }

    error_reporting(E_ALL && E_WARNING && E_NOTICE);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);

    require_once 'includes/common.inc.php';

    $requestParameters = RequestParametersParser::getRequestParameters($_POST, !empty($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : null);
    _log($requestParameters);

    try
    {
        $quizResults = new QuizResults();
        $quizResults->InitFromRequest($requestParameters);
        $generator = QuizReportFactory::CreateGenerator($quizResults, $requestParameters);
        $report = $generator->createReport();
      $lastname = $_POST['USER_LAST_NAME'];
      $firstname = $_POST['USER_FIRST_NAME'];
      $studentid = $_POST['STUDENTID'];
      $teachername = $_POST['TEACHER_NAME'];
      $period = $_POST['PERIOD'];
      $psp =$_POST['psp'];
        $sp= $_POST['sp'];
       $tp= $_POST['tp'];
        $dateTime = date('Y-m-d_H-i-s');
        $detailed_results_xml = $_POST['dr'];
     //write complete results to txt file
        $resultFilename = dirname(__FILE__) . "/result/quiz_result_{$teachername}_{$period}_{$lastname}_{$firstname}_{$dateTime}.txt";
        @file_put_contents($resultFilename, $report);

        echo "OK";
    }
    catch (Exception $e)
    {
        error_log($e);

        echo "Error: " . $e->getMessage();
    }

    function _log($requestParameters)
    {
        $logFilename = dirname(__FILE__) . '/log/quiz_results.log';
        $event       = array('ts' => date('Y-m-d H:i:s'), 'request_parameters' => $requestParameters, 'ts_' => time());
        $logMessage  = json_encode($event);
        $logMessage .= ',' . PHP_EOL;
        @file_put_contents($logFilename, $logMessage, FILE_APPEND);
    }
$conn->close();
?> 