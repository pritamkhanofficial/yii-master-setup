<?php
namespace app\components;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use app\models\ErrorLogs;
use app\models\UsersActivity;
use yii\web\ErrorAction;
use yii\web\NotFoundHttpException;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use  yii\helpers\Url;
use yii\web\Cookie;

// +-----------------------------------------------
// | @author          : Pritam Khan
// | @created_at      : 09/04/2023
// | @updated_by      : 
// | @Version (branch): FEAT-001-2023
// +-----------------------------------------------
// +---------------------------

class Helpers extends Component
{
    public function generateToken($is_token= true, $length = 32)
    {
        //return md5(time().mt_rand()).uniqid(); for custom token
        if($is_token){
            $token = 'token_'.Yii::$app->security->generateRandomString($length);
        }else{
            $token = Yii::$app->security->generateRandomString($length);
        }
        
        return $token;
    }

    public function generateFlash($alert = array())
    {
        if(array_key_exists('type',$alert)){
          Yii::$app->session->setFlash('type', $alert['type']);
        }else{
          Yii::$app->session->setFlash('type', "success");
        }

        if(array_key_exists('title',$alert)){
          Yii::$app->session->setFlash('title', $alert['title']);
        }else{
          Yii::$app->session->setFlash('title', "Success");
        }
        if(array_key_exists('message',$alert)){
          Yii::$app->session->setFlash('message', $alert['message']);
        }else{
          Yii::$app->session->setFlash('message', NULL);
        }
    }

    public function generateHash($string = NULL)
    {
      return Yii::$app->security->generatePasswordHash($string);
    }

    public function getStatus($status = true)
    {
      if (!is_null($status)) {
        if(is_numeric($status) && !is_float($status)){
            if($status){
              return 'Active';
            }elseif (!$status) {
              return 'Deactive';
            }
        }
      }
      return 'Active';
    }

    public function logData()
    {
        $model = new ErrorLogs();
        $controller = Yii::$app->controller->id;
        $action = Yii::$app->controller->action->id;
        $url = Yii::$app->request->hostInfo . Yii::$app->request->url;
        $ipaddress = Yii::$app->request->userIP;
        $agent = Yii::$app->request->getUserAgent();
        $exception = Yii::$app->errorHandler->exception;
        $log_data = NULL;       
        if($exception){
            if(empty($exception->statusCode)){
                $log_data['statusCode'] =  'Not Found';
            }else{
                $log_data['statusCode'] =  $exception->statusCode;

            }

            $log_data['getMessage'] =  $exception->getMessage();
            $log_data = json_encode($log_data);
            
        }       
        
        if (Yii::$app->user->isGuest) {
            $created_by = 1;
        }else{
            $created_by = Yii::$app->user->id;
        }
        $logdata = ['ErrorLogs'=>[
            'log_token' => $this->generateToken(),
            'agent' => $agent,
            'url' => $url,
            'controller' => $controller,
            'action' => $action,
            'device_ip' => $ipaddress,
            'log_message' => $log_data,
            'created_at' => $this->dbDateFormat(),
            'created_by' => $created_by,
        ]];
        $model->load($logdata);
        if ($model->save()) {
            return "Data Save";
        }else{
            echo "<pre>";
            print_r($model->getErrors());
            exit;
        }
    }

    public function usersActivity($rememberMe = false)
    {
        $action = Yii::$app->controller->action->id;
        $ipaddress = Yii::$app->request->userIP;
        if($action === 'index'){
            $model = new UsersActivity();
            $agent = Yii::$app->request->getUserAgent();
            if (Yii::$app->user->isGuest) {
                $user_id = 1;
            }else{
                $user_id = Yii::$app->user->id;
            }
            $data = ['UsersActivity'=>[
                'user_id' => $user_id,
                'login' => $this->dbDateFormat(),
                'logout' => $this->dbDateFormat(),
                'ip' => $ipaddress,
                'agent' => $agent,
                'created_at' => $this->dbDateFormat(),
            ]];
            $model->load($data);
            if ($model->save()) {
                $activity_id = Yii::$app->db->getLastInsertID();
		            Yii::$app->session->set('activity_id',$activity_id); 

                if($rememberMe){
                  $cookie = new Cookie([
                    'name' => 'activity_id',
                    'value' => $activity_id,
                    'expire' => time() + 3600 * 24 * 30, // Set the expiration time for the cookie like 30 days
                  ]);
                  Yii::$app->getResponse()->getCookies()->add($cookie);
                }            
                $data = [
                    'last_ip'=>$ipaddress,
                    'last_login'=>$this->dbDateFormat(),
                    'is_online'=>1,
                ];
                $result = Yii::$app->db->createCommand()
                ->update('users', $data, ['id'=>$user_id])
                ->execute();
                if ($result) {
                    return true;
                }
            }else{
                echo "<pre>";
                print_r($model->getErrors());
                exit;
            }
        }else if($action === 'logout'){

          
            $user_id = Yii::$app->user->id;
            $id = Yii::$app->session->get('activity_id');
            if(is_null($id)){
              $id = Yii::$app->getRequest()->getCookies()->get('activity_id');
            }
            $model = $this->findModel($id);
            $data = ['UsersActivity'=>[
                'logout' => $this->dbDateFormat(),
                'ip' => $ipaddress,
                'updated_at' => $this->dbDateFormat(),
            ]];
            $model->load($data);
            if ($model->save()) {
                $data = [
                    'last_ip'=>$ipaddress,
                    'last_logout'=>$this->dbDateFormat(),
                    'is_online'=>0,
                ];
                $result = Yii::$app->db->createCommand()
                ->update('users', $data, ['id'=>$user_id])
                ->execute();
                if ($result) {
                    return true;
                }
            }else{
                echo "<pre>";
                print_r($model->getErrors());
                exit;
            }
        }
        
    }

    public function dbDateFormat()
    {
        return Yii::$app->formatter->asDatetime('now'); 
    }

