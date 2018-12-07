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
 * 自然语言处理NLP
 */
class AipNlp extends AipBase{

    /**
     * 分词
     * @var string
     */
    private $wordsegUrl = 'https://aip.baidubce.com/rpc/2.0/nlp/v1/wordseg';

    /**
     * 词性标注
     * @var string
     */
    private $wordposUrl = 'https://aip.baidubce.com/rpc/2.0/nlp/v1/wordpos';

    /**
     * 词向量
     * @var string
     */
    private $wordEmbeddingUrl = 'https://aip.baidubce.com/rpc/2.0/nlp/v2/word_emb_vec';

    /**
     * 词相似度
     * @var string
     */
    private $wordSimEmbeddingUrl = 'https://aip.baidubce.com/rpc/2.0/nlp/v2/word_emb_sim';

    /**
     * 中文DNN语言模型
     * @var string
     */
    private $dnnlmUrl = 'https://aip.baidubce.com/rpc/2.0/nlp/v2/dnnlm_cn';

    /**
     * 短文本相似度
     * @var string
     */
    private $simnetUrl = 'https://aip.baidubce.com/rpc/2.0/nlp/v2/simnet';

    /**
     * 情感观点挖掘
     * @var string
     */
    private $commentTagUrl = 'https://aip.baidubce.com/rpc/2.0/nlp/v2/comment_tag';

    /**
     * 词法分析
     * @var string
     */
    private $lexerUrl = 'https://aip.baidubce.com/rpc/2.0/nlp/v1/lexer';

    /**
     * 情感分析接口
     * @var string
     */
    private $sentimentClassifyUrl = 'https://aip.baidubce.com/rpc/2.0/nlp/v1/sentiment_classify';

    /**
     * 依存分析接口
     * @var string
     */
    private $depParserUrl = 'https://aip.baidubce.com/rpc/2.0/nlp/v1/depparser';


    /**
     * 格式化结果
     * @param $content string
     * @return mixed
     */
    protected function proccessResult($content){
        return json_decode(mb_convert_encoding($content, 'UTF8', 'GBK'), true);
    }

    /**
     * 分词
     * @param  string $query 自然语言
     * @param  array $options 可选参数
     * @return array
     */
    public function wordseg($query, $options=array()){

        $data = array();
        $data['query'] = urlencode(mb_convert_encoding($query, 'GBK', 'UTF8'));

        return $this->request($this->wordsegUrl, urldecode(json_encode($data)));
    }

    /**
     * 词性标注
     * @param  string $query 自然语言
     * @param  array $options 可选参数
     * @return array
     */
    public function wordpos($query, $options=array()){
        
        $data = array();
        $data['query'] = urlencode(mb_convert_encoding($query, 'GBK', 'UTF8'));

        return $this->request($this->wordposUrl, urldecode(json_encode($data)));
    }

    /**
     * 词向量
     * @param  string $word 自然语言
     * @return array
     */
    public function wordEmbedding($word, $options=array()){

        $data = array();
        $data['word'] = urlencode(mb_convert_encoding($word, 'GBK', 'UTF8'));

        $data = array_merge($data, $options);

        return $this->request($this->wordEmbeddingUrl, urldecode(json_encode($data)));
    }

    /**
     * 词相似度
     * @param  string $word1 自然语言1
     * @param  string $word2 自然语言2
     * @return array
     */
    public function wordSimEmbedding($word1, $word2, $options=array()){

        $data = array();
        $data['word_1'] = urlencode(mb_convert_encoding($word1, 'GBK', 'UTF8'));
        $data['word_2'] = urlencode(mb_convert_encoding($word2, 'GBK', 'UTF8'));

        $data = array_merge($data, $options);

        return $this->request($this->wordSimEmbeddingUrl, urldecode(json_encode($data)));
    }

    /**
     * 中文DNN语言模型
     * @param  string $text 自然语言
     * @param  array $options 可选参数
     * @return array
     */
    public function dnnlm($text, $options=array()){

        $data = array();
        $data['text'] = urlencode(mb_convert_encoding($text, 'GBK', 'UTF8'));

        $data = array_merge($data, $options);

        return $this->request($this->dnnlmUrl, urldecode(json_encode($data)));
    }

    /**
     * 短文本相似度
     * @param  string $text1 自然语言1
     * @param  string $text2 自然语言2
     * @param  array $options 可选参数
     * @return array
     */
    public function simnet($text1, $text2, $options=array()){

        $data = array();
        $data['text_1'] = urlencode(mb_convert_encoding($text1, 'GBK', 'UTF8'));
        $data['text_2'] = urlencode(mb_convert_encoding($text2, 'GBK', 'UTF8'));

        $data = array_merge($data, $options);

        return $this->request($this->simnetUrl, urldecode(json_encode($data)));
    }

    /**
     * 情感观点挖掘
     * @param  string $text 自然语言
     * @param  array $options 可选参数
     * @return array
     */
    public function commentTag($text, $options=array()){

        $data = array();
        $data['text'] = urlencode(mb_convert_encoding($text, 'GBK', 'UTF8'));

        $data = array_merge($data, $options);

        return $this->request($this->commentTagUrl, urldecode(json_encode($data)));
    }

    /**
     * 词法分析
     * @param  string $text 文本
     * @return array
     */
    public function lexer($text){

        $data = array();
        $data['text'] = urlencode(mb_convert_encoding($text, 'GBK', 'UTF8'));

        return $this->request($this->lexerUrl, urldecode(json_encode($data)));
    }

    /**
     * 情感分析
     * @param  string $text 自然语言
     * @param  array $options 可选参数
     * @return array
     */
    public function sentimentClassify($text, $options=array()){

        $data = array();
        $data['text'] = urlencode(mb_convert_encoding($text, 'GBK', 'UTF8'));

        $data = array_merge($data, $options);

        return $this->request($this->sentimentClassifyUrl, urldecode(json_encode($data)));
    }

    /**
     * 依存分析
     * @param  string $text 自然语言
     * @param  array $options 可选参数
     * @return array
     */
    public function depParser($text, $options=array()){

        $data = array();
        $data['text'] = urlencode(mb_convert_encoding($text, 'GBK', 'UTF8'));

        $data = array_merge($data, $options);

        return $this->request($this->depParserUrl, urldecode(json_encode($data)));
    }

}
