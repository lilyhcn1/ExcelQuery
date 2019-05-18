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
 * 黄反识别
 */
class AipImageCensor extends AipBase{

    /**
     * antiporn api url
     * @var string
     */
    private $antiPornUrl = 'https://aip.baidubce.com/rest/2.0/antiporn/v1/detect';

    /**
     * antiporn gif api url
     * @var string
     */
    private $antiPornGifUrl = 'https://aip.baidubce.com/rest/2.0/antiporn/v1/detect_gif';

    /**
     * antiterror api url
     * @var string
     */
    private $antiTerrorUrl = 'https://aip.baidubce.com/rest/2.0/antiterror/v1/detect';

    /**
     * @var string
     */
    private $faceAuditUrl = 'https://aip.baidubce.com/rest/2.0/solution/v1/face_audit';

    /**
     * @var string
     */
    private $imageCensorCombUrl = 'https://aip.baidubce.com/api/v1/solution/direct/img_censor';

    /**
     * @param  string $image 图像读取
     * @return array
     */
    public function antiPorn($image){

        $data = array();
        $data['image'] = $image;

        return $this->request($this->antiPornUrl, $data);
    }

    /**
     * @param  string $image 图像读取
     * @return array
     */
    public function multi_antiporn($images){

        $data = array();
        foreach($images as $image){
            $data[] = array(
                'image' => base64_encode($image),
            );
        }

        return $this->multi_request($this->antiPornUrl, $data);
    }

    /**
     * 格式检查
     * @param  string $url
     * @param  array $data
     * @return array
     */
    protected function validate($url, &$data){

        if(!is_array($data)){
            return true;
        }

        if(isset($data['images'])){
            $imageB64s = array();
            foreach($data['images'] as $image){
                $imageInfo = AipImageUtil::getImageInfo($image);

                //图片格式检查
                if(!in_array($imageInfo['mime'], array('image/jpeg', 'image/png', 'image/bmp', 'image/gif'))){
                    return array(
                        'error_code' => 'SDK109',
                        'error_msg' => 'unsupported image format',
                    );
                }

                //编码后小于4m
                $imageB64 = base64_encode($image);
                if(strlen($imageB64) >= 4 * 1024 * 1024){
                    return array(
                        'error_code' => 'SDK100',
                        'error_msg' => 'image size error',
                    );
                }

                $imageB64s[] = $imageB64;
            }

            $data['images'] = implode(',', $imageB64s);
        }else if(isset($data['image'])){
            $imageInfo = AipImageUtil::getImageInfo($data['image']);
            $data['image'] = base64_encode($data['image']);

            //Gif 格式校验
            if($url == $this->antiPornGifUrl){

                if($imageInfo['mime'] != 'image/gif'){
                    return array(
                        'error_code' => 'SDK109',
                        'error_msg' => 'unsupported image format',
                    );
                }

                return true;
            }

            //图片格式检查
            if(!in_array($imageInfo['mime'], array('image/jpeg', 'image/png', 'image/bmp', 'image/gif'))){
                return array(
                    'error_code' => 'SDK109',
                    'error_msg' => 'unsupported image format',
                );
            }

            //编码后小于4m
            if(strlen($data['image']) >= 4 * 1024 * 1024){
                return array(
                    'error_code' => 'SDK100',
                    'error_msg' => 'image size error',
                );
            }

        }

        return true;
    }

    /**
     * @param  string $image 图像读取
     * @return array
     */
    public function antiPornGif($image){

        $data = array();
        $data['image'] = $image;

        return $this->request($this->antiPornGifUrl, $data);
    }

    /**
     * @param  string $image 图像读取
     * @return array
     */
    public function antiTerror($image){

        $data = array();
        $data['image'] = $image;

        return $this->request($this->antiTerrorUrl, $data);
    }

    /**
     * @param  string $images 图像读取
     * @return array
     */
    public function faceAudit($images, $configId=''){

        // 非数组则处理为数组
        if(!is_array($images)){
            $images = array(
                $images,
            );
        }

        $data = array(
            'configId' => $configId,
        );

        $isUrl = substr(trim($images[0]), 0, 4) === 'http';
        if(!$isUrl){
            $data['images'] = $images;
        }else{
            $urls = array();
            
            foreach($images as $url){
                $urls[] = urlencode($url);
            }
            
            $data['imgUrls'] = implode(',', $urls);
        }

        return $this->request($this->faceAuditUrl, $data);
    }

    /**
     * @param  string $image 图像读取
     * @return array
     */
    public function imageCensorComb($image, $scenes='antiporn', $options=array()){

        $scenes = !is_array($scenes) ? explode(',', $scenes) : $scenes;
        
        $data = array(
            'scenes' => $scenes,
        );

        $isUrl = substr(trim($image), 0, 4) === 'http';
        if(!$isUrl){
            $data['image'] = base64_encode($image);
        }else{
            $data['imgUrl'] = $image;
        }

        $data = array_merge($data, $options);

        return $this->request($this->imageCensorCombUrl, json_encode($data), array(
            'Content-Type' => 'application/json',
        ));
    }
}