    public function timeDeffrenceCalculator($from_time = NULL)
    {
      // echo $from_time; die;
      $start_date = new \DateTime($from_time);
      $to_date  = $this->dbDateFormat();
      $diff = $start_date->diff(new \DateTime($to_date));
      
     /* For Refrence */

    /*  echo $since_start->days.' days total<br>';
    echo $since_start->y.' years<br>';
    echo $since_start->m.' months<br>';
    echo $since_start->d.' days<br>';
    echo $since_start->h.' hours<br>';
    echo $since_start->i.' minutes<br>';
    echo $since_start->s.' seconds<br>'; */

    // echo 'Hour '.$timeDeffrence->h.' Minute '.$timeDeffrence->i.' Second '.$timeDeffrence->s; die;
     /* ------------ */ 

     return $diff;
    }



public function getTimeZone(){
      $timezon_arr = array (
                    'Pacific/Midway' => '(GMT-11:00) Pacific, Midway',
                    'Pacific/Niue' => '(GMT-11:00) Pacific, Niue',
                    'Pacific/Pago_Pago' => '(GMT-11:00) Pacific, Pago Pago',
                    'America/Adak' => '(GMT-10:00) America, Adak',
                    'Pacific/Honolulu' => '(GMT-10:00) Pacific, Honolulu',
                    'Pacific/Rarotonga' => '(GMT-10:00) Pacific, Rarotonga',
                    'Pacific/Tahiti' => '(GMT-10:00) Pacific, Tahiti',
                    'Pacific/Marquesas' => '(GMT-09:30) Pacific, Marquesas',
                    'America/Anchorage' => '(GMT-09:00) America, Anchorage',
                    'America/Juneau' => '(GMT-09:00) America, Juneau',
                    'America/Metlakatla' => '(GMT-09:00) America, Metlakatla',
                    'America/Nome' => '(GMT-09:00) America, Nome',
                    'America/Sitka' => '(GMT-09:00) America, Sitka',
                    'America/Yakutat' => '(GMT-09:00) America, Yakutat',
                    'Pacific/Gambier' => '(GMT-09:00) Pacific, Gambier',
                    'America/Los_Angeles' => '(GMT-08:00) America, Los Angeles',
                    'America/Tijuana' => '(GMT-08:00) America, Tijuana',
                    'America/Vancouver' => '(GMT-08:00) America, Vancouver',
                    'Pacific/Pitcairn' => '(GMT-08:00) Pacific, Pitcairn',
                    'America/Boise' => '(GMT-07:00) America, Boise',
                    'America/Cambridge_Bay' => '(GMT-07:00) America, Cambridge Bay',
                    'America/Creston' => '(GMT-07:00) America, Creston',
                    'America/Dawson' => '(GMT-07:00) America, Dawson',
                    'America/Dawson_Creek' => '(GMT-07:00) America, Dawson Creek',
                    'America/Denver' => '(GMT-07:00) America, Denver',
                    'America/Edmonton' => '(GMT-07:00) America, Edmonton',
                    'America/Fort_Nelson' => '(GMT-07:00) America, Fort Nelson',
                    'America/Hermosillo' => '(GMT-07:00) America, Hermosillo',
                    'America/Inuvik' => '(GMT-07:00) America, Inuvik',
                    'America/Mazatlan' => '(GMT-07:00) America, Mazatlan',
                    'America/Phoenix' => '(GMT-07:00) America, Phoenix',
                    'America/Whitehorse' => '(GMT-07:00) America, Whitehorse',
                    'America/Yellowknife' => '(GMT-07:00) America, Yellowknife',
                    'America/Bahia_Banderas' => '(GMT-06:00) America, Bahia Banderas',
                    'America/Belize' => '(GMT-06:00) America, Belize',
                    'America/Chicago' => '(GMT-06:00) America, Chicago',
                    'America/Chihuahua' => '(GMT-06:00) America, Chihuahua',
                    'America/Costa_Rica' => '(GMT-06:00) America, Costa Rica',
                    'America/El_Salvador' => '(GMT-06:00) America, El Salvador',
                    'America/Guatemala' => '(GMT-06:00) America, Guatemala',
                    'America/Indiana/Knox' => '(GMT-06:00) America, Indiana, Knox',
                    'America/Indiana/Tell_City' => '(GMT-06:00) America, Indiana, Tell City',
                    'America/Managua' => '(GMT-06:00) America, Managua',
                    'America/Matamoros' => '(GMT-06:00) America, Matamoros',
                    'America/Menominee' => '(GMT-06:00) America, Menominee',
                    'America/Merida' => '(GMT-06:00) America, Merida',
                    'America/Mexico_City' => '(GMT-06:00) America, Mexico City',
                    'America/Monterrey' => '(GMT-06:00) America, Monterrey',
                    'America/North_Dakota/Beulah' => '(GMT-06:00) America, North Dakota, Beulah',
                    'America/North_Dakota/Center' => '(GMT-06:00) America, North Dakota, Center',
                    'America/North_Dakota/New_Salem' => '(GMT-06:00) America, North Dakota, New Salem',
                    'America/Ojinaga' => '(GMT-06:00) America, Ojinaga',
                    'America/Rankin_Inlet' => '(GMT-06:00) America, Rankin Inlet',
                    'America/Regina' => '(GMT-06:00) America, Regina',
                    'America/Resolute' => '(GMT-06:00) America, Resolute',
                    'America/Swift_Current' => '(GMT-06:00) America, Swift Current',
                    'America/Tegucigalpa' => '(GMT-06:00) America, Tegucigalpa',
                    'America/Winnipeg' => '(GMT-06:00) America, Winnipeg',
                    'Pacific/Galapagos' => '(GMT-06:00) Pacific, Galapagos',
                    'America/Atikokan' => '(GMT-05:00) America, Atikokan',
                    'America/Bogota' => '(GMT-05:00) America, Bogota',
                    'America/Cancun' => '(GMT-05:00) America, Cancun',
                    'America/Cayman' => '(GMT-05:00) America, Cayman',
                    'America/Detroit' => '(GMT-05:00) America, Detroit',
                    'America/Eirunepe' => '(GMT-05:00) America, Eirunepe',
                    'America/Grand_Turk' => '(GMT-05:00) America, Grand Turk',
                    'America/Guayaquil' => '(GMT-05:00) America, Guayaquil',
                    'America/Havana' => '(GMT-05:00) America, Havana',
                    'America/Indiana/Indianapolis' => '(GMT-05:00) America, Indiana, Indianapolis',
                    'America/Indiana/Marengo' => '(GMT-05:00) America, Indiana, Marengo',
                    'America/Indiana/Petersburg' => '(GMT-05:00) America, Indiana, Petersburg',
                    'America/Indiana/Vevay' => '(GMT-05:00) America, Indiana, Vevay',
                    'America/Indiana/Vincennes' => '(GMT-05:00) America, Indiana, Vincennes',
                    'America/Indiana/Winamac' => '(GMT-05:00) America, Indiana, Winamac',
                    'America/Iqaluit' => '(GMT-05:00) America, Iqaluit',
                    'America/Jamaica' => '(GMT-05:00) America, Jamaica',
                    'America/Kentucky/Louisville' => '(GMT-05:00) America, Kentucky, Louisville',
                    'America/Kentucky/Monticello' => '(GMT-05:00) America, Kentucky, Monticello',
                    'America/Lima' => '(GMT-05:00) America, Lima',
                    'America/Nassau' => '(GMT-05:00) America, Nassau',
                    'America/New_York' => '(GMT-05:00) America, New York',
                    'America/Panama' => '(GMT-05:00) America, Panama',
                    'America/Pangnirtung' => '(GMT-05:00) America, Pangnirtung',
                    'America/Port-au-Prince' => '(GMT-05:00) America, Port-au-Prince',
                    'America/Rio_Branco' => '(GMT-05:00) America, Rio Branco',
                    'America/Toronto' => '(GMT-05:00) America, Toronto',
                    'Pacific/Easter' => '(GMT-05:00) Pacific, Easter',
                    'America/Anguilla' => '(GMT-04:00) America, Anguilla',
                    'America/Antigua' => '(GMT-04:00) America, Antigua',
                    'America/Aruba' => '(GMT-04:00) America, Aruba',
                    'America/Barbados' => '(GMT-04:00) America, Barbados',
                    'America/Blanc-Sablon' => '(GMT-04:00) America, Blanc-Sablon',
                    'America/Boa_Vista' => '(GMT-04:00) America, Boa Vista',
                    'America/Campo_Grande' => '(GMT-04:00) America, Campo Grande',
                    'America/Caracas' => '(GMT-04:00) America, Caracas',
                    'America/Cuiaba' => '(GMT-04:00) America, Cuiaba',
                    'America/Curacao' => '(GMT-04:00) America, Curacao',
                    'America/Dominica' => '(GMT-04:00) America, Dominica',
                    'America/Glace_Bay' => '(GMT-04:00) America, Glace Bay',
                    'America/Goose_Bay' => '(GMT-04:00) America, Goose Bay',
                    'America/Grenada' => '(GMT-04:00) America, Grenada',
                    'America/Guadeloupe' => '(GMT-04:00) America, Guadeloupe',
                    'America/Guyana' => '(GMT-04:00) America, Guyana',
                    'America/Halifax' => '(GMT-04:00) America, Halifax',
                    'America/Kralendijk' => '(GMT-04:00) America, Kralendijk',
                    'America/La_Paz' => '(GMT-04:00) America, La Paz',
                    'America/Lower_Princes' => '(GMT-04:00) America, Lower Princes',
                    'America/Manaus' => '(GMT-04:00) America, Manaus',
                    'America/Marigot' => '(GMT-04:00) America, Marigot',
                    'America/Martinique' => '(GMT-04:00) America, Martinique',
                    'America/Moncton' => '(GMT-04:00) America, Moncton',
                    'America/Montserrat' => '(GMT-04:00) America, Montserrat',
                    'America/Port_of_Spain' => '(GMT-04:00) America, Port of Spain',
                    'America/Porto_Velho' => '(GMT-04:00) America, Porto Velho',
                    'America/Puerto_Rico' => '(GMT-04:00) America, Puerto Rico',
                    'America/Santo_Domingo' => '(GMT-04:00) America, Santo Domingo',
                    'America/St_Barthelemy' => '(GMT-04:00) America, St. Barthelemy',
                    'America/St_Kitts' => '(GMT-04:00) America, St. Kitts',
                    'America/St_Lucia' => '(GMT-04:00) America, St. Lucia',
                    'America/St_Thomas' => '(GMT-04:00) America, St. Thomas',
                    'America/St_Vincent' => '(GMT-04:00) America, St. Vincent',
                    'America/Thule' => '(GMT-04:00) America, Thule',
                    'America/Tortola' => '(GMT-04:00) America, Tortola',
                    'Atlantic/Bermuda' => '(GMT-04:00) Atlantic, Bermuda',
                    'America/St_Johns' => '(GMT-03:30) America, St. Johns',
                    'America/Araguaina' => '(GMT-03:00) America, Araguaina',
                    'America/Argentina/Buenos_Aires' => '(GMT-03:00) America, Argentina, Buenos Aires',
                    'America/Argentina/Catamarca' => '(GMT-03:00) America, Argentina, Catamarca',
                    'America/Argentina/Cordoba' => '(GMT-03:00) America, Argentina, Cordoba',
                    'America/Argentina/Jujuy' => '(GMT-03:00) America, Argentina, Jujuy',
                    'America/Argentina/La_Rioja' => '(GMT-03:00) America, Argentina, La Rioja',
                    'America/Argentina/Mendoza' => '(GMT-03:00) America, Argentina, Mendoza',
                    'America/Argentina/Rio_Gallegos' => '(GMT-03:00) America, Argentina, Rio Gallegos',
                    'America/Argentina/Salta' => '(GMT-03:00) America, Argentina, Salta',
                    'America/Argentina/San_Juan' => '(GMT-03:00) America, Argentina, San Juan',
                    'America/Argentina/San_Luis' => '(GMT-03:00) America, Argentina, San Luis',
                    'America/Argentina/Tucuman' => '(GMT-03:00) America, Argentina, Tucuman',
                    'America/Argentina/Ushuaia' => '(GMT-03:00) America, Argentina, Ushuaia',
                    'America/Asuncion' => '(GMT-03:00) America, Asuncion',
                    'America/Bahia' => '(GMT-03:00) America, Bahia',
                    'America/Belem' => '(GMT-03:00) America, Belem',
                    'America/Cayenne' => '(GMT-03:00) America, Cayenne',
                    'America/Fortaleza' => '(GMT-03:00) America, Fortaleza',
                    'America/Maceio' => '(GMT-03:00) America, Maceio',
                    'America/Miquelon' => '(GMT-03:00) America, Miquelon',
                    'America/Montevideo' => '(GMT-03:00) America, Montevideo',
                    'America/Nuuk' => '(GMT-03:00) America, Nuuk',
                    'America/Paramaribo' => '(GMT-03:00) America, Paramaribo',
                    'America/Punta_Arenas' => '(GMT-03:00) America, Punta Arenas',
                    'America/Recife' => '(GMT-03:00) America, Recife',
                    'America/Santarem' => '(GMT-03:00) America, Santarem',
                    'America/Santiago' => '(GMT-03:00) America, Santiago',
                    'America/Sao_Paulo' => '(GMT-03:00) America, Sao Paulo',
                    'Antarctica/Palmer' => '(GMT-03:00) Antarctica, Palmer',
                    'Antarctica/Rothera' => '(GMT-03:00) Antarctica, Rothera',
                    'Atlantic/Stanley' => '(GMT-03:00) Atlantic, Stanley',
                    'America/Noronha' => '(GMT-02:00) America, Noronha',
                    'Atlantic/South_Georgia' => '(GMT-02:00) Atlantic, South Georgia',
                    'America/Scoresbysund' => '(GMT-01:00) America, Scoresbysund',
                    'Atlantic/Azores' => '(GMT-01:00) Atlantic, Azores',
                    'Atlantic/Cape_Verde' => '(GMT-01:00) Atlantic, Cape Verde',
                    'Africa/Abidjan' => '(GMT) Africa, Abidjan',
                    'Africa/Accra' => '(GMT) Africa, Accra',
                    'Africa/Bamako' => '(GMT) Africa, Bamako',
                    'Africa/Banjul' => '(GMT) Africa, Banjul',
                    'Africa/Bissau' => '(GMT) Africa, Bissau',
                    'Africa/Conakry' => '(GMT) Africa, Conakry',
                    'Africa/Dakar' => '(GMT) Africa, Dakar',
                    'Africa/Freetown' => '(GMT) Africa, Freetown',
                    'Africa/Lome' => '(GMT) Africa, Lome',
                    'Africa/Monrovia' => '(GMT) Africa, Monrovia',
                    'Africa/Nouakchott' => '(GMT) Africa, Nouakchott',
                    'Africa/Ouagadougou' => '(GMT) Africa, Ouagadougou',
                    'Africa/Sao_Tome' => '(GMT) Africa, Sao Tome',
                    'America/Danmarkshavn' => '(GMT) America, Danmarkshavn',
                    'Antarctica/Troll' => '(GMT) Antarctica, Troll',
                    'Atlantic/Canary' => '(GMT) Atlantic, Canary',
                    'Atlantic/Faroe' => '(GMT) Atlantic, Faroe',
                    'Atlantic/Madeira' => '(GMT) Atlantic, Madeira',
                    'Atlantic/Reykjavik' => '(GMT) Atlantic, Reykjavik',
                    'Atlantic/St_Helena' => '(GMT) Atlantic, St. Helena',
                    'Europe/Dublin' => '(GMT) Europe, Dublin',
                    'Europe/Guernsey' => '(GMT) Europe, Guernsey',
                    'Europe/Isle_of_Man' => '(GMT) Europe, Isle of Man',
                    'Europe/Jersey' => '(GMT) Europe, Jersey',
                    'Europe/Lisbon' => '(GMT) Europe, Lisbon',
                    'Europe/London' => '(GMT) Europe, London',
                    'UTC' => '(GMT) UTC',
                    'Africa/Algiers' => '(GMT+01:00) Africa, Algiers',
                    'Africa/Bangui' => '(GMT+01:00) Africa, Bangui',
                    'Africa/Brazzaville' => '(GMT+01:00) Africa, Brazzaville',
                    'Africa/Casablanca' => '(GMT+01:00) Africa, Casablanca',
                    'Africa/Ceuta' => '(GMT+01:00) Africa, Ceuta',
                    'Africa/Douala' => '(GMT+01:00) Africa, Douala',
                    'Africa/El_Aaiun' => '(GMT+01:00) Africa, El Aaiun',
                    'Africa/Kinshasa' => '(GMT+01:00) Africa, Kinshasa',
                    'Africa/Lagos' => '(GMT+01:00) Africa, Lagos',
                    'Africa/Libreville' => '(GMT+01:00) Africa, Libreville',
                    'Africa/Luanda' => '(GMT+01:00) Africa, Luanda',
                    'Africa/Malabo' => '(GMT+01:00) Africa, Malabo',
                    'Africa/Ndjamena' => '(GMT+01:00) Africa, Ndjamena',
                    'Africa/Niamey' => '(GMT+01:00) Africa, Niamey',
                    'Africa/Porto-Novo' => '(GMT+01:00) Africa, Porto-Novo',
                    'Africa/Tunis' => '(GMT+01:00) Africa, Tunis',
                    'Arctic/Longyearbyen' => '(GMT+01:00) Arctic, Longyearbyen',
                    'Europe/Amsterdam' => '(GMT+01:00) Europe, Amsterdam',
                    'Europe/Andorra' => '(GMT+01:00) Europe, Andorra',
                    'Europe/Belgrade' => '(GMT+01:00) Europe, Belgrade',
                    'Europe/Berlin' => '(GMT+01:00) Europe, Berlin',
                    'Europe/Bratislava' => '(GMT+01:00) Europe, Bratislava',
                    'Europe/Brussels' => '(GMT+01:00) Europe, Brussels',
                    'Europe/Budapest' => '(GMT+01:00) Europe, Budapest',
                    'Europe/Busingen' => '(GMT+01:00) Europe, Busingen',
                    'Europe/Copenhagen' => '(GMT+01:00) Europe, Copenhagen',
                    'Europe/Gibraltar' => '(GMT+01:00) Europe, Gibraltar',
                    'Europe/Ljubljana' => '(GMT+01:00) Europe, Ljubljana',
                    'Europe/Luxembourg' => '(GMT+01:00) Europe, Luxembourg',
                    'Europe/Madrid' => '(GMT+01:00) Europe, Madrid',
                    'Europe/Malta' => '(GMT+01:00) Europe, Malta',
                    'Europe/Monaco' => '(GMT+01:00) Europe, Monaco',
                    'Europe/Oslo' => '(GMT+01:00) Europe, Oslo',
                    'Europe/Paris' => '(GMT+01:00) Europe, Paris',
                    'Europe/Podgorica' => '(GMT+01:00) Europe, Podgorica',
                    'Europe/Prague' => '(GMT+01:00) Europe, Prague',
                    'Europe/Rome' => '(GMT+01:00) Europe, Rome',
                    'Europe/San_Marino' => '(GMT+01:00) Europe, San Marino',
                    'Europe/Sarajevo' => '(GMT+01:00) Europe, Sarajevo',
                    'Europe/Skopje' => '(GMT+01:00) Europe, Skopje',
                    'Europe/Stockholm' => '(GMT+01:00) Europe, Stockholm',
                    'Europe/Tirane' => '(GMT+01:00) Europe, Tirane',
                    'Europe/Vaduz' => '(GMT+01:00) Europe, Vaduz',
                    'Europe/Vatican' => '(GMT+01:00) Europe, Vatican',
                    'Europe/Vienna' => '(GMT+01:00) Europe, Vienna',
                    'Europe/Warsaw' => '(GMT+01:00) Europe, Warsaw',
                    'Europe/Zagreb' => '(GMT+01:00) Europe, Zagreb',
                    'Europe/Zurich' => '(GMT+01:00) Europe, Zurich',
                    'Africa/Blantyre' => '(GMT+02:00) Africa, Blantyre',
                    'Africa/Bujumbura' => '(GMT+02:00) Africa, Bujumbura',
                    'Africa/Cairo' => '(GMT+02:00) Africa, Cairo',
                    'Africa/Gaborone' => '(GMT+02:00) Africa, Gaborone',
                    'Africa/Harare' => '(GMT+02:00) Africa, Harare',
                    'Africa/Johannesburg' => '(GMT+02:00) Africa, Johannesburg',
                    'Africa/Juba' => '(GMT+02:00) Africa, Juba',
                    'Africa/Khartoum' => '(GMT+02:00) Africa, Khartoum',
                    'Africa/Kigali' => '(GMT+02:00) Africa, Kigali',
                    'Africa/Lubumbashi' => '(GMT+02:00) Africa, Lubumbashi',
                    'Africa/Lusaka' => '(GMT+02:00) Africa, Lusaka',
                    'Africa/Maputo' => '(GMT+02:00) Africa, Maputo',
                    'Africa/Maseru' => '(GMT+02:00) Africa, Maseru',
                    'Africa/Mbabane' => '(GMT+02:00) Africa, Mbabane',
                    'Africa/Tripoli' => '(GMT+02:00) Africa, Tripoli',
                    'Africa/Windhoek' => '(GMT+02:00) Africa, Windhoek',
                    'Asia/Beirut' => '(GMT+02:00) Asia, Beirut',
                    'Asia/Famagusta' => '(GMT+02:00) Asia, Famagusta',
                    'Asia/Gaza' => '(GMT+02:00) Asia, Gaza',
                    'Asia/Hebron' => '(GMT+02:00) Asia, Hebron',
                    'Asia/Jerusalem' => '(GMT+02:00) Asia, Jerusalem',
                    'Asia/Nicosia' => '(GMT+02:00) Asia, Nicosia',
                    'Europe/Athens' => '(GMT+02:00) Europe, Athens',
                    'Europe/Bucharest' => '(GMT+02:00) Europe, Bucharest',
                    'Europe/Chisinau' => '(GMT+02:00) Europe, Chisinau',
                    'Europe/Helsinki' => '(GMT+02:00) Europe, Helsinki',
                    'Europe/Kaliningrad' => '(GMT+02:00) Europe, Kaliningrad',
                    'Europe/Kyiv' => '(GMT+02:00) Europe, Kyiv',
                    'Europe/Mariehamn' => '(GMT+02:00) Europe, Mariehamn',
                    'Europe/Riga' => '(GMT+02:00) Europe, Riga',
                    'Europe/Sofia' => '(GMT+02:00) Europe, Sofia',
                    'Europe/Tallinn' => '(GMT+02:00) Europe, Tallinn',
                    'Europe/Vilnius' => '(GMT+02:00) Europe, Vilnius',
                    'Africa/Addis_Ababa' => '(GMT+03:00) Africa, Addis Ababa',
                    'Africa/Asmara' => '(GMT+03:00) Africa, Asmara',
                    'Africa/Dar_es_Salaam' => '(GMT+03:00) Africa, Dar es Salaam',
                    'Africa/Djibouti' => '(GMT+03:00) Africa, Djibouti',
                    'Africa/Kampala' => '(GMT+03:00) Africa, Kampala',
                    'Africa/Mogadishu' => '(GMT+03:00) Africa, Mogadishu',
                    'Africa/Nairobi' => '(GMT+03:00) Africa, Nairobi',
                    'Antarctica/Syowa' => '(GMT+03:00) Antarctica, Syowa',
                    'Asia/Aden' => '(GMT+03:00) Asia, Aden',
                    'Asia/Amman' => '(GMT+03:00) Asia, Amman',
                    'Asia/Baghdad' => '(GMT+03:00) Asia, Baghdad',
                    'Asia/Bahrain' => '(GMT+03:00) Asia, Bahrain',
                    'Asia/Damascus' => '(GMT+03:00) Asia, Damascus',
                    'Asia/Kuwait' => '(GMT+03:00) Asia, Kuwait',
                    'Asia/Qatar' => '(GMT+03:00) Asia, Qatar',
                    'Asia/Riyadh' => '(GMT+03:00) Asia, Riyadh',
                    'Europe/Istanbul' => '(GMT+03:00) Europe, Istanbul',
                    'Europe/Kirov' => '(GMT+03:00) Europe, Kirov',
                    'Europe/Minsk' => '(GMT+03:00) Europe, Minsk',
                    'Europe/Moscow' => '(GMT+03:00) Europe, Moscow',
                    'Europe/Simferopol' => '(GMT+03:00) Europe, Simferopol',
                    'Europe/Volgograd' => '(GMT+03:00) Europe, Volgograd',
                    'Indian/Antananarivo' => '(GMT+03:00) Indian, Antananarivo',
                    'Indian/Comoro' => '(GMT+03:00) Indian, Comoro',
                    'Indian/Mayotte' => '(GMT+03:00) Indian, Mayotte',
                    'Asia/Tehran' => '(GMT+03:30) Asia, Tehran',
                    'Asia/Baku' => '(GMT+04:00) Asia, Baku',
                    'Asia/Dubai' => '(GMT+04:00) Asia, Dubai',
                    'Asia/Muscat' => '(GMT+04:00) Asia, Muscat',
                    'Asia/Tbilisi' => '(GMT+04:00) Asia, Tbilisi',
                    'Asia/Yerevan' => '(GMT+04:00) Asia, Yerevan',
                    'Europe/Astrakhan' => '(GMT+04:00) Europe, Astrakhan',
                    'Europe/Samara' => '(GMT+04:00) Europe, Samara',
                    'Europe/Saratov' => '(GMT+04:00) Europe, Saratov',
                    'Europe/Ulyanovsk' => '(GMT+04:00) Europe, Ulyanovsk',
                    'Indian/Mahe' => '(GMT+04:00) Indian, Mahe',
                    'Indian/Mauritius' => '(GMT+04:00) Indian, Mauritius',
                    'Indian/Reunion' => '(GMT+04:00) Indian, Reunion',
                    'Asia/Kabul' => '(GMT+04:30) Asia, Kabul',
                    'Antarctica/Mawson' => '(GMT+05:00) Antarctica, Mawson',
                    'Asia/Aqtau' => '(GMT+05:00) Asia, Aqtau',
                    'Asia/Aqtobe' => '(GMT+05:00) Asia, Aqtobe',
                    'Asia/Ashgabat' => '(GMT+05:00) Asia, Ashgabat',
                    'Asia/Atyrau' => '(GMT+05:00) Asia, Atyrau',
                    'Asia/Dushanbe' => '(GMT+05:00) Asia, Dushanbe',
                    'Asia/Karachi' => '(GMT+05:00) Asia, Karachi',
                    'Asia/Oral' => '(GMT+05:00) Asia, Oral',
                    'Asia/Qyzylorda' => '(GMT+05:00) Asia, Qyzylorda',
                    'Asia/Samarkand' => '(GMT+05:00) Asia, Samarkand',
                    'Asia/Tashkent' => '(GMT+05:00) Asia, Tashkent',
                    'Asia/Yekaterinburg' => '(GMT+05:00) Asia, Yekaterinburg',
                    'Indian/Kerguelen' => '(GMT+05:00) Indian, Kerguelen',
                    'Indian/Maldives' => '(GMT+05:00) Indian, Maldives',
                    'Asia/Colombo' => '(GMT+05:30) Asia, Colombo',
                    'Asia/Kolkata' => '(GMT+05:30) Asia, Kolkata',
                    'Asia/Kathmandu' => '(GMT+05:45) Asia, Kathmandu',
                    'Antarctica/Vostok' => '(GMT+06:00) Antarctica, Vostok',
                    'Asia/Almaty' => '(GMT+06:00) Asia, Almaty',
                    'Asia/Bishkek' => '(GMT+06:00) Asia, Bishkek',
                    'Asia/Dhaka' => '(GMT+06:00) Asia, Dhaka',
                    'Asia/Omsk' => '(GMT+06:00) Asia, Omsk',
                    'Asia/Qostanay' => '(GMT+06:00) Asia, Qostanay',
                    'Asia/Thimphu' => '(GMT+06:00) Asia, Thimphu',
                    'Asia/Urumqi' => '(GMT+06:00) Asia, Urumqi',
                    'Indian/Chagos' => '(GMT+06:00) Indian, Chagos',
                    'Asia/Yangon' => '(GMT+06:30) Asia, Yangon',
                    'Indian/Cocos' => '(GMT+06:30) Indian, Cocos',
                    'Antarctica/Davis' => '(GMT+07:00) Antarctica, Davis',
                    'Asia/Bangkok' => '(GMT+07:00) Asia, Bangkok',
                    'Asia/Barnaul' => '(GMT+07:00) Asia, Barnaul',
                    'Asia/Ho_Chi_Minh' => '(GMT+07:00) Asia, Ho Chi Minh',
                    'Asia/Hovd' => '(GMT+07:00) Asia, Hovd',
                    'Asia/Jakarta' => '(GMT+07:00) Asia, Jakarta',
                    'Asia/Krasnoyarsk' => '(GMT+07:00) Asia, Krasnoyarsk',
                    'Asia/Novokuznetsk' => '(GMT+07:00) Asia, Novokuznetsk',
                    'Asia/Novosibirsk' => '(GMT+07:00) Asia, Novosibirsk',
                    'Asia/Phnom_Penh' => '(GMT+07:00) Asia, Phnom Penh',
                    'Asia/Pontianak' => '(GMT+07:00) Asia, Pontianak',
                    'Asia/Tomsk' => '(GMT+07:00) Asia, Tomsk',
                    'Asia/Vientiane' => '(GMT+07:00) Asia, Vientiane',
                    'Indian/Christmas' => '(GMT+07:00) Indian, Christmas',
                    'Asia/Brunei' => '(GMT+08:00) Asia, Brunei',
                    'Asia/Choibalsan' => '(GMT+08:00) Asia, Choibalsan',
                    'Asia/Hong_Kong' => '(GMT+08:00) Asia, Hong Kong',
                    'Asia/Irkutsk' => '(GMT+08:00) Asia, Irkutsk',
                    'Asia/Kuala_Lumpur' => '(GMT+08:00) Asia, Kuala Lumpur',
                    'Asia/Kuching' => '(GMT+08:00) Asia, Kuching',
                    'Asia/Macau' => '(GMT+08:00) Asia, Macau',
                    'Asia/Makassar' => '(GMT+08:00) Asia, Makassar',
                    'Asia/Manila' => '(GMT+08:00) Asia, Manila',
                    'Asia/Shanghai' => '(GMT+08:00) Asia, Shanghai',
                    'Asia/Singapore' => '(GMT+08:00) Asia, Singapore',
                    'Asia/Taipei' => '(GMT+08:00) Asia, Taipei',
                    'Asia/Ulaanbaatar' => '(GMT+08:00) Asia, Ulaanbaatar',
                    'Australia/Perth' => '(GMT+08:00) Australia, Perth',
                    'Australia/Eucla' => '(GMT+08:45) Australia, Eucla',
                    'Asia/Chita' => '(GMT+09:00) Asia, Chita',
                    'Asia/Dili' => '(GMT+09:00) Asia, Dili',
                    'Asia/Jayapura' => '(GMT+09:00) Asia, Jayapura',
                    'Asia/Khandyga' => '(GMT+09:00) Asia, Khandyga',
                    'Asia/Pyongyang' => '(GMT+09:00) Asia, Pyongyang',
                    'Asia/Seoul' => '(GMT+09:00) Asia, Seoul',
                    'Asia/Tokyo' => '(GMT+09:00) Asia, Tokyo',
                    'Asia/Yakutsk' => '(GMT+09:00) Asia, Yakutsk',
                    'Pacific/Palau' => '(GMT+09:00) Pacific, Palau',
                    'Australia/Darwin' => '(GMT+09:30) Australia, Darwin',
                    'Antarctica/DumontDUrville' => '(GMT+10:00) Antarctica, DumontDUrville',
                    'Asia/Ust-Nera' => '(GMT+10:00) Asia, Ust-Nera',
                    'Asia/Vladivostok' => '(GMT+10:00) Asia, Vladivostok',
                    'Australia/Brisbane' => '(GMT+10:00) Australia, Brisbane',
                    'Australia/Lindeman' => '(GMT+10:00) Australia, Lindeman',
                    'Pacific/Chuuk' => '(GMT+10:00) Pacific, Chuuk',
                    'Pacific/Guam' => '(GMT+10:00) Pacific, Guam',
                    'Pacific/Port_Moresby' => '(GMT+10:00) Pacific, Port Moresby',
                    'Pacific/Saipan' => '(GMT+10:00) Pacific, Saipan',
                    'Australia/Adelaide' => '(GMT+10:30) Australia, Adelaide',
                    'Australia/Broken_Hill' => '(GMT+10:30) Australia, Broken Hill',
                    'Antarctica/Casey' => '(GMT+11:00) Antarctica, Casey',
                    'Antarctica/Macquarie' => '(GMT+11:00) Antarctica, Macquarie',
                    'Asia/Magadan' => '(GMT+11:00) Asia, Magadan',
                    'Asia/Sakhalin' => '(GMT+11:00) Asia, Sakhalin',
                    'Asia/Srednekolymsk' => '(GMT+11:00) Asia, Srednekolymsk',
                    'Australia/Hobart' => '(GMT+11:00) Australia, Hobart',
                    'Australia/Lord_Howe' => '(GMT+11:00) Australia, Lord Howe',
                    'Australia/Melbourne' => '(GMT+11:00) Australia, Melbourne',
                    'Australia/Sydney' => '(GMT+11:00) Australia, Sydney',
                    'Pacific/Bougainville' => '(GMT+11:00) Pacific, Bougainville',
                    'Pacific/Efate' => '(GMT+11:00) Pacific, Efate',
                    'Pacific/Guadalcanal' => '(GMT+11:00) Pacific, Guadalcanal',
                    'Pacific/Kosrae' => '(GMT+11:00) Pacific, Kosrae',
                    'Pacific/Noumea' => '(GMT+11:00) Pacific, Noumea',
                    'Pacific/Pohnpei' => '(GMT+11:00) Pacific, Pohnpei',
                    'Asia/Anadyr' => '(GMT+12:00) Asia, Anadyr',
                    'Asia/Kamchatka' => '(GMT+12:00) Asia, Kamchatka',
                    'Pacific/Fiji' => '(GMT+12:00) Pacific, Fiji',
                    'Pacific/Funafuti' => '(GMT+12:00) Pacific, Funafuti',
                    'Pacific/Kwajalein' => '(GMT+12:00) Pacific, Kwajalein',
                    'Pacific/Majuro' => '(GMT+12:00) Pacific, Majuro',
                    'Pacific/Nauru' => '(GMT+12:00) Pacific, Nauru',
                    'Pacific/Norfolk' => '(GMT+12:00) Pacific, Norfolk',
                    'Pacific/Tarawa' => '(GMT+12:00) Pacific, Tarawa',
                    'Pacific/Wake' => '(GMT+12:00) Pacific, Wake',
                    'Pacific/Wallis' => '(GMT+12:00) Pacific, Wallis',
                    'Antarctica/McMurdo' => '(GMT+13:00) Antarctica, McMurdo',
                    'Pacific/Apia' => '(GMT+13:00) Pacific, Apia',
                    'Pacific/Auckland' => '(GMT+13:00) Pacific, Auckland',
                    'Pacific/Fakaofo' => '(GMT+13:00) Pacific, Fakaofo',
                    'Pacific/Kanton' => '(GMT+13:00) Pacific, Kanton',
                    'Pacific/Tongatapu' => '(GMT+13:00) Pacific, Tongatapu',
                    'Pacific/Chatham' => '(GMT+13:45) Pacific, Chatham',
                    'Pacific/Kiritimati' => '(GMT+14:00) Pacific, Kiritimati',
                  );


      return $timezon_arr;
  }

