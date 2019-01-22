<?php

/**
 * Description of BittionMainComponent: Generic function that could be used in every controller
 *
 * @author rey
 */
class BittionMainComponent extends Component {

    public function fnSetFormatDate($date) {
        $date = explode('/', $date);
//        dd/mm/yy
        $arrayDate['day'] = $date[0];
        $arrayDate['month'] = $date[1];
        $arrayDate['year'] = $date[2];
        return $arrayDate;
    }

    public function fnSetFormatDateTime($date) {
        //BEFORE
//        $date = explode('/', $date);
////        dd/mm/yy/h:m:ss
//        $arrayDate['day'] = $date[0];
//        $arrayDate['month'] = $date[1];
//        $arrayDate['year'] = $date[2];
//        $time = explode(':', $date[3]);
//        $arrayDate['hour'] = $time[0];
//        $arrayDate['min'] = $time[1];
//        $arrayDate['sec'] = $time[2];
//        return $arrayDate;

        //CHANGED because im using correct date format set on DB postgresql now set on macos
//        dd/mm/yy h:m:ss
        $date = explode(' ', $date);

        $dateNormal = explode('/',$date[0]);
        $dateTime = explode(':',$date[1]);
        
        $arrayDate['day'] = $dateNormal[0];
        $arrayDate['month'] = $dateNormal[1];
        $arrayDate['year'] = $dateNormal[2];
        $arrayDate['hour'] = $dateTime[0];
        $arrayDate['min'] = $dateTime[1];
        $arrayDate['sec'] = $dateTime[2];
        return $arrayDate;
    }

    public function fnSetFormatDateTimeLastLogin($date) {
        //BEFORE
//        $date = explode('/', $date);
////        dd/mm/yy/h:m:ss
//        $arrayDate['day'] = $date[0];
//        $arrayDate['month'] = $date[1];
//        $arrayDate['year'] = $date[2];
//        $time = explode(':', $date[3]);
//        $arrayDate['hour'] = $time[0];
//        $arrayDate['min'] = $time[1];
//        $arrayDate['sec'] = $time[2];
//        return $arrayDate;

        //CHANGED because im using correct date format set on DB postgresql now set on macos
//        dd/mm/yy h:m:ss
        $date = explode(' ', $date);

        $dateNormal = explode('-',$date[0]);  //this must be changed in openshift with "-"
        $dateTime = explode(':',$date[1]);

        $arrayDate['day'] = $dateNormal[2];
        $arrayDate['month'] = $dateNormal[1];
        $arrayDate['year'] = $dateNormal[0];
        $arrayDate['hour'] = $dateTime[0];
        $arrayDate['min'] = $dateTime[1];
        $arrayDate['sec'] = $dateTime[2];
        return $arrayDate;
    }

    public function fnGetFormatDate($date) {
        if ($date == '') {
            return '';
        }
        return date('d/m/Y', strtotime($date)); //In this case not needed 'cause I changed DB format time to default "d/m/y" (not like it was on windows) Now is correct.
        //Will leave this function in case is needed in certains occasions
//        return $date; // this is the quick fix
    }

    public function fnListParameters($parameterKey, $parameterValue, $parameterName) {
//        $this->loadModel('AdmParameter'); //won't work here
        $this->AdmParameter = ClassRegistry::init('AdmParameter');
        return $parameters = $this->AdmParameter->find('list', array(
            'fields' => array('AdmParameter.' . $parameterValue, 'AdmParameter.' . $parameterName)
            , 'conditions' => array('AdmParameter.parameter_key' => $parameterKey)
        ));
    }

    public function fnGetExceptionResponse($code) {
        //Postgresql Error Codes ref: http://www.postgresql.org/docs/9.3/static/errcodes-appendix.html
//        $content = array('state'=>'error', 'content'=>'Error de excepción.');
        $content = array('status' => 'ERROR', 'title' => 'Acción denegada!', 'content' => $code);
        switch ($code) {
            case '23502'://Not null violation
                $content['content'] = 'Campos vacios o nulos';
                break;
            case '23503'://Foreign Key Violation
                $content['content'] = 'No se puede eliminar porque ya fue usado en el sistema';
                break;
            case '23505'://Unique violation
                $content['content'] = 'Existe duplicados';
                break;
            case '22007'://Invalid date time format
                $content['content'] = 'Formato de fecha no válida';
                break;
            case '22P02'://Invalid text representation - when text send to integer
                $content['content'] = 'Sintaxis de entrada no válida para números';
                break;
            case 'P0001'://Triggers - Life Cycles 
                $content['content'] = 'Por los Ciclos de Vida';
                break;
            case '22003'://Triggers - Life Cycles 
                $content['content'] = 'Valor numérico fuera de rango';
                break;
        }
        return $content;
    }

    public function fnGetMethodResponse($status, $attributes = null) {
        $title = '';
        $data = ''; //could be a string or an array
        $content = '';
        if ($attributes != null) {
            if (isset($attributes['data'])) {
                $data = $attributes['data'];
            }
            if (isset($attributes['title'])) {
                $title = $attributes['title'];
            }
            if (isset($attributes['content'])) {
                $content = $attributes['content'];
            }
        }
        $content = $this->_fnSetDefaultContent($content);
        if ($status == 'ERROR') {
            if ($content == '') {
                $content = 'Error de proceso';
            }
            if ($title == '') {
                $title = 'Error!';
            }
        } elseif ($status == 'SUCCESS') {
            if ($content == '') {
                $content = 'Datos guardados';
            }
            if ($title == '') {
                $title = 'Exito!';
            }
        }
        return array('status' => $status, 'title' => $title, 'content' => $content, 'data' => $data);
    }


    private function _fnSetDefaultContent($content) {
        $default = array('CREATE' => 'Creado', 'DELETE' => 'Eliminado', 'ACTIVE' => 'Actividad cambiada', 'STATE' => 'Estado cambiado', 'RESTORE' => 'Restaurado',
            'PASSWORD_CHANGED' => 'Contraseña cambiada', 'EMAIL_CHANGED' => 'Correo electrónico cambiado'); //'ajaxError'=> 'Error interno del servidor', 'sessionExpired'=> 'Su sesión ha expirado, vuelva a iniciar sesión.'
        if (isset($default[$content])) {
            return $default[$content];
        }
        return $content;
    }

    //    Created: 29/06/2015 | Developer: reyro | Description: Before sending date to the DB set to yyyy-mm-dd unix format
    public function fnSetUnixFormatDate($date) {
        $date = explode('/', $date);
        //        dd/mm/yy
        $arrayDate['day'] = $date[0];
        $arrayDate['month'] = $date[1];
        $arrayDate['year'] = $date[2];
        return $arrayDate['year'].'-'.$arrayDate['month'].'-'.$arrayDate['day'];
    }

//END CLASS    
}

?>
