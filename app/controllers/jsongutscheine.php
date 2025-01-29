<?php

class jsongutscheine extends Controller
{
    /**
     * @file: jsongutscheine.php
     * @package:    e:\openserver7\OpenServer\domains\localhost\f3-url-shortener\app\controllers
     * @created:    Tue Mar 10 2020
     * @author:     oppo
     * @version:    1.0.0
     * @modified:   Tuesday March 10th 2020 6:35:55 pm
     */

    protected $configuration;
    /**
     * @var mixed
     */
    protected $db;
    // protected $f3;
    // \Base $f3
    public function __construct()
    {
        parent::__construct();
        // $this->f3 = $f3;
        $this->db            = new DB\Jig('app/data/', DB\Jig::FORMAT_JSON);
        $this->configuration = new \DB\Jig\Mapper($this->db, 'sysconfig.json');
    }

    public function index()
    {
    }

    public function pdf()
    {
        if ($this->f3->exists('SESSION.paypal')) {
            $pdf_sess = $this->f3->get('SESSION.paypal');
        } else {
            // $this->f3->set('SESSION.flash', array());
            // $this->f3->push('SESSION.flash', array('type' => 'warning', 'msg' => 'Please wait for your account to be approved before submitting forms!'));
            $this->f3->set('SESSION.error', 'Data pdf existiert nicht');
            $this->f3->reroute('/gutscheine.html');
        }
        $mode = null;

        // $this->f3->route('GET|POST /@controller/@action/@id', '\@controller->@action');
        if ($this->f3->exists('PARAMS.id')) {
            $mode = (string) $this->f3->get('PARAMS.id');
            // $this->f3->set( 'name', $mode );
        }

        // https://blooms.developservice.de/modules/mod_terminfinder/tmpl/pdf.php?view=print&s=25&vn=Alex&nn=ONEPLUS&str=&plz=&ort=&mobil=&email=info@1plus-agency.com&d=2020-02-06&t=10:00%20AM&m=912&dl=2&v=false

        $pdf_image_dir = ONEPLUS_DIR_PATH . '/assets/images/pdf/';
        echo '<pre>';
        echo ('Dieser Artikel befindet sich im Entwicklungsmodus');
        echo '</pre>';
        // exit;
        echo '<pre>';
        var_export($pdf_sess);
        echo '</pre>';
        exit;

        // 'gutschein' 'rechnung'
        if ($mode) {
            // setlocale(LC_TIME, "de_DE");

            date_default_timezone_set('Europe/Berlin');
            $loc = setlocale(LC_ALL, 'de_DE@euro', 'de_DE', 'deu_deu');

            // user_data_tab_three
            $vertretung = bloomArrayHelper::getValueJoom($pdf_sess, 'vertretung', false, 'STRING');
            $mobilenumber = bloomArrayHelper::getValueJoom($pdf_sess, 'mobilenumber', null, 'STRING');
            $email        = bloomArrayHelper::getValueJoom($pdf_sess, 'email', null, 'STRING');
            $vorname      = bloomArrayHelper::getValueJoom($pdf_sess, 'vorname', null, 'STRING');
            $nachname     = bloomArrayHelper::getValueJoom($pdf_sess, 'nachname', null, 'STRING');
            $name         = $vorname . ' ' . $nachname;

            if (!class_exists('FPDF')) {
                require ONEPLUS_DIR_PATH . '/app/helper/fpdf/fpdf.php';
                require ONEPLUS_DIR_PATH . '/app/helper/fpdi/fpdi.php';
            }

            // PDF Erzeugen
            $pdfGS = new FPDI();

            if ($bezahlvorgang->design == '1') {
                $pdfGS->setSourceFile($pdf_image_dir . '/email_layout_1.pdf');
            } elseif ($bezahlvorgang->design == '3') {
                $pdfGS->setSourceFile($pdf_image_dir . '/email_layout_3.pdf');
            } else {
                $pdfGS->setSourceFile($pdf_image_dir . '/email_layout_2.pdf');
            }

            $tplidxGS = $pdfGS->importPage(1);

            $pdfGS->addPage();
            $pdfGS->useTemplate($tplidxGS);

            $pdfGS->SetFont('Arial', '', 8);
            $pdfGS->SetXY(53, 159);
            $pdfGS->MultiCell(100, 7, $bezahlvorgang->greetings, 0);

            define('EURO', chr(128));

            $pdfGS->SetFont('Arial', 'B', 18);
            $pdfGS->SetXY(95.5, 208);
            $pdfGS->Cell(0, 0, $bezahlvorgang->amount . ' ' . EURO);

            $pdfGS->SetFont('Arial', 'B', 18);
            $pdfGS->SetXY(91, 222.5);
            $pdfGS->Cell(0, 0, $rechnung->Gutscheincode);

            $gutscheinPDF = $pdfGS->Output('blooms_gutschein.pdf', 'S');

            $pdfGS->close();

            // $zeit            = helperblooms::uml( $zeit );

        }
        // $arr  = file_get_contents($page_host.$base'/helper/json/prices_allprices.json');
        //http://localhost/f3-url-shortener/helper/json/prices_allprices.json
    }
}