 public function getDateFormat()
    {
        $date_format = [
          'Y-m-d' => 'yyyy-mm-dd', 
          'Y/m/d' => 'yyyy/mm/dd',
          'Y.m.d' => 'yyyy.mm.dd', 
          'd-M-Y' => 'dd-mmm-yyyy', 
          'd/M/Y' => 'dd/mmm/yyyy', 
          'd.M.Y' => 'dd.mmm.yyyy', 
          'd-m-Y' => 'dd-mm-yyyy', 
          'd/m/Y' => 'dd/mm/yyyy', 
          'd.m.Y' => 'dd.mm.yyyy', 
          'm-d-Y' => 'mm-dd-yyyy', 
          'm/d/Y' => 'mm/dd/yyyy', 
          'm.d.Y' => 'mm.dd.yyyy'
        ];
        return $date_format;
}

public function getNicetime($date)
{
    $get_format = 'Y-m-d H:i:s';
    if (empty($date)) {
        return "Unknown";
    }
    // Current time as MySQL DATETIME value
    $csqltime = date('Y-m-d H:i:s');
    // Current time as Unix timestamp
    $ptime = strtotime($date);
    $ctime = strtotime($csqltime);

    //Now calc the difference between the two
    $timeDiff = floor(abs($ctime - $ptime) / 60);

    //Now we need find out whether or not the time difference needs to be in
    //minutes, hours, or days
    if ($timeDiff < 2) {
        $timeDiff = "Just now";
    } elseif ($timeDiff > 2 && $timeDiff < 60) {
        $timeDiff = floor(abs($timeDiff)) . " minutes ago";
    } elseif ($timeDiff > 60 && $timeDiff < 120) {
        $timeDiff = floor(abs($timeDiff / 60)) . " hour ago";
    } elseif ($timeDiff < 1440) {
        $timeDiff = floor(abs($timeDiff / 60)) . " hours ago";
    } elseif ($timeDiff > 1440 && $timeDiff < 2880) {
        $timeDiff = floor(abs($timeDiff / 1440)) . " day ago";
    } elseif ($timeDiff > 2880) {
        $timeDiff = date($get_format, $ptime);
    }
    return $timeDiff;
}


