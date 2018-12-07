<?php
/*
* Copyright (c) 2017 Baidu.com, Inc. All Rights Reserved
*
* Licensed under the Apache License, Version 2.0 (the "License"); you may not
* use this file except in compliance with the License. You may obtain a copy of
* the License at
*
* Http://www.apache.org/licenses/LICENSE-2.0
*
* Unless required by applicable law or agreed to in writing, software
* distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
* WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
* License for the specific language governing permissions and limitations under
* the License.
*/

require_once 'lib/AipBase.php';

/**
 * 文字OCR
 */
class AipOcr extends AipBase{

    /**
     * idcard api url
     * @var string
     */
    private $idcardUrl = 'https://aip.baidubce.com/rest/2.0/ocr/v1/idcard';
    
    /**
     * bankcard api url
     * @var string
     */
    private $bankcardUrl = 'https://aip.baidubce.com/rest/2.0/ocr/v1/bankcard';
    
    /**
     * general api url
     * @var string
     */
    private $generalUrl = 'https://aip.baidubce.com/rest/2.0/ocr/v1/general';

    /**
     * basic general api url
     * @var string
     */
    private $basicGeneralUrl = 'https://aip.baidubce.com/rest/2.0/ocr/v1/general_basic';

    /**
     * web image url
     * @var string
     */
    private $webImageUrl = 'https://aip.baidubce.com/rest/2.0/ocr/v1/webimage';

    /**
     * enhanced general url
     * @var string
     */
    private $enhancedGeneralUrl = 'https://aip.baidubce.com/rest/2.0/ocr/v1/general_enhanced';    

    /**
     * @var string
     */
    private $drivingLicenseUrl = 'https://aip.baidubce.com/rest/2.0/ocr/v1/driving_license';  

    /**
     * @var string
     */
    private $vehicleLicenseUrl = 'https://aip.baidubce.com/rest/2.0/ocr/v1/vehicle_license';  

    /**
     * @var string
     */
    private $tableRequestUrl = 'https://aip.baidubce.com/rest/2.0/solution/v1/form_ocr/request';

    /**
     * @var string
     */
    private $tableResultUrl = 'https://aip.baidubce.com/rest/2.0/solution/v1/form_ocr/get_request_result';

    /**
     * @var string
     */
    private $licensePlateUrl = 'https://aip.baidubce.com/rest/2.0/ocr/v1/license_plate';

    /**
     * @var string
     */ 
    private $accurateUrl = 'https://aip.baidubce.com/rest/2.0/ocr/v1/accurate';

    /**
     * @var string
     */
    private $basicAccurateUrl = 'https://aip.baidubce.com/rest/2.0/ocr/v1/accurate_basic';

    /**
     * @var string
     */
    private $receiptUrl = 'https://aip.baidubce.com/rest/2.0/ocr/v1/receipt';

    /**
     * @var string
     */
    private $businessLicenseUrl = 'https://aip.baidubce.com/rest/2.0/ocr/v1/business_license';

    /**
     * @param  string $image 图像读取
     * @param  bool $isFront 身份证是 true正面 false反面
     * @param  array $options 可选参数
     * @return array
     */
    public function idcard($image, $isFront, $options=array()){

        $data = array();
        $data['image'] = $image;
        $data['id_card_side'] = $isFront ? 'front' : 'back';

        $data = array_merge($data, $options);

        return $this->request($this->idcardUrl, $data);
    }
    
    /**
     * @param  string $image 图像读取
     * @return array
     */
    public function bankcard($image){

        $data = array();
        $data['image'] = $image;

        return $this->request($this->bankcardUrl, $data);
    }

    /**
     * @param  string $image 图像读取
     * @param  array $options 可选参数
     * @return array
     */
    public function general($image, $options=array()){

        $data = array();
        $data['image'] = $image;

        $data = array_merge($data, $options);  
        
        return $this->request($this->generalUrl, $data);
    }

    /**
     * @param  string $image 图像读取
     * @param  array $options 可选参数
     * @return array
     */
    public function basicGeneral($image, $options=array()){

        $data = array();
        $data['image'] = $image;

        $data = array_merge($data, $options);  
        
        return $this->request($this->basicGeneralUrl, $data);
    }

    /**
     * @param  string $image 图像读取
     * @param  options 接口可选参数
     *                detect_direction : true/false
     *                language_type :
     *                <p>识别语言类型，若不传则默认为CHN_ENG。
     *                  可选值包括：CHN_ENG - 中英文混合；
     *                  ENG - 英文；
     *                  POR - 葡萄牙语；
     *                  FRE - 法语；
     *                  GER - 德语；
     *                  ITA - 意大利语；
     *                  SPA - 西班牙语；
     *                  RUS - 俄语；
     *                  JAP - 日语</p>
     *                mask : 表示mask区域的黑白灰度图片，白色代表选中, base64编码
     *                detect_language: true/false
     * @return array
     */
    public function webImage($image, $options=array()){

        $data = array();
        $data['image'] = $image;

        $data = array_merge($data, $options);
        
        return $this->request($this->webImageUrl, $data);   
    }

