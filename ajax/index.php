<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('memory_limit', '-1');
date_default_timezone_set('Asia/Krasnoyarsk');
if (!isset($_POST['request']) || !isset($_POST['key']))
    die('stoppe');

if ($_POST['request'] == 'getFilters') {
    getFilter($_POST['key']);
} elseif ($_POST['request'] == 'getData') {
    $data = $_POST['data'];
    $res = array(
        'size' => (int)$data['size'],
        'page' => 0,
        'filter' => array(
            'awaitForApprove' => null,
            'awaitOperatorCheck' => null,
            'editApp' => null,
            'idApplicantType' => array(),
            'idCertObjectType' => array(),
            'idDeclScheme' => array(),
            'idDeclType' => array(),
            'idGroupEEU' => array(),
            'idGroupRU' => array(),
            'idProductEEU' => array(),
            'idProductOrigin' => array(),
            'idProductRU' => array(),
            'idProductType' => array(),
            'idTechReg' => array(),
            'isProtocolInvalid' => null,
            'violationSendDate' => null,
            'status' => ($data['state'] ? array($data['state']) : array()),
            'columnsSearch' => array(
                array(
                    'name' => 'number',
                    'search' => $data['number'],
                    'translated' => false,
                    'type' => 0
                )
            ),
            'regDate' => array(
                'minDate' => ($data['regDateMin'] ? date('Y-m-d', strtotime($data['regDateMin'])) : null),
                'maxDate' => ($data['regDateMax'] ? date('Y-m-d', strtotime($data['regDateMax'])) : null)
            ),
            'endDate' => array(
                'minDate' => ($data['endDateMin'] ? date('Y-m-d', strtotime($data['endDateMin'])) : null),
                'maxDate' => ($data['endDateMax'] ? date('Y-m-d', strtotime($data['endDateMax'])) : null)
            ),
        ),
        'columnsSort' => array(
            array(
                'column' => 'declDate',
                'sort' => 'DESC'
            )
        ),
    );

    getData($_POST['key'], json_encode($res));
}

function getToken()
{
    $authorization = null;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://pub.fsa.gov.ru/login');
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, '{"username": "anonymous", "password": "hrgesf7HDR67Bd"}');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: text/plain',
        'Cookie: JSESSIONID=390DDE3E8E400B24BFA5F6E0589937E8'
    ));
    curl_setopt($ch, CURLOPT_HEADERFUNCTION, function ($ch, $header) use (&$authorization) {
        $len = strlen($header);
        $header = explode(':', $header, 2);
        if (count($header) < 2)
            return $len;
        if (strtolower(trim($header[0])) == 'authorization')
            $authorization = trim($header[1]);
        return $len;
    }
    );
    $response = curl_exec($ch);
    curl_close($ch);

    $responsede = json_decode($response, true);
    if ($response === false)
        return false;
    elseif (isset($responsede['error']))
        return array('result' => 'fail', 'errorMsg' => $responsede['message']);
    else
        return array('result' => 'success', 'key' => $authorization);
}

function getData($key, $data)
{

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://pub.fsa.gov.ru/api/v1/rds/common/declarations/get');
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 0);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: ' . $key,
        'Content-Type: application/json',
        'Cookie: JSESSIONID=CD652E00487E224F9A3D27521027F731'
    ));

    $response = curl_exec($ch);
    $responsede = json_decode($response, true);
    curl_close($ch);
    $colorStatuses = array(0 => 'bg-white', 11 => 'bg-primary', 6 => 'bg-info', 14 => 'bg-success', 15 => 'bg-danger', 14 => 'bg-warning', 1 => 'bg-secondary', 11 => 'bg-light',);
    if ($response === false) {
        $results = array('result' => 'fail', 'errorMsg' => curl_error($ch));
        echo json_encode($results);
    } elseif (!$response || (isset($responsede['message']) && $responsede['message'] == 'Access Denied')) {
        $key = getToken();
        getData($key, $data);
    } else {
        $results = array('key' => $key, 'result' => 'success', 'colorStatuses' => $colorStatuses, 'data' => $responsede);
        echo json_encode($results);
    }
}

function getFilter($key)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://pub.fsa.gov.ru/api/v1/rds/common/identifiers');
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 0);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: ' . $key,
        'Content-Type: application/json',
        'Cookie: JSESSIONID=CD652E00487E224F9A3D27521027F731'
    ));

    $response = curl_exec($ch);
    curl_close($ch);


    $responsede = json_decode($response, true);

    if ($response === false) {
        $results = array('result' => 'fail', 'errorMsg' => curl_error($ch));
        echo json_encode($results);
    } elseif (!$response || (isset($responsede['error']) && $responsede['message'] == 'Access Denied')) {
        $keyarr = getToken();
        if ($keyarr['result'] == 'success')
            getFilter($keyarr['key']);
        else
            echo json_encode(array('result' => 'fail', 'errorMsg' => $keyarr['errorMsg']));
    } else {
        $result = array('key' => $key, 'result' => 'success', 'data' => $responsede);
        echo json_encode($result);
    }
}