    public function sendMail($userData)
    {
      //Load Composer's autoloader
      require 'vendor/autoload.php';

      //Create an instance; passing `true` enables exceptions
      $mail = new PHPMailer(true);

      try {
          //Server settings
          // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
          $mail->isSMTP();                                            //Send using SMTP
          $mail->Host       = 'mail.techniglob.in';                     //Set the SMTP server to send through
          $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
          $mail->Username   = 'support@techniglob.in';                     //SMTP username
          $mail->Password   = 'lwZ#4(yMhi&R';                               //SMTP password
          // $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
          $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

          //Recipients
          $mail->setFrom('support@techniglob.in', 'School Management System');
          $mail->addAddress($userData->email, $userData->full_name);     //Add a recipient
          /* $mail->addAddress('ellen@example.com');               //Name is optional
          $mail->addReplyTo('info@example.com', 'Information');
          $mail->addCC('cc@example.com');
          $mail->addBCC('bcc@example.com'); */

          //Attachments
          //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
          //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

          //Content
          $mail->isHTML(true);                                  //Set email format to HTML
          $mail->Subject = 'Please reset your password';
          $mail->Body    = $this->htmlMailFormat($userData);
          //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

          if($mail->send()){
              return true;
          }
      } catch (Exception $e) {
          echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
      }
    }

