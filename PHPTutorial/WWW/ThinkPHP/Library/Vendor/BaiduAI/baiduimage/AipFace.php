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
 * 人脸检测
 */
class AipFace extends AipBase{

    /**
     * face detect api Url
     * @var string
     */
    private $detectUrl = 'https://aip.baidubce.com/rest/2.0/vis-faceattribute/v1/faceattribute';

    /**
     * match users api url
     * @var string
     */
    private $matchUrl = 'https://aip.baidubce.com/rest/2.0/face/v2/match';

    /**
     *
     * @var string
     */
    private $addUserUrl = 'https://aip.baidubce.com/rest/2.0/face/v2/faceset/user/add';

    /**
     *
     * @var string
     */
    private $updateUserUrl = 'https://aip.baidubce.com/rest/2.0/face/v2/faceset/user/update';

    /**
     *
     * @var string
     */
    private $deleteUserUrl = 'https://aip.baidubce.com/rest/2.0/face/v2/faceset/user/delete';

    /**
     *
     * @var string
     */
    private $verifyUserUrl = 'https://aip.baidubce.com/rest/2.0/face/v2/verify';

    /**
     *
     * @var string
     */
    private $identifyUserUrl = 'https://aip.baidubce.com/rest/2.0/face/v2/identify';

    /**
     *
     * @var string
     */
    private $getUserUrl = 'https://aip.baidubce.com/rest/2.0/face/v2/faceset/user/get';

    /**
     *
     * @var string
     */
    private $getGroupListUrl = 'https://aip.baidubce.com/rest/2.0/face/v2/faceset/group/getlist';

    /**
     *
     * @var string
     */
    private $getGroupUsersUrl = 'https://aip.baidubce.com/rest/2.0/face/v2/faceset/group/getusers';

    /**
     *
     * @var string
     */
    private $addGroupUserUrl = 'https://aip.baidubce.com/rest/2.0/face/v2/faceset/group/adduser';

    /**
     *
     * @var string
     */
    private $deleteGroupUserUrl = 'https://aip.baidubce.com/rest/2.0/face/v2/faceset/group/deleteuser';
 
    /**
     * @param  string $image 读取图像
     * @param  array $option 可选参数
     * @return array 检测结果
     * 
     */
    public function detect($image, $options=array()){

        $data = array();
        $data['image'] = $image;

        $data = array_merge($data, $options);

        return $this->request($this->detectUrl, $data);
    }

    /**
     * 图片 array base64 encode
     * @param  array $images
     * @return string
     */
    private function getEncodeImages($images){
        if(is_string($images)){
            return base64_encode(trim($images));
        }

        $result = array();
        foreach($images as $image){
            if(!empty($image)){
                $result[] = base64_encode($image);
            }
        }

        return $result;
    }

    /**
     * 两两比对
     * @param  array $images
     * @param  array $options
     * @return array
     */
    public function match($images, $options=array()){
        $data = array();
        $data['images'] = $images;

        $data = array_merge($data, $options);

        return $this->request($this->matchUrl, $data);      
    }

    /**
     * 格式检查
     * @param  string $url
     * @param  array $data
     * @return array
     */
    protected function validate($url, &$data){

        // user_info参数 不超过256B
        if(isset($data['user_info'])){

            if(strlen($data['user_info']) > 256){
                return array(
                    'error_code' => 'SDK103',
                    'error_msg' => 'user_info size error',
                );
            }
        }

        // group_id参数 组成为字母/数字/下划线，且不超过48B
        if(isset($data['group_id'])){

            if(is_array($data['group_id'])){
                $groupIds = $data['group_id'];
            }else{
                $groupIds = array(
                    $data['group_id'],
                );
            }

            foreach($groupIds as $groupId){            
                if(!preg_match('/^\w+$/', $groupId)){
                    return array(
                        'error_code' => 'SDK104',
                        'error_msg' => 'group_id format error',
                    );       
                }

                if(strlen($groupId) > 48){
                    return array(
                        'error_code' => 'SDK105',
                        'error_msg' => 'group_id size error',
                    );
                }
            }

            $data['group_id'] = implode(',', $groupIds);
        }

        // uid参数 组成为字母/数字/下划线，且不超过128B
        if(isset($data['uid'])){
            if(!preg_match('/^\w+$/', $data['uid'])){
                return array(
                    'error_code' => 'SDK106',
                    'error_msg' => 'uid format error',
                );       
            }

            if(strlen($data['uid']) > 128){
                return array(
                    'error_code' => 'SDK107',
                    'error_msg' => 'uid size error',
                );
            }
        }

        if(isset($data['image'])){          
            $data['image'] = $this->getEncodeImages($data['image']);

            //编码后小于10m
            if(empty($data['image']) || strlen($data['image']) >= 10 * 1024 * 1024){
                return array(
                    'error_code' => 'SDK100',
                    'error_msg' => 'image size error',
                );
            }
        }elseif(isset($data['images'])){
            $images = $this->getEncodeImages($data['images']);
            $data['images'] = implode(',', $images);

            // 人脸比对 编码后小于20m 其他 10m
            if($url == $this->matchUrl){
                if(count($images) < 2 || strlen($data['images']) >= 20 * 1024 * 1024){
                    return array(
                        'error_code' => 'SDK100',
                        'error_msg' => 'image size error',
                    );
                }
            }else{
                if(count($images) < 1 || strlen($data['images']) >= 10 * 1024 * 1024){
                    return array(
                        'error_code' => 'SDK100',
                        'error_msg' => 'image size error',
                    );
                }
            }
        }

        return true;
    }

