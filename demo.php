<?php

class Demo {

    public function wap_api_pro_return(){
    	$data = input();
    	dump($data);
    }

    // 收钱吧设备使用流程：激活->每日使用前签到->交易
    public function wap_api_pro(){
    	$sqb = new \sqb\sqb();
    	$terminal_sn = '100013760006700826';
    	$terminal_key = 'a6eab23b6559673b9fb455e80ff84605';
    	
    	// 必填参数
    	$params['terminal_sn'] = $terminal_sn;           //收钱吧终端ID
	    $params['client_sn'] = $sqb->getClient_Sn(32);//商户系统订单号,必须在商户系统内唯一；且长度不超过64字节
	    $params['total_amount'] = '1';//以分为单位,不超过10位纯数字字符串,超过1亿元的收款请使用银行转账
	    $params['subject'] = '报名费';//本次交易的概述
	    $params['operator'] = '亲葱收银台';//发起本次交易的操作员
	    $params['return_url'] = url('index/index/wap_api_pro_return',false,true,true);

	    // 非必填参数
	    // 支付方式，目前支持的支付方式参照附录 《支付方式》。不传默认选择当前环境支持的支付方式。如在支付宝客户端打开则使用支付宝支付。
	    // $params['payway'] = '3';
	    // 支付回调的地址
		// $params['notify_url'] = 'http://10.0.0.157/dashboard/test.php';


	    $data = $sqb->wap_api_pro($terminal_sn,$terminal_key,$params);
	    // 302跳转的方式进入付款    无需选定支付方式，在支付宝中自动打开支付宝付款，微信中自动打开微信付款，其他浏览器打开则提示请在各类支付APP中打开。
	    header($data);
    }

    // 激活设备，每台设备只需要激活一次，需要自己长久保持激活后接收到的数据，设备每天第一次使用前需要先签到
    public function activate(){
    	$sqb = new \sqb\sqb();
    	// 这两个参数可以写死
    	$vendor_sn = '91800617';
	    $vendor_key = '5603112da9e48a653a11f65c6225d65b';
	    // 必填参数
	    $params['app_id'] = '2019030800001376';           //app id，从服务商平台获取2017112500000439
	    $params['code'] = '19106193';              //激活码内容11654978，激活码从商户平台获取，创建激活码，一般7天过期，自定义可激活次数，此激活码10次
	    $params['device_id'] = '10001';//设备唯一身份ID，可自由设置，但是必须唯一！

	    // 以下参数非必填
	    $params['client_sn']='10001';                   //第三方终端号，必须保证在app id下唯一
	    $params['name']='报名系统H5在线支付';                       //终端名
	    $params['os_info']='WEB';                 //当前系统信息，如: Android5.0
	    // $params['sdk_version']='';                    //SDK版本
	    $data = $sqb->activate($vendor_sn, $vendor_key,$params);
	    return $data;
    }

    //签到 定时任务
    public function checkin(){
    	$sqb = new \sqb\sqb();
//    	$terminal_sn = '100013760006700826';
//    	$terminal_key = 'f516300e4d50bd8659abc605bd61013d';
        $terminal_sn=db("config")->value("terminal_sn");
        $terminal_key=db("config")->value("terminal_key");
    	$params['terminal_sn'] = $terminal_sn;              //终端号
	    $params['device_id'] = '10001';//设备唯一身份ID

	    $params['os_info']='WEB';                 //当前系统信息，如: Android5.0
	    //    $params['sdk_version']='';                    //SDK版本
	    $data = $sqb->checkin($terminal_sn,$terminal_key,$params);
	    dump($data);
	    return $data;
    }

