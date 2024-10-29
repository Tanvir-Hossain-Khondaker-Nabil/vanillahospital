<?php
/**
 * Created by PhpStorm.
 * User: Mobarok-RC
 * Date: 10/22/2022
 * Time: 11:53 AM
 */

namespace App\Services;


class ConfigInstaller implements Installer {

    public $options;

    public function __construct( $arr=array() ){

        $this->options = $arr;

    }

    public function put( $key, $value ){

        $this->options[$key] = $value;

    }

    public function convertOptions(){

        $arr = $this->options;
        $data = "<?php\n\n";
        $data .= "return [\n\n\n\n";

        foreach( $arr as $key => $value ){

            $data .= "'" . $key . "' => '" . $value . "',";
            $data .= "\n\n";

        }

        return $data."\n\n];";

    }

    public function install( $filename, $dir='' ){

        if( file_put_contents( $dir . $filename, $this->convertOptions() ) ){

            return true;

        }

        return false;

    }

}