    /**
     * 添加用户
     * @param string $uid
     * @param string $userInfo
     * @param string $groupId
     * @param array $image
     * @param array $options
     * @return array
     */
    public function addUser($uid, $userInfo, $groupId, $image, $options=array()){

        $data = array();
        $data['uid'] = $uid;
        $data['user_info'] = $userInfo;
        $data['group_id'] = $groupId;
        $data['image'] = $image;
        
        $data = array_merge($data, $options);

        return $this->request($this->addUserUrl, $data);
    }

    /**
     * 更新用户
     * @param string $uid
     * @param string $userInfo
     * @param string $groupId
     * @param array $images
     * @return array
     */
    public function updateUser($uid, $userInfo, $groupId, $image){
        
        $data = array();
        $data['uid'] = $uid;
        $data['user_info'] = $userInfo;
        $data['group_id'] = $groupId;
        $data['image'] = $image;

        return $this->request($this->updateUserUrl, $data);
    }

    /**
     * 删除用户
     * @param  string $uid
     * @param  string $groupId
     * @return array
     */
    public function deleteUser($uid, $options=array()){
        
        $data = array();
        $data['uid'] = $uid;

        $data = array_merge($data, $options);

        return $this->request($this->deleteUserUrl, $data);
    }

    /**
     * 认证用户
     * @param  string $uid
     * @param  array $images
     * @param  array $options
     * @return array
     */
    public function verifyUser($uid, $groupId, $image, $options=array()){
        
        $data = array();
        $data['uid'] = $uid;
        $data['group_id'] = $groupId;
        $data['image'] = $image;

        $data = array_merge($data, $options);

        return $this->request($this->verifyUserUrl, $data);
    }

    /**
     * 识别用户
     * @param  string $groupId
     * @param  array $image
     * @param  array  $options
     * @return array
     */
    public function identifyUser($groupId, $image, $options=array()){
        $data = array();
        $data['group_id'] = $groupId;
        $data['image'] = $image;

        $data = array_merge($data, $options);

        return $this->request($this->identifyUserUrl, $data);        
    }

    /**
     * 用户信息查询
     * @param  string $uid
     * @param  array  $options
     * @return array
     */
    public function getUser($uid, $options=array()){
        
        $data = array();
        $data['uid'] = $uid;

        $data = array_merge($data, $options);
        
        return $this->request($this->getUserUrl, $data);       
    }

    /**
     * APP下组列表查询
     * @param  array  $options
     * @return array
     */
    public function getGroupList($options=array()){

        $data = array();

        $data = array_merge($data, $options);

        return $this->request($this->getGroupListUrl, $data);  
    }

    /**
     * 组内用户列表查询
     * @param  string $groupId
     * @param  array  $options
     * @return array
     */
    public function getGroupUsers($groupId, $options=array()){

        $data = array();
        $data['group_id'] = $groupId;

        $data = array_merge($data, $options);

        return $this->request($this->getGroupUsersUrl, $data); 
    }

    /**
     * 组内添加用户
     * @param string $srcGroupId
     * @param string $dstGroupId
     * @param string $uid
     * @return array
     */
    public function addGroupUser($srcGroupId, $dstGroupId, $uid){
        
        $data = array();
        $data['src_group_id'] = $srcGroupId;
        $data['group_id'] = $dstGroupId;
        $data['uid'] = $uid;

        return $this->request($this->addGroupUserUrl, $data);  
    }

    /**
     * 组内删除用户
     * @param  string $groupId
     * @param  string $uid
     * @return array
     */
    public function deleteGroupUser($groupId, $uid){
        
        $data = array();
        $data['group_id'] = $groupId;
        $data['uid'] = $uid;

        return $this->request($this->deleteGroupUserUrl, $data);         
    }

}
