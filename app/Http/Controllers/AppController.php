<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Hash;
use Illuminate\Support\Str;
use App\Helpers\Helper;
use App\User;
use Exception;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\BlockUserLog;
use Carbon\Carbon;
use App\Traits\MSGWhatsappTrait;
use App\Traits\MSGSMSTrait;
use Browser;
use Illuminate\Support\Facades\Config;
use PDF;
use App\Traits\S3ConfigTrait;
use App\Rules\Recaptcha;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use App\ContactUs;
class AppController extends Controller
{
    
    use ThrottlesLogins;
    protected $maxAttempts = 6; // Default is 5
    protected $decayMinutes = 6; // Default is 1
    /**
    * Create a new controller instance.
    *
    * @return void
    */
    public function __construct()
    {
        $this->middleware('throttle:60,1');
    }

    public function testApi(Request $request)
    {
        $api_check_status = false;
        // Setup request to send json via POST
        $data = array(
            "candidates_id" =>["1060","1059"],
            "reference_number"=> "BCD-0000000098"
        );
        $payload = json_encode($data);
        $apiURL = "http://bcd.local/api/client/reports/download";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        $authorization = "Authorization: Bearer ".env('SUREPASS_PRODUCTION_TOKEN');
        //$authorization = "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"; // Prepare the authorisation token
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
        curl_setopt($ch, CURLOPT_URL, $apiURL);
        // Attach encoded JSON string to the POST fields
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        $resp = curl_exec ( $ch );
        curl_close ( $ch );
        return $resp;
        // $array_data =  json_decode($resp,true);

    }

    public function demoPDFReport()
    {
        // dd(Crypt::encryptString('22'));

        $path = public_path().'/uploads/demo/';

        $file_name = 'G2.pdf';

        $temp_file_name = time().'.pdf';

        // if(File::exists($path))
        // {
        //     File::cleanDirectory($path);
        // }

         $pdf=PDF::loadView('demopdfreport')->save($path.$file_name);

        //  $pdf->getMpdf()->SetCompression(false);

        //$output = shell_exec('gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE -dPDFSETTINGS=/screen -dQUIET -dBATCH -sOutputFile='.$path.$temp_file_name.' '.$path.$file_name.'');

        //$output =  shell_exec('pdftk '.$path.$file_name.' output '.$path.$temp_file_name.' compress');

        //$output =  shell_exec('sudo ps2pdf -dPDFSETTINGS=/screen /var/www/html/bcd/public/uploads/demo/Grow_Some_Dream.pdf /var/www/html/bcd/public/uploads/demo/test.pdf');

        //$output =  shell_exec('shrink '.$path.$file_name.' '.$path.$temp_file_name.' -compressed');

        // dd($output);

        //return response()->download($path.$temp_file_name);

        return $pdf->stream('document.pdf');

        // return view('demopdfreport');

        //dd(File::name($path.$file_name));

        //dd(number_format(File::size($path.$file_name) / 1048576, 2));

        // $file_size = number_format(File::size($path.$file_name) / 1048576, 2);

        // if($file_size > 3)
        // {
        //     $output = shell_exec('gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE -dPDFSETTINGS=/ebook -dQUIET -dBATCH -sOutputFile='.$path.$temp_file_name.' '.$path.$file_name.'');
        // }
    }

    public function panPDFReport()
    {
        $pdf=PDF::loadView('panpdfreport');

        return $pdf->stream('document.pdf');
    }

    public function voterPDFReport()
    {
        $pdf=PDF::loadView('voterpdfreport');

        return $pdf->stream('document.pdf');
    }

    public function rcPDFReport()
    {
        $pdf=PDF::loadView('rcpdfreport');

        return $pdf->stream('document.pdf');
    }

    public function passportPDFReport()
    {
        $pdf=PDF::loadView('passportpdfreport');

        return $pdf->stream('document.pdf');
    }

    public function dlPDFReport()
    {
        $pdf=PDF::loadView('dlpdfreport');

        return $pdf->stream('document.pdf');
    }

    public function bankPDFReport()
    {
        $pdf=PDF::loadView('bankpdfreport');

        return $pdf->stream('document.pdf');
    }