    //B扫C支付（用户展示付款码)
    public function bsc_pay(){
    	$sqb = new \sqb\sqb();
    	$terminal_sn = '100013760006700826';
    	$terminal_key = 'a6eab23b6559673b9fb455e80ff84605';
    	
    	// 必填参数
	    $params['terminal_sn'] = $terminal_sn;          //终端号
	    $params['client_sn'] = $sqb->getClient_Sn(16); //商户系统订单号,必须在商户系统内唯一；且长度不超过64字节
	    $params['total_amount'] = '1';                   //交易总金额,以分为单位
	    $params['payway'] = '1';                         //支付方式,1:支付宝 3:微信 4:百付宝 5:京东钱包 6:qq钱包
	    $params['dynamic_id'] = '';  //条码内容,支付码的二维码或条码的内容
	    $params['subject'] = '报名费';                   //交易简介
	    $params['operator'] = '亲葱收银台';                    //门店操作员

	    // 非必填参数
	    /*$params['description'] = '';       //对商品或本次交易的描述
	    $params['longitude'] = '';         //经纬度必须同时出现   "121.615459404"
	    $params['latitude'] = '';          //经纬度必须同时出现   "31.4056441552"
	    $params['device_id'] = '';         //设备指纹(好像就是设备id吧，官方文档非要说什么设备指纹，好像好高大上的样子)
	    //收钱吧与特定第三方单独约定的参数集合,json格式，最多支持24个字段，每个字段key长度不超过64字节，value长度不超过256字节  { "goods_tag": "beijing"}
	    $params['extended'] = '';
	    //格式为json goods_details的值为数组，每一个元素包含五个字段，goods_id商品的编号，goods_name商品名称，quantity商品数量，price商品单价，单位为分，promotion_type优惠类型，0:没有优惠 1: 支付机构优惠，为1会把相关信息送到支付机构
	    //"goods_details": [{"goods_id": "wx001","goods_name": "苹果笔记本电脑","quantity": 1,"price": 2,"promotion_type": 0},{"goods_id":"wx002","goods_name":"tesla","quantity": 1,"price": 2,"promotion_type": 1}]
	    $params['goods_details'] = '';
	    //任何调用者希望原样返回的信息，可以用于关联商户ERP系统的订单或记录附加订单内容  { "tips": "200" }
	    $params['reflect'] = '';
	    //支付回调的地址  例如：www.baidu.com 如果支付成功通知时间间隔为1s,5s,30s,600s
	    $params['notify_url'] = '';*/


	    $data = $sqb->pay($terminal_sn,$terminal_key,$params);
	    return $data;
	    // dump(json_decode($data,true));//返回数组形式
    }

    // 支付状态查询接口
    public function query(){
    	$sqb = new \sqb\sqb();
    	$terminal_sn = '100013760006700826';
    	$terminal_key = 'a6eab23b6559673b9fb455e80ff84605';
    	
    	// 必填参数  sn与client_sn不能同时为空，优先按照sn查找订单，如果没有，再按照client_sn查询
	    $params['terminal_sn'] = $terminal_sn;           //收钱吧终端ID
	    $params['sn']='7895256879889609';              //收钱吧系统内部唯一订单号
	    $params['client_sn'] = '6969898612264991';//商户系统订单号,必须在商户系统内唯一；且长度不超过64字节

	    $data = $sqb->query($terminal_sn,$terminal_key,$params);
	    dump($data);
	    dump(json_decode($data,true));
    }

    public function refund(){
    	$sqb = new \sqb\sqb();
    	$terminal_sn = '100013760006700826';
    	$terminal_key = 'a6eab23b6559673b9fb455e80ff84605';
    	
    	// 必填参数
    	$params['terminal_sn'] = $terminal_sn;           //收钱吧终端ID
    	/*sn与client_sn不能同时为空，优先按照sn查找订单，如果没有，再按照client_sn查询*/
	    $params['sn'] = '7895256874282957';              //收钱吧系统内部唯一订单号
		// $params['client_sn']='7895256874282957';//商户系统订单号,必须在商户系统内唯一；且长度不超过64字节
	    $params['refund_amount'] = '1';                   //退款金额
	    //商户退款所需序列号,表明是第几次退款
	    /*商户退款所需序列号，用于唯一标识某次退款请求，以防止意外的重复退款。正常情况下，对同一笔订单进行多次退款请求时该字段不能重复；而当通信质量不佳，终端不确认退款请求是否成功，自动或手动发起的退款请求重试，则务必要保持序列号不变*/
	    $params['refund_request_no'] = '001';
	    $params['operator'] = '亲葱收银台';                    //门店操作员

	    // 非必填参数
	    // 商户退款流水号，如果商户同一笔订单多次退款，需要传入不同的退款流水号来区分退款，如果退款请求超时，需要发起查询，并根据查询结果的client_tsn判断本次退款请求是否成功
	    //$params['client_tsn'] = '';
	    // 收钱吧与特定第三方单独约定的参数集合,json格式，最多支持24个字段，每个字段key长度不超过64字节，value长度不超过256字节
	    //$params['extended']='';
	    /*格式为json goods_details的值为数组，每一个元素包含五个字段，一个是goods_id商品的编号，一个是goods_name商品名称，一个是quantity商品数量，一个是price商品单价，单位为分，一个是promotion_type优惠类型，0:没有优惠 1: 支付机构优惠，为1会把相关信息送到支付机构*/
	    //$params['goods_details'] = '';

	    $data = $sqb->refund($terminal_sn,$terminal_key,$params);
	    dump($data);
	    dump(json_decode($data,true));
    }

