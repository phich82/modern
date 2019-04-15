<?php

/**
 * Về nguyên lý, Repository cũng giống interface, cần phải chia nhỏ component và làm chúng loosely couple với nhau.
 * 
 * Bạn hãy tưởng tượng 1 request lên server thường sẽ qua những công đoạn xử lý nào?
 *      Request -> check authen -> check author -> validation -> process raw request data -> update database -> render view -> Response
 * 
 * Lí tưởng nhất là bạn coi từng phần là component riêng và sử dụng interface vào để linh hoạt hóa các component đó.
 * 
 * Với phần update database, chúng ta sẽ sử dụng repository để làm.
 * 
 * 
 */

 /** Code không repository (giả sử chúng ta có Eloquent Model Book) */
// class BookController extends BaseController
// {
//     public function index()
//     {
//         $books = Book::paginate(10);
//         return view('books.inde');
//     }

//     public function store(Request $request)
//     {
//         Book::insert($request->all());
//         return redirect()->route('books.index');
//     }
// }

/**
 *  - Cách code này có vấn đề gì không? Xin thưa với bạn rằng nó chẳng có vấn đề gì cả, 
 *    chỉ là nó không linh hoạt mà thôi -> bạn đã gắn chặt controller với eloquent model 
 *    mà thôi -> nếu bạn muốn sử dụng ORM nào khác thì bạn phải thay đổi lại tất cả những 
 *    chỗ sử dụng Eloquent mà thôi.
 * 
 *  - Sử dụng repository thì làm thế nào?
 *      + Tạo interface
 *      + Tạo class implement interface đó
 *      + Inject inteface vào client (ở ví dụ này client là controller)
 *      + Bindding trong service provider
 */

/** create inteface */
interface IBookRepository
{
    public function paginate($quantity);
    public function persist($data);
}

class FakeBook
{
    public function paginate(int $page)
    {
        echo "Records from paginating.\n";
    }

    public function insert($data)
    {
        echo "New record inserted.\n";
    }
}

class FakeRequest
{
    public function all()
    {
        return [];
    }
}

class FakeCache
{
    public function tags(string $table)
    {
        return $this;
    }

    public function rememberForever(string $name, callable $callable)
    {
        return $callable();
    }
}

/** implemention for this interface */
class BookRepository implements IBookRepository
{
    protected $model;

    public function __construct(FakeBook $model)
    {
        $this->model = $model;
    }
    public function paginate($page = 1)
    {
        return $this->model->paginate($page = 1);
    }

    public function persist($data)
    {
        return $this->model->insert($data);
    }
}

/**
 * Chúng ta inject eloquent model Book vào trong construct và sử dụng nó ở trong class chứ không 
 * sử dụng static function như ví dụ trên -> nếu bạn sử dụng ORM nào khác – không phải eloquent 
 * – không có các method paginate() chẳng hạn, bạn có thể sử dụng Adapter để kết nối trong trường 
 * hợp này.
 * 
 * Inject interface vào client (controller)
 */
class BookController
{
    protected $repository;

    public function __construct(IBookRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $books = $this->repository->paginate(10);
        echo "Open index page.\n";
    }

    public function store(FakeRequest $request)
    {
        $this->repository->persist($request->all());
        echo "Redirect to index page.\n";
    }
}

//$controller = new BookController(new BookRepository(new FakeBook));
//$controller->index();
//$controller->store(new FakeRequest);
//echo PHP_EOL;

/**
 * Luôn luôn inject interface vào trong client (controller), 
 * khi đó bạn mới linh hoạt bằng cách bindding trong service provider.
 * 
 * Tác dụng lớn nhất của repository là bạn không phụ thuộc vào 1 biến thể tương tác với database này cả 
 *      -> ví dụ như bạn hoàn toàn không phụ thuộc vào Eloquent mà có thể sử dụng ORM khác (Doctrine ORM)
 * 
 * Ngoài ra, tùy sự sáng tạo của bạn mà bạn có thể làm được nhiều việc khác với repository, ví dụ như viết 
 * 1 lớp cache layer cho repository với decorator. 
 */

class BookCacheRepository implements IBookRepository
{
    protected $repository;
    protected $cache;
 
    public function __construct(IBookRepository $repository, FakeCache $cache)
    {
        $this->repository = $repository;
        $this->cache = $cache;
    }

    public function paginate($page = 1)
    {
        return $this->cache->tags('books')->rememberForever('books.paginate', function() use ($page) {
            return $this->repository->paginate($page);
        });
    }
 
    public function persist($data)
    {
        return $this->repository->persist($data);
    }
}

$book_cache_repo = new BookCacheRepository(new BookRepository(new FakeBook), new FakeCache); 
$controller_cache = new BookController($book_cache_repo);

echo "Index:\n";
$controller_cache->index();
echo PHP_EOL;

echo "Store:\n";
$controller_cache->store(new FakeRequest);