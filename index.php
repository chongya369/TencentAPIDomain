<head>
    <link rel="stylesheet" type="text/css" href="a.css">
</head>

<body>
    <div class="container">
        <div class="login-wrapper">
            <div class="header">域名注册查询</div>
            <form method="post" action="/">
                <div class="form-wrapper">
                    <input type="text" name="DomainName" placeholder="输入要查询的域名" class="input-item">
                    <input style="cursor: pointer;" class="btn" type="submit" name="chaxun" value="查询" />
                </div>
            </form>
            <div class="msg">
                
            </div>
</body>
<?php
$DomainName = $_POST["DomainName"];
require_once "vendor/autoload.php";
use TencentCloud\Common\Credential;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Domain\V20180808\DomainClient;
use TencentCloud\Domain\V20180808\Models\CheckDomainRequest;
try {
    // 实例化一个认证对象，入参需要传入腾讯云账户 SecretId 和 SecretKey，此处还需注意密钥对的保密
    // 代码泄露可能会导致 SecretId 和 SecretKey 泄露，并威胁账号下所有资源的安全性。以下代码示例仅供参考，建议采用更安全的方式来使用密钥，请参见：https://cloud.tencent.com/document/product/1278/85305
    // 密钥可前往官网控制台 https://console.cloud.tencent.com/cam/capi 进行获取
    $SecretId = "你的SecretId";
    $SecretKey = "你的SecretKey";
    $cred = new Credential("$SecretId", "$SecretKey");
    // 实例化一个http选项，可选的，没有特殊需求可以跳过
    $httpProfile = new HttpProfile();
    $httpProfile->setEndpoint("domain.tencentcloudapi.com");

    // 实例化一个client选项，可选的，没有特殊需求可以跳过
    $clientProfile = new ClientProfile();
    $clientProfile->setHttpProfile($httpProfile);
    // 实例化要请求产品的client对象,clientProfile是可选的
    $client = new DomainClient($cred, "", $clientProfile);

    // 实例化一个请求对象,每个接口都会对应一个request对象
    $req = new CheckDomainRequest();

    //请求的内容
    $params = array(
        "DomainName" => "$DomainName",
        "Period" => "1"
    );
    if (array_key_exists('chaxun', $_POST)) {
        $req->fromJsonString(json_encode($params));

        // 返回的resp是一个CheckDomainResponse的实例，与请求对象对应
        $resp = $client->CheckDomain($req);

        // 输出json格式的字符串回包
        //print_r($resp->toJsonString());
        $array = (array)$resp; //json转array
        echo "查询域名：" . $array["DomainName"] . "<br>";
        //echo $array["Available"]."<br>";
        if ($array["Available"]) {
            //echo '域名未被注册';
            if (!$array["Premium"]) {
                echo "域名可注册";
            } else {
                echo "溢价域名不可注册";
            }
        } else {
            echo "域名已被注册";
        }
    }
}
//我也不懂什么意思
catch (TencentCloudSDKException $e) {
    echo $e;
}
