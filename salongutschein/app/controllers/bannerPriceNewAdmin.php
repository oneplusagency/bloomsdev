<?php

// use PHPMailer\PHPMailer\Exception;
// use PHPMailer\PHPMailer\PHPMailer;

class bannerPriceNewAdmin extends Controller
{
    /**
     * @file: bannerPriceAdmin.php
     * @package:    e:\openserver7\OpenServer\domains\localhost\f3-url-shortener\app\controllers
     * @created:    Wed Mar 04 2020
     * @author:     oppo
     * @version:    1.0.0
     * @modified:   Wednesday March 4th 2020 12:56:24 pm
     * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
     */

    protected $configuration;
    /**
     * @var mixed
     */
    protected $banners;
    /**
     * @var mixed
     */
    protected $db;
    // protected $f3;
    // \Base $f3

    const SORT_BY_SORT = ['order' => 'sort SORT_ASC'];
    //title
    const SORT_BY_ID = ['order' => 'id SORT_DESC'];

    const FILE_NAME = 'price-new-banner.json';

    public function __construct()
    {
        parent::__construct();
        // $this->f3 = $f3;
        $this->db = new DB\Jig('app/data/', DB\Jig::FORMAT_JSON);
        // $this->configuration = new \DB\Jig\Mapper($this->db, 'sysconfig.json');
        $this->banners = new DB\Jig\Mapper($this->db, self::FILE_NAME);
    }

    /**
     * @return mixed
     */
    public function loadBysort()
    {
        $banners = $this->banners;
        // $banners->find();
        $out = array();
        // $banners->fields(array('sort')); // all fields, but not these
        $banners->load();

        if ($banners->dry()) {
            // Nothing found, redirect to main page

        } else {

            $records = $banners->find(null, self::SORT_BY_SORT);
            $fields  = $banners->fields();
            foreach ($records as $key => $value) {
                $temp       = array();
                $temp['id'] = $value['_id'];
                foreach ($fields as $field) {
                    if (isset($value[$field]) && $field != 'password') {
                        $temp[$field] = $value[$field];
                    }
                }
                $out[$temp['id']] = $temp;
            }
        }
        return $out;
    }

    /**
     * @return mixed
     */
    public function maxId()
    {

        // $gallery = $this->banners;
        // $gallery->reset();
        // $gallery->load()->find(null, ['order' => 'sort SORT_DESC']);
        // if ($gallery->dry()) {
        //     return 0;
        // } else {
        //     return $gallery['sort'];
        // }
        $sort = 0;
        $gal  = $this->loadBysort();
        if (!empty($gal) && count($gal)) {
            $sort = end($gal);
            $sort = $sort['sort'];
        }
    }


    public function category()
    {

        // $_POST["action"] = "fetch";
        //action.php
        if (isset($_POST["action"])) {



            $action = bloomArrayHelper::getValueJoom($_POST, 'action', null, 'STRING');
            $type   = bloomArrayHelper::getValueJoom($_POST, 'type', null, 'STRING');

            if ($action == "fetch") {

                $categories = $this->db->read(self::FILE_NAME);

                // var_dump($categories);
                // die;

                $output = '';

                if (count($categories) == 0) {
                    $output .= '<tr scope="row">';
                    $output .= "<td colspan='4' style='text-align:center'> No records found </td>";
                    $output .= '</tr>';
                } else {
                    foreach ($categories as $n => $_cat) {
                        // ++$n;

                        $output .= '<tr scope="row" class="table-info">';
                        $output .= "<td colspan='3' style='text-align:left'>  " . $_cat['name'] . " </td>";
                        $output .= "<td><a class='btn btn-danger btn-sm' onclick=\"deleteCategory('" . $_cat['_id'] . "')\" ><i class=\"fa fa-trash\" aria-hidden=\"true\"></i></a></td>";
                        $output .= '</tr>';

                        foreach ($_cat['items'] as $_item) {
                            $output .= '<tr scope="row">';
                            $output .= "<td></td>";
                            $output .= "<td> " . $_item['title'] . " </td>";
                            $output .= "<td> " . $_item['price'] . " <i class=\"fa fa-eur\" aria-hidden=\"true\"></i> </td>";
                            $output .= "<td><a class='btn btn-sm'  onclick=\"deleteItem('" . $_item['_id'] . "', '" . $_cat['_id'] . "')\" ><i style='color:red' class=\"fa fa-times-circle\" aria-hidden=\"true\"></i></a></td>";
                            $output .= '</tr>';
                        }
                    }
                }

                echo $output;
            } else if ($action == 'fetch_array') {
                $categories = $this->db->read(self::FILE_NAME);
                echo json_encode($categories);
            } else if ($action == "insert") {

                $name = $this->f3->get('POST.new_category_name');
                $addCategoryResult = $this->addCategory($name);

                echo json_encode($addCategoryResult);
            } else if ($action == "delete") {
                $categoryId = $this->f3->get('POST.cat_id');

                $deleteResult = $this->deleteCategory($categoryId);
                echo json_encode($deleteResult);
            }
        }
        exit;
    }


