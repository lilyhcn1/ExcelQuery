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
 * 知识图谱
 */
class AipKg extends AipBase{

    /**
     * url
     * @var string
     */
    public $getUserTasksUrl = 'https://aip.baidubce.com/rest/2.0/kg/v1/pie/task_query';

    /**
     * url
     * @var string
     */
    public $getTaskInfoUrl = 'https://aip.baidubce.com/rest/2.0/kg/v1/pie/task_info';

    /**
     * url
     * @var string
     */
    public $createTaskUrl = 'https://aip.baidubce.com/rest/2.0/kg/v1/pie/task_create';

    /**
     * url
     * @var string
     */
    public $updateTaskUrl = 'https://aip.baidubce.com/rest/2.0/kg/v1/pie/task_update';

    /**
     * url
     * @var string
     */
    public $startTaskUrl = 'https://aip.baidubce.com/rest/2.0/kg/v1/pie/task_start';

    /**
     * url
     * @var string
     */
    public $getTaskStatusUrl = 'https://aip.baidubce.com/rest/2.0/kg/v1/pie/task_status';

    /**
     * @param  array $options 可选参数
     * @return array
     */
    public function getUserTasks($options=array()){

        $data = array();

        $data = array_merge($data, $options);  
        
        return $this->request($this->getUserTasksUrl, $data);
    }

    /**
     * @param  int $taskId 任务ID
     * @return array
     */
    public function getTaskInfo($taskId){

        $data = array();
        $data['id'] = $taskId; 
        
        return $this->request($this->getTaskInfoUrl, $data);
    }

    /**
     * @param  string $name
     * @param  string $tplStr
     * @param  string $inputMapping
     * @param  string $outputFile
     * @param  string $urlPattern
     * @param  array $options 可选参数
     * @return array
     */
    public function createTask(
        $name,
        $tplStr,
        $inputMapping,
        $outputFile,
        $urlPattern,
        $options=array()
    ){

        $data = array();
        $data['name'] = $name;
        $data['template_content'] = $tplStr;
        $data['input_mapping_file'] = $inputMapping;
        $data['url_pattern'] = $urlPattern;
        $data['output_file'] = $outputFile;

        $data = array_merge($data, $options);  
        
        return $this->request($this->createTaskUrl, $data);
    }

    /**
     * @param  int $taskId 任务ID
     * @param  array $options 可选参数
     * @return array
     */
    public function updateTask($taskId, $options=array()){

        $data = array();
        $data['id'] = $taskId;

        $data = array_merge($data, $options);

        return $this->request($this->updateTaskUrl, $data);
    }    

    /**
     * @param  int $taskId 任务ID
     * @return array
     */
    public function startTask($taskId){

        $data = array();
        $data['id'] = $taskId; 
        
        return $this->request($this->startTaskUrl, $data);
    }

    /**
     * @param  int $taskId 任务ID
     * @return array
     */
    public function getTaskStatus($taskId){

        $data = array();
        $data['id'] = $taskId; 

        return $this->request($this->getTaskStatusUrl, $data);
    }
}