    public function htmlMailFormat($userData)
    {

      $url = Url::to(['site/changepassword/'.$userData->generate_token]);
        return '<!DOCTYPE html>
        <html xmlns="http://www.w3.org/1999/xhtml">
          <head>
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <meta name="x-apple-disable-message-reformatting" />
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <meta name="color-scheme" content="light dark" />
            <meta name="supported-color-schemes" content="light dark" />
            <title></title>
            <style type="text/css" rel="stylesheet" media="all">
            /* Base ------------------------------ */
            
            @import url("https://fonts.googleapis.com/css?family=Nunito+Sans:400,700&display=swap");
            body {
            width: 100% !important;
            height: 100%;
            margin: 0;
            -webkit-text-size-adjust: none;
            }
            
            a {
            color: #3869D4;
            }
            
            a img {
            border: none;
            }
            
            td {
            word-break: break-word;
            }
            
            .preheader {
            display: none !important;
            visibility: hidden;
            mso-hide: all;
            font-size: 1px;
            line-height: 1px;
            max-height: 0;
            max-width: 0;
            opacity: 0;
            overflow: hidden;
            }
            /* Type ------------------------------ */
            
            body,
            td,
            th {
            font-family: "Nunito Sans", Helvetica, Arial, sans-serif;
            }
            
            h1 {
            margin-top: 0;
            color: #333333;
            font-size: 22px;
            font-weight: bold;
            text-align: left;
            }
            
            h2 {
            margin-top: 0;
            color: #333333;
            font-size: 16px;
            font-weight: bold;
            text-align: left;
            }
            
            h3 {
            margin-top: 0;
            color: #333333;
            font-size: 14px;
            font-weight: bold;
            text-align: left;
            }
            
            td,
            th {
            font-size: 16px;
            }
            
            p,
            ul,
            ol,
            blockquote {
            margin: .4em 0 1.1875em;
            font-size: 16px;
            line-height: 1.625;
            }
            
            p.sub {
            font-size: 13px;
            }
            /* Utilities ------------------------------ */
            
            .align-right {
            text-align: right;
            }
            
            .align-left {
            text-align: left;
            }
            
            .align-center {
            text-align: center;
            }
            
            .u-margin-bottom-none {
            margin-bottom: 0;
            }
            /* Buttons ------------------------------ */
            
            .button {
            background-color: #3869D4;
            border-top: 10px solid #3869D4;
            border-right: 18px solid #3869D4;
            border-bottom: 10px solid #3869D4;
            border-left: 18px solid #3869D4;
            display: inline-block;
            color: #FFF;
            text-decoration: none;
            border-radius: 3px;
            box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16);
            -webkit-text-size-adjust: none;
            box-sizing: border-box;
            }
            
