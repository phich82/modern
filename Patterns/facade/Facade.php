<?php 

/**
 * 1. Problem:
 *      Giả sử bạn có 1 chuỗi các hành động được thực hiện theo thứ tự, và các hành động này lại được yêu cầu 
 *      ở nhiều nơi trong phạm vi ứng dụng. Vậy mỗi lúc bạn cần dùng đến nó, bạn lại phải copy-paste hoặc viết
 *      lại đoạn code đó vào những nơi cần sử dụng trong ứng dụng. Điều này có vẻ ok, copy cũng nhanh nên chẳng 
 *      sao, nhưng nếu bỗng nhiên làm xong bạn nhận ra cần phải thay đổi lại cấu trúc và mã xử lý trong hầu hết 
 *      chuỗi hành động đó, vậy bạn sẽ làm gì? 
 * 
 *      Đây chính là mấu chốt của vấn đề, bạn sẽ lại đi lục lại đoạn code đó ở tất cả các nơi, rồi lại sửa nó. 
 *      Điều này quá tốn thời gian và hơn nữa dường như bạn đang mất đi sự kiểm soát các đoạn mã của mình và 
 *      trong quá trình đó còn có nguy cơ phát sinh lỗi.
 * 
 * 2. Solution:
 *      Những gì bạn cần phải làm chỉ là thiết kế một Facade, trong đó phương thức facade sẽ xử lý các đoạn code
 *      dùng đi dùng lại. Từ quan điểm trên, chúng ta chỉ cần gọi Facade để thực thi các hành động dựa trên các 
 *      parameters được cung cấp. Bây giờ, nếu chúng ta cần bất kỳ thay đổi nào trong quá trình trên, công việc 
 *      sẽ đơn giản hơn rất nhiểu, là chỉ cần thay đổi các xử lý trong phương thức facade của bạn và mọi thứ sẽ 
 *      được đồng bộ thay vì thực hiện sự thay đổi ở những nơi sử dụng cả chuỗi các mã code đó. Đây chính là mấu 
 *      chốt của vấn đề, bạn sẽ lại đi lục lại đoạn code đó ở tất cả các nơi, rồi lại sửa nó. Điều này quá tốn 
 *      thời gian và hơn nữa dường như bạn đang mất đi sự kiểm soát các đoạn mã của mình và trong quá trình đó 
 *      còn có nguy cơ phát sinh lỗi.
 * 
 * 3. Example:
 *      Một quá trình kiểm tra đơn giản khi mua hang online bao gồm các bước sau: 
 *          - Thêm sản phẩm vào giỏ hàng. 
 *          - Tính toán chi phí vận chuyển. 
 *          - Tính toán tiền chiết khấu. 
 *          - Tạo đơn đặt hàng.
 * 
 * 4. Apply:
 *      Facade pattern chỉ nên thực hiện trong tình huống mà bạn cần một interface duy nhất để hoàn thành nhiều nhiệm vụ.
 */

class Cart { public function getItems() { return [['a' => 'A', 'price' => 100]]; } }

class Shipping { public function processShipping() { } }

class Order {

    private $cart;

    public function __construct($cart) {
        $this->cart = $cart;
    }

    public function addOrder() {
        if ($this->cart && count($this->cart->getItems()) > 0) {
            echo 'Order has successfully been created.';
        } else {
            echo 'Cart empty.';
        }
    }
}

class OrderFacade 
{
    private $cart;

    public function __construct() {
        $this->cart = new Cart();
    }

    public function createOrder()
    {
        if ($this->checkQuantity()) {
            $this->processShipping();
            $this->addOrder();
        }
    }

    private function checkQuantity()
    {
        return count($this->cart->getItems()) > 0 ? true : false;
    }

    private function processShipping()
    {
        $shipping = new Shipping($this->cart->getItems());
        $shipping->processShipping();
    }

    private function addOrder() 
    {
        $order = new Order();
        $order->addOrder($this->cart);
    }
}