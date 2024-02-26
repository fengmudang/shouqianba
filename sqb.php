<?php

namespace sqb;

class sqb{
	// 支付
	function  pay($terminal_sn,$terminal_key,$params)
	{
	    $api_domain = 'https://api.shouqianba.com';
	    $url = $api_domain . "/upay/v2/pay";

	    $ret = $this->pre_do_execute($params, $url, $terminal_sn, $terminal_key);

	    return $ret;
	}

	// 签到
	function checkin($terminal_sn, $terminal_key,$params)
	{
	    $api_domain = 'https://api.shouqianba.com';
	    $url = $api_domain . '/terminal/checkin';
	    $ret = $this->pre_do_execute($params, $url, $terminal_sn, $terminal_key);

	    return $ret;
	//         string(189) "{"result_code":"200","biz_response":{"terminal_sn":"100114020002343785","terminal_key":"e79d5371d7dda6cfcb875ef67db33234",
	//"merchant_sn":"","merchant_name":"","store_sn":"","store_name":""}}"

	}

	// 退款
	function refund($terminal_sn, $terminal_key,$params)
	{
	    $api_domain = 'https://api.shouqianba.com';
	    $url = $api_domain . '/upay/v2/refund';

	    $ret = $this->pre_do_execute($params, $url, $terminal_sn, $terminal_key);

	    return $ret;
	}

	// 激活
	function activate($vendor_sn, $vendor_key,$params)
	{
	    $api_domain = 'https://api.shouqianba.com';
	    $url = $api_domain . '/terminal/activate';
	    // $j_params = json_encode($params);
	    // $sign = $this->getSign($j_params . $vendor_key);
	    // $result = $this->httpPost($url, $j_params, $sign, $vendor_sn);

	    $ret = $this->pre_do_execute($params, $url, $vendor_sn, $vendor_key);

	    return $ret;
	//    string(247) "{"result_code":"200","biz_response":{"terminal_sn":"100114020002373208","terminal_key":"059c443b2e67d2c4630e218b3282887c",
	//"merchant_sn":"18956397746","merchant_name":"半夜鸡叫","store_sn":"00010101001200200046406","store_name":"半夜鸡叫"}}"

	}

	// 预下单
	function precreate($terminal_sn, $terminal_key,$params)
	{
	    $api_domain = 'https://api.shouqianba.com';
	    $url = $api_domain . '/upay/v2/precreate';

	    $ret = $this->pre_do_execute($params, $url, $terminal_sn, $terminal_key);
	    /*
	     * string(44) "https://api.shouqianba.com/upay/v2/precreate"
	    string(724) "{"result_code":"200","error_code":"","error_message":"","biz_response":{"result_code":"PRECREATE_SUCCESS","error_code":"","error_message":"","data":{"sn":"7895253189084906","client_sn":"6521100263201711163297047920",
	    "client_tsn":"6521100263201711163297047920","trade_no":"","finish_time":"","channel_finish_time":"","status":"CREATED","order_status":"CREATED","payway":"1","payway_name":"支付宝","sub_payway":"2","payer_uid":"","payer_login":"","total_amount":"1","net_amount":"1",
	    "qr_code":"https://qr.alipay.com/bax06545wtwccfvlsmxj0076","qr_code_image_url":"https://api.shouqianba.com/upay/qrcode?content=https%3A%2F%2Fqr.alipay.com%2Fbax06545wtwccfvlsmxj0076","subject":"pizza","operator":"Obama","payment_list":[]}}}"
	     *
	     *
	     * */
	    return $ret;

	}

	// 取消 冲正
	function cancel($terminal_sn, $terminal_key,$params)
	{
	    $api_domain = 'https://api.shouqianba.com';
	    $url = $api_domain . '/upay/v2/cancel';

	    $ret = $this->pre_do_execute($params, $url, $terminal_sn, $terminal_key);

	//        string(41) "https://api.shouqianba.com/upay/v2/revoke"
	//string(220) "{"result_code":"200","error_code":"","error_message":"","biz_response":
	//{"result_code":"FAIL","error_code":"UPAY_CANCEL_INVALID_ORDER_STATE","error_message":"当前的订单7895253130997784状态是REFUNDED","data":null}}"
	    return $ret;

	}

	// 主动撤单
	function revoke($terminal_sn, $terminal_key,$params)
	{
	    $api_domain = 'https://api.shouqianba.com';
	    $url = $api_domain . '/upay/v2/revoke';

	    $ret = $this->pre_do_execute($params, $url, $terminal_sn, $terminal_key);

	    return $ret;

	}