    public function item()
    {

        // $_POST["action"] = "fetch";
        //action.php
        if (isset($_POST["action"])) {

            $action = bloomArrayHelper::getValueJoom($_POST, 'action', null, 'STRING');
            $type   = bloomArrayHelper::getValueJoom($_POST, 'type', null, 'STRING');

            if ($action == "insert") {

                $title = $this->f3->get('POST.title');
                $price = $this->f3->get('POST.price');

                $categoryId = $this->f3->get('POST.category_id');
                $extraInfo = $this->f3->get('POST.extraInfo');


                $addCategoryResult = $this->addItem($categoryId, $title, $price, $extraInfo);

                echo json_encode($addCategoryResult);
            } else if ($action == "delete") {

                $itemId = $this->f3->get('POST.item_id');
                $categoryId = $this->f3->get('POST.cat_id');

                $deleteResult = $this->deleteItem($categoryId, $itemId);
                echo json_encode($deleteResult);
            }
        }
        exit;
    }

    /**
     * Check _id is already exists in records
     * Returns boolean
     */
    private function checkCategoryExistsById(string $_id)
    {

        $existingRecords = $this->db->read(self::FILE_NAME);

        // Check same category should not exists
        foreach ($existingRecords as $oneCategory) {
            if ($oneCategory['_id'] === $_id) return true;
        }

        return false;
    }

    /**
     * Check _id is already exists in records
     * Returns boolean
     */
    private function checkItemExistsByCatIdItemId(string $_catId, string $_id)
    {

        $existingRecords = $this->db->read(self::FILE_NAME);

        // Check same category should not exists
        foreach ($existingRecords as $oneCategory) {
            // Skip category which not belongs to
            if ($oneCategory['_id'] != $_catId) continue;

            foreach ($oneCategory['items'] as $item) {
                if ($item['_id'] === $_id) return true;
            }
        }

        return false;
    }

    /**
     * Add new category if it doesn't exists
     * Returns Array
     */
    private function addCategory(string $name)
    {

        $name = trim($name); // Remove unncessary spaces


        // Prepare result
        $result = [
            'success' => false,
            'category' => [],
            'error_msg' => '',
            'success_msg' => ''
        ];

        try {

            if ($name == '')  throw new Exception("Bitte geben Sie den Namen an");
            // Hash the name
            $_id = hash('sha256', $name);


            // Check same category should not exists
            if ($this->checkCategoryExistsById($_id)) throw new Exception('Doppelte Kategorie');

            // New category record
            $newCategory = [];
            $newCategory['_id'] = $_id;
            $newCategory['name'] = $name;
            $newCategory['description'] = '';
            $newCategory['items'] = [];

            $existingRecords = $this->db->read(self::FILE_NAME);

            // Add into existing records
            $existingRecords[] = $newCategory;

            // Write the file
            $res  = $this->db->write(self::FILE_NAME, $existingRecords);
            if ($res === false) throw new Exception("Kategorie konnte nicht hinzugefügt werden");

            $result['success'] = true;
            $result['success_msg'] = "$name hinzugefügt";
            $result['category'] = $newCategory;
        } catch (\Exception $e) {
            $result['success'] = false;
            $result['error_msg'] = $e->getMessage();
        }

        return $result;
    }


