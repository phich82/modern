<?php

/** 
 * Factory là cái nhà máy sản xuất ra các object theo các điều kiện có sẵn.
 * 
 * Đặc điểm để nghĩ đến factory là các điều kiện if-else / switch-case giống nhau được lặp lại ở nhiều chỗ.
 * 
 * Các hãng máy bay bây giờ đều có kiểu đánh giá level khách hàng thân thiết: 
 *      - hạng bạch kim, 
 *      - hàng vàng, 
 *      - hạng bạc, 
 *      - hạng đồng 
 *      
 *      -> với mỗi hạng thì chiết khấu vé khác nhau và tặng dặm bay khác nhau
 */
class FakeUser
{
    public $type;

    public function __construct(string $type = 'copper')
    {
        $this->type = $type;
    }
}

/** 1. Tập trung xử lý phần điều kiện trong if-else vào các object riêng biệt */
class Platinum
{
    public function discount()
    {
        return 0.1;
    }
    public function bonus()
    {
        return 10;
    }
}
 
Class Golden
{
    public function discount()
    {
        return 0.07;
    }
    public function bonus()
    {
        return 7;
    }
}
 
Class Silver
{
    public function discount()
    {
        return 0.05;
    }
    public function bonus()
    {
        return 5;
    }
}
 
Class Copper
{
    public function discount()
    {
        return 0.03;
    }
    public function bonus()
    {
        return 3;
    }
}

/** Sinh object theo điều kiện đầu vào */
class UserTypeFactory
{
    public static function make($type)
    {
        switch ($type) {
            case 'platinum': return new Platinum; break;
            case 'golden'  : return new Golden;   break;
            case 'silver'  : return new Silver;   break;
            case 'copper'  : return new Copper;   break;
        }
    }
}

/** 3. Sử dụng trong client */
class OrderController
{

    private $user;

    public function __construct(FakeUser $user)
    {
        $this->user = $user;
    }
    /**
     * Store price information when booking ticket
     */
    public function getPrice()
    {
        $price    = 500;
        $customer = UserTypeFactory::make($this->user->type);
        $discount = $customer->discount();
        $price    = $price - $price * $discount;
        echo "Price: ".$price."\n";
    }
 
    public function getBonus()
    {
        $customer = UserTypeFactory::make($this->user->type);
        echo "Bonus: ".$customer->bonus()."\n";
    }
}

$controller = new OrderController(new FakeUser('golden'));

$controller->getPrice();
$controller->getBonus();