	// 查询
	function query($terminal_sn, $terminal_key,$params)
	{
	    $api_domain = 'https://api.shouqianba.com';
	    $url = $api_domain . '/upay/v2/query';

	    $ret = $this->pre_do_execute($params, $url, $terminal_sn, $terminal_key);
	    /*string(40) "https://api.shouqianba.com/upay/v2/query"
	    string(594) "{"result_code":"200","error_code":"","error_message":"","biz_response":
	    {"result_code":"SUCCESS","error_code":"","error_message":"","data":{"sn":"7895253130997784","client_sn":"2002673090172838","client_tsn":"2002673090172838-001",
	    "trade_no":"6521100263201711162107115070","finish_time":"1510803598466","channel_finish_time":"","status":"SUCCESS",
	    "order_status":"REFUNDED","payway":"1","payway_name":"支付宝","sub_payway":"1","payer_uid":"","payer_login":"","total_amount":"1",
	    "net_amount":"0","subject":"Pizza","operator":"kay","payment_list":[{"type":"ALIPAY_HUABEI","amount_total":"1"}]}}}"*/
	    return $ret;

	}

	// wap支付
	function wap_api_pro($terminal_sn, $terminal_key,$params)
	{
	    ksort($params);

	    $param_str = "";
	    foreach ($params as $k => $v) {
	        $param_str .= $k . '=' . $v . '&';
	    }

	    $sign = strtoupper(md5($param_str . 'key=' . $terminal_key));
	    $paramsStr = $param_str . "sign=" . $sign;


	    $res = "https://qr.shouqianba.com/gateway?" . $paramsStr;
	    //将这个url生成二维码扫码或在微信链接中打开可以完成测试
	    // file_put_contents('./runtime/log/wap_api_pro_' . date('Y-m-d') . '.txt', $res, FILE_APPEND);

	    /*
	     * https://m.wosai.cn/qr/gateway?client_sn=0007&notify_url=https://www.shouqianba.com/&operator=Obama&
	     * return_url=http://www.baidu.com&subject=pizza&terminal_sn=100114020002444498&
	     * total_amount=1&sign=40CF32733C5A8AF3FE1D175196762458
	     * */
	//        var_dump($res);exit;
	//    header($res);
	    return $res;

	}

	function pre_do_execute($params, $url, $terminal_sn, $terminal_key)
	{
	    $j_params = json_encode($params);
	    $sign = $this->getSign($j_params . $terminal_key);
	    $result = $this->httpPost($url, $j_params, $sign, $terminal_sn);
	    return $result;
	}


	// 获取Sn
	function getClient_Sn($codeLenth)
	{
	    $str_sn = '';
	    for ($i = 0; $i < $codeLenth; $i++) {
	        if ($i == 0)
	            $str_sn .= rand(1, 9); // first field will not start with 0.
	        else
	            $str_sn .= rand(0, 9);
	    }
	    return $str_sn;

	}

	// 签名
	function getSign($signStr)
	{

	    $md5 = Md5($signStr);
	    return $md5;

	}

	// http请求头
	function httpPost($url, $body, $sign, $sn)
	{

	    $header = array(
	        "Format:json",
	        "Content-Type: application/json",
	        "Authorization:$sn" . ' ' . $sign
	    );


	    $result = $this->do_execute($url, $body, $header);
	    return $result;

	}

	// 执行操作
	function do_execute($url, $postfield, $header)
	{
	    //    var_dump($url);echo '<br>';
	    //    var_dump($postfield);echo '<br>';
	    //    var_dump($header);echo '<br>';exit;

	    $ch = curl_init();

	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);  // 从证书中检查SSL加密算法是否存在

	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_POST, 1);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $postfield);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);

	    $response = curl_exec($ch);
	    // var_dump(curl_error($ch));  //查看报错信息
	    // file_put_contents('./runtime/log/web_api_' . date('Y-m-d') . '.txt', date("Y-m-d H:i:s", time()) . "===" . "返回：" . $response . "\n" . "请求应用参数：" . $postfield . "\n" . "\n" . "请求url：" . $url . "\n", FILE_APPEND);
	    // var_dump($url);
	    //echo '<br>';
	    // var_dump($response);
	    // exit;

	    //    $httpStatusCode = curl_getinfo($ch);

	    curl_close($ch);
	    return $response;
	}
}
