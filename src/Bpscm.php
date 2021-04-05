<?php

namespace linmasahiro;

use Exception;

/**
 * 金財通電子發票取號模組
 *
 * @author LIN MASAHIRO <k80092@gmail.com>
 */
class Bpscm
{
    /**
     * 賣方統編
     *
     * @var int
     */
    public $sellerBan;

    /**
     * 帳號
     *
     * @var string
     */
    public $userID;

    /*
    * 密碼
    *
    * @var string
    */
    public $password;

    /*
    * 是否為正式模式
    *
    * @var bool
    */
    public $isProductionMode;

    /**
     * 發票取號網址
     *
     * @var string
     */
    public $B2B2CInvoiceAddOrderUrl = 'https://www.bpscm.com.tw/SCMWebService/B2B2CWebService.asmx/B2B2CInvoice_AddOrder';

    /**
     * 發票取號網址(測試版)
     *
     * @var string
     */
    public $B2B2CInvoiceAddOrderTestUrl = 'http://61.57.230.103/SCMWebServiceTest/B2B2CWebService.asmx/B2B2CInvoice_AddOrder';

    /**
     * 訂單上傳執行結果代碼
     *
     * @var array
     */
    protected $xmlResponseCode = [
        'ER001' => '訂單編號欄位不可空白!',
        'ER002' => '訂單狀態欄位只可為 0 , 1 , 2 !',
        'ER003' => '訂單日期欄位不可空白!',
        'ER004' => '訂單日期格式錯誤!',
        'ER005' => '預計出貨日期欄位不可空白!',
        'ER006' => '預計出貨日期格式錯誤!',
        'ER007' => '訂單異動日期欄位不可空白!',
        'ER008' => '訂單異動日期格式錯誤!',
        'ER009' => '稅率別欄位不可空白!',
        'ER010' => '訂單金額(未稅)格式錯誤!',
        'ER011' => '訂單稅額格式錯誤!',
        'ER012' => '訂單金額(含稅)欄位不可空白!',
        'ER013' => '訂單金額(含稅)格式錯誤!',
        'ER014' => '賣方統一編號欄位不可空白!',
        'ER015' => '會員編號欄位不可空白!',
        'ER016' => '會員地址欄位不可空白!',
        'ER017' => '會員電話號碼格式錯誤!',
        'ER018' => '會員手機號碼格式錯誤!',
        'ER019' => '電子郵件格式錯誤!',
        'ER020' => '紅利點數折扣金額格式錯誤!',
        'ER021' => '索取紙本發票欄位不可空白!',
        'ER022' => '訂單主檔欄位數錯誤。',
        'ER023' => '訂單含稅金額與訂單明細含稅金額加總不同!',
        'ER024' => '已有訂單資料,無法新增!',
        'ER025' => '沒有訂單資料,無法刪除!',
        'ER026' => '訂單資料已被刪除,不重覆刪除!',
        'ER027' => '沒有訂單資料,無法修單!',
        'ER028' => '訂單資料已被刪除,無法修單!',
        'ER029' => '系統發生錯誤,匯入正式檔失敗!',
        'ER030' => '發票區間內發票號碼已被取用完畢,請設定新的區間,再上傳訂單!',
        'ER031' => '商品編號:XXXXXX-序號欄位不可空白!',
        'ER032' => '商品編號:XXXXXX-訂單編號欄位不可空白!',
        'ER033' => '商品編號:XXXXXX-商品名稱欄位不可空白!',
        'ER034' => '商品編號:XXXXXX-單價格式錯誤!',
        'ER035' => '商品編號:XXXXXX-數量欄位不可空白!',
        'ER036' => '商品編號:XXXXXX-數量欄位格式錯誤!',
        'ER037' => '商品編號:XXXXXX-未稅金額格式錯誤!',
        'ER038' => '商品編號:XXXXXX-含稅金額欄位不可空白!',
        'ER039' => '商品編號:XXXXXX-含稅金額格式錯誤!',
        'ER040' => '商品編號:XXXXXX-健康捐格式錯誤!',
        'ER041' => '商品編號:XXXXXX-稅率別欄位不可空白!',
        'ER042' => '商品編號:XXXXXX-紅利點數折扣金額格式錯誤!',
        'ER043' => '商品編號:XXXXXX-訂單明細檔欄位數錯誤。',
        'ER044' => '紅利點數折扣金額大於訂單明細含稅金額!',
        'ER045' => '紅利點數折扣金額大於訂單主檔含稅金額!',
        'ER051' => '發票已被列印!',
        'ER052' => '已過關帳日,發票待作廢,請至 Web 系統執行『發票作廢』功能並同意或不同意(開折讓單)作廢發票!',
        'ER053' => '上傳檔賣方統編為空值,請補資料重新上傳。',
        'ER054' => '賣方尚未建立公司基本資料。統編-',
        'ER055' => '系統發生錯誤,匯入暫存主檔失敗!',
        'ER056' => '系統發生錯誤,匯入暫存明細檔失敗!',
        'ER057' => '匯入會員資料發生錯誤!',
        'ER058' => '上傳訂單筆數有錯誤!',
        'ER060' => '要有發票且為開立狀態才可更改發票為作廢或待作廢!',
        'ER061' => 'XML 單筆上傳訂單只接受新單的資料',
        'ER062' => 'XML 單筆上傳新增訂單預計出貨日必須小於等於今天日期',
        'ER065' => '紙本發票的會員地址空白!',
        'ER066' => '賣方統編不屬於該體系的中心廠,訂單匯入失敗!',
        'ER070' => '載具種類代號錯誤!請修正!',
        'ER071' => '手機條碼的隱碼驗證錯誤!請修正!',
        'ER072' => '自然人憑證的隱碼驗證錯誤!請修正!',
        'ER080' => '不接受 0 元訂單!'
    ];