            .button--green {
            background-color: #22BC66;
            border-top: 10px solid #22BC66;
            border-right: 18px solid #22BC66;
            border-bottom: 10px solid #22BC66;
            border-left: 18px solid #22BC66;
            }
            
            .button--red {
            background-color: #FF6136;
            border-top: 10px solid #FF6136;
            border-right: 18px solid #FF6136;
            border-bottom: 10px solid #FF6136;
            border-left: 18px solid #FF6136;
            }
            
            @media only screen and (max-width: 500px) {
            .button {
            width: 100% !important;
            text-align: center !important;
            }
            }
            /* Attribute list ------------------------------ */
            
            .attributes {
            margin: 0 0 21px;
            }
            
            .attributes_content {
            background-color: #F4F4F7;
            padding: 16px;
            }
            
            .attributes_item {
            padding: 0;
            }
            /* Related Items ------------------------------ */
            
            .related {
            width: 100%;
            margin: 0;
            padding: 25px 0 0 0;
            -premailer-width: 100%;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            }
            
            .related_item {
            padding: 10px 0;
            color: #CBCCCF;
            font-size: 15px;
            line-height: 18px;
            }
            
            .related_item-title {
            display: block;
            margin: .5em 0 0;
            }
            
            .related_item-thumb {
            display: block;
            padding-bottom: 10px;
            }
            
