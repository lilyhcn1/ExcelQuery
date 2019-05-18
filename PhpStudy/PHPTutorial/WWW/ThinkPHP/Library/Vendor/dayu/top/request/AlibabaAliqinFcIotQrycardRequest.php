<?php
/**
 * TOP API: alibaba.aliqin.fc.iot.qrycard request
 * 
 * @author auto create
 * @since 1.0, 2016.10.24
 */
class AlibabaAliqinFcIotQrycardRequest
{
	/** 
	 * 外部计费号
	 **/
	private $billReal;
	
	/** 
	 * 外部计费来源
	 **/
	private $billSource;
	
	/** 
	 * ICCID
	 **/
	private $iccid;
	
	private $apiParas = array();
	
	public function setBillReal($billReal)
	{
		$this->billReal = $billReal;
		$this->apiParas["bill_real"] = $billReal;
	}

	public function getBillReal()
	{
		return $this->billReal;
	}

	public function setBillSource($billSource)
	{
		$this->billSource = $billSource;
		$this->apiParas["bill_source"] = $billSource;
	}

	public function getBillSource()
	{
		return $this->billSource;
	}

	public function setIccid($iccid)
	{
		$this->iccid = $iccid;
		$this->apiParas["iccid"] = $iccid;
	}

	public function getIccid()
	{
		return $this->iccid;
	}

	public function getApiMethodName()
	{
		return "alibaba.aliqin.fc.iot.qrycard";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->billReal,"billReal");
		RequestCheckUtil::checkNotNull($this->billSource,"billSource");
		RequestCheckUtil::checkNotNull($this->iccid,"iccid");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