    /**
     * 建構子
     *
     * @param bool $isTest 是否為測試模式
     *
     * @return void
     */
    public function __construct(string $sellerBan, string $userID, string $password, bool $isProductionMode)
    {
        $this->sellerBan = $sellerBan;
        $this->userID = $userID;
        $this->password = $password;
        $this->isProductionMode = $isProductionMode;
    }

    /**
     * 傳送XML開立電子發票
     *
     * @param array $orderInfo 訂單內容
     *
     * @return array
     */
    public function postInvoiceXml(array $orderInfo): array
    {
        try {
            $xml = $this->createPostXml(0, $orderInfo);
            $curl = curl_init();
            $url = $this->isProductionMode ? $this->B2B2CInvoiceAddOrderUrl : $this->B2B2CInvoiceAddOrderTestUrl;
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, 'userID=' . $this->userID . '&pwd=' . $this->password . '&xmlString=' . $xml);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            $responseXml = curl_exec($curl);
            $responseString = simplexml_load_string($responseXml);
            if (! $responseString) {
                // 上傳失敗
                return [
                    'result' => 'ERROR',
                    'message' => '與金財通串接失敗'
                ];
            }
            $con = json_encode($responseString);
            $response = json_decode($con, true);
            if (empty($response['InvoiceNo'])) {
                // 發票號碼為空，開立失敗
                return [
                    'result' => 'ERROR',
                    'message' => $response['ErrorMessage']
                ];
            }

            return [
                'result' => 'OK',
                'invoiceNo' => $response['InvoiceNo'],
                'message' => (! empty($response['ErrorMessage'])) ? $response['ErrorMessage'] : ''
            ];
        } catch (Exception $e) {
            return [
                'result' => 'ERROR',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * 傳送XML開立折讓
     *
     * param array $orderInfo 訂單內容
     *
     * @return array
     */
    public function postAllowanceXml(array $orderInfo): array
    {
        try {
            $xml = $this->createPostXml(3, $orderInfo);
            $curl = curl_init();
            $url = $this->isProductionMode ? $this->B2B2CInvoiceAddOrderUrl : $this->B2B2CInvoiceAddOrderTestUrl;
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, 'userID=' . $this->userID . '&pwd=' . $this->password . '&xmlString=' . $xml);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            $responseXml = curl_exec($curl);
            $responseString = simplexml_load_string($responseXml);
            if (! $responseString) {
                // 上傳失敗
                return [
                    'result' => 'ERROR',
                    'message' => '與金財通串接失敗'
                ];
            }
            $con = json_encode($responseString);
            $response = json_decode($con, true);
            if (empty($response['AllowanceNo'])) {
                // 折讓單號為空，開立失敗
                return [
                    'result' => 'ERROR',
                    'message' => $response['ErrorMessage']
                ];
            }

            return [
                'result' => 'OK',
                'allowanceNo' => $response['AllowanceNo'],
                'message' => (! empty($response['ErrorMessage'])) ? $response['ErrorMessage'] : ''
            ];
        } catch (Exception $e) {
            return [
                'result' => 'ERROR',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * 建立POST用的XML字串
     *
     * @param integer $type      訂單狀態
     * @param array   $orderInfo 訂單內容
     *
     * @return string
     */
    private function createPostXml(int $type, array $orderInfo): string
    {
        $params = $orderInfo;
        $params['OrderStatus'] = $type;
        $orderNo = $orderInfo['OrderNo'];
        $membersXml = '';
        if (isset($params['Members'])) {
            $membersXml = implode('', array_map(function ($member) {
                return '<Member>' . $this->createXml($member) . '</Member>';
            }, $params['Members']));
            unset($params['Members']);
        }
        $detailsXml = '';
        if (isset($params['OrderDetails'])) {
            $detailsXml = implode('', array_map(function ($detail) {
                return '<Detail>' . $this->createXml($detail) . '</Detail>';
            }, $params['OrderDetails']));
            unset($params['OrderDetails']);
        }
        $xmlString = $this->createXml($params) . '<Members>' . $membersXml  . '</Members><OrderDetails>' . $detailsXml . '</OrderDetails>';
        $xml = <<<EOD
<?xml version="1.0" encoding='UTF-8'?>
<OrderData>
<Order No="$orderNo">
$xmlString
</Order>
</OrderData>
EOD;
        return $xml;
    }

    /**
     * 建立XML文字
     *
     * @param array $array         參數陣列
     * @param array $cdataSections 需要轉義的特殊文字
     *
     * @return string
     */
    private function createXml(array $array, array $cdataSections = []): string
    {
        $xml = '';
        foreach ($array as $key => $value) {
            if (is_array($value) === true) {
                $xml .= sprintf('<%s>%s</%s>', $key, $this->createXml($value, $cdataSections), $key);
            } else {
                if (empty($value) === false && (in_array($key, $cdataSections, true) === true || (bool) preg_match('/[<>&]/', $value) === true)) {
                    $value = '<![CDATA[' . $value . ']]>';
                }
                $xml .= sprintf('<%s>%s</%s>', $key, $value, $key);
            }
        }

        return $xml;
    }
}
