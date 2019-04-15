<?php 

/**
 * Adapter Pattern
 *      => có thể kết nối các thành phần với nhau mà không làm thay đổi trạng thái ban đầu của chủ thể
 * 
 * 1. Problem:
 *      Bạn có 1 cái sạc laptop 3 chân, nhưng ổ cắm chỉ nhận 2 chân 
 *          -> bạn cần 1 cái adapter chuyển từ 3 chân (kết nối với sạc laptop) thành 2 chân (kết nối với ổ cắm)
 * 
 *      Mac Book pro chỉ hỗ trợ cổng xuất ra màn hình thông qua hdmi và thunderbolt. 
 *          -> bạn đã mua 1 cái màn hình chỉ hỗ trợ đầu vào là VGA hoặc DVI ==> bạn cần adapter
 */



interface ISocial
{
    public function post();
}

class Facebook implements ISocial
{
    public function post() {
        echo "Post to Facebook.\n";
    }
}

$fb = new Facebook();
$fb->post();

/** Bây giờ, nếu muốn post lên Twitter thì sao?
 * 
 *      - Create interface for Twitter
 *      - Create a new class implemention for this interface * 
 */

interface ITwitter
{
    public function tweet();
}

class Twitter implements ITwitter
{
    public function tweet()
    {
        echo "Post to Twitter.\n";
    }
}

echo PHP_EOL;
$tw = new Twitter();
$tw->tweet();

/**
 * Nhưng mà hiện tại, interface ISocial đang được sử dụng everywhere 
 *      -> muốn sử dụng được Twitter thì chúng ta phải làm cách nào 
 *         biến function tweet() thành function post() để có thể binding 
 *         trong ISocial.
 * 
 *      -> 2 ways:
 *          - Way 1: edit class Twitter, biến function tweet() thành post() rồi implement interface ISocial đúng như yêu cầu
 *              class Twitter implements ISocial
 *              {
 *                  public function post()
 *                  {
 *                      echo "Post to Twitter.\n";
 *                  }
 *              }
 * 
 *              –> Đây là cách đơn giản nhất, và thực tế nếu nó không sinh ra error thì quá tuyệt vời, bạn có thể sử dụng được 
 *                 luôn. Nhưng thực tế, code sẽ phức tạp hơn rất nhiều, và các method trong class sẽ phụ thuộc vào lẫn nhau nên 
 *                 việc thay đổi code có sẵn sẽ tiềm ẩn rất cao nguy cơ sinh ra lỗi. 
 *              -> Vậy làm cách nào vừa giữ nguyên code cũ, vừa ghép vào hệ thống được? 
 *                      -> use Adapter (way 2)
 * 
 *          - Way 2: use Adapter pattern
 *              + Tạo 1 adapter để liên kết 2 phần với nhau (ISocial & ITwitter)
 *                  -> implements ISocial (available/old)
 *                  -> inject ITwitter (new) into method __construct()
 *              + Execute/nest method tweet() of ITwitter within method post() of ISocial 
 *                (wrap method tweet() in method post())
 */
class TwitterAdapter implements ISocial
{
    protected $twitter;

    public function __construct(ITwitter $twitter)
    {
        $this->twitter = $twitter;
    }

    public function post()
    {
        $this->twitter->tweet();
    }
}

echo PHP_EOL;
$twa = new TwitterAdapter(new Twitter());
$twa->post();