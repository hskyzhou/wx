<?php 
	namespace App\Services;

	use App\Services\Contracts\WxReceiveNormalContract;

	class WxReceiveNormalService implements WxReceiveNormalContract{
		/**
		 * 设置返回 普通文本信息
		 * 
		 * @param		
		 * 
		 * @author		xezw211@gmail.com
		 * 
		 * @date		2016-03-16 19:58:34
		 * 
		 * @return		
		 */
		public function sendTextInfo($data){
			$template = $this->getTextXml();

			return sprintf($template, $data['ToUserName'], $data['FromUserName'], time(), $data['Content']);
		}


		/**
		 * 
		 * 
		 * @param		
		 * 
		 * @author		xezw211@gmail.com
		 * 
		 * @date		2016-03-16 19:44:14
		 * 
		 * @return		
		 */
		protected getTextXml(){
			return "
				<xml>
				    <ToUserName><![CDATA[%s]]></ToUserName>
				    <FromUserName><![CDATA[%s]]></FromUserName>
				    <CreateTime>%s</CreateTime>
				    <MsgType><![CDATA[text]]></MsgType>
				    <Content><![CDATA[%s]]></Content>
			    </xml>
			";
		}
	}