    private function deleteCategory(string $_catId)
    {

        // Prepare result
        $result = [
            'success' => false,
            'category' => [],
            'error_msg' => '',
            'success_msg' => ''
        ];

        try {

            if ($_catId == '')  throw new Exception("Ungültige Kategorie");

            if ($this->checkCategoryExistsById($_catId) === false) throw new Exception('Kategorie konnte nicht gefunden werden');

            $existingRecords = $this->db->read(self::FILE_NAME);

            foreach ($existingRecords as $key => $er) {
                if ($er['_id'] === $_catId) {
                    unset($existingRecords[$key]);
                }
            }


            // Write the file
            $res  = $this->db->write(self::FILE_NAME, $existingRecords);
            if ($res === false) throw new Exception("Kategorie konnte nicht entfernt werden");

            $result['success'] = true;
            $result['success_msg'] = "ENTFERNT";
        } catch (\Exception $e) {
            $result['success'] = false;
            $result['error_msg'] = $e->getMessage();
        }

        return $result;
    }
    /**
     * Add new item under category if it doesn't exists
     * Returns Array
     */
    private function addItem(string $_catId, string $_title, string $_price, $extraInfo = null)
    {
        $_title = trim($_title); // Remove unncessary spaces
        $_price = trim($_price);


        // Prepare result
        $result = [
            'success' => false,
            'category' => [],
            'error_msg' => '',
            'success_msg' => ''
        ];

        try {
            if ($_title == '')  throw ("Bitte Titel angeben");
            if ($_price == '')  throw ("Bitte Preis angeben");
            if ($_catId == '')  throw ("Bitte Kategorie auswählen");


            // Hash the name
            $_id = hash('sha256', $_title);

            // Check same item should not exists in one category
            if ($this->checkItemExistsByCatIdItemId($_catId, $_id)) throw new Exception('Artikel duplizieren');

            // New category record
            $newItem = [];
            $newItem['_id'] = $_id;
            $newItem['title'] = $_title;
            $newItem['description'] = '';
            $newItem['price'] = $_price;
            if ($extraInfo !== null) $newItem['extra_info'] = $extraInfo;

            $existingRecords = $this->db->read(self::FILE_NAME);

            foreach ($existingRecords as &$er) {
                if ($er['_id'] === $_catId) {
                    $er['items'][] = $newItem;
                }
            }

            // var_dump($existingRecords);
            // die;

            // Write the file
            $res  = $this->db->write(self::FILE_NAME, $existingRecords);
            if ($res === false) throw new Exception("Element konnte nicht hinzugefügt werden");

            $result['success'] = true;
            $result['success_msg'] = "$_title hinzugefügt";
            $result['item'] = $newItem;
        } catch (\Exception $e) {
            $result['success'] = false;
            $result['error_msg'] = $e->getMessage();
        }

        return $result;
    }


    private function deleteItem(string $_catId, string $_itemId)
    {

        // Prepare result
        $result = [
            'success' => false,
            'category' => [],
            'error_msg' => '',
            'success_msg' => ''
        ];

        try {

            if ($_catId == '')  throw ("Ungültige Kategorie");
            if ($_itemId == '')  throw ("Ungültiger Artikel");

            if ($this->checkItemExistsByCatIdItemId($_catId, $_itemId) === false) throw new Exception('Artikel konnte nicht gefunden werden');

            $existingRecords = $this->db->read(self::FILE_NAME);

            foreach ($existingRecords as &$er) {
                if ($er['_id'] === $_catId) {
                    foreach ($er['items'] as $key => &$item) {
                        if ($item['_id'] === $_itemId) {
                            unset($er['items'][$key]);
                            break;
                        }
                    }
                }
            }

            // Write the file
            $res  = $this->db->write(self::FILE_NAME, $existingRecords);
            if ($res === false) throw new Exception("Element konnte nicht entfernt werden");

            $result['success'] = true;
            $result['success_msg'] = "ENTFERNT";
        } catch (\Exception $e) {
            $result['success'] = false;
            $result['error_msg'] = $e->getMessage();
        }

        return $result;
    }
}