            .related_heading {
            border-top: 1px solid #CBCCCF;
            text-align: center;
            padding: 25px 0 10px;
            }
            /* Discount Code ------------------------------ */
            
            .discount {
            width: 100%;
            margin: 0;
            padding: 24px;
            -premailer-width: 100%;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            background-color: #F4F4F7;
            border: 2px dashed #CBCCCF;
            }
            
            .discount_heading {
            text-align: center;
            }
            
            .discount_body {
            text-align: center;
            font-size: 15px;
            }
            /* Social Icons ------------------------------ */
            
            .social {
            width: auto;
            }
            
            .social td {
            padding: 0;
            width: auto;
            }
            
            .social_icon {
            height: 20px;
            margin: 0 8px 10px 8px;
            padding: 0;
            }
            /* Data table ------------------------------ */
            
            .purchase {
            width: 100%;
            margin: 0;
            padding: 35px 0;
            -premailer-width: 100%;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            }
            
            .purchase_content {
            width: 100%;
            margin: 0;
            padding: 25px 0 0 0;
            -premailer-width: 100%;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            }
            
            .purchase_item {
            padding: 10px 0;
            color: #51545E;
            font-size: 15px;
            line-height: 18px;
            }
            
            .purchase_heading {
            padding-bottom: 8px;
            border-bottom: 1px solid #EAEAEC;
            }
            