    //预下单操作
    public function precreate(){
    	$sqb = new \sqb\sqb();
    	$terminal_sn = '100013760006700826';
    	$terminal_key = 'a6eab23b6559673b9fb455e80ff84605';
    	
    	// 必填参数
    	$params['terminal_sn'] = $terminal_sn;           //收钱吧终端ID
	//        $params['sn']='7895253130995555';              //收钱吧系统内部唯一订单号
	    $params['client_sn'] = $sqb->getClient_Sn(32);//商户系统订单号,必须在商户系统内唯一；且长度不超过64字节
	    $params['total_amount'] = '1';                   //金额
	    $params['payway'] = '3';                 //内容为数字的字符串 支付方式
	    $params['subject'] = '报名费';                //本次交易的概述
	    $params['operator'] = '亲葱收银台';              //发起本次交易的操作员


	    // $params['sub_payway']='3';               //内容为数字的字符串，如果要使用WAP支付，则必须传 "3", 使用小程序支付请传"4"
	    // $params['payer_uid']='ooWEX5z3G1i6HlolIb0xJSeVRNFo';                    //消费者在支付通道的唯一id,微信WAP支付必须传open_id,支付宝WAP支付必传用户授权的userId
	//        $params['description']='';            //对商品或本次交易的描述
	//        $params['longitude']='';             //经纬度必须同时出现
	//        $params['latitude']='';              //经纬度必须同时出现
	//        $params['extended']='';              //收钱吧与特定第三方单独约定的参数集合,json格式，最多支持24个字段，每个字段key长度不超过64字节，value长度不超过256字节
	//        $params['goods_details']='';        //
	//        $params['reflect']='';              //任何调用者希望原样返回的信息
	//        $params['notify_url']='';            //支付回调的地址

	    $data = $sqb->precreate($terminal_sn,$terminal_key,$params);
	    dump($data);
	    dump(json_decode($data,true));
    }

    // 取消订单
    public function cancel(){
    	$sqb = new \sqb\sqb();
    	$terminal_sn = '100013760006700826';
    	$terminal_key = 'a6eab23b6559673b9fb455e80ff84605';
    	
    	// 必填参数
    	$params['terminal_sn'] = $terminal_sn;           //收钱吧终端ID
	//        $params['sn']='7895253130997784';              //收钱吧系统内部唯一订单号
	    $params['client_sn'] = '35268734257062134488049679598050';//商户系统订单号,必须在商户系统内唯一；且长度不超过64字节

	    $data = $sqb->cancel($terminal_sn,$terminal_key,$params);
	    dump($data);
	    dump(json_decode($data,true));
    }

    // 主动撤单
    public function revoke(){
    	$sqb = new \sqb\sqb();
    	$terminal_sn = '100013760006700826';
    	$terminal_key = 'a6eab23b6559673b9fb455e80ff84605';
    	
    	// 必填参数
    	$params['terminal_sn'] = $terminal_sn;           //收钱吧终端ID
	//        $params['sn']='7895253130997784';              //收钱吧系统内部唯一订单号
	    $params['client_sn'] = '35268734257062134488049679598050';//商户系统订单号,必须在商户系统内唯一；且长度不超过64字节

	    $data = $sqb->revoke($terminal_sn,$terminal_key,$params);
	    dump($data);
	    dump(json_decode($data,true));
    }
}
