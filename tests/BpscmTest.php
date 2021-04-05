<?php
use PHPUnit\Framework\TestCase;
use linmasahiro\Bpscm;

class BpscmTest extends TestCase
{
    /**
     * @var Bpscm
     */
    protected $object;

    /**
     * 訂單號碼
     *
     * @var string
     */
    public $orderNo;

    /**
     * 發票號碼
     *
     * @var string
     */
    public $invoiceNo;

    /**
     * 實體化測試類別
     */
    protected function setUp(): void {
        $this->object = new Bpscm('your sellerNo', 'your ID', 'your password', false);
        $this->orderNo = 'ORD' . Date('YmdHis');
    }

    /**
     * 上傳發票
     * @test
     */
    public function uploadInvoice() {
        $orderInfo = [
            'OrderNo' => $this->orderNo,
            'OrderDate' => Date('Y/m/d'), // 下單日期 Y EX: 2010/11/15
            'ExpectedShipDate' => Date('Y/m/d'), // 預計出貨日 Y
            'UpdateOrderDate' => Date('Y/m/d'), // 訂單異動日期 Y
            'RateType' => '1', // 稅率類別 Y (1: Taxable 2: zero tax 3: tax free)
            'Amount' => '0', // 金額 Y
            'TaxAmount' => '0', // 稅 Y
            'TotalAmount' => 100, // 合計金額 Y
            'SellerBAN' => '', // 統編 Y
            'SellerCode' => '', // 賣家代碼 O
            'BuyerBAN' => '', // 買家統編 O
            'BuyerCompanyName' => '', // 買家抬頭 O
            'Members' => [
                [
                    'ID' => 'k80092@gmail.com', // 會員ID Y
                    'Name' => 'LinMasahiro', // 會員名稱 O
                    'ZipCode' => '', // 郵遞區號 O
                    'Address' => '高雄市大寮區', // 地址 O
                    'Tel' => '', // 電話 O
                    'MobilePhone' => '', // 手機 O
                    'Email' => 'k80092@gmail.com' // 信箱 Y
                ]
            ],
            'DiscountAmount' => 0, // 折扣金額 O
            'PaperInvoiceMark' => '1', // 需要紙本發票 Y (1:Paper 0:Not paper (Including E-invoice & donation))
            'DonateMark' => '', // 捐獻代碼 O
            'PaymentType' => '', // 付款方式 O
            'RelateNumber1' => '', // ?? O
            'RelateNumber2' => '', // 折讓時用 O
            'RelateNumber3' => '', // ?? O
            'MainRemark' => '', // ?? O
            'CarrierType' => '', // ?? O
            'CarrierId1' => '', // ?? O
            'CarrierId2' => '', // ?? O
            'OrderDetails' => [
                [
                    'SeqNo' => 1, // 序號 Y
                    'ItemID' => 'A01', // 商品ID Y
                    'Barcode' => '', // 二維條碼 O
                    'ItemName' => '商品A', // 品名 Y
                    'ItemSpec' => '', // 規格 O
                    'Unit' => '', // 數量 O
                    'UnitPrice' => 50, // 單價 O
                    'Qty' => 1, // 購買數量 Y
                    'Amount' => '0', // 金額 O
                    'TaxAmount' => '0', // 稅 O
                    'TotalAmount' => 50, // 合計金額 Y
                    'HealthAmount' => '0', // 健康捐 O
                    'RateType' => '1', // 稅率類別 Y
                    'DiscountAmount' => 0, // 折扣金額 O
                    'DetailRemark' => '', // ?? O
                ],
                [
                    'SeqNo' => 2, // 序號 Y
                    'ItemID' => 'A02', // 商品ID Y
                    'Barcode' => '', // 二維條碼 O
                    'ItemName' => '商品2', // 品名 Y
                    'ItemSpec' => '', // 規格 O
                    'Unit' => '', // 數量 O
                    'UnitPrice' => 50, // 單價 O
                    'Qty' => 1, // 購買數量 Y
                    'Amount' => '0', // 金額 O
                    'TaxAmount' => '0', // 稅 O
                    'TotalAmount' => 50, // 合計金額 Y
                    'HealthAmount' => '0', // 健康捐 O
                    'RateType' => '1', // 稅率類別 Y
                    'DiscountAmount' => 0, // 折扣金額 O
                    'DetailRemark' => '', // ?? O
                ]
            ]
        ];
        $result = $this->object->postInvoiceXml($orderInfo);
        $this->assertEquals('OK', $result['result']);
        $this->assertNotNull($result['invoiceNo']);
    }

    /**
     * 上傳折讓
     * @test
     */
    public function uploadAllowance() {
        $orderInfo = [
            'OrderNo' => $this->orderNo,
            'OrderDate' => Date('Y/m/d'), // 下單日期 Y EX: 2010/11/15
            'ExpectedShipDate' => Date('Y/m/d'), // 預計出貨日 Y
            'UpdateOrderDate' => Date('Y/m/d'), // 訂單異動日期 Y
            'RateType' => '1', // 稅率類別 Y (1: Taxable 2: zero tax 3: tax free)
            'Amount' => '0', // 金額 Y
            'TaxAmount' => '0', // 稅 Y
            'TotalAmount' => 50, // 合計金額 Y
            'SellerBAN' => '', // 統編 Y
            'SellerCode' => '', // 賣家代碼 O
            'BuyerBAN' => '', // 買家統編 O
            'BuyerCompanyName' => '', // 買家抬頭 O
            'Members' => [
                [
                    'ID' => 'k80092@gmail.com', // 會員ID Y
                    'Name' => 'LinMasahiro', // 會員名稱 O
                    'ZipCode' => '', // 郵遞區號 O
                    'Address' => '高雄市大寮區', // 地址 O
                    'Tel' => '', // 電話 O
                    'MobilePhone' => '', // 手機 O
                    'Email' => 'k80092@gmail.com' // 信箱 Y
                ]
            ],
            'DiscountAmount' => 0, // 折扣金額 O
            'PaperInvoiceMark' => '1', // 需要紙本發票 Y (1:Paper 0:Not paper (Including E-invoice & donation))
            'DonateMark' => '', // 捐獻代碼 O
            'PaymentType' => '', // 付款方式 O
            'RelateNumber1' => '', // ?? O
            'RelateNumber2' => '', // 折讓時用 O
            'RelateNumber3' => '', // ?? O
            'MainRemark' => '', // ?? O
            'CarrierType' => '', // ?? O
            'CarrierId1' => '', // ?? O
            'CarrierId2' => '', // ?? O
            'OrderDetails' => [
                [
                    'SeqNo' => 1, // 序號 Y
                    'ItemID' => 'A01', // 商品ID Y
                    'Barcode' => '', // 二維條碼 O
                    'ItemName' => '商品A', // 品名 Y
                    'ItemSpec' => '', // 規格 O
                    'Unit' => '', // 數量 O
                    'UnitPrice' => 50, // 單價 O
                    'Qty' => 1, // 購買數量 Y
                    'Amount' => '0', // 金額 O
                    'TaxAmount' => '0', // 稅 O
                    'TotalAmount' => 50, // 合計金額 Y
                    'HealthAmount' => '0', // 健康捐 O
                    'RateType' => '1', // 稅率類別 Y
                    'DiscountAmount' => 0, // 折扣金額 O
                    'DetailRemark' => '', // ?? O
                ]
            ]
        ];
        $result = $this->object->postAllowanceXml($orderInfo);
        $this->assertEquals('OK', $result['result']);
        $this->assertNotNull($result['allowanceNo']);
    }
}