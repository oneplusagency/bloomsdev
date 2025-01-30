<?php

// namespace Controllers;

class Admin extends Controller
{

    protected $per_page = 10;
    protected $admin;

    public static function is_admin()
    {
        return isset($_SESSION['admin_id']);
    }

    public static function can_admin_login($redir_url = 'admin/login')
    {
        if (!self::is_admin()) {

            die(header('Location: ' . \Base::instance()->BASE . '/' . $redir_url));
        }

        return true;
    }

    public function beforeRoute()
    {

        parent::beforeRoute();

        if (self::is_admin()) {
            // $this->admin = \Models\Admin::find($_SESSION['admin_id'])->first();
            $this->base->set('admin', $this->admin);
        }
    }

    // public function afterRoute()
    // {
    //     parent::afterRoute();
    //     echo \Template::instance()->render('backend.php');
    // }

    public function index()
    {
        self::can_admin_login();

        $this->base->mset([
            'title' => 'Admin Area',
            'content' => 'admin/main.php'
        ]);
    }

    public function login()
    {
        self::cannot_admin_login();

        if (isset($_POST['login'])) {

            $email = $_POST['email'];
            $password = $_POST['password'];

            $find = \Models\Admin::where('email', $email);
            $count = $find->count();

            if ($count > 0) {

                $user = $find->first();
                $passHash = $user->password;

                if ($this->base->get('phpass')->CheckPassword($password, $passHash)) {

                    $_SESSION['admin_id'] = $user->id;
                    die(self::cannot_admin_login());
                } else {
                    $this->base->set('error', 'Invalid email or password');
                }
            } else {
                $this->base->set('error', 'Invalid email or password');
            }
        }

        $this->base->mset([
            'title' => 'Admin Login',
            'content' => 'admin/login.php'
        ]);
    }

    public function categories()
    {
        self::can_admin_login();

        $count = \Models\Category::all()->count();
        $paginator = new \Kilte\Pagination\Pagination($count, $this->page, $this->per_page);
        $categories = \Models\Category::offset($paginator->offset())->limit($paginator->limit())->get();
        $pages = new \Illuminate\Support\Collection($paginator->build());

        $this->base->mset([
            'title' => 'Product Categories',
            'content' => 'admin/categories.php',
            'categories' => $categories,
            'pages' => $pages
        ]);
    }

    public function products($f3, $param)
    {

        self::can_admin_login();

        $categories = \Models\Category::all();

        if (isset($param['product_id'])) {

            $product = \Models\Product::find($param['product_id']);

            if (!$product) {
                $this->base->error(404);
            }

            if (isset($_POST['save'])) {

                $thumbs = $this->base->get('thumbs');
                $fileStorage = $this->base->get('fileStorage');
                $fileValidation = $this->base->get('fileValidation');

                $this->base->get('db')->transaction(function ($db) use ($thumbs, $product, $fileStorage, $fileValidation) {

                    $product->name = $_POST['name'];
                    $product->price = $_POST['price'];
                    $product->stock = $_POST['stock'];
                    $product->category_id = $_POST['category_id'];

                    for ($i = 1; $i <= 3; $i++) {

                        $imageKey = "image_" . $i;
                        $removeKey = "remove_" . $imageKey;
                        $thumbnailKey = $imageKey . '_thumb';

                        $uploader = new \Upload\File('image_' . $i, $fileStorage);
                        $uploader->addValidations($fileValidation);

                        if (isset($_POST[$removeKey])) {

                            $imagePath = realpath(__DIR__ . '/..') . '/assets/images/' . $product->$imageKey;
                            unlink($imagePath);
                            $product->$imageKey = null;

                            $thumbPath = realpath(__DIR__ . '/..') . '/assets/images/cache/' . $product->$thumbnailKey;;
                            unlink($thumbPath);
                            $product->$thumbnailKey = null;

                            continue;
                        } elseif ($_FILES[$imageKey]['name'] == "") {
                            continue;
                        }

                        $newname = implode('_', ['image', $product->id, $i]);
                        $uploader->setName($newname);

                        try {

                            $uploader->upload();
                            $product->$imageKey = $uploader->getNameWithExtension();

                            /** load image to cache **/
                            $thumbs->load($uploader->getNameWithExtension())->resize(200, 200, Simplify_Thumb::SCALE_TO_FIT, $forceAspectRatio = true)->cache();

                            /** get cache name **/
                            $fullCacheFileName = $thumbs->getCacheFilename();
                            $e = explode('/', $fullCacheFileName);
                            $cacheFileName = $e[1];

                            /** cache **/
                            $thumbs->save($fullCacheFileName);

                            $product->$thumbnailKey = $cacheFileName;
                        } catch (\Exception $e) {

                            $this->base->set('error', $e->getMessage());
                        }
                    }

                    $product->save();
                });
            }

            //$product = $product->first();

            $this->base->mset([
                'title' => 'Edit Product',
                'content' => 'admin/product.php',
                'product' => $product,
                'categories' => $categories
            ]);
        } else {

            $this->base->mset([
                'title' => 'Products',
                'content' => 'admin/products.php',
                'products' => \Models\Product::all()
            ]);
        }
    }

    public function addProduct()
    {
        self::can_admin_login();

        $categories = \Models\Category::all();

        if (isset($_POST['add'])) {

            //dd($_POST, $_FILES);

            $rules = [
                'name' => 'required|max_len,50',
                'price' => 'required|max_len,10|numeric',
                'stock' => 'required|max_len,10|numeric',
                'description' => 'required'

            ];
            $gump = new \GUMP;
            $gump->validation_rules($rules);
            $run = $gump->run($_POST);

            $product_id = '';

            if ($run) {

                $thumbs = $this->base->get('thumbs');
                $fileStorage = $this->base->get('fileStorage');
                $fileValidation = $this->base->get('fileValidation');
                $product = new \Models\Product;

                $this->base->get('db')->transaction(function ($db) use ($product, $thumbs, $fileStorage, $fileValidation) {

                    $product->name = $_POST['name'];
                    $product->price = $_POST['price'];
                    $product->stock = $_POST['stock'];
                    $product->description = $_POST['description'];
                    $product->category_id = $_POST['category_id'];

                    $product->save();

                    $product_id = $product->id;

                    for ($i = 1; $i <= 3; $i++) {

                        $imageKey = "image_" . $i;
                        $thumbnailKey = $imageKey . "_thumb";

                        if ($_FILES[$imageKey]['name'] == '') {
                            continue;
                        }

                        $uploader = new \Upload\File($imageKey, $fileStorage);
                        $uploader->addValidations($fileValidation);
                        $newname = implode('_', ['image', $product_id, $i]);
                        $uploader->setName($newname);

                        try {

                            $uploader->upload();
                            $product->$imageKey = $newname;

                            /** load image to cache **/
                            $thumbs->load($uploader->getNameWithExtension())->resize(200, 200, \Simplify\Thumb::SCALE_TO_FIT, $forceAspectRatio = true)->cache();

                            /** get cache name **/
                            $fullCacheFileName = $thumbs->getCacheFilename();
                            $e = explode('/', $fullCacheFileName);
                            $cacheFileName = $e[1];

                            /** cache **/
                            $thumbs->save($fullCacheFileName);

                            /** update thumb name **/
                            $product->$thumbnailKey = $cacheFileName;
                        } catch (Exception $e) {

                            $this->base->set('error', $e->getMessage());
                        }
                    }

                    /** save row **/
                    $product->save();
                });

                die(header('Location: ' . $this->base->get('BASE') . '/admin/products/' . $product_id)); //error lol

            } else {

                $this->base->set('error', $gump->get_readable_errors());
            }
        }

        $this->base->mset([
            'title' => 'Add Product',
            'content' => 'admin/addproduct.php',
            'categories' => $categories
        ]);
    }

    public function invoices($f3, $param)
    {
        self::can_admin_login();

        if (isset($param['id'])) {

            $invoice = \Models\Invoice::find($param['id']);

            if (!$invoice) {
                $this->base->error(404);
            }

            $this->base->mset([
                'title' => 'Invoice #' . $invoice->id,
                'content' => 'admin/invoice.php',
                'invoice' => $invoice
            ]);
        } else {

            $this->base->mset([
                'title' => 'Invoices',
                'content' => 'admin/invoices.php',
                'invoices' => \Models\Invoice::all()
            ]);
        }
    }

    public function orders($f3, $param)
    {

        $this->base->mset([
            'title' => 'Orders',
            'content' => 'admin/orders.php',
            'orders' => \Models\Order::all()
        ]);
    }

    public function customers($f3, $param)
    {

        if (isset($param['customer_id'])) {

            $customer = \Models\Customer::find($param['customer_id']);

            if (!$customer) {
                $this->base->error(404);
            }

            $this->base->mset([
                'title' => 'Customer Profile',
                'content' => 'admin/customer.php',
                'customer' => $customer
            ]);
        } else {

            $this->base->mset([
                'title' => 'Customers',
                'content' => 'admin/customers.php',
                'customers' => \Models\Customer::all()
            ]);
        }
    }
}