            .purchase_heading p {
            margin: 0;
            color: #85878E;
            font-size: 12px;
            }
            
            .purchase_footer {
            padding-top: 15px;
            border-top: 1px solid #EAEAEC;
            }
            
            .purchase_total {
            margin: 0;
            text-align: right;
            font-weight: bold;
            color: #333333;
            }
            
            .purchase_total--label {
            padding: 0 15px 0 0;
            }
            
            body {
            background-color: #F2F4F6;
            color: #51545E;
            }
            
            p {
            color: #51545E;
            }
            
            .email-wrapper {
            width: 100%;
            margin: 0;
            padding: 0;
            -premailer-width: 100%;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            background-color: #F2F4F6;
            }
            
            .email-content {
            width: 100%;
            margin: 0;
            padding: 0;
            -premailer-width: 100%;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            }
            /* Masthead ----------------------- */
            
            .email-masthead {
            padding: 25px 0;
            text-align: center;
            }
            
            .email-masthead_logo {
            width: 94px;
            }
            
            .email-masthead_name {
            font-size: 16px;
            font-weight: bold;
            color: #A8AAAF;
            text-decoration: none;
            text-shadow: 0 1px 0 white;
            }
            /* Body ------------------------------ */
            
            .email-body {
            width: 100%;
            margin: 0;
            padding: 0;
            -premailer-width: 100%;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            }
            
            .email-body_inner {
            width: 570px;
            margin: 0 auto;
            padding: 0;
            -premailer-width: 570px;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            background-color: #FFFFFF;
            }
            
            .email-footer {
            width: 570px;
            margin: 0 auto;
            padding: 0;
            -premailer-width: 570px;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            text-align: center;
            }
            
            .email-footer p {
            color: #A8AAAF;
            }
            
            .body-action {
            width: 100%;
            margin: 30px auto;
            padding: 0;
            -premailer-width: 100%;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            text-align: center;
            }
            
            .body-sub {
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid #EAEAEC;
            }
            
            .content-cell {
            padding: 45px;
            }
            /*Media Queries ------------------------------ */
            
            @media only screen and (max-width: 600px) {
            .email-body_inner,
            .email-footer {
            width: 100% !important;
            }
            }
            
            @media (prefers-color-scheme: dark) {
            body,
            .email-body,
            .email-body_inner,
            .email-content,
            .email-wrapper,
            .email-masthead,
            .email-footer {
            background-color: #333333 !important;
            color: #FFF !important;
            }
            p,
            ul,
            ol,
            blockquote,
            h1,
            h2,
            h3,
            span,
            .purchase_item {
            color: #FFF !important;
            }
            .attributes_content,
            .discount {
            background-color: #222 !important;
            }
            .email-masthead_name {
            text-shadow: none !important;
            }
            }
            
            :root {
            color-scheme: light dark;
            supported-color-schemes: light dark;
            }
            </style>
            <!--[if mso]>
            <style type="text/css">
            .f-fallback  {
            font-family: Arial, sans-serif;
            color: #ffff;
            }
            </style>
            <![endif]-->
          </head>
          <body>
            <span class="preheader">Use this link to reset your password. The link is only valid for 24 hours.</span>
            <table class="email-wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
              <tr>
                <td align="center">
                  <table class="email-content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                    <tr>
                      <td class="email-masthead">
                        <a href="https://www.techniglob.in" class="f-fallback email-masthead_name">
                          School Management System
                        </a>
                      </td>
                    </tr>
                    <!-- Email Body -->
                    <tr>
                      <td class="email-body" width="570" cellpadding="0" cellspacing="0">
                        <table class="email-body_inner" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
                          <!-- Body content -->
                          <tr>
                            <td class="content-cell">
                              <div class="f-fallback">
                                <h1>Hi '.$userData->full_name.',</h1>
                                <p>You recently requested to reset your password for your account. Use the button below to reset it. <strong>This password reset is only valid for the next 1 hours.</strong></p>
                                <!-- Action -->
                                <table class="body-action" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                  <tr>
                                    <td align="center">
                                      <table width="100%" border="0" cellspacing="0" cellpadding="0" role="presentation">
                                        <tr>
                                          <td align="center">
                                            <a href="'.$url.'" class="f-fallback button button--green" target="_blank">Reset your password</a>
                                          </td>
                                        </tr>
                                      </table>
                                    </td>
                                  </tr>
                                </table>
                                <p>If you did not request a password reset, please ignore this email or <a href="https://www.techniglob.in">contact support</a> if you have questions.</p>
                                <p>Thanks,
                                <br>The Team Techniglob </p>
                                <!-- Sub copy -->
                                <table class="body-sub" role="presentation">
                                  <tr>
                                    <td>
                                      <p class="f-fallback sub">If you’re having trouble with the button above, copy and paste the URL below into your web browser.</p>
                                      <p class="f-fallback sub">'.$url.'</p>
                                    </td>
                                  </tr>
                                </table>
                              </div>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <table class="email-footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
                          <tr>
                            <td class="content-cell" align="center">
                              <p class="f-fallback sub align-center">
                                [Company Name, LLC]
                                <br>1234 Street Rd.
                                <br>Suite 1234
                              </p>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </body>
        </html>';
    }

    protected function findModel($id)
    {
        if (($model = UsersActivity::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
}