<?php

namespace MelisPlatformFrameworks\Support;

class MelisPlatformFrameworks
{
    /**
     * Function to download framework skeleton
     *
     * @param $frameworkName
     * @return array
     */
    public static function downloadFrameworkSkeleton($frameworkName)
    {
        $tempZipFile = '';

        $result = [
            'success' => true,
            'message' => ucfirst($frameworkName).' skeleton downloaded successfully'
        ];

        $isCliReqs = php_sapi_name() == 'cli' ? true : false;

        //third party file
        $thirdPartyFolder = !$isCliReqs ? $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'thirdparty' : 'thirdparty';
        //create thirdparty folder if not exist
        if(!file_exists($thirdPartyFolder)) {
            mkdir($thirdPartyFolder);
            chmod($thirdPartyFolder, 0777);
        }

        if(is_writable($thirdPartyFolder)) {
            //get frameworks config
            $frameworksConfig = require __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'module.config.php';
            //get market place url
            $marketplace = $frameworksConfig['melis-marketplace-url'];

            /**
             * Get framework zip skeleton on marketplace
             */
            try {
                //get framework zip file path
                $zipFil = $marketplace . $frameworksConfig['third-party-framework-skeleton'][strtolower($frameworkName)];
                //check if curl is available
                if (function_exists('curl_version')) {
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $zipFil);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    $fwSkeleton = curl_exec($ch);
                    $retCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    /**
                     * check if zip file exist
                     */
                    if($retCode != '200'){
                        //cannot find zip file
                        $result['success'] = false;
                        $result['message'] = 'Error on downloading framework zip file.';
                    }
                    curl_close($ch);
                } else {
                    $fwSkeleton = file_get_contents($zipFil);
                    $fileHeaders = @get_headers($zipFil);

                    /**
                     * check if zip file exist
                     */
                    if(!strpos($fileHeaders[0], '200')){
                        //cannot find zip file
                        $result['success'] = false;
                        $result['message'] = 'Error on downloading framework zip file.';
                    }
                }

                /**
                 * Create temporary file to store
                 * the framework skeleton
                 */
                $tempZipFile = $thirdPartyFolder .DIRECTORY_SEPARATOR. "temp_file.zip";
                $file = fopen($tempZipFile, "w+");
                fputs($file, $fwSkeleton);
                fclose($file);

            }catch (\Exception $ex){
                $result['success'] = false;
                $result['message'] = $ex->getMessage();
            }

            /**
             * Process the extraction
             */
            if (file_exists($tempZipFile)) {
                chmod($tempZipFile, 0777);

                $zip = new \ZipArchive();
                $res = $zip->open($tempZipFile);
                if ($res === true) {
                    // extract it to thirdparty folder
                    if(!$zip->extractTo($thirdPartyFolder)){
                        //cannot extract zip file
                        $result['success'] = false;
                        $result['message'] = 'Cannot extract zip file inside thirdparty folder.';
                    }
                    $zip->close();
                }else{
                    //cannot open temporary zip file to extract
                    $result['success'] = false;
                    $result['message'] = 'Cannot open zip file on thirdparty folder.';
                }
                //remove the temporary zip file
                unlink($tempZipFile);
            }else{
                $result['success'] = false;
                $result['message'] = 'Cannot find path: '.$tempZipFile;
            }
        }else{
            //thirdpary folder not writable
            $result['success'] = false;
            $result['message'] = 'Thirdparty folder is not writable.';
        }

        return $result;
    }
}