    public function eCourtPDFReport()
    {
        $response = '{
            "reports": [
                {
                    "address": "VILL AMAHI MISHRA PS BHORE DIST GOPALGANJ",
                    "address_district": "gopalganj",
                    "address_pincode": "",
                    "address_state": "bihar",
                    "address_street": "amahi mishra bhore",
                    "address_taluka": "gopalganj",
                    "address_wc": 7,
                    "business_category": "Serious",
                    "case_category": "criminal",
                    "case_code": "214200031382014",
                    "case_decision_date": "---",
                    "case_no": "214200031382014",
                    "case_no_year": "---",
                    "case_status": "",
                    "case_type": "Cr. Case Complaint (P)",
                    "case_type_descriptions": [
                        {
                            "act": "ipc",
                            "description": "Whoever, except in the case provided for by section 334, voluntarily causes hurt, shall be punished with imprisonment of either description for a term which may extend to one year, or with fine which may extend to one thousand rupees, or with both.",
                            "id": "5cac9c58ae41ac02292058da",
                            "section": "323",
                            "title": "Punishment for voluntarily causing hurt"
                        },
                        {
                            "act": "ipc",
                            "description": "Whoever, except in the case provided for by section 334, voluntarily causes hurt by means of any instrument for shooting, stabbing or cutting, or any instrument which, used as a weapon of offence, is likely to cause death, or by means of fire or any heated substance, or by means of any poison or any corrosive substance, or by means of any explosive substance or by means of any substance which it is deleterious to the human body to inhale, to swallow, or to receive into the blood, or by means of any animal, shall be punished with imprisonment of either description for a term which may extend to three years, or with fine, or with both.",
                            "id": "5cac9c59ae41ac02292058db",
                            "section": "324",
                            "title": "Voluntarily causing hurt by dangerous weapons or means"
                        },
                        {
                            "act": "ipc",
                            "description": "Whoever commits theft shall be punished with imprisonment of either description for a term which may extend to three years, or with fine, or with both.",
                            "id": "5cac9ca5ae41ac0229205925",
                            "section": "379",
                            "title": "Punishment for theft"
                        },
                        {
                            "act": "ipc",
                            "description": "When a criminal act is done by several persons in furtherance of the common intention of all, each of such persons is liable for that act in the same manner as if it were done by him alone.",
                            "id": "5cac9b0aae41ac0229205794",
                            "section": "34",
                            "title": "Acts done by several persons in furtherance of common intention -"
                        }
                    ],
                    "case_year": "2014",
                    "cnr": "BRGO020017562008",
                    "court_code": 3,
                    "court_name": "CJM Division",
                    "court_no_judge": "35 - J.M 1st class Cum Addl. Munsif-X",
                    "court_no_name": "---",
                    "data_category": "imprisonment fine The_Indian_Penal_Code__1860 323 324 379 34",
                    "decision_date": "",
                    "dist_code": 23,
                    "dist_name": "Gopalganj",
                    "filing_no": "29939/2014     Filing date: 12-11-2008",
                    "fine": "No fine",
                    "fir_no": "",
                    "first_hearing_date": "09-05-2014",
                    "global_category": "ability ipap police",
                    "id": "43dde642747cafd8917e9bf8624dac0a",
                    "imprisonment": "No imprisonment",
                    "link": "https://pdf-reports-springrole.s3.amazonaws.com/governmentReport/43dde642747cafd8917e9bf8624dac0a",
                    "md5": "f8e0319c3547f7c145c13dba5e026399",
                    "model_score": -2.4033468,
                    "name": "THE STATE OF BIHAR SHYAMLAL SAH",
                    "name_wc": 6,
                    "nature_of_disposal": "",
                    "next_hearing_date": "11-12-2014",
                    "oparty": "JAGESHAR SAH, PRABHAWATI DEVI",
                    "order_summary": "The case status is unknown",
                    "police_station": "",
                    "purpose_of_hearing": "Appearence",
                    "raw_address": "VILL AMAHI MISHRA\r\nPS BHORE\r\nDIST GOPALGANJ",
                    "registration_no": "3138/2014     Registration date: 12-11-2008",
                    "score": 82.95,
                    "source": "ecourt",
                    "state_code": 8,
                    "state_name": "Bihar",
                    "subject": "",
                    "type": 0,
                    "under_acts": "Indian Penal Code",
                    "under_sections": "323 324 379 34",
                    "uniq_case_id": "43dde642747cafd8917e9bf8624dac0a",
                    "year": "0"
                },
                {
                    "address": "",
                    "address_wc": 0,
                    "bench_type": "Single Bench",
                    "case_no": "200600405802019",
                    "case_no2": 40580,
                    "case_status": "CASE DISPOSED",
                    "case_type": 6,
                    "case_type_descriptions": [
                        {
                            "act": "ipc",
                            "description": "Whoever adulterates any article of food or drink, so as to make such article noxious as food or drink, intending to sell such article as food or drink, or knowing it to be likely that the same will be sold as food or drink, shall be punished with imprisonment of either description for a term which may extend to six months, or with fine which may extend to one thousand rupees, or with both.",
                            "id": "5cac9c22ae41ac02292058a3",
                            "section": "272",
                            "title": "Adulteration of food or drink intended for sale"
                        },
                        {
                            "act": "ipc",
                            "description": "Whoever sells, or offers or exposes for sale, as food or drink, any article which has been rendered or has become noxious, or is in a state unfit for food or drink, knowing or having reason to believe that the same is noxious as food or drink, shall be punished with imprisonment of either description for a term which may extend to six months, or with fine which may extend to one thousand rupees, or with both.",
                            "id": "5cac9c23ae41ac02292058a4",
                            "section": "273",
                            "title": "Sale of noxious food or drink"
                        }
                    ],
                    "case_year": "2019",
                    "cnr": "BRHC010611922019",
                    "coram": "1285-Mr. Justice Birendra Kumar",
                    "court": "ecourt high court",
                    "court_code": "1",
                    "court_complex_code": "1",
                    "court_name": "Principal Bench Patna",
                    "date_of_decision": "2019-07-02",
                    "decision_date": "02nd July 2019",
                    "dist_name": "",
                    "f": "Disposed",
                    "filing_date": "20-06-2019",
                    "filing_no": "CR. MISC. /45529/2019",
                    "fir_no": "86",
                    "fir_year": "2019",
                    "first_hearing_date": "02nd July 2019",
                    "hide_pet_name": "N",
                    "hide_res_name": "N",
                    "id": "2dd1b1a974795c9c12dc7cc6bd4f408f",
                    "judicial_branch": "Judicial Section",
                    "link": "https://pdf-reports-springrole.s3.amazonaws.com/governmentReport/2dd1b1a974795c9c12dc7cc6bd4f408f",
                    "lpet_name": "",
                    "lres_name": "",
                    "md5": "85715f2f2588a92c42dcf40339ce681f",
                    "model_score": -2.9195173,
                    "name": "MITHILESH SAH @ MITHLESH SAH",
                    "name_wc": 5,
                    "nature_of_disposal": "Contested--BAIL GRANTED",
                    "oparty": "The State of Bihar",
                    "order_summary": "The case status is disposed",
                    "pet_name": "MITHILESH SAH @ MITHLESH SAH",
                    "police_station": "RIGA",
                    "ref_md5": "85715f2f2588a92c42dcf40339ce681f",
                    "registration_date": "01-07-2019",
                    "registration_no": "CR. MISC. /40580/2019",
                    "res_name": "The State of Bihar",
                    "score": 80.46,
                    "source": "high court",
                    "state_code": "8",
                    "state_name": "Bihar",
                    "sub_type": "",
                    "time_stamp": "2020-05-01T12:09:04.100201",
                    "type": 0,
                    "type_name": "CR. MISC.",
                    "under_acts": "INDIAN PENAL CODE",
                    "under_sections": "272,273,",
                    "uniq_case_id": "2dd1b1a974795c9c12dc7cc6bd4f408f"
                },
                {
                    "address": "",
                    "address_wc": 0,
                    "business_category": "Serious",
                    "case_category": "criminal",
                    "case_code": "218200018882019",
                    "case_decision_date": "",
                    "case_no": "218200018882019",
                    "case_no_year": "",
                    "case_status": "Pending",
                    "case_type": " G.r.",
                    "case_type_descriptions": [
                        {
                            "act": "ipc",
                            "description": "\t\n\t\tWhoever kidnaps any minor or, not being the lawful guardian of a minor, obtains the custody of the minor, in order that such minor may be employed or used for the purposes of begging shall be punishable with imprisonment of either description for a term which may extend to ten years, and shall also be liable to fine.\n\t\tWhoever maims any minor in order that such minor may be employed or used for the purposes of begging shall be punishable with imprisonment for life, and shall also be liable to fine.\n\t\tWhere any person, not being the lawful guardian of a minor, employs or uses such minor for the purposes of begging, it shall be presumed, unless the contrary is proved, that he kidnapped or otherwise obtained the custody of that minor in order that the minor might be employed or used for the purposes of begging.\n\t\tIn this section\n\t\t\n\t\t\t“begging” means:\n\t\t\t\n\t\t\t\tsoliciting or receiving alms in a public place, whether under the pretence of singing, dancing, fortunetelling, performing tricks or selling articles or otherwise;\n\t\t\t\tentering on any private premises for the purpose of soliciting or receiving alms;\n\t\t\t\texposing or exhibiting, with the object of obtaining or extorting alms, any sore, wound, injury, deformity or disease, whether of himself or of any other person or of an animal;\n\t\t\t\tusing a minor as an exhibit for the purpose of soliciting or receiving alms;\n\t\t\t\n\t\t\t“minor” means:\n\t\t\t\n\t\t\t\tin the case of a male, a person under sixteen years of age; and\n\t\t\t\tin the case of a female, a person under eighteen years of age.\n\t\t\t\n\t\t\t\n\t\t\t",
                            "id": "5cac9c87ae41ac0229205908",
                            "section": "363",
                            "title": "Punishment for kidnapping"
                        },
                        {
                            "act": "ipc",
                            "description": "Whoever kidnaps or abducts any person with intent to cause that person to be secretly and wrongfully confined, shall be punished with imprisonment of either description for a term which may extend to seven years, and shall also be liable to fine.",
                            "id": "5cac9c8bae41ac022920590c",
                            "section": "365",
                            "title": "Kidnapping or abducting with intent secretly and wrongfully to confine person"
                        }
                    ],
                    "case_year": "2019",
                    "cnr": "BRKT020028042019",
                    "court_code": 4,
                    "court_name": "CJM Div. Katihar",
                    "court_no_judge": " 44-Sub-Judge VI",
                    "court_no_name": "",
                    "data_category": "imprisonment fine The_Indian_Penal_Code__1860 363 365",
                    "decision_date": "",
                    "dist_code": 5,
                    "dist_name": "Katihar",
                    "fhd": "2019-05-04",
                    "filing_no": " 2800/2019    04-05-2019",
                    "fine": "No fine",
                    "fir_no": " 138",
                    "first_hearing_date": " 25th July 2019",
                    "global_category": "ability ipap intent police womensafety",
                    "id": "fb599ba394966e68c82c7a6a17d30f01",
                    "imprisonment": "No imprisonment",
                    "link": "https://pdf-reports-springrole.s3.amazonaws.com/governmentReport/fb599ba394966e68c82c7a6a17d30f01",
                    "md5": "0e368334f2b4cf7639c5eb200cd945ee_53c784040433043cfe9d6c90b3716f2c",
                    "model_score": -3.2838328,
                    "name": "MITHLESH SAH AND OTHERS",
                    "name_wc": 4,
                    "nature_of_disposal": "",
                    "next_hearing_date": " 25th July 2019",
                    "oparty": "State of Bihar",
                    "order_summary": "The case status is pending",
                    "police_station": " BARARI",
                    "purpose_of_hearing": " Final Form",
                    "raw_address": "",
                    "registration_no": " 1888/2019     04-05-2019",
                    "score": 78.62,
                    "source": "ecourt",
                    "state_code": 8,
                    "state_name": "Bihar",
                    "subject": "",
                    "time_stamp": "2019-05-07T00:00:00Z",
                    "type": 1,
                    "under_acts": "Indian Penal Code",
                    "under_sections": "363,365",
                    "uniq_case_id": "fb599ba394966e68c82c7a6a17d30f01",
                    "year": " 2019"
                },
                {
                    "address": "",
                    "address_wc": 0,
                    "business_category": "Tax related",
                    "case_category": "criminal",
                    "case_code": "214900002042018",
                    "case_decision_date": "",
                    "case_no": "214900002042018",
                    "case_no_year": "",
                    "case_status": "",
                    "case_type": "Excise Act",
                    "case_year": "2018",
                    "cnr": "BRMP010019562018",
                    "court_code": 2,
                    "court_name": "DJ Div. Madhepura",
                    "court_no_judge": "4-ADJ-II",
                    "court_no_name": "",
                    "data_category": " The_Central_Excises_and_Salt_and_Additional_Duties_of_Excise__Amendment__Act__1980 The_Mines__Amendment__Act__1983 30 30_A_",
                    "decision_date": "",
                    "dist_code": 12,
                    "dist_name": "MADHEPURA",
                    "filing_no": "1591/2018      12-06-2018  ",
                    "fir_no": "109",
                    "first_hearing_date": "18th August 2018",
                    "global_category": "police",
                    "id": "0bdbfc86eb14000062762639c99713b7",
                    "link": "https://pdf-reports-springrole.s3.amazonaws.com/governmentReport/0bdbfc86eb14000062762639c99713b7",
                    "md5": "832298978f808b63f75b515424761a59_5ed4fe8348a185ae9e8e9f138ac7beac",
                    "model_score": -3.2838328,
                    "name": "Mithlesh Sah and anothers",
                    "name_wc": 4,
                    "nature_of_disposal": "",
                    "next_hearing_date": "14th February 2019",
                    "oparty": "Hari Shankar Urao",
                    "order_summary": "The case status is unknown",
                    "police_station": "KUMAR KHAND",
                    "purpose_of_hearing": "AWAITED FOR FINAL FORM/ CHARGES",
                    "raw_address": "",
                    "registration_no": "204/2018    12-06-2018",
                    "score": 78.62,
                    "source": "ecourt",
                    "state_code": 8,
                    "state_name": "BIHAR",
                    "subject": "",
                    "time_stamp": "2019-01-07T00:00:00Z",
                    "type": 1,
                    "under_acts": "Excise (Amendment )Act",
                    "under_sections": "30(a)",
                    "uniq_case_id": "0bdbfc86eb14000062762639c99713b7",
                    "year": "2018"
                },
                {
                    "address": "",
                    "address_district": "",
                    "address_pincode": "",
                    "address_state": "",
                    "address_street": "",
                    "address_taluka": "",
                    "address_wc": 0,
                    "business_category": "",
                    "case_category": "civil",
                    "case_code": "214200003242018",
                    "case_decision_date": "",
                    "case_no": "214200003242018",
                    "case_no_year": "",
                    "case_status": "Pending",
                    "case_type": "Pending",
                    "case_year": "2018",
                    "cnr": "BRKH020015132018",
                    "court_code": 3,
                    "court_name": "",
                    "court_no_judge": "13-J.M.I-st Class-II",
                    "court_no_name": "",
                    "data_category": " The_National_Trust_for_Welfare_of_Persons_with_Autism__Cerebral_Palsy__Mental_Retardation_and_Multiple_Disabilities_Act__1999 ",
                    "decision_date": "",
                    "dist_code": 14,
                    "dist_name": "Khagaria",
                    "filing_no": "350/2018   25-04-2018  ",
                    "fir_no": "",
                    "first_hearing_date": "26th April 2018",
                    "global_category": "",
                    "id": "8d9c81a2a35b58b060b96659097ff0a9",
                    "link": "https://pdf-reports-springrole.s3.amazonaws.com/governmentReport/8d9c81a2a35b58b060b96659097ff0a9",
                    "md5": "208da6c5dfd557381668111cbd9f4c83",
                    "model_score": -3.2838328,
                    "name": "Mithlesh Sah and others",
                    "name_wc": 4,
                    "nature_of_disposal": "",
                    "next_hearing_date": "29th June 2018",
                    "oparty": "Prabhu Sah",
                    "order_summary": "The case status is pending",
                    "police_station": "",
                    "purpose_of_hearing": "",
                    "raw_address": "",
                    "registration_no": "324/2018 25-04-2018",
                    "score": 78.62,
                    "source": "ecourt",
                    "state_code": 8,
                    "state_name": "Bihar",
                    "subject": "",
                    "type": 1,
                    "under_acts": "Witch Act",
                    "under_sections": "3,4",
                    "uniq_case_id": "8d9c81a2a35b58b060b96659097ff0a9",
                    "year": "2018"
                },
                {
                    "address": "",
                    "address_wc": 0,
                    "business_category": "Serious",
                    "case_category": "criminal",
                    "case_code": "216400065402016",
                    "case_decision_date": "",
                    "case_no": "216400065402016",
                    "case_no_year": "",
                    "case_status": "Pending",
                    "case_type": " G.R.",
                    "case_type_descriptions": [
                        {
                            "act": "ipc",
                            "description": "Whoever is guilty of rioting, shall be punished with imprisonment of either description for a term which may extend to two years, or with fine, or with both.",
                            "id": "5cac9b84ae41ac022920580e",
                            "section": "147",
                            "title": "Punishment for rioting"
                        },
                        {
                            "act": "ipc",
                            "description": "If an offence is committed by any member of an unlawful assembly in prosecution of the common object of that assembly, or such as the members of that assembly knew to be likely to be committed in prosecution of that object, every person who, at the time of the committing of that offence, is a member of the same assembly, is guilty of that offence.",
                            "id": "5cac9b86ae41ac0229205810",
                            "section": "149",
                            "title": "Every member of unlawful assembly guilty of offence committed in prosecution of common object"
                        },
                        {
                            "act": "ipc",
                            "description": "Whoever wrongfully restrains any person shall be punished with simple imprisonment for a term which may extend to one month, or with fine which may extend to five hundred rupees, or with both.",
                            "id": "5cac9c6cae41ac02292058ee",
                            "section": "341",
                            "title": "Punishment for wrongful restraint"
                        },
                        {
                            "act": "ipc",
                            "description": "Whoever, except in the case provided for by section 334, voluntarily causes hurt, shall be punished with imprisonment of either description for a term which may extend to one year, or with fine which may extend to one thousand rupees, or with both.",
                            "id": "5cac9c58ae41ac02292058da",
                            "section": "323",
                            "title": "Punishment for voluntarily causing hurt"
                        },
                        {
                            "act": "ipc",
                            "description": "Whoever does any act with such intention or knowledge and under such circumstances that, if he by that act caused death, he would be guilty of culpable homicide not amounting to murder, shall be punished with imprisonment of either description for a term which may extend to three years, or with fine, or with both; and, if hurt is caused to any person by such act, shall be punished with imprisonment of either description for a term which may extend to seven years, or with fine, or with both.",
                            "id": "5cac9c49ae41ac02292058cb",
                            "section": "308",
                            "title": "Attempt to commit culpable homicide"
                        },
                        {
                            "act": "ipc",
                            "description": "Whoever intentionally insults, and thereby gives provocation to any person, intending or knowing it to be likely that such provocation will cause him to break the public peace, or to commit any other offence, shall be punished with imprisonment of either description for a term which may extend to two years, or with fine, or with both.",
                            "id": "5cac9d2aae41ac02292059a9",
                            "section": "504",
                            "title": "Intentional insult with intent to provoke breach of the peace"
                        }
                    ],
                    "case_year": "2016",
                    "cnr": "BRMG020040772016",
                    "court_code": 3,
                    "court_name": "CJM Div. Munger",
                    "court_no_judge": " 24-ACJM 4th",
                    "court_no_name": "",
                    "data_category": "imprisonment fine The_Indian_Penal_Code__1860 147 149 341 323 308 504",
                    "decision_date": "",
                    "dist_code": 11,
                    "dist_name": "MUNGER",
                    "filing_no": " 6540/2016    14-04-2016",
                    "fine": "No fine",
                    "fir_no": " 14",
                    "first_hearing_date": " 02nd February 2019",
                    "global_category": "ability ipap police",
                    "id": "732af6bbd24694f41797cff07218d843",
                    "imprisonment": "No imprisonment",
                    "link": "https://pdf-reports-springrole.s3.amazonaws.com/governmentReport/732af6bbd24694f41797cff07218d843",
                    "md5": "82f4b9920841a29f8301ac9f5137cfcc_659ab8193b67d311960a4a43a7134592",
                    "model_score": -3.2838328,
                    "name": "MITHLESH SAH AND OTHERS",
                    "name_wc": 4,
                    "nature_of_disposal": "",
                    "next_hearing_date": " 02nd February 2019",
                    "oparty": "DINESH KUMAR MANDAL",
                    "order_summary": "The case status is pending",
                    "police_station": " Gangta PS",
                    "purpose_of_hearing": " Appearence",
                    "raw_address": "",
                    "registration_no": " 6540/2016     14-04-2016",
                    "score": 78.62,
                    "source": "ecourt",
                    "state_code": 8,
                    "state_name": "BIHAR",
                    "subject": "",
                    "time_stamp": "2018-10-23T00:00:00Z",
                    "type": 1,
                    "under_acts": "Indian Penal Code",
                    "under_sections": "147,149,341,323,308,504",
                    "uniq_case_id": "732af6bbd24694f41797cff07218d843",
                    "year": " 2016"
                }
            ],
            "status": "completed",
            "_id": "615eb0ba1d9ca00019c6a694",
            "belongsTo": "615eb0911d9ca00019c6a693",
            "query": {
                "name": "Mithlesh Sah",
                "address": "Amahi Mishra, Bhore, Jigna Dubey, Gopalganj, Bihar, 841426 ",
                "fatherName": "Rajendra Sah"
            },
            "createdAt": "2021-10-07T08:32:58.593Z",
            "updatedAt": "2021-10-07T08:32:58.593Z",
            "__v": 0
        }';

        $response_array = json_decode($response,true);

        // dd($response_array['status']);

        $query = $response_array['query'];

        $reports = $response_array['reports'];

        // dd($response_array['reports']);

        $pdf=PDF::loadView('e-courtdemo',compact('query','reports'));

        return $pdf->stream('document.pdf');

        // return view('e-courtdemo',compact('query','reports'));

        // dd($response_array['query']);