    /**
     * @param  string $image 图像读取
     * @param  options 接口可选参数
     *                detect_direction : true/false
     *                language_type :
     *                <p>识别语言类型，若不传则默认为CHN_ENG。
     *                  可选值包括：CHN_ENG - 中英文混合；
     *                  ENG - 英文；
     *                  POR - 葡萄牙语；
     *                  FRE - 法语；
     *                  GER - 德语；
     *                  ITA - 意大利语；
     *                  SPA - 西班牙语；
     *                  RUS - 俄语；
     *                  JAP - 日语</p>
     *                mask : 表示mask区域的黑白灰度图片，白色代表选中, base64编码
     *                detect_language: true/false
     * @return array
     */
    public function enhancedGeneral($image, $options=array()){

        $data = array();
        $data['image'] = $image;

        $data = array_merge($data, $options);
        
        return $this->request($this->enhancedGeneralUrl, $data);   
    }

    /**
     * 行驶证
     * @param  string $image 图像读取
     * @param  options 接口可选参数
     * @return array
     */
    public function vehicleLicense($image, $options=array()){

        $data = array();
        $data['image'] = $image;

        $data = array_merge($data, $options);

        return $this->request($this->vehicleLicenseUrl, $data);   
    }

    /**
     * 驾驶证
     * @param  string $image 图像读取
     * @param  options 接口可选参数
     * @return array
     */
    public function drivingLicense($image, $options=array()){

        $data = array();
        $data['image'] = $image;

        $data = array_merge($data, $options);

        return $this->request($this->drivingLicenseUrl, $data);   
    }

    /**
     * 格式检查
     * @param  string $url
     * @param  array $data
     * @return array
     */
    protected function validate($url, &$data){
        if($url === $this->tableResultUrl){
            return true;
        }

        // 支持url
        if(preg_match('/^\w{1,128}:\/\//', $data['image'])){
            $data['url'] = $data['image'];
            unset($data['image']);
            return true;
        }

        $imageInfo = AipImageUtil::getImageInfo($data['image']);

        //图片格式检查
        if(!in_array($imageInfo['mime'], array('image/jpeg', 'image/png', 'image/bmp'))){
            return array(
                'error_code' => 'SDK109',
                'error_msg' => 'unsupported image format',
            );
        }

        //图片大小检查
        if($imageInfo['width'] < 15 || $imageInfo['width'] > 4096 || $imageInfo['height'] < 15 || $imageInfo['height'] > 4096){
            return array(
                'error_code' => 'SDK101',
                'error_msg' => 'image length error',
            );
        }

        $data['image'] = base64_encode($data['image']);

        //编码后小于4m
        if(strlen($data['image']) >= 4 * 1024 * 1024){
            return array(
                'error_code' => 'SDK100',
                'error_msg' => 'image size error',
            );
        }

        return true;
    }

    /**
     * 异步请求
     * @param  string $image 图像读取
     * @param  options 接口可选参数
     * @return array
     */
    public function tableRecognitionAsync($image, $options=array()){

        $data = array();
        $data['image'] = $image;

        $data = array_merge($data, $options);

        return $this->request($this->tableRequestUrl, $data);   
    }

    /**
     * 获取结果
     * @param  string $requestId 图像读取
     * @param  options 接口可选参数
     * @return array
     */
    public function getTableRecognitionResult($requestId, $options=array()){

        $data = array();
        $data['request_id'] = $requestId;

        $data = array_merge($data, $options);

        return $this->request($this->tableResultUrl, $data);   
    }

    /**
     * 同步请求
     * @param  string $image 图像读取
     * @param  options 接口可选参数
     * @return array
     */
    public function tableRecognition($image, $options=array(), $timeout=10000){
        $result = $this->tableRecognitionAsync($image);

        if(isset($result['error_code'])){
            return $result;
        }

        $requestId = $result['result'][0]['request_id'];
        $count = ceil($timeout / 1000);
        for($i=0; $i<$count; $i++){

            $result = $this->getTableRecognitionResult($requestId, $options);

            // 完成
            if($result['result']['ret_code'] == 3){ 
                break;
            }

            sleep(1);
        }

        return $result;
    }

    /**
     * 车牌
     * @param  string $image 图像读取
     * @param  options 接口可选参数
     * @return array
     */
    public function licensePlate($image, $options=array()){

        $data = array();
        $data['image'] = $image;

        $data = array_merge($data, $options);

        return $this->request($this->licensePlateUrl, $data);   
    }

    /**
     * @param  string $image 图像读取
     * @param  array $options 可选参数
     * @return array
     */
    public function accurate($image, $options=array()){

        $data = array();
        $data['image'] = $image;

        $data = array_merge($data, $options);  
        
        return $this->request($this->accurateUrl, $data);
    }

    /**
     * @param  string $image 图像读取
     * @param  array $options 可选参数
     * @return array
     */
    public function basicAccurate($image, $options=array()){

        $data = array();
        $data['image'] = $image;

        $data = array_merge($data, $options);  
        
        return $this->request($this->basicAccurateUrl, $data);
    }

    /**
     * @param  string $image 图像读取
     * @param  array $options 可选参数
     * @return array
     */
    public function receipt($image, $options=array()){

        $data = array();
        $data['image'] = $image;

        $data = array_merge($data, $options);  
        
        return $this->request($this->receiptUrl, $data);
    }

    /**
     * @param  string $image 图像读取
     * @param  array $options 可选参数
     * @return array
     */
    public function businessLicense($image, $options=array()){

        $data = array();
        $data['image'] = $image;

        $data = array_merge($data, $options);
        
        return $this->request($this->businessLicenseUrl, $data);
    }

}