        // dd($response_array['reports']);
    }

    public function eCourtSamplePDFReport()
    {

        $pdf=PDF::loadView('e-courtpdfreport');

        return $pdf->stream('document.pdf');

        // return view('e-courtpdfreport',compact('query','reports'));

       
    }

    public function upiPDFReport()
    {
        $pdf=PDF::loadView('upipdfreport');

        return $pdf->stream('document.pdf');
    }

    public function demoInvoice()
    {
        $pdf=PDF::loadView('demo_invoice');

        return $pdf->stream('document.pdf');

        // return view('demo_invoice');
    }

    public function cinPDFReport()
    {
        $pdf=PDF::loadView('cinpdfreport');

        return $pdf->stream('document.pdf');
    }

    public function cibilPDFReport()
    {
        $pdf=PDF::loadView('cibilpdfreport');

        return $pdf->stream('document.pdf');
    }

    public function signOut(Request $request)
    {
        Session::getHandler()->destroy(Auth::user()->session_id);
        Auth::user()->session_id = NULL;
        Auth::user()->save();

        return response()->json([
            'success' => true,
        ]);
    }

    public function loggedOut(Request $request)
    {
        $email   = $request->loggedin_email;  
        // dd($email);
        $profile_data = DB::table('users as u')
        ->select('u.id','u.first_name','u.user_type','u.email','u.status','u.business_id','u.session_id','u.is_deleted','u.is_email_verified','u.attempts','u.is_blocked','u.blocked_at','is_sms_otp_verified')        
        ->where(['u.email' =>$email])        
        ->first();
        if ($profile_data) {

            Session::getHandler()->destroy($profile_data->session_id);
            DB::table('users')->where(['email' =>$email])->update(['session_id'=>NULL]);
            // Auth::user()->session_id = NULL;
            // Auth::user()->save();
        }
        return response()->json([
            'success' => true,
        ]);
       
    }

    /**
     * User Authentication
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    //
    public function userAuthenticate(Request $request)
    { 

       $browser = NULL;  //get_browser();
        //    dd($a->browser);
            
        // if(!empty($_SERVER['HTTP_CLIENT_IP'])) {  
        $ip = $request->getClientIp(true);

            // dd($ip);
        // }  
        
        $email      = $request->input('email');    
        $password   = $request->input('password'); 
        
        // dd($email);
        
        $return = '';
        if($request->has('r')){
            $return = '?r='.$request->get('r');
        }

        // validate user
        $rules = [
            'email'     => 'required|email:rfc,dns',  
            'password'  => 'required'                                        
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()){
            return response()->json([
                'success'       => false,
                'error_type'    =>'validation',
                'next_action'   =>'show_validation',
                'errors'        => $validator->errors()
            ]);
        }
        
        // check sms user available or not
        $user = DB::table('users as u')
        ->select('u.id','u.email','u.business_id','u.first_name','u.user_type','u.password','attempts','is_blocked','blocked_at')        
        ->where(['u.email' =>$email])        
        ->first(); 

       
        if($user === null)
        {
            return response()->json(['success' => false,'error_type'=>'wrong_email_or_password','next_action'=>'','redirect'=>'']); 
        }

        $pStatus = Hash::check($password, $user->password); 

        //if sms otp is not matched
        if($pStatus === false)
        {
            if ($user->user_type=="user") {
                # code...
           
                if (($user->attempts) <= 4 && $user->is_blocked =='0') {
                    $attempt = $user->attempts;
                    $attempt=$attempt+1;
                    DB::table('users')->where('email',$email)->update(['attempts'=>$attempt]);

                    //block user log
                    $block_user_log = new BlockUserLog;
                    $block_user_log->email= $user->email;
                    $block_user_log->attempts= 1;
                    $user->is_blocked = '0';
                    $block_user_log->hit_time=date('Y-m-d H:i:s');
                    $block_user_log->save(); 

                    return response()->json(['success' => false,'error_type'=>'wrong_email_or_password','next_action'=>'','redirect'=>'']); 
                }
                if (($user->attempts) > 4  ) {
                    if ($user->is_blocked =='0') {
                        $attempt = $user->attempts;
                        $attempt=$attempt+1;
                        DB::table('users')->where('email',$email)->update(['attempts'=>$attempt,'is_blocked'=>'1','blocked_at'=>date('Y-m-d H:i:s')]);
                        //block user log
                        // $block_user_log = new BlockUserLog;
                        // $block_user_log->email= $user->email;
                        // $block_user_log->attempts= 1;
                        // $block_user_log->is_blocked = '1';
                        // $block_user_log->hit_time=date('Y-m-d H:i:s');
                        // $block_user_log->save(); 

                        // $email = $user->email;
                        // $name  = $user->first_name;
                        // $msg = "If it’s not attempted by you, contact your Admin.";
                        // $data  = array('name'=>$name,'email'=>$email,'msg'=>$msg,'ip'=>$ip,'browser'=>$browser->browser,'user_email'=>'');
                        
                        // if($email!="" || $email!=NULL){
                        //   Mail::send(['html'=>'mails.blocked-user'], $data, function($message) use($email,$name) {
                        //     $message->to($email, $name)->subject
                        //       ('Clobminds  System - Your account suspended notification ');
                        //     $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                        //   });
                        // }
                        //   Email to Admin
                          $admin=   DB::table('users')->where('id',$user->business_id)->first();

                         $email = $admin->email;
                         $name  = $admin->first_name;
                        $user_email =$user->email;
                         
                        $data  = array('name'=>$name,'email'=>$email,'ip'=>$ip,'browser'=>$browser->browser,'user_email'=>$user_email,'msg'=>'');

                        // if($email!="" || $email!=NULL){
                        //   Mail::send(['html'=>'mails.blocked-user'], $data, function($message) use($email,$name) {
                        //     $message->to($email, $name)->subject
                        //       ("Clobminds  System - Your user's account suspended notification");
                        //     $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                        //   });
                        // }
                        // return response()->json(['success' => false,'error_type'=>'To many attempts','next_action'=>'','redirect'=>'']); 
        
                    }
                    else{

                        return response()->json(['success' => false,'error_type'=>'To many attempts','next_action'=>'','redirect'=>'']); 
    
                    }
                
                }
            } 
            else {

                return response()->json(['success' => false,'error_type'=>'wrong_email_or_password','next_action'=>'','redirect'=>'']); 
            } 
            
        }

        
        if($user != null)
        {            
            // check profile is completed or not after otp validated
            $profile_data = DB::table('users as u')
            ->select('u.id','u.first_name','u.user_type','u.email','u.status','u.business_id','u.session_id','u.is_deleted','u.is_email_verified','u.attempts','u.is_blocked','u.blocked_at','u.is_sms_otp_verified')        
            ->where(['u.email' =>$email])        
            ->first();
            $redirect = '';
            
            //check session is empty or not
            // if (!empty($profile_data->session_id)) {
            //     return response()->json(['success' => true,'error_type'=>'User is already loggedin in other device ', 'next_action'=>'','redirect'=>'']);

            // }
            // check profile status
            if( $profile_data->status == 0  ){         
                return response()->json(['success' => false,'error_type'=>'account-inactive', 'next_action'=>'','redirect'=>'']); 
            } 

            if($profile_data->is_deleted == 1)
            {
                return response()->json(['success' => false,'error_type'=>'account-deleted', 'next_action'=>'','redirect'=>'']); 
            }

            if(($profile_data->is_email_verified==0 && $profile_data->is_sms_otp_verified==0) && $profile_data->user_type=='guest')
            {
                return response()->json(['success' => false,'error_type'=>'account-email', 'next_action'=>'','redirect'=>'']); 
            }
           
            if( $profile_data->status == 1  ){
                // $block_time ="";
                // $now_time = "";
                // $remain="";
                $block_time = Carbon::parse($profile_data->blocked_at);
                // dd($block_time);
                $now_time = Carbon::now();
                if ($block_time) {
                    $remain= $block_time->diffInHours($now_time);
                }
                // dd($remain);
                if ($profile_data->is_blocked== 1 ) {

                    if ($remain >= 4 ) {
                       
                        DB::table('users')->where('email',$email)->update(['attempts'=>NULL,'is_blocked'=>'0','unblocked_at'=>date('Y-m-d H:i:s')]);

                        // $profile_data->attempts= '0';
                        // $profile_data->is_blocked = '0';
                        // $profile_data->unblocked_at =date('Y-m-d H:i:s');
                        // $profile_data->save();
    
                        //block user log
                        $block_user_log = new BlockUserLog;
                        $block_user_log->email= $user->email;
                        $block_user_log->attempts= 1;
                        $user->is_blocked = '0';
                        $block_user_log->hit_time=date('Y-m-d H:i:s');
                        $block_user_log->save(); 
                    } else {

                        return response()->json(['success' => false,'error_type'=>'To many attempts','next_action'=>'','redirect'=>'']); 
                        
                    } 
                  
                }
                
                $previous_session =$profile_data->session_id;
                if ($previous_session) {
                    date_default_timezone_set('Asia/Kolkata');
                    $otp=mt_rand(1000,9999);
                    $name= $profile_data->first_name;
                    $email_send_at= date('Y-m-d h:i:s');
                    $created_at= date('Y-m-d h:i:s');
                    $sender = $profile_data->business_id;
                    $updated_at= date('Y-m-d h:i:s');
                    $items=DB::table('2_factor_authentications')->where(['user_id'=>$profile_data->id,'email'=>$email,'status'=>'0']);

                    if($items->count()>0)
                             {
                               $items=$items->update(
                                             [
                                                 "user_id" => $profile_data->id,
                                                 "email"=>$email,
                                                 "otp"=>$otp,
                                                 "email_send_at"=>$email_send_at,
                                                 "updated_at"=>$created_at,
                                             ]
                                       );
                                 
                                 $data  = array('name'=>$name,'email'=>$email,'otp'=>$otp,'sender'=>$sender);
                                 if($items)
                                 {
                                   Mail::send('mails.login-otp', $data, function($message) use($email,$name) {
                                     $message->to($email)->subject
                                       ('Clobminds  System - OTP Verification');
                                           $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                                       });
                                   
                                 }
                             }
                             else
                             {
                               $insert=DB::table('2_factor_authentications')
                                       ->insert(
                                             [
                                                "user_id" => $profile_data->id,
                                                "email"=>$email,
                                                "otp"=>$otp,
                                                "email_send_at"=>$email_send_at,
                                                "created_at"=>$created_at,
                                                "updated_at"=>$updated_at
                                             ]
                                       );
                               $data  = array('name'=>$name,'otp'=>$otp,'sender'=>$sender);
                               if($insert){
                                 Mail::send('mails.login-otp', $data, function($message) use($email) {
                                   $message->to($email)->subject
                                     ('Clobminds  System - OTP Verification');
                                           $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                                     });
                                   
                               }
                             }
                    return response()->json(['success' => false,'error_type'=>'logged-in','email'=>$email, 'next_action'=>'','redirect'=>'']);
                }

                Auth::loginUsingId($profile_data->id);
                $user_id = $profile_data->id;    
                
                //If user already logged in
               
                $request->session()->regenerate();
                // $previous_session = Auth::User()->session_id;
                // if ($previous_session) {
                //     Session::getHandler()->destroy($previous_session);
                // }
                Auth::user()->session_id = Session::getId();
                Auth::user()->save();

                //find the user type and redirect 
                if ($profile_data->user_type =='candidate') {
                    $user_type = DB::table('users as u')
                    ->select('u.id','u.user_type')        
                    ->where(['u.id' =>$profile_data->id])        
                    ->first();
                } else {
                    $user_type = DB::table('users as u')
                    ->select('u.id','u.user_type')        
                    ->where(['u.id' =>$profile_data->business_id])        
                    ->first();
                }
                
                
               
                
                if($user_type->user_type == 'customer'){
                    // dd($user_type->user_type);
                    // $redirect = env('APP_ADMIN_URL');
                    
                    $current_user_type  = Auth::user()->user_type;
                    $VIEW_ACESS= false;
                    $VIEW_ACESS   = Helper::can_access('Dashboard','');//passing action title and route group name
                    if($current_user_type=='user'){
                        if($VIEW_ACESS){
                            // dd("access approve");
                            $redirect = Config::get('app.admin_home_url');

                        }else{
                            $redirect = Config::get('app.user_access_url');
                        }
                        
                    }
                    else {
                        $redirect = Config::get('app.admin_home_url');
                    }
                }

                if($user_type->user_type == 'client'){
                    // $redirect = env('APP_CLIENT_HOME');
                    $redirect = Config::get('app.client_home_url');
                }

                if($user_type->user_type == 'vendor'){
                    // dd($user_type->user_type);
                    $redirect = env('APP_VENDOR_URL');
                }
                if($user_type->user_type == 'superadmin'){
                    $redirect = Config::get('app.superadmin_home_url');
                    // $redirect = env('APP_SUPERADMIN_URL');
                }
                
                if($user_type->user_type == 'guest'){
                    $redirect = Config::get('app.instant_home_url');
                    // $redirect = env('APP_GUEST_HOME');
                }
                if($user_type->user_type =='candidate'){

                    $redirect = env('APP_CANDIDATE_URL');
                }
                
                return response()->json(['success' => true,'error_type'=>$user_type, 'next_action'=>'','redirect'=>$redirect]); 
            }

        }
        

    }

    /**
     * Show the main home page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
    */
    // Verify otp to Open Generate Report
    public function verifyOtp(Request $request)
    {
        // dd($request->otp);
            // $business_id = Auth::user()->id;
            // $rules = [
            //     // 'abc' => 'required',
            //     // 'otp'  => 'required|array',
            //     'otp.*'  => 'required',
            //     // 'otp.*' => '|min:1'   
            //     // 'mob' => 'required',             
            // ];

            // $custom=[
            //   'otp.*.required' => 'The otp field is required'
            // ];

            // $validator = Validator::make($request->all(), $rules,$custom);
            // if ($validator->fails())
            //     return response()->json([
            //         'fail' => true,
            //         'errors' => $validator->errors(),
            //         'error_type'=>'validation'
            //     ]);


          // Validation
          if(count($request->otp)==0)
          {
            return response()->json([
                'fail' => true,
                'errors' => ['otp'=>['The OTP field is required']],
                'error_type'=>'validation'
            ]);
          }
          else
          {
            foreach($request->otp as $value)
            {
                if($value=='' || $value==NULL)
                {
                  return response()->json([
                            'fail' => true,
                            'errors' => ['otp'=>['The OTP must be 4 digits']],
                            'error_type'=>'validation'
                        ]);
                }
                
                else if(!is_numeric($value))
                {
                  return response()->json([
                      'fail' => true,
                      'errors' => ['otp'=>['The OTP must be numeric']],
                      'error_type'=>'validation'
                  ]);
                }
            }
          }
          $otp=implode('',$request->otp);

        //   dd($request->otp);
         
          $email_id=$request->verify_email;
        //   $user_id = 

          DB::beginTransaction();
          try
          {
                $data = DB::table('2_factor_authentications')
                          ->where(['email'=>$email_id,'status'=>'0'])
                          ->first();
                // dd($data);
                // DB::table('aadhar_check_v2s')->insert($data);
                if($otp==$data->otp)
                {
                  DB::table('2_factor_authentications')
                  ->where('id',$data->id)
                  ->update(['status'=>'1','otp'=>NULL]);

                  $profile_data = DB::table('users as u')
                  ->select('u.id','u.first_name','u.user_type','u.email','u.status','u.business_id','u.session_id','u.is_deleted','u.is_email_verified','u.attempts','u.is_blocked','u.blocked_at','u.is_sms_otp_verified')        
                  ->where(['u.email' =>$email_id])        
                  ->first();
                  $redirect = '';
                  Session::getHandler()->destroy($profile_data->session_id);
                  DB::table('users')->where(['email' =>$email_id])->update(['session_id'=>NULL]);
                  
                  Auth::loginUsingId($profile_data->id);
                  $user_id = $profile_data->id;    
                  
                  //If user already logged in
                 
                  $request->session()->regenerate();
                  // $previous_session = Auth::User()->session_id;
                  // if ($previous_session) {
                  //     Session::getHandler()->destroy($previous_session);
                  // }
                  Auth::user()->session_id = Session::getId();
                  Auth::user()->save();
  
                  //find the user type and redirect 
                  if ($profile_data->user_type =='candidate') {
                      $user_type = DB::table('users as u')
                      ->select('u.id','u.user_type')        
                      ->where(['u.id' =>$profile_data->id])        
                      ->first();
                  } else {
                      $user_type = DB::table('users as u')
                      ->select('u.id','u.user_type')        
                      ->where(['u.id' =>$profile_data->business_id])        
                      ->first();
                  }
                  
  
                 
                  
                  if($user_type->user_type == 'customer'){
                      // dd($user_type->user_type);
                      // $redirect = env('APP_ADMIN_URL');
                      $redirect = Config::get('app.admin_home_url');
                  }
  
                  if($user_type->user_type == 'client'){
                      // $redirect = env('APP_CLIENT_HOME');
                      $redirect = Config::get('app.client_home_url');
                  }
  
                  if($user_type->user_type == 'vendor'){
                      // dd($user_type->user_type);
                      $redirect = env('APP_VENDOR_URL');
                  }
                  if($user_type->user_type == 'superadmin'){
                      $redirect = Config::get('app.superadmin_home_url');
                      // $redirect = env('APP_SUPERADMIN_URL');
                  }
                  
                  if($user_type->user_type == 'guest'){
                      $redirect = Config::get('app.instant_home_url');
                      // $redirect = env('APP_GUEST_HOME');
                  }
                  if($user_type->user_type =='candidate'){
  
                      $redirect = env('APP_CANDIDATE_URL');
                  }
                  DB::commit();
                  return response()->json(['success' => true,'error_type'=>$user_type, 'next_action'=>'','redirect'=>$redirect]);

                  // $jaf_form_data = DB::table(' jaf_form_data AS jfd ')
                  // ->join(' job_items AS ji','ji.candidate_id', '=', 'jfd.candidate_id')
                  // ->select('jfd.*','ji.jaf_status')
                  // ->where(['jfd.candidate_id'=>$candidate_id])
                  // ->groupBy('jfd.candidate_id')->first();

                  // if ( $jaf_form_data) {

                  //   DB::table('reports')->where('candidate_id',$candidate_id)->update(['status'=>'interim']);

                  // }
                    // DB::commit();
                    // return response()->json([
                    //   'fail' =>false,
                      
                    // ]);
                }
                return response()->json([
                    'success'      => false, 
                    'error_type'     => "yes",
                    'message' => "Otp Didn't match try again"
                ]);
          }
          catch (\Exception $e) {
                DB::rollback();
                // something went wrong
                // dd($e->getMessage());
                return $e;
          }  

    }
    public function index()
    {
        $items = [];
        return view('main-web.index',compact('items'));
    }

    public function contact()
    {
        return view('main-web.contact');     
    }
    public function login()
    {
        return view('auth.login_old');     
    }
    //verification started Page
    public function verificationStarted()
    {
        if(Auth::check())
        {
            return redirect(env('APP_VERIFY_URL'));
        }
        else        {
            return view('main-web.verify-started');
        }
    }

    //Verification term and confdition
    public function termCondition()
    {
        if(Auth::check())
        {
            return redirect(env('APP_VERIFY_URL'));
        }
        else{
            return view('main-web.term-and-condition');
        }
    }
    //Candidate log in  page for verification 
    public function loginCandidate()
    {
        if(Auth::check())
        {
            return redirect(env('APP_VERIFY_URL'));
        }
        else{
            return view('main-web.verify-login');

        }
    }
    //Send OTP to candidate using mail
    public function loginCandidateOtpSend(Request $request)
    {
        date_default_timezone_set('Asia/Kolkata');

        $otp=mt_rand(1000,9999);
        $created_at= date('Y-m-d H:i:s');
        $rules= [
            
            'mobilenumber'       => 'required|regex:/^((?!(0))[0-9\s\-\+\(\)]{10,10})$/',
            
         ];
        
         $customMessages = [
            'mobilenumber.required'=>'Phone Number is required !!' ,
            'mobilenumber.regex'=>'Phone Number must be Valid & 10-digit Number !!' ,
          
        ];

         $validator = Validator::make($request->all(), $rules,$customMessages);
         if ($validator->fails()){
             return response()->json([
                 'success' => false,
                 'errors' => $validator->errors()
             ]);
         }
         DB::beginTransaction();
        try{
            $candidate=DB::table('users')->select('email','name')->where(['phone'=>$request->mobilenumber,'user_type'=>'candidate'])->first();
            if ($candidate) {
                if ($candidate->email!=NULL) {
                    $email=$candidate->email;
                    $name=$candidate->name;
               
                    $data=
                    [
                        "mobile" => $request->mobilenumber,
                        "otp"=>$otp,
                        "created_at"=>$created_at,
                        
                    ];
                
                $otp_id=DB::table('digital_verify_otps')->insertGetId($data);
                // dd($insert);
                
                $data  = array('name'=>$name,'otp'=>$otp);
                
                    Mail::send('mails.candidate-login-otp', $data, function($message) use($email) {
                        $message->to($email)->subject
                        ('Clobminds  System - OTP Verification');
                                $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                    }); 
                   
                }
                DB::commit();
                return response()->json([
                    'success' =>true,
                    'mail' =>'yes',
                    'id'=>base64_encode($otp_id)
                ]);
            }
            else {
                return response()->json([
                    'success' =>true,
                    'mail' =>'no'
                ]);
            }
            
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }  
        // dd($request->all());
    }
    //Candidate OTP in  page for verification 
    public function loginCandidateOtp(Request $request)
    {   
        if(Auth::check())
        {
            return redirect(env('APP_VERIFY_URL'));
        }
        else{
            $id=base64_decode($request->id);
            $otp=DB::table('digital_verify_otps')->where('id',$id)->first();
            return view('main-web.verify-otp',compact('otp'));
        }
    }

    public function loginCandidateOtpVerify(Request $request)
    {
        // Validation
        if(count($request->otp)==0)
        {
            return response()->json([
                'success' => false,
                'errors' => ['otp'=>['The OTP field is required']],
                'error_type'=>'validation'
            ]);
        }
        else
        {
            foreach($request->otp as $value)
            {
                if($value=='' || $value==NULL)
                {
                    return response()->json([
                            'success' => false,
                            'errors' => ['otp'=>['The OTP must be 4 digits']],
                            'error_type'=>'validation'
                        ]);
                }
                
                else if(!is_numeric($value))
                {
                    return response()->json([
                        'success' => false,
                        'errors' => ['otp'=>['The OTP must be numeric']],
                        'error_type'=>'validation'
                    ]);
                }
            }
        }
        $otp=implode('',$request->otp);

        //   dd($request->otp);

        $mobile=$request->mobile;
        //   $user_id = 

        DB::beginTransaction();
        try
        {
            $data = DB::table('digital_verify_otps')
                        ->where(['mobile'=>$mobile,'status'=>'0'])
                        ->latest()->first();
            // dd($data);
            // DB::table('aadhar_check_v2s')->insert($data);
            if($otp==$data->otp)
            {
                DB::table('digital_verify_otps')
                ->where('id',$data->id)
                ->update(['status'=>'1','otp'=>NULL]);

                $profile_data = DB::table('users as u')
                ->select('u.id','u.first_name','u.user_type','u.email','u.status','u.business_id','u.session_id','u.is_deleted','u.is_email_verified','u.attempts','u.is_blocked','u.blocked_at','u.is_sms_otp_verified')        
                ->where(['u.phone' =>$mobile])        
                ->first();
                $redirect = '';
                Session::getHandler()->destroy($profile_data->session_id);
                DB::table('users')->where(['phone' =>$mobile])->update(['session_id'=>NULL]);
                
                Auth::loginUsingId($profile_data->id);
                $user_id = $profile_data->id;    
                
                //If user already logged in
                
                $request->session()->regenerate();
                // $previous_session = Auth::User()->session_id;
                // if ($previous_session) {
                //     Session::getHandler()->destroy($previous_session);
                // }
                Auth::user()->session_id = Session::getId();
                Auth::user()->save();

                //find the user type and redirect 
                if ($profile_data->user_type =='candidate') {
                    $user_type = DB::table('users as u')
                    ->select('u.id','u.user_type')        
                    ->where(['u.id' =>$profile_data->id])        
                    ->first();
                }
                if($user_type->user_type =='candidate'){
                    $redirect = env('APP_VERIFY_URL');
                }
                DB::commit();
                return response()->json(['success' => true,'error_type'=>$user_type, 'next_action'=>'','redirect'=>$redirect]);

            }
            return response()->json([
                'success'      => false, 
                'error_type'     => "yes",
                'message' => "Otp Didn't match try again"
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            // dd($e->getMessage());
            return $e;
        }  
    }

    public function contactStore(Request $request)
    {
        $rules= 
        [
            'name'   => 'required|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
            'email'        => 'required|email:rfc,dns',
            'mobile' => 'required|regex:/^(?=.*[0-9])[0-9]{10}$/',
            'subject'   => 'required|regex:/^[a-zA-Z0-9., ]+$/u|min:2|max:255',
            'message' => 'required',
            'g-recaptcha-response' => ['required', new Recaptcha()]
        ];

        $custom=[
            'g-recaptcha-response.required' => 'The captcha is Required'
        ];

        $validator = Validator::make($request->all(), $rules,$custom);
        
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        $data=[
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'subject' => $request->subject,
            'message' => $request->message
        ];

        // $contact_id=DB::table('contact_us')->insertGetId($data);

        $contact = ContactUs::create($data);

        $contact_id = $contact->id;

        $name=$request->name;
        $email=$request->email;

        $data  = array('name'=>$name,'email'=>$email);

        Mail::send(['html'=>'mails.user-contact'], $data, function($message) use($email,$name) {
            $message->to($email, $name)->subject
            ('Clobminds  System - Your Query Confirmation');
                $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
            });

        // $name='Clobminds ';
        // $email='info@my-bcd.com';

        // $contact_us=DB::table('contact_us')->where('id',$contact_id)->first();

        // $data  = array('name'=>$name,'email'=>$email,'contact'=>$contact_us);

        // Mail::send(['html'=>'mails.admin-contact'], $data, function($message) use($email,$name) {
        //     $message->to($email, $name)->subject
        //     ('Clobminds  System - New query from My-BCD');
        //         $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
        //     });


        return response()->json([
            'success' => true,
        ]);

    }

    public function privacyPolicy()
    {
        return view('main-web.privacy-policy');     
    }

    public function terms()
    {
        return view('main-web.terms');     
    }

    public function forgotPassword(){
        return view('auth.forgot-password');
    }

    /**
     * Show the pricing page
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function pricing()
    {
        $package = DB::table('subscription_plans')
                   ->select('*')
                   ->get();
      
        return view('main-web.pricing',compact('package'));

    }
    
    /**
     * Show the signup form
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function signup()
    {
        $country = DB::table('countries')
                   ->select('id','name')
                     ->get();
      
        return view('main-web.signup',compact('country'));

    }

    // save signup data
    public function save_signup(Request $request)
    {
        // dd($request);

            $this->validate($request,[
                    'first_name'    =>'required',
                    'last_name'     =>'required',
                    'email'         =>'required|email|unique:users',
                    'phone'         =>'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                    'country'       =>'required',
                    'terms'         =>'required',
                    'user_type'     =>'required',
                    'password'      =>'min:6',
                    'password_confirmation' =>'required_with:password|same:password|min:6',
                ],
                [
                    'terms.required'=>'Please confirm the term and conditions', // custom message
                ]);
            // save user data
            $user_data    = [
              'name'            => $request->input('first_name').' '.$request->input('last_name'),
              'first_name'      => $request->input('first_name'),
              'last_name'       => $request->input('last_name'),
              'phone'           => $request->input('phone'), 
              'email'           => $request->input('email'), 
              'user_type'       => 'customer',
              'user_account_type'=>$request->input('user_type'),
              'password'        => bcrypt($request->input('password')),       
              'country_id'      => $request->input('country_id'),
              'created_at'      => date('Y-m-d H:i:s')
            ];

            $user_id = DB::table('users')->insertGetId($user_data);

            $parent_id = Helper::get_superadmin_id();
            
            //updated business id 
            DB::table('users')->where(['id'=>$user_id])->update(['business_id'=>$user_id,'parent_id'=>$parent_id]);
            
            // Attach customer subscription 
            DB::table('user_subscriptions')->insertGetId(['business_id'=>$user_id,'subscription_id'=>'1','status'=>'1','created_at'=>date('Y-m-d H:i:s')]);
            
            //Subscription services
            DB::table('user_services')->insertGetId(['business_id'=>$user_id,'service_id'=>'1','status'=>'1','start_date'=>date('Y-m-d'),'created_at'=>date('Y-m-d H:i:s')]);
            
            //send email to customer
            $email = $request->input('email');
            $name  = $request->input('first_name');
            $data  = array('name'=>$name,'email'=>$email,'password'=>$request->input('password'));
   
            Mail::send(['html'=>'mails.account-info'], $data, function($message) use($email,$name) {
                 $message->to($email, $name)->subject
                    ('Clobminds  System - Your account credential');
                 $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
            });

            //insert token 
            $random_token = Str::random(60);
            DB::table('users')->where(['id'=>$user_id])->update(['email_verification_sent_at'=>date('Y-m-d H:i:s'),'email_verification_token'=>$random_token]);
            //
            $data  = ['name'=>$name,'email'=>$email,'password'=>$request->input('password'), 'token'=>$random_token];
            Mail::send(['html'=>'mails.email-verification'], $data, function($message) use($email,$name) {
                $message->to($email, $name)->subject
                   ('Clobminds  System - Please confirm your email address');
                $message->from('techms849@gmail.com','Clobminds  System');
           });

        return redirect()->route('/thank-you');

    }

    /**
     * Verify the email .
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function verify_email_link(Request $request)
    {
        
        $token = "";
         //validate 
        if(request()->has('token'))
        {
 
             if(request()->filled('token'))
             {
                 $token = request()->token;
             }
             else{
                 echo "URL is missing!";
             }
 
        }
        else
        {
            echo "URL is missing!";
        }
        
        $token_data = DB::table('users')
                   ->select('id','email_verification_token')
                   ->where(['email_verification_token'=>$token])
                   ->first();

        if($token_data !=null){
            DB::table('users')->where(['id'=>$token_data->id])->update(['email_verified_at'=>date('Y-m-d H:i:s')]);
            return view('main-web.verify-email');
        }
        else{
            echo "Link is not valid!";
        }

    }

    /**
     * Show the business form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function business_info()
    {
        $countries = DB::table('countries')->get();
        $user = DB::table('users')->where('id',Auth::user()->business_id)->first();

        $items = [];
        return view('main-web.business-info-form',compact('countries','user'));

    }

    /**
     * Show the business info.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function business_info_save(Request $request)
    {
        $business_id = Auth::user()->business_id;

        // Form validation
         $this->validate($request, [
            'address'   => 'required',
            'pin_code'   => 'required',
            'city'      => 'required',
            'state'     => 'required',
            'country'   => 'required',
            'company'   => 'required',
            'business_email'            => 'required',
            'business_phone_number'     => 'required',
            'gst_number'                => 'required',
            'contract_signed_by'        => 'required',
            'hr_name'                   => 'required',
            'work_order_date'           => 'required',
            'work_operating_date'       => 'required',
            'billing_detail'            => 'required',
            'owner_first_name'          => 'required',
            'owner_email'               => 'required',
            'owner_phone_number'        => 'required',
            'owner_designation'         => 'required',
            'dealing_first_name'        => 'required',
            'dealing_email'             => 'required',
            'dealing_phone_number'      => 'required',
            'dealing_designation'       => 'required',
         ]
        );

        //update data
        //insert business info
        $b_data = 
        [
            'business_id'   =>$business_id,
            'company_name'  =>$request->input('company'),
            'address_line1' =>$request->input('address'),
            'zipcode'       =>$request->input('pincode'),
            'city_name'     =>$request->input('city'),
            'state_name'    =>$request->input('state'),
            'email'         =>$request->input('business_email'),
            'phone'         =>$request->input('business_phone_number'),
            'gst_number'    =>$request->input('gst_number'),
            'tin_number'    =>$request->input('tin_number'),
            'hr_name'       =>$request->input('hr_name'),
            'work_order_date'       => date('Y-m-d',strtotime($request->input('work_order_date'))),
            'work_operating_date'   => date('Y-m-d',strtotime($request->input('work_operating_date'))),
            'billing_detail'        => $request->input('billing_detail'),
            'billing_mode'          =>$request->input('billing_mode'),
            'contract_signed_by'    =>$request->input('contract_signed_by'),
            'created_at'            => date('Y-m-d H:i:s')
        ];
        
        DB::table('user_businesses')->insertGetId($b_data);

        //contact info
        //owner contact
        $b_data = 
        [
            'business_id'   =>$business_id,
            'contact_type'  =>'owner',
            'designation'   =>$request->input('owner_designation'),
            'first_name'    =>$request->input('owner_first_name'),
            'last_name'     =>$request->input('owner_last_name'),
            'email'         =>$request->input('owner_email'),
            'phone'         =>$request->input('owner_phone_number'),
            'landline_number'=>$request->input('owner_landline_number'),
            'created_at'    => date('Y-m-d H:i:s')
        ];
        
        DB::table('user_business_contacts')->insertGetId($b_data);
        //dealing officer
        $b_data = 
        [
            'business_id'   =>$business_id,
            'contact_type'  =>'dealing_officer',
            'designation'   =>$request->input('dealing_designation'),
            'first_name'    =>$request->input('dealing_first_name'),
            'last_name'     =>$request->input('dealing_last_name'),
            'email'         =>$request->input('dealing_email'),
            'phone'         =>$request->input('dealing_phone_number'),
            'landline_number'=>$request->input('dealing_landline_number'),
            'created_at'    => date('Y-m-d H:i:s')
        ];
        
        DB::table('user_business_contacts')->insertGetId($b_data);
        //acount officer
        $b_data = 
        [
            'business_id'   =>$business_id,
            'contact_type'  =>'account_officer',
            'designation'   =>$request->input('account_designation'),
            'first_name'    =>$request->input('account_first_name'),
            'last_name'     =>$request->input('account_last_name'),
            'email'         =>$request->input('account_email'),
            'phone'         =>$request->input('account_phone_number'),
            'landline_number'=>$request->input('account_landline_number'),
            'created_at'     => date('Y-m-d H:i:s')
        ];
        
        DB::table('user_business_contacts')->insertGetId($b_data);

        //udpate business info status 
        DB::table('users')->where(['id'=>$business_id])->update(['is_business_data_completed'=>'1']);
        
        //if data saved redirect to home page
        return redirect('/home');
    }
    
    /**
     * Show the thank you page after signup
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function thank_you()
    {
        $country = DB::table('countries')
                   ->select('id','name')
                     ->get();
      
        return view('main-web.thank-you',compact('country'));

    }
   



    /**
     * Show the checkout page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
    */

    public function checkout(Request $request)
    {
        $items = [];

         //validate 
         if(request()->has('id'))
         {
             if(request()->filled('id'))
             {
                 $id = request()->id;
             }
             else{
                return redirect('/pricing'); 
             }
         }
         else
         {
            return redirect('/pricing');
         }

        $package = DB::table('subscription_plans')->where(['id'=>$id])->first();

        $countries = DB::table('countries')->get();

        return view('main-web.checkout',compact('package','countries'));
    }

    //
    // public function testMail()
    // {                       
        
    //    //send notification to user 
    //    $email   = 'mithilesh.techsaga@gmail.com';
    //    $name    = 'Mithilesh Sah';
 
    //    $data = array('name'=>$name,'email'=>$email);
    //     // $mail = Config::get('mail');
    //     // dd($mail);
    //     Mail::send(['html'=>'mails.testMail'], $data, function($message) use($email,$name) {
    //         $message->to($email, $name)->subject("Clobminds Email");
    //         $message->from( 'info@Clobminds.com', "info@Clobminds.com");
    //     });
 
    // }  

    // Pop Up for forget Password 
    public function forgetPasswordPopup(Request $request)
    {
        $rules= [
            'forgetemail'  =>'required|email:rfc,dns',
             
          ];
         
          $customMessages = [
            //'email.required' => 'Please fill email to forget password.',
            'forgetemail.required' => 'Please fill email to forget password.',
           
         ];

        $token=mt_rand(100000000000000,9999999999999999);
   
          $validator = Validator::make($request->all(), $rules,$customMessages);
           
          if ($validator->fails()){
              return response()->json([
                  'success' => false,
                  'errors' => $validator->errors()
              ]);
          }

         $email = $request->forgetemail;
        //  dd($email);
        $user = DB::table('users')->where(['email'=>$email,'is_blocked'=>'0'])->first();
        
        DB::table('users')->where('email',$email)->update(
            [
                'email_verification_token' => $token
            ]);
       
        if ($user) {
            $blocked = DB::table('users')->where(['email'=>$email,'is_blocked'=>'0'])->first();
            if($blocked){
                $email = $user->email;
                $name  = $user->name;
                $id    = base64_encode($user->id);
                
                $data  = array('name'=>$name,'email'=>$email,'id'=>$id,'token_no' => base64_encode($token));

                Mail::send(['html'=>'mails.forget-password'], $data, function($message) use($email,$name) {
                    $message->to($email, $name)->subject
                    ('Clobminds Pvt Ltd  - Reset Password Link');
                        $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                    });

                    return response()->json([
                        'success' =>true,
                        'email' => $email,
                        'custom'  =>'yes',
                        'errors'  =>[]
                    ]);
                }
                else{
                    return response()->json([
                        'success' =>false,
                        'custom'  =>'yes',
                        'errors'  =>['email'=>'This user is blocked ,please contact to admin!']
                    ]);
                }
        } else {
            return response()->json([
                'success' =>false,
                'custom'  =>'yes',
                'errors'  =>['email'=>'Please enter your correct email']
            ]);
        }
         
  
    }

    public function guest_create(Request $request)
    {
        
        if(Auth::check())
            return redirect('/');
        else
            return view('main-web.guest-register');
    }

    public function guest_store(Request $request)
    {
        // $this->validate($request, [
        //     'full_name'   => 'required|regex:/^[a-zA-Z ]+$/u|min:2|max:255',
        // ]);

        // dd($request->first_name);
        $mobile_number=NULL;
        $otp = '1111';
        $rules= 
        [
            'first_name'   => 'required|regex:/^[A-Za-z]+([A-Za-z]+\s)*[A-za-z]+$/u|min:1|max:255',
            'last_name'   => 'nullable|regex:/^[A-Za-z]+([A-Za-z]+\s)*[A-za-z]+$/u|min:1|max:255',
            // 'company_name'   => 'nullable|regex:/^[a-zA-Z ]+$/u|min:2|max:255',
            'job_title'   => 'nullable|regex:/^[A-Za-z]+([A-Za-z]+\s)*[A-za-z]+$/u|min:2|max:255',
            'email'        => 'required|email:rfc,dns|unique:users',
            'mobile_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:users,phone',
            'password' => 'required|min:10|same:confirm_password',
            'confirm_password' => 'required|min:10|same:password',
            // 'purge_data' => 'required_if:purge_check,on|nullable|integer|gte:7',
            'feature' => 'required',
            'term' => 'required',
        ];

        $custom=[
            'first_name.regex' => 'First name must be a string',
            'last_name.regex' => 'Last name must be a string',
            'mobile_number.regex' => 'Mobile Number must be 10-digit number',
            'job_title.regex' => 'Job Title must be a string',
            'email.unique'  =>'Email id has already been taken',
            'mobile_number.unique'  =>'Mobile Number has already been taken',
            'feature.required' => 'You should agree to our Feature Term.',
            'term.required' => 'You should agree to our Terms of Service.',
            // 'purge_data.required_with' => 'Purge Data Field is required',
            // 'purge_data.integer' => 'Purge Data must be numeric.',
            // 'purge_data.gte' => 'Purge Data must be atleast 7 days.'
        ];
        $validator = Validator::make($request->all(), $rules,$custom);
        
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }
        DB::beginTransaction();
        try{

            $raw_pass =$request->input('password');
       
            $token=Str::random(50);

            $mobile_number = preg_replace('/\D/', '', $request->mobile_number);

            if(strlen($mobile_number)!=10)
            {
                return response()->json([
                    'success' => false,
                    'custom'=>'yes',
                    'errors' => ['mobile_number'=>'Mobile Number Must be 10-digit Number !!']
                ]);
            }
        
            if (!preg_match("/^(?=.{10,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[$@%£!]).*$/", $raw_pass)){
            
                    return response()->json([
                        'success' => false,
                        'custom'=>'yes',
                        'errors' => ['password'=>'Password must be atleast 10 characters long including (Atleast 1 uppercase letter(A–Z), Lowercase letter(a–z), 1 number(0–9), 1 non-alphanumeric symbol(‘$@%£!’) !']
                    ]);
            }

            if($request->has('company_name') && $request->company_name!='')
            {
                if($request->job_title=='')
                {
                    return response()->json([
                        'success' => false,
                        'custom'=>'yes',
                        'errors' => ['job_title'=>'The Job Title field is required !']
                    ]);                
                }                              
            }

            // $mobile_no_with_code = $request->primary_phone_code.''.$mobile_number;

            // $otp=mt_rand(1000,9999);

            // // $response_otp=MSGWhatsappTrait::sendAccountRegisterOTP($mobile_no_with_code,$otp);

            // $response_otp=MSGSMSTrait::sendAccountRegisterOTP($mobile_no_with_code,$otp);

            // if(count($response_otp)>0)
            // {
            //     if($response_otp['status'])
            //     {
            //         $super_admin=DB::table('users')->where('user_type','superadmin')->first();
            //         // hash password
            //         $password = bcrypt($request->input('password'));

            //         $user_id=DB::table('users')->insertGetId([
            //             'parent_id' => $super_admin->id,
            //             'first_name' => trim($request->first_name),
            //             'last_name' => trim($request->last_name),
            //             'name' => trim($request->first_name.' '.$request->last_name),
            //             'email' => trim($request->email),
            //             'phone' => $mobile_number,
            //             'phone_code' => $request->primary_phone_code,
            //             'phone_iso' => $request->primary_phone_iso,
            //             'password' => $password,
            //             'user_type' => 'guest',
            //             'sms_otp' => $otp,
            //             'sms_otp_sent_at' => date('Y-m-d H:i:s'),
            //             // 'email_verification_token' => $token,
            //             // 'email_verification_sent_at' =>  date('Y-m-d H:i:s'),
            //             'created_at' => date('Y-m-d H:i:s')
            //         ]);

            //         DB::table('users')->where('id',$user_id)->update([
            //             'business_id' => $user_id
            //         ]);

            //         if($request->has('company_name') && $request->company_name!='')
            //         {
            //             DB::table('user_businesses')->insert(
            //                 [ 
            //                 'business_id' => $user_id,
            //                 'company_name' => trim($request->company_name),
            //                 'job_title' => trim($request->company_name)!=''?trim($request->job_title):NULL,
            //                 'created_at' => date('Y-m-d H:i:s')
            //                 ]
            //             );
            //         }

            //         // if($request->has('purge_check') || $request->purge_check!=NULL)
            //         // {
            //         //     $purge_day = 7;
                        
            //         //     if($request->has('purge_data') && $request->purge_data !='' && $request->purge_data >=7)
            //         //     {
            //         //         $purge_day = $request->purge_data;
            //         //     }

            //         //     DB::table('users')->where(['id'=>$user_id])->update([
            //         //         'is_purged' => 1,
            //         //         'purge_days' => $purge_day
            //         //     ]);
            //         // }

            //         DB::commit();
            //         return response()->json([
            //             'success' =>true,
            //             'custom'  =>'yes',
            //             'user_id'   => base64_encode($user_id),
            //         ]);
            //     }
            //     else
            //     {
            //         return response()->json([
            //             'message'  =>'Something Went Wrong !!',
            //         ]);
            //     }

                
                
            // }

            $super_admin=DB::table('users')->where('user_type','superadmin')->first();
                    // hash password
            $password = bcrypt($request->input('password'));

            $name = trim(preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $request->first_name.' '.$request->last_name));

            $user_id=DB::table('users')->insertGetId([
                'parent_id' => $super_admin->id,
                'first_name' => trim($request->first_name),
                'last_name' => trim($request->last_name),
                'name' => $name,
                'email' => trim($request->email),
                'phone' => $mobile_number,
                'phone_code' => $request->primary_phone_code,
                'phone_iso' => $request->primary_phone_iso,
                'password' => $password,
                'user_type' => 'guest',
                // 'sms_otp' => $otp,
                // 'sms_otp_sent_at' => date('Y-m-d H:i:s'),
                'email_verification_token' => $token,
                'email_verification_sent_at' =>  date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s')
            ]);

            DB::table('users')->where('id',$user_id)->update([
                'business_id' => $user_id
            ]);

            if($request->has('company_name') && $request->company_name!='')
            {
                DB::table('user_businesses')->insert(
                    [ 
                    'business_id' => $user_id,
                    'company_name' => trim($request->company_name),
                    'job_title' => trim($request->company_name)!=''?trim($request->job_title):NULL,
                    'created_at' => date('Y-m-d H:i:s')
                    ]
                );
            }

            $name=$request->first_name.' '.$request->last_name;

            $email=$request->email;

            $data=['name' =>$name,'email' => $email,'token' => $token];

            Mail::send(['html'=>'mails.email-verify'], $data, function($message) use($email,$name) {
                $message->to($email, $name)->subject
                    ('Clobminds  System - Email Verification');
                $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
            });

            DB::commit();
            return response()->json([
                'success' =>true,
                'custom'  =>'yes',
                'user_id'   => base64_encode($user_id),
            ]);

            // return response()->json([
            //     'message' => 'Something Went Wrong !!'
            // ]);

        }
        catch (\Exception $e) {
            DB::rollback();
            return $e;
        }
        
    }

    public function verifyAccount(Request $request)
    {
        $user_id=base64_decode($request->id);
        
        if($request->isMethod('get'))
        {
            $user=DB::table('users')->where(['id'=>$user_id])->first();

            if($user->is_email_verified==1 || $user->is_sms_otp_verified==1)
            {
                return redirect('/thank-you-account_verify');
            }

            return view('main-web.verify-account',compact('user'));
        }

        
        // Validation
        if(count($request->otp)==0)
        {
          return response()->json([
              'fail' => true,
              'errors' => ['otp'=>['The otp field is required']],
              'error_type'=>'validation'
          ]);
        }
        else
        {
          foreach($request->otp as $value)
          {
              if($value=='' || $value==NULL)
              {
                return response()->json([
                          'fail' => true,
                          'errors' => ['otp'=>['The otp field is required']],
                          'error_type'=>'validation'
                      ]);
              }
              else if(!is_numeric($value))
              {
                return response()->json([
                    'fail' => true,
                    'errors' => ['otp'=>['The otp must be numeric']],
                    'error_type'=>'validation'
                ]);
              }
          }
        }

        DB::beginTransaction();
        try{
            $otp=implode('',$request->otp);

            // Check whether OTP is Correct or Not

            $user = DB::table('users')->where(['id'=>$user_id,'sms_otp'=>$otp])->first();

            if($user==NULL)
            {
                return response()->json([
                    'fail' => true,
                    'errors' => ['otp'=>["The Otp Didn't match try again"]],
                    'error_type'=>'validation'
                ]);
            }
            else
            {
                $sms_date_time = date('Y-m-d H:i:s',strtotime($user->sms_otp_sent_at.' +'.'3 minutes'));

                $today_date_time = date('Y-m-d H:i:s');

                if(strtotime($today_date_time) > strtotime($sms_date_time))
                {
                    return response()->json([
                        'fail' => true,
                        'errors' => ['otp'=>["The Otp time is expired, Try Again !!"]],
                        'error_type'=>'validation'
                    ]);
                }
            }

            DB::table('users')->where(['id'=>$user_id])->update([
                'is_sms_verified' => 1,
                'sms_verified_at' => date('Y-m-d H:i:s')
            ]);

            DB::commit();
            return response()->json([
                'fail' =>false,
            ]);

        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
         }

        
    }

    public function resendOTP(Request $request)
    {
        $user_id = base64_decode($request->id);

        DB::beginTransaction();
        try{

            $user = DB::table('users')->where(['id'=>$user_id])->first();

            if($user!=NULL)
            {
                $sms_date_time = date('Y-m-d H:i:s',strtotime($user->sms_otp_sent_at.' +'.'3 minutes'));

                $today_date_time = date('Y-m-d H:i:s');

                if(strtotime($today_date_time) < strtotime($sms_date_time))
                {
                    return response()->json([
                        'status' => false,
                        'message' => 'You have already sent the OTP, Try after some time !!'
                    ]);
                }

                $otp = NULL;

                $mobile_number = preg_replace('/\D/', '', $user->phone);

                $mobile_no_with_code = $user->phone_code.''.$mobile_number;

                $otp=mt_rand(1000,9999);

                // $response_otp = MSGWhatsappTrait::sendAccountRegisterOTP($mobile_no_with_code,$otp);

                $response_otp=MSGSMSTrait::sendAccountRegisterOTP($mobile_no_with_code,$otp);

                if(count($response_otp)>0)
                {
                    if($response_otp['status'])
                    {
                        $sms_date = date('Y-m-d H:i:s');

                        DB::table('users')->where(['id'=>$user_id])->update([
                            'sms_otp' => $otp,
                            'sms_otp_sent_at' => $sms_date,
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
                        
                        DB::commit();
                        return response()->json([
                            'status' => true,
                            'date' => $sms_date 
                        ]);
                    }
                    else
                    {
                        return response()->json([
                            'status' => false,
                            'message' => 'Something Went Wrong'
                        ]);
                    }
                    
                }

                return response()->json([
                    'status' => false,
                    'message' => 'Something Went Wrong'
                ]);
            }
            return response()->json([
                'status' => false,
                'message' => 'Something Went Wrong'
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            return $e;
        }
        
    }

    public function mobileAccountVerify(Request $request)
    {
        // validate user
        $rules = [
            'mobile_number'    => 'required|regex:/^[0-9]{10}/',                                        
        ];

        $custom=[
            'mobile_number.regex' => 'Mobile Number must be 10-digit number',
        ];

        $validator = Validator::make($request->all(), $rules,$custom);
        if ($validator->fails()){
            return response()->json([
                'status'       => false,
                'error_type'    =>'validation',
                'errors'        => $validator->errors()
            ]);
        }

        DB::beginTransaction();
        try{

            $user = DB::table('users')->where(['phone'=>$request->mobile_number])->first();

            if($user!=NULL)
            {
                $sms_date_time = date('Y-m-d H:i:s',strtotime($user->sms_otp_sent_at.' +'.'3 minutes'));

                $today_date_time = date('Y-m-d H:i:s');

                if(strtotime($today_date_time) < strtotime($sms_date_time))
                {
                    return response()->json([
                        'status' => false,
                        'error_type' => 'custom',
                        'errors' => 'OTP has already been sent to this number, Try after some time !!'
                    ]);
                }

                $otp = NULL;

                $mobile_number = preg_replace('/\D/', '', $user->phone);

                $mobile_no_with_code = $user->phone_code.''.$mobile_number;

                $otp=mt_rand(1000,9999);

                // $response_otp = MSGWhatsappTrait::sendAccountRegisterOTP($mobile_no_with_code,$otp);

                $response_otp=MSGSMSTrait::sendAccountRegisterOTP($mobile_no_with_code,$otp);

                if(count($response_otp)>0)
                {
                    if($response_otp['status'])
                    {
                        $sms_date = date('Y-m-d H:i:s');

                        DB::table('users')->where(['id'=>$user->id])->update([
                            'sms_otp' => $otp,
                            'sms_otp_sent_at' => $sms_date,
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
                        
                        DB::commit();
                        return response()->json([
                            'status' => true,
                            'id' => base64_encode($user->id) 
                        ]);
                    }
                    else
                    {
                        return response()->json([
                            'status' => false,
                            'error_type' => '',
                            'message' => 'Something Went Wrong'
                        ]);
                    }
                    
                }

                return response()->json([
                    'status' => false,
                    'error_type' => '',
                    'message' => 'Something Went Wrong'
                ]);
            }

            return response()->json([
                'status' => false,
                'error_type' => 'validation',
                'errors' => ['mobile_number'=> "Mobile Number Didn't Match !!"]
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            return $e;
        }
        
    }

    public function emailverification(Request $request)
    {
        $user_id=base64_decode($request->id);
        $user=DB::table('users')->where(['id'=>$user_id])->first();
        if($user->is_email_verified==1 || $user->is_sms_otp_verified==1)
        {
            return redirect('/thank-you-account_verify');
        }
        return view('main-web.mailverification',compact('user'));
    }

    public function verifyEmailLink(Request $request)
    {

        $token = "";
        //validate 
       if($request->has('token'))
       {

            if($request->filled('token'))
            {
                $token = request()->token;
            }
            else{
                echo "URL is missing!";
            }

       }
       else
       {
           echo "URL is missing!";
       }
       
       $token_data = DB::table('users')
                  ->select('id','email_verification_token','is_email_verified')
                  ->where(['email_verification_token'=>$token])
                  ->first();

       if($token_data !=null){
           if($token_data->is_email_verified==1)
                return redirect('/thank-you-account_verify');
            
            DB::table('users')->where(['id'=>$token_data->id])->update(['is_email_verified'=>1,'email_verified_at'=>date('Y-m-d H:i:s')]);
            return redirect('/thank-you-account_verify');
       }
       else{
           echo "Link is not valid!";
       }
    }

    public function thankyouemail(Request $request)
    {
        return view('main-web.email-thank-you');
    }

    public function loginActivity()
    {
        if(Auth::check())
        {
            $user_id = Auth::user()->id;

            $login_log = DB::table('login_logout_activity_logs')->where(['user_id'=>$user_id])->latest()->first();

            if($login_log!=NULL)
            {
                DB::table('login_logout_activity_logs')->where(['id'=>$login_log->id])->update([
                    'last_login_activity_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                return response()->json(['success'=> true,'message'=> 'login log updated successfully !!']);
            }
        }

        return response()->json(['success'=> false,'message'=> 'login log update failed !!']);


    }

    public function guestPurgeNotify(){
        
        $users = DB::table('users')->where(['user_type'=>'guest','is_purged'=>'1'])->get();
        // dd($users);
        $today_date = date('Y-m-d');
        if(count($users)>0)
        {
            foreach($users as $user)
            {

                $purge_date = date('Y-m-d',strtotime($user->created_at.'+'.($user->purge_days - 1).'days'));

                if(strtotime($today_date)>=strtotime($purge_date))
                {
                    $user_d=DB::table('users')->where(['id'=>$user->id])->first();

                    $name=$user_d->name;

                    $email=$user_d->email;

                    $msg = 'You Have Receive The Purge Notification, So If You Does Not have any pending orders then you can delete your account !!';
            
                    $data=['name' =>$name,'email' => $email,'user'=>$user_d,'msg'=>$msg,'date' => $purge_date];

                    Mail::send(['html'=>'mails.purge-notify'], $data, function($message) use($email,$name) {
                        $message->to($email, $name)->subject
                            ('Clobminds System - Purge Notification');
                        $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                    });
                }
            }
        }

        // echo 'done';
    }

    
    public function addressVerificationForm(Request $request)
    {
        $jaf_id = base64_decode($request->id);

        if($request->isMethod('get'))
        {
            $address_ver  = DB::table('address_verifications')->where(['jaf_id'=>$jaf_id])->first();

            if($address_ver!=NULL && $address_ver->status==1)
            {
                return view('thankyou-address-verification-form');
            }

            $jaf_data=DB::table('jaf_form_data as jf')
                ->select('jf.*','s.name as service_name','s.verification_type','s.type_name')
                ->join('services as s','jf.service_id','=','s.id')
                ->where(['jf.id'=>$jaf_id])
                ->first();

            return view('address-verification-form',compact('jaf_data','address_ver'));
        }

        //dd($request->all());

        $custom=[
            'address_type.required' => 'Select the Address Type..',
            'front_door.required'  => 'The front door field is required.',
            'front_door_cam.required'  => 'The front door field is required.',
            'profile_photo.required'  => 'The profile photo field is required.',
            'profile_photo_cam.required'  => 'The profile photo field is required.',
            'id_proof.required'  => 'The id proof field is required.',
            'id_proof_cam.required'  => 'The id proof field is required.',
            'nearest_landmark.required'  => 'The nearest landmark field is required.',
            'nearest_landmark_cam.required'  => 'The nearest landmark field is required.',
        ];

        $validator = Validator::make($request->all(), [
            'phone_number'  => 'nullable',
            'email_address'  => 'nullable|email:rfc,dns',
            'street_address'=> 'nullable',
            'house_building'=> 'nullable',
            'address_type'      => 'required',
            'address'           => 'nullable',
            'nature_of_residence' => 'required',
            'period_stay_from'         => 'required|date|before_or_equal:'.date('d-m-Y'),
            'period_stay_to'         => 'required|date|before_or_equal:'.date('d-m-Y'),
            'verifier_name'         => 'required',
            'relation_with_verifier' => 'required',
            's_width'       => 'required|integer|min:0',
            's_height'       => 'required|integer|min:0',
            'front_door'    => 'nullable|mimes:jpg,jpeg,png,svg|max:200000',
            'front_door_cam'    => 'nullable',
            'profile_photo' => 'nullable|mimes:jpg,jpeg,png,svg|max:200000',
            'profile_photo_cam'    => 'nullable',
            'id_proof'      => 'nullable|mimes:jpg,jpeg,png,svg|max:200000',
            'id_proof_cam'    => 'nullable',
            'nearest_landmark'  => 'nullable|mimes:jpg,jpeg,png,svg|max:200000',
            'nearest_landmark_cam'    => 'nullable',
            'signature'         => 'required',
        ],$custom);

        $validator->sometimes('front_door', 'required', function ($request) {
            return $request->s_width <= 991;
        });

        $validator->sometimes('front_door_cam', 'required', function ($request) {
            return $request->s_width > 991;
        });

        $validator->sometimes('profile_photo', 'required', function ($request) {
            return $request->s_width <= 991;
        });

        $validator->sometimes('profile_photo_cam', 'required', function ($request) {
            return $request->s_width > 991;
        });

        $validator->sometimes('id_proof', 'required', function ($request) {
            return $request->s_width <= 991;
        });

        $validator->sometimes('id_proof_cam', 'required', function ($request) {
            return $request->s_width > 991;
        });

        $validator->sometimes('nearest_landmark', 'required', function ($request) {
            return $request->s_width <= 991;
        });

        $validator->sometimes('nearest_landmark_cam', 'required', function ($request) {
            return $request->s_width > 991;
        });

        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        $phone_number  = $request->get('phone_number');
        $email_address  = $request->get('email_address');
        $zipcode  = $request->get('zipcode');
        $address_type  = $request->get('address_type');
        $address  = $request->get('address');
        $nature_of_residence  = $request->get('nature_of_residence');
        $period_stay_from  = $request->get('period_stay_from')!=NULL ? date('Y-m-d',strtotime($request->get('period_stay_from'))) : NULL;
        $period_stay_to  = $request->get('period_stay_to')!=NULL ? date('Y-m-d',strtotime($request->get('period_stay_to'))) : NULL;
        $verifier_name  = $request->get('verifier_name');
        $relation_with_verifier  = $request->get('relation_with_verifier');
        
        $geo_latitude       = $request->get('geo_latitude');
        $geo_longitude      = $request->get('geo_longitude');
        $geo_full_address   = $request->get('geo_address');

        $address_ver  = DB::table('address_verifications')->where(['jaf_id'=>$jaf_id])->first();

        if($address_ver!=NULL && $address_ver->status==1)
        {
            return response()->json([
                                        'success' => false,
                                        'errors'=> ['all'=>'Address Verification Form Has Already Been Submitted, Try Again with Some Other Check !!']
                                    ]);
        }

        DB::beginTransaction();
        try{

            $jaf_data=DB::table('jaf_form_data as jf')
                ->select('jf.*','s.name as service_name','s.verification_type','s.type_name','u.first_name','u.last_name','u.parent_id')
                ->join('services as s','jf.service_id','=','s.id')
                ->join('users as u','u.id','=','jf.candidate_id')
                ->where(['jf.id'=>$jaf_id])
                ->first();

            // Signature

            $draw_signature = NULL;

            if($request->has('signature') && $request->signature!=null)
            {
                $folderPath = public_path('uploads/candidate-signature/'); 

                if(!File::exists($folderPath))
                {
                    File::makeDirectory($folderPath, $mode = 0777, true, true);
                }
            
                $image_parts = explode(";base64,", $request->signature);
                      
                $image_type_aux = explode("image/", $image_parts[0]);
                // dd($image_type_aux);
                $image_type = $image_type_aux[1];
                   
                $image_base64 = base64_decode($image_parts[1]);

                $draw_signature =   time() . '.'.$image_type;
                $file = $folderPath . $draw_signature;
                file_put_contents($file, $image_base64);
            }

            if($address_ver!=NULL)
            {
                $address_data = [
                    'first_name'   => $jaf_data->first_name,
                    'last_name'   => $jaf_data->last_name,
                    'email'         => $email_address,
                    'phone'         => $phone_number,
                    'full_address' =>$address,
                    'nature_of_residence' =>$nature_of_residence,
                    'period_stay_from'  => $period_stay_from,
                    'period_stay_to'   => $period_stay_to,
                    'verifier_name'   => $verifier_name,
                    'relation_with_verifier' => $relation_with_verifier,
                    'zipcode'  =>$zipcode,
                    'latitude'      =>$geo_latitude,
                    'longitude'     =>$geo_longitude,
                    'geo_address'   =>$geo_full_address,
                    'geo_latitude'  =>$geo_latitude,
                    'geo_longitude' =>$geo_longitude,
                    'address_type'  =>$address_type,
                    'signature'         =>$draw_signature,
                    'signature_latitude' => $geo_latitude,
                    'signature_longitude' => $geo_longitude,
                    'status'  => 1,
                    'updated_at'    =>date('Y-m-d H:i:s')    
                ];

                DB::table('address_verifications')->where('id',$address_ver->id)->update($address_data);
            }
            else
            {
                $address_data = [
                    'first_name'   => $jaf_data->first_name,
                    'last_name'   => $jaf_data->last_name,
                    'business_id'  =>  $jaf_data->business_id,
                    'candidate_id'  =>$jaf_data->candidate_id,
                    'jaf_id'        => $jaf_data->id,
                    'email'         => $email_address,
                    'phone'         => $phone_number,
                    'full_address' =>$address,
                    'nature_of_residence' =>$nature_of_residence,
                    'period_stay_from'  => $period_stay_from,
                    'period_stay_to'   => $period_stay_to,
                    'verifier_name'   => $verifier_name,
                    'relation_with_verifier' => $relation_with_verifier,
                    'zipcode'  =>$zipcode,
                    'latitude'      =>$geo_latitude,
                    'longitude'     =>$geo_longitude,
                    'geo_address'   =>$geo_full_address,
                    'geo_latitude'  =>$geo_latitude,
                    'geo_longitude' =>$geo_longitude,
                    'address_type'  =>$address_type,
                    'signature'         =>$draw_signature,
                    'signature_latitude' => $geo_latitude,
                    'signature_longitude' => $geo_longitude,
                    'status'  => 1,
                    'created_at'    =>date('Y-m-d H:i:s')    
                ];
    
                DB::table('address_verifications')
                ->insert($address_data);
            }

            


            // Check Screen Width for uploading attachements by Camera or File
            if($request->s_width > 991)
            {
                if($request->has('front_door_cam') && $request->front_door_cam!=null)
                {
                    $folderPath = public_path('/uploads/candidate-front-door/');

                    if(!File::exists($folderPath))
                    {
                        File::makeDirectory($folderPath, $mode = 0777, true, true);
                    }
                
                    $image_parts = explode(";base64,", $request->signature);
                        
                    $image_type_aux = explode("image/", $image_parts[0]);
                    // dd($image_type_aux);
                    $image_type = $image_type_aux[1];
                    
                    $image_base64 = base64_decode($image_parts[1]);

                    $image_file =   time() . '.'.$image_type;
                    $file = $folderPath . $image_file;
                    file_put_contents($file, $image_base64);

                    $file_id=DB::table('address_verification_file_uploads')->insertGetID([
                        'business_id' =>$jaf_data->business_id,
                        'candidate_id' => $jaf_data->candidate_id,
                        'jaf_id' => $jaf_data->id,
                        'file_type' => 'front_door',
                        'image' => $image_file,
                        'latitude' => $geo_latitude,
                        'longitude' => $geo_longitude,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }

                if($request->has('profile_photo_cam') && $request->profile_photo_cam!=null)
                {
                    $folderPath = public_path('/uploads/candidate-selfie/');

                    if(!File::exists($folderPath))
                    {
                        File::makeDirectory($folderPath, $mode = 0777, true, true);
                    }
                
                    $image_parts = explode(";base64,", $request->signature);
                        
                    $image_type_aux = explode("image/", $image_parts[0]);
                    // dd($image_type_aux);
                    $image_type = $image_type_aux[1];
                    
                    $image_base64 = base64_decode($image_parts[1]);

                    $image_file =   time() . '.'.$image_type;
                    $file = $folderPath . $image_file;
                    file_put_contents($file, $image_base64);

                    $file_id=DB::table('address_verification_file_uploads')->insertGetID([
                        'business_id' =>$jaf_data->business_id,
                        'candidate_id' => $jaf_data->candidate_id,
                        'jaf_id' => $jaf_data->id,
                        'file_type' => 'profile_photo',
                        'image' => $image_file,
                        'latitude' => $geo_latitude,
                        'longitude' => $geo_longitude,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }

                if($request->has('id_proof_cam') && $request->id_proof_cam!=null)
                {
                    $folderPath = public_path('/uploads/address-proof/');

                    if(!File::exists($folderPath))
                    {
                        File::makeDirectory($folderPath, $mode = 0777, true, true);
                    }
                
                    $image_parts = explode(";base64,", $request->signature);
                        
                    $image_type_aux = explode("image/", $image_parts[0]);
                    // dd($image_type_aux);
                    $image_type = $image_type_aux[1];
                    
                    $image_base64 = base64_decode($image_parts[1]);

                    $image_file =   time() . '.'.$image_type;
                    $file = $folderPath . $image_file;
                    file_put_contents($file, $image_base64);

                    $file_id=DB::table('address_verification_file_uploads')->insertGetID([
                        'business_id' =>$jaf_data->business_id,
                        'candidate_id' => $jaf_data->candidate_id,
                        'jaf_id' => $jaf_data->id,
                        'file_type' => 'address_proof',
                        'image' => $image_file,
                        'latitude' => $geo_latitude,
                        'longitude' => $geo_longitude,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }

                if($request->has('nearest_landmark_cam') && $request->nearest_landmark_cam!=null)
                {
                    $destinationPath = public_path('/uploads/candidate-location/');

                    if(!File::exists($folderPath))
                    {
                        File::makeDirectory($folderPath, $mode = 0777, true, true);
                    }
                
                    $image_parts = explode(";base64,", $request->signature);
                        
                    $image_type_aux = explode("image/", $image_parts[0]);
                    // dd($image_type_aux);
                    $image_type = $image_type_aux[1];
                    
                    $image_base64 = base64_decode($image_parts[1]);

                    $image_file =   time() . '.'.$image_type;
                    $file = $folderPath . $image_file;
                    file_put_contents($file, $image_base64);

                    $file_id=DB::table('address_verification_file_uploads')->insertGetID([
                        'business_id' =>$jaf_data->business_id,
                        'candidate_id' => $jaf_data->candidate_id,
                        'jaf_id' => $jaf_data->id,
                        'file_type' => 'location',
                        'image' => $image_file,
                        'latitude' => $geo_latitude,
                        'longitude' => $geo_longitude,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);

                }
            }
            else
            {
                // Front Door
                if($files = $request->file('front_door'))
                {
                    $destinationPath = public_path('/uploads/candidate-front-door/');
                    
                    if(!File::exists($destinationPath))
                    {
                        File::makeDirectory($destinationPath, $mode = 0777, true, true);
                    }
    
                    $image = time().$request->file('front_door')->getClientOriginalName();
    
                    $files->move($destinationPath, $image);
    
                    $file_id=DB::table('address_verification_file_uploads')->insertGetID([
                        'business_id' =>$jaf_data->business_id,
                        'candidate_id' => $jaf_data->candidate_id,
                        'jaf_id' => $jaf_data->id,
                        'file_type' => 'front_door',
                        'image' => $image,
                        'latitude' => $geo_latitude,
                        'longitude' => $geo_longitude,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }
    
                // Profile Photo
                if($files = $request->file('profile_photo'))
                {
                    $destinationPath = public_path('/uploads/candidate-selfie/');
                    
                    if(!File::exists($destinationPath))
                    {
                        File::makeDirectory($destinationPath, $mode = 0777, true, true);
                    }
    
                    $image = time().$request->file('profile_photo')->getClientOriginalName();
    
                    $files->move($destinationPath, $image);
    
                    $file_id=DB::table('address_verification_file_uploads')->insertGetID([
                        'business_id' =>$jaf_data->business_id,
                        'candidate_id' => $jaf_data->candidate_id,
                        'jaf_id' => $jaf_data->id,
                        'file_type' => 'profile_photo',
                        'image' => $image,
                        'latitude' => $geo_latitude,
                        'longitude' => $geo_longitude,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }

                // Address Proof
                if($files = $request->file('id_proof'))
                {
                    $destinationPath = public_path('/uploads/address-proof/');
                    
                    if(!File::exists($destinationPath))
                    {
                        File::makeDirectory($destinationPath, $mode = 0777, true, true);
                    }
    
                    $image = time().$request->file('id_proof')->getClientOriginalName();
    
                    $files->move($destinationPath, $image);
    
                    $file_id=DB::table('address_verification_file_uploads')->insertGetID([
                        'business_id' =>$jaf_data->business_id,
                        'candidate_id' => $jaf_data->candidate_id,
                        'jaf_id' => $jaf_data->id,
                        'file_type' => 'address_proof',
                        'image' => $image,
                        'latitude' => $geo_latitude,
                        'longitude' => $geo_longitude,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }

                // Nearest Landmark
                if($files = $request->file('nearest_landmark'))
                {
                    $destinationPath = public_path('/uploads/candidate-location/');
                    
                    if(!File::exists($destinationPath))
                    {
                        File::makeDirectory($destinationPath, $mode = 0777, true, true);
                    }
    
                    $image = time().$request->file('nearest_landmark')->getClientOriginalName();
    
                    $files->move($destinationPath, $image);
    
                    $file_id=DB::table('address_verification_file_uploads')->insertGetID([
                        'business_id' =>$jaf_data->business_id,
                        'candidate_id' => $jaf_data->candidate_id,
                        'jaf_id' => $jaf_data->id,
                        'file_type' => 'location',
                        'image' => $image,
                        'latitude' => $geo_latitude,
                        'longitude' => $geo_longitude,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }

             

            $kams = DB::table('users as u')
                    ->select('u.*')
                    ->join('key_account_managers as k','k.user_id','=','u.id')
                    ->where('k.business_id',$jaf_data->business_id)
                    ->get();

            if(count($kams)>0)
            {
                $address_ver  = DB::table('address_verifications')->where(['jaf_id'=>$jaf_data->id])->first();

                $sender = DB::table('users')->where('id',$jaf_data->parent_id)->first();

                $candidate = DB::table('users')->where('id',$jaf_data->candidate_id)->first();
                
                foreach($kams as $kam)
                {
                    $email = $kam->email;
                    $name = $kam->first_name;

                    $data = array('name'=>$name,'email'=>$email,'sender'=>$sender,'candidate'=>$candidate,'jaf_form'=>$jaf_data,'address_ver'=>$address_ver);

                    Mail::send(['html'=>'mails.address-save-notify'], $data, function($message) use($email,$name) {
                        $message->to($email, $name)->subject
                            ('Clobminds System - Address Verification');
                        $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                    });
                }
            }

            DB::commit();
            return response()->json([
                'success' =>true,
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }    

    }
   
   public function errorGeoLocation(Request $request)
    {
        $viewRender = view('error-geolocation')->render();
        return response()->json(array('success' => true, 'html'=>$viewRender));
    }

    public function insuffClear(Request $request){
        $jaf_id=base64_decode($request->id);
        $redirectuser=DB::table('jaf_form_data')->where(['id'=> $jaf_id])->first();
        $service_name=Helper::get_service_name($redirectuser->service_id);
        if($redirectuser->is_insufficiency==0){
            return view('main-web.thank-you');
       
        }
      
        return view('admin.candidates.insuff-clear',compact('jaf_id','service_name'));
    }
    
    public function clearJafInsuff(Request $request){
       
        $candidate = DB::table('jaf_form_data as jf')
                    ->select('jf.*','u.parent_id')
                    ->join('users as u','u.id','=','jf.candidate_id')
                    ->where(['jf.id'=>$request->jaf_id])
                    ->first();
        $candidate_id =$candidate->candidate_id; 
        $service_id   = $candidate->service_id; 
        $item_id      = $request->jaf_id; 
        $parent_id=$candidate->parent_id;

        $business_id = $candidate->business_id;
        $user_id=$candidate_id;
        $is_updated= FALSE;
        $s3_config=NULL;
        $c_file_platform = 'web';
        $attach_on_select=[];
        $allowedextension=['jpg','jpeg','png','svg','pdf'];
        $zipname="";
        DB::beginTransaction();
        try
        {
            if($request->hasFile('attachment') && $request->file('attachment') !="")
            {
                $filePath = public_path('/uploads/clear-insuff/'); 
                $fileimgpath=public_path('/uploads/jaf-files/');
                $filereportpath=public_path('/uploads/report-files/');
                $files= $request->file('attachment');
                foreach($files as $file)
                {
                        $extension = $file->getClientOriginalExtension();

                        $check = in_array($extension,$allowedextension);

                        $file_size = number_format(File::size($file) / 1048576, 2);

                        if(!$check)
                        {
                            return response()->json([
                            'fail' => true,
                            'errors' => ['attachment' => 'Only jpg,jpeg,png,pdf are allowed !'],
                            'error_type'=>'validation'
                            ]);                        
                        }

                        if($file_size > 10)
                        {
                            return response()->json([
                              'fail' => true,
                              'error_type'=>'validation',
                              'errors' => ['attachment' => 'The document size must be less than only 10mb Upload !'],
                            ]);                        
                        }
                }
            
                $zipname = 'clear-insuff-'.date('Ymdhis').'.zip';
                $zip = new \ZipArchive();      
                $zip->open(public_path().'/uploads/clear-insuff/'.$zipname, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
            
                foreach($files as $file)
                {
                    

                    $file_data = $file->getClientOriginalName();
                    $no = str_shuffle('123456789023456789034567890456789905678906789078908909000987654321987654321876543217654321654321543214321321211');
                    $random_no = substr($no, 0, 2);
                    $tmp_data = $random_no.'.'.$file_data;
                    $data = $file->move($filePath, $tmp_data);       
                    $attach_on_select[]=$tmp_data;
                    
                    copy($filePath.$tmp_data,$fileimgpath.$tmp_data);
                    copy($filePath.$tmp_data,$filereportpath.$tmp_data);

                    $path=public_path()."/uploads/clear-insuff/".$tmp_data;
                    $zip->addFile($path, '/clear-insuff/'.basename($path));  

                    $jaf_data=DB::table('jaf_form_data')->where(['id'=>$item_id])->first();
                    $attachedFiles = DB::table('service_attachment_types as sat')
                    ->select('sat.*')                        
                    ->where(['sat.service_id'=>$jaf_data->service_id,'sat.attachment_name'=>'Other']) 
                    ->first();
                        $attachmentdata=[
                            'jaf_id'=>$jaf_data->id,
                            'candidate_id'=>$jaf_data->candidate_id,
                            'business_id'=>$jaf_data->business_id,
                            'file_name'=>$tmp_data,
                            'attachment_type'=>'main',
                            'created_by'=>$jaf_data->business_id,
                            'service_attachment_id'=> $attachedFiles->id,
                            'service_attachment_name'=>'Insufficiency Cleared Document'
                        ];
                        DB::table('jaf_item_attachments')->insert($attachmentdata);
                    
                        $reportdata=DB::table('report_items as ri')
                                        ->join('jaf_item_attachments as jia','jia.jaf_id','=','ri.jaf_id')
                                        ->where(['ri.jaf_id'=>$item_id ])
                                        ->first();
                        $report_item=[
                            'report_id'=>$reportdata->report_id,
                            'report_item_id'=>$reportdata->id,
                            'jaf_item_attachment_id'=>$reportdata->id,
                            'file_name'=>$tmp_data,
                            'attachment_type'=>'main',
                            'created_by'=>$reportdata->business_id,
                            'service_attachment_id'=>$attachedFiles->id,
                            'service_attachment_name'=>'Insufficiency Cleared Document'

                        ];
                        DB::table('report_item_attachments')->insert( $report_item);


                    //     $raiseuserDetail=DB::table('insufficiency_logs as inlg')
                    //     ->select('u.name','u.email','inlg.*')
                    //     ->join('users as u','u.id','=','inlg.created_by')
                    //     ->where(['inlg.jaf_form_data_id'=>$jaf_data->id,'inlg.status'=>'raised'])
                    //     ->latest('inlg.id')->first();
                    //     $candidates=DB::table('users as u')
                    //     ->select('u.*','j.business_id as coc_id','j.id as jaf_id','v.created_at as insuff_clear_date','v.created_by as insuff_clear_by','v.item_number','v.notes','s.verification_type','s.name as service_name','v.business_id as cust_id','v.attachment','v.updated_at','v.updated_by')
                    //     ->join('jaf_form_data as j','u.id','=','j.candidate_id')
                    //     ->join('verification_insufficiency as v','v.jaf_form_data_id','=','j.id')
                    //     ->join('services as s','s.id','=','v.service_id')
                    //     ->where(['u.user_type'=>'candidate','j.id'=>$$item_id,'v.status'=>'removed','v.id'=>$raiseuserDetail->id])
                    //     ->first();
                    //     dd($candidates);
                    //     $sender = DB::table('users')->where(['id'=>$raiseuserDetail->parent_id])->first();
                        
                    //     $name=$raiseuserDetail->name;
                    //     $email=$raiseuserDetail->email;
                    //     $msg= "Insufficiency Cleared By Candidate";
                    //     $data  = array('name'=>$name,'email'=>$email,'msg'=>$msg,'link'=>'','candidate'=>$candidates,'sender'=>$sender);
                    //     Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email,$name) {
                    //     $message->to($email, $name)->subject
                    //         ('Clobminds Pvt Ltd - Insufficiency Notification');
                    //     $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                    // }); 
                }

                $zip->close();
            }
        
        $s3_config = S3ConfigTrait::s3Config();

        
        $jaf_data=DB::table('jaf_form_data')->where(['id'=>$item_id])->first();
        DB::table('jaf_form_data')->where(['id'=>$item_id])
                                ->update(['is_insufficiency'=>'0','clear_insuff_notes'=>$request->comments,'clear_insuff_attachment' => $zipname!=NULL?$zipname:NULL,'clear_insuff_attachment_file_platform'=>$c_file_platform,'verification_status'=>'success','verified_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')]); 
        $is_updated=TRUE;
          $ver_insuff=DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$item_id,'service_id'=>$service_id,'status'=>'removed'])->first();
          if($ver_insuff!=NULL)
          {
              $ver_insuff_data=[
                'notes' => $request->comments,
                'updated_by' =>  $candidate_id,
                'attachment'  => $zipname!=""?$zipname:NULL, 
                'attachment_file_platform' => $c_file_platform,
                'updated_at' => date('Y-m-d H:i:s')
              ];

              DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$item_id,'service_id'=>$service_id,'status'=>'removed'])->update($ver_insuff_data);

              $ver_id=$ver_insuff->id;
          }
          else
          {
           
              $ver_insuff_data=[
                'parent_id' => $parent_id,
                'business_id' => $business_id,
                'coc_id' => $jaf_data->business_id,
                'candidate_id' => $candidate_id,
                'service_id'  => $jaf_data->service_id,
                'jaf_form_data_id' => $jaf_data->id,
                'item_number' => $jaf_data->check_item_number,
                'activity_type'=> 'jaf-insuff',
                'status'=>'removed',
                'notes' => $request->comments,
                'attachment' => $zipname!=NULL?$zipname:NULL,
                'attachment_file_platform' => $c_file_platform,
                'created_by'   => $candidate_id,
                'created_at'   => date('Y-m-d H:i:s'),
            ];
      
            $ver_id=DB::table('verification_insufficiency')->insertGetId($ver_insuff_data);
          }

          $insuff_log_data=[
            'parent_id' => $parent_id,
            'business_id' => $business_id,
            'coc_id' => $jaf_data->business_id,
            'candidate_id' => $candidate_id,
            'service_id'  => $service_id,
            'jaf_form_data_id' => $jaf_data->id,
            'item_number' => $jaf_data->check_item_number,
            'activity_type'=> 'jaf-insuff',
            'status'=>'removed',
            'notes' => $request->comments,
            'created_by'   => $candidate_id,
            'attachment'  => $zipname!=""?$zipname:NULL, 
            'attachment_file_platform' => $c_file_platform,
            'created_at'   => date('Y-m-d H:i:s'),
          ];
    
          DB::table('insufficiency_logs')->insert($insuff_log_data);

          if(count($attach_on_select)>0)
          {
              $file_data=DB::table('insufficiency_attachments')->where(['jaf_form_data_id'=>$item_id,'service_id'=>$service_id,'status'=>'removed'])->get();

              if(count($file_data)>0)
              {
                  $path=public_path().'/uploads/clear-insuff/';
                  foreach($file_data as $file)
                  {
                      if(File::exists($path.$file->file_name))
                      {
                          File::delete($path.$file->file_name);
                      }
                  }

                  DB::table('insufficiency_attachments')->where(['jaf_form_data_id'=>$item_id,'service_id'=>$service_id,'status'=>'removed'])->delete();

              }

              $i=0;
              $file_platform = 'web';

              if($s3_config!=NULL)
              {

                $path=public_path().'/uploads/clear-insuff/';
                
                $s3filePath = 'uploads/clear-insuff/';

                if(!Storage::disk('s3')->exists($s3filePath))
                {
                    Storage::disk('s3')->makeDirectory($s3filePath,0777, true, true);
                }

                foreach($attach_on_select as $item)
                {

                  $file_platform = 'web';

                  if(File::exists($path.$attach_on_select[$i]))
                  {
                    $file_platform = 's3';
                    $file = Helper::createFileObject($path.$attach_on_select[$i]);

                    Storage::disk('s3')->put($s3filePath.$attach_on_select[$i],file_get_contents($file));

                    File::delete($path.$attach_on_select[$i]);
                  }

                  $insuff_file=[
                    'parent_id' => $parent_id,
                    'business_id' => $business_id,
                    'coc_id' => $jaf_data->business_id,
                    'candidate_id' => $candidate_id,
                    'service_id'  => $service_id,
                    'jaf_form_data_id' => $jaf_data->id,
                    'item_number' => $jaf_data->check_item_number,
                    'status'=>'removed',
                    'file_name' => $attach_on_select[$i],
                    'file_platform' => $file_platform,
                    'created_by'   => $candidate_id,
                    'created_at'   => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                  ];
            
                  $file_id = DB::table('insufficiency_attachments')->insertGetId($insuff_file);

                  $i++;
                }

                if(File::exists($path.'tmp-files/'))
                {
                    File::cleanDirectory($path.'tmp-files/');
                }

              } 
              else
              {
                  foreach($attach_on_select as $item)
                  {

                    $insuff_file=[
                      'parent_id' => $parent_id,
                      'business_id' => $business_id,
                      'coc_id' => $jaf_data->business_id,
                      'candidate_id' => $candidate_id,
                      'service_id'  => $service_id,
                      'jaf_form_data_id' => $jaf_data->id,
                      'item_number' => $jaf_data->check_item_number,
                      'status'=>'removed',
                      'file_name' => $attach_on_select[$i],
                      'file_platform' => $file_platform,
                      'created_by'   => $candidate_id,
                      'created_at'   => date('Y-m-d H:i:s'),
                      'updated_at' => date('Y-m-d H:i:s')
                    ];
              
                    $file_id = DB::table('insufficiency_attachments')->insertGetId($insuff_file);

                    $i++;
                  }
              }
          }

          $ver_insuff=DB::table('verification_insufficiency')->where(['id'=>$ver_id])->first();
         $verinsuff_Data=DB::table('insufficiency_logs')->where(['jaf_form_data_id'=> $jaf_data->id,'status'=>'raised'])->latest()->first();
          $candidates=DB::table('users as u')
              ->select('u.*','j.business_id as coc_id','j.id as jaf_id','v.created_at as insuff_clear_date','v.created_by as insuff_clear_by','v.item_number','v.notes','s.verification_type','s.name as service_name','v.business_id as cust_id','v.attachment','v.updated_at','v.updated_by')
              ->join('jaf_form_data as j','u.id','=','j.candidate_id')
              ->join('verification_insufficiency as v','v.jaf_form_data_id','=','j.id')
              ->join('services as s','s.id','=','v.service_id')
              ->where(['u.user_type'=>'candidate','j.id'=>$item_id,'v.status'=>'removed','v.id'=>$ver_insuff->id])
              ->first();

            if($candidates!=NULL)
            {
              // $client=DB::table('users')->where(['id'=>$candidates->coc_id])->first();
              // $name = $client->name;
              // $email = $client->email;
              // $msg= "Insufficiency Cleared For Candidate";
              // $sender = DB::table('users')->where(['id'=>$candidates->parent_id])->first();
              // if($candidates->attachment!=NULL)
              // {
              //   $url = url('/').'/uploads/clear-insuff/'.$zipname;

              //   if($s3_config!=NULL)
              //   {
              //     $filePath = 'uploads/clear-insuff/';

              //     $disk = Storage::disk('s3');

              //     $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
              //         'Bucket'                     => Config::get('filesystems.disks.s3.bucket'),
              //         'Key'                        => $filePath.$zipname,
              //         'ResponseContentDisposition' => 'attachment;'//for download
              //     ]);

              //     $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

              //     $url = (string)$req->getUri();
              //   }

              //   $data  = array('name'=>$name,'email'=>$email,'msg'=>$msg,'link'=>$url,'candidate'=>$candidates,'sender'=>$sender);
              // }
              // else
              //   $data  = array('name'=>$name,'email'=>$email,'msg'=>$msg,'link'=>'','candidate'=>$candidates,'sender'=>$sender);

              // Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email,$name) {
              //   $message->to($email, $name)->subject
              //       ('Clobminds Pvt Ltd - Insufficiency Notification');
              //   $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
              // });

             
                
                    $user_data=DB::table('users')->where(['id'=>$verinsuff_Data->created_by])->first();
                    $name1 = $user_data->name;
                    $email1 = $user_data->email;
                    $msg= "Insufficiency Cleared For Candidate";
                    $sender = DB::table('users')->where(['id'=>$candidates->parent_id])->first();
                    
                      $data  = array('name'=>$name1,'email'=>$email1,'msg'=>$msg,'link'=>'','candidate'=>$candidates,'sender'=>$sender);

                    Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email1,$name1) {
                        $message->to($email1, $name1)->subject
                            ('Clobminds Pvt Ltd- Insufficiency Notification');
                        $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                    });



                //   if($ver_insuff_data!=NULL)
                //   {
                //       $user_data=DB::table('users')->where(['id'=>$ver_insuff_data->created_by])->first();
                //       $name = $user_data->name;
                //       $email = $user_data->email;
                //       $msg= "Insufficiency Cleared For Candidate";
                //       $sender = DB::table('users')->where(['id'=>$candidates->parent_id])->first();
                //       if($candidates->attachment!=NULL)
                //       {
                //         $url = url('/').'/uploads/clear-insuff/'.$zipname;

                //         if($s3_config!=NULL)
                //         {
                //           $filePath = 'uploads/clear-insuff/';

                //           $disk = Storage::disk('s3');

                //           $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                //               'Bucket'                     => Config::get('filesystems.disks.s3.bucket'),
                //               'Key'                        => $filePath.$zipname,
                //               'ResponseContentDisposition' => 'attachment;'//for download
                //           ]);

                //           $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

                //           $url = (string)$req->getUri();
                //         }

                //         $data  = array('name'=>$name,'email'=>$email,'msg'=>$msg,'link'=>$url,'candidate'=>$candidates,'sender'=>$sender);
                //       }
                //       else
                //         $data  = array('name'=>$name,'email'=>$email,'msg'=>$msg,'link'=>'','candidate'=>$candidates,'sender'=>$sender);
                        
                //       Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email,$name) {
                //         $message->to($email, $name)->subject
                //             ('Clobminds Pvt Ltd- Insufficiency Notification');
                //         $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                //       }); 
                //   }

            }

          if($is_updated){  
            //generate report here 
            // $this->generateCandidateReport($candidate_id);
            DB::commit();
            return response()->json([
            'fail' => false,
            'status'=>'ok',
            'message' => 'updated',                
            ], 200);
          }
          else{
              DB::commit();
              return response()->json([
              'fail' => true,
              'status' =>'no',
              ], 200);
          }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }   
    
    }

    

    
}

