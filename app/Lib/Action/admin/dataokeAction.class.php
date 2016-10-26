<?php
class dataokeAction extends BackendAction 
{
	public function _initialize() 
	{
		parent::_initialize();
	}
	public function index()
	{
		$zym_78 = C('ftx_dtk');
		if(!$zym_78)
		{
			$zym_77 = '您还没有设置大淘客appkey，请先到大淘客平台申请,<span class="red"><a href="http://www.dataoke.com/ucenter/appkey_apply.asp" target="_blank">点此申请</a></span>,再到网站后台采集设置中填写。';
		}
		else
		{
			$zym_77 = '杨他他淘宝客与嗨推网大淘客平台数据合作，可通过大淘客API接口采集内部优惠券及商品数据，<span class="red"><a href="http://bbs.yangtata.com/forum.php?mod=viewthread&tid=5084" target="_blank">点此查看采集教程 </a></span>';
		}
		$this->assign('info', $zym_77);
		$this->display();
	}
	public function setting()
	{
		if(IS_POST)
		{
			$zym_75 = C('ftx_fz');
			$zym_76 = I('page','','intval');
			$zym_80 = I('mod','','intval');
			$zym_81 = C('ftx_youpid');
			if(!$zym_75)
			{
				$this->ajaxReturn(0, '请先在采集设置中绑定分类ID!');
			}
			F('dataoke_setting', array( 'page' => $zym_76, 'mod' => $zym_80, 'hightpid' => $zym_81, ));
			$this->collect();
		}
	}
	public function collect() 
	{
		if (false === $zym_86 = F('dataoke_setting')) 
		{
			$this->ajaxReturn(0, L('illegal_parameters'));
		}
		$zym_80 = $zym_86['mod'];
		$zym_85 =C('ftx_lastyj');
		if($zym_80==2 || $zym_80 == 3)
		{
			$zym_84 = check_cookies('http://pub.alimama.com/common/getUnionPubContextInfo.json');
			if(!$zym_84)
			{
				$this->ajaxReturn(0, '登陆超时！请重新获取阿里妈妈cookies，否则获取不到推广链接。');
			}
		}
		$zym_76 = $zym_86['page'];
		$zym_82 = array( 'dataoke_file'=> FTX_DATA_PATH.'cookies/dataoke2.txt', );
		$zym_83 = $this->_get('p', 'intval', $zym_76);
		if($zym_83==1)
		{
			$zym_74 = 0;
		}
		else
		{
			if(F('totalcoll'))
			{
				$zym_74 = F('totalcoll');
			}
			else
			{
				$zym_74 = 0;
			}
		}
		$zym_73 = http($zym_82['dataoke_file']);
		if(!$zym_73 && $zym_73 = '操作异常！')
		{
			$this->ajaxReturn(0, '请先获取数据。');
		}
		else
		{
			$zym_64 = json_decode($zym_73,true);
			$zym_65 = count($zym_64);
			$zym_63 = $zym_83 - 1;
			$zym_62 = $zym_63*1;
			$zym_60 = $zym_83*1;
			$zym_61=0;
			if($zym_65)
			{
				for($zym_66=$zym_62;$zym_66<$zym_60;$zym_66++)
				{
					$zym_67 = $zym_64[$zym_66]['Org_Price'];
					$zym_72 = $zym_64[$zym_66]['Price'];
					$zym_71 = $zym_64[$zym_66]['IsTmall'];
					if($zym_71)
					{
						$zym_70 = 'B';
					}
					else
					{
						$zym_70 = 'C';
					}
					$zym_68 = $zym_64[$zym_66]['Quan_time'];
					$zym_68 = str_replace('00:00:00','',$zym_68);
					$zym_69 = $zym_64[$zym_66]['Quan_surplus'];
					$zym_88 = $zym_64[$zym_66]['Quan_receive'];
					$zym_112 = $zym_64[$zym_66]['Quan_price'];
					$zym_107 = $zym_64[$zym_66]['Quan_condition'];
					$zym_106 = $zym_64[$zym_66]['SellerID'];
					$zym_105 =$zym_64[$zym_66]['Cid'];
					$zym_103 =$zym_64[$zym_66]['GoodsID'];
					$zym_104 = $zym_64[$zym_66]['Quan_id'];
					$zym_108 = $zym_64[$zym_66]['Sales_num'];
					$zym_109 =$zym_64[$zym_66]['Introduce'];
					$zym_115 =$zym_64[$zym_66]['Pic'];
					$zym_114 =$zym_64[$zym_66]['Title'];
					$zym_113 = $zym_64[$zym_66]['Commission'];
					$zym_110 = $zym_64[$zym_66]['Commission_jihua'];
					$zym_111 = $zym_64[$zym_66]['Commission_queqiao'];
					$zym_102 = $zym_64[$zym_66]['Jihua_link'];
					if($zym_111>$zym_110)
					{
						if($zym_80==3)
						{
							$zym_101 = $this->getqueqiao($zym_103);
						}
						else
						{
							$zym_101 = 'http://uland.taobao.com/coupon/edetail?activityId='.$zym_104.'&pid='.$zym_86['hightpid'].'&itemId='.$zym_103.'&src=cd_cdll';
						}
					}
					if($zym_110>$zym_111)
					{
						if($zym_102)
						{
							if($zym_80 ==2 || $zym_80 ==3)
							{
								$zym_93 = $this->getjihua($zym_103);
							}
							if($zym_80==3)
							{
								$zym_101 = $this->getclick($zym_103);
							}
							else
							{
								$zym_101 = 'http://uland.taobao.com/coupon/edetail?activityId='.$zym_104.'&pid='.$zym_86['hightpid'].'&itemId='.$zym_103.'&src=cd_cdll&dx=1';
							}
						}
						else
						{
							if($zym_80==3)
							{
								$zym_101 = $this->getclick($zym_103);
							}
							else
							{
								$zym_101 = 'http://uland.taobao.com/coupon/edetail?activityId='.$zym_104.'&pid='.$zym_86['hightpid'].'&itemId='.$zym_103.'&src=cd_cdll';
							}
						}
					}
					$zym_92 = $zym_113;
					$zym_91 = $zym_72*$zym_92/100;
					$zym_89 = number_format($zym_91,1);
					$zym_90 = 'http://taoquan.taobao.com/coupon/unify_apply.htm?sellerId='.$zym_106.'&activityId='.$zym_104;
					$zym_94 = 'http://h5.m.taobao.com/ump/coupon/detail/index.html?sellerId='.$zym_106.'&activityId='.$zym_104;
					$zym_95 = round(($zym_72/$zym_67)*10,1);
					$zym_100 = C('ftx_coupon_add_time');
					if($zym_100)
					{
						$zym_99 = (int)(time()+$zym_100*3600);
					}
					else
					{
						$zym_99 = (int)(time()+72*86400);
					}
					if($zym_105==1)
					{
						$zym_75 = C('ftx_fz');
					}
					if($zym_105==2)
					{
						$zym_75 = C('ftx_my');
					}
					if($zym_105==3)
					{
						$zym_75 = C('ftx_hzp');
					}
					if($zym_105==4)
					{
						$zym_75 = C('ftx_jj');
					}
					if($zym_105==5)
					{
						$zym_75 = C('ftx_xbps');
					}
					if($zym_105==6)
					{
						$zym_75 = C('ftx_ms');
					}
					if($zym_105==7)
					{
						$zym_75 = C('ftx_wtcp');
					}
					if($zym_105==8)
					{
						$zym_75 = C('ftx_smjd');
					}
					$zym_98 = d('items')->get_tags_by_title($zym_114);
					$zym_96 = implode(',',$zym_98);
					$zym_97['item_type']=2;
					$zym_97['q_sur']=$zym_69;
					$zym_97['q_has']=$zym_88;
					$zym_97['q_price']=$zym_112;
					$zym_97['q_time']=$zym_68;
					$zym_97['pc_url']=$zym_90;
					$zym_97['wap_url']=$zym_94;
					$zym_97['q_info']=$zym_107;
					$zym_97['shop_type']=$zym_70;
					$zym_97['tags']=$zym_96;
					$zym_97['price']=$zym_67;
					$zym_97['volume']=$zym_108;
					$zym_97['desc']= getdesc($zym_103);
					$zym_97['intro']=$zym_109;
					$zym_97['coupon_rate']=$zym_95*1000;
					$zym_97['sellerId']=$zym_106;
					$zym_97['title']=$zym_114;
					$zym_97['click_url']=$zym_101;
					$zym_97['num_iid']= $zym_103;
					$zym_97['pic_url']=$zym_115;
					$zym_97['coupon_price']=$zym_72;
					$zym_97['cate_id']=$zym_75;
					$zym_97['coupon_end_time']=$zym_99;
					;
					$zym_97['coupon_start_time']=time();
					if($zym_103 && $zym_75 && $zym_69 && $zym_89>=$zym_85 && $zym_101)
					{
						$zym_59['item_list'][]=$zym_97;
					}
				}
			}
			if($zym_83>$zym_65)
			{
				$this->ajaxReturn(0, '已经采集完成！请返回，谢谢');
			}
			$zym_61=0;
			foreach ($zym_59['item_list'] as $zym_58 => $zym_20) 
			{
				$zym_21= $this->_ajax_tb_publish_insert($zym_20);
				if($zym_21>0)
				{
					$zym_61++;
				}
				$zym_74++;
			}
			F('totalcoll',$zym_74);
			$this->assign('p',$zym_83);
			$this->assign('lowyj',$zym_85);
			$this->assign('totalnum', $zym_65);
			$this->assign('totalcoll', $zym_74);
			$zym_19 = $this->fetch('collect');
			$this->ajaxReturn(1, '', $zym_19);
		}
	}
	private function _ajax_tb_publish_insert($zym_18) 
	{
		$zym_18['title']=trim(strip_tags($zym_18['title']));
		$zym_59 = D('items')->ajax_tb_publish($zym_18);
		return $zym_59;
	}
	public function getjihua($zym_106)
	{
		$zym_16 = C('ftx_cookie');
		$zym_17 = array(' ','　','' . "\xa" . '','' . "\xd" . '','' . "\x9" . '');
		$zym_22 = array("","","","","");
		$zym_16 = str_replace($zym_17, $zym_22, $zym_16);
		$zym_23 =get_word($zym_16,'_tb_token_=',';');
		$zym_28 = get_word($zym_16,'t=',';');
		$zym_27 = get_word($zym_16,'cna=',';');
		$zym_26 = get_word($zym_16,'l=',';');
		$zym_24 = get_word($zym_16,'mm-guidance3',';');
		$zym_25 = get_word($zym_16,'_umdata=',';');
		$zym_15 = get_word($zym_16,'cookie2=',';');
		$zym_14 = get_word($zym_16,'cookie32=',';');
		$zym_5 = get_word($zym_16,'cookie31=',';');
		$zym_6 = get_word($zym_16,'alimamapwag=',';');
		$zym_4 = get_word($zym_16,'login=',';');
		$zym_3 = get_word($zym_16,'alimamapw=',';');
		$zym_1 = 't='.$zym_28.';cna='.$zym_27.';l='.$zym_26.';mm-guidance3='.$zym_24.';_umdata='.$zym_25.';cookie2='.$zym_15.';_tb_token_='.$zym_23.';v=0;cookie32='.$zym_14.';cookie31='.$zym_5.';alimamapwag='.$zym_6.';login='.$zym_4.';alimamapw='.$zym_3;
		$zym_2 =microtime(true)*1000;
		$zym_2 = explode('.', $zym_2);
		$zym_7 = '淘宝客高手网站联盟由杨他他发起上万人齐推广，希望申请定向计划佣金，长期在线招商QQ群：457872439';
		$zym_8 = 'http://pub.alimama.com/pubauc/getCommonCampaignDetails.json?oriMemberid='.$zym_106.'&t='.$zym_2[0].'&_tb_token_='.$zym_23.'&_input_charset=utf-8';
		$zym_13 = curl_init();
		curl_setopt($zym_13, CURLOPT_URL, $zym_8);
		curl_setopt($zym_13, CURLOPT_REFERER, 'http://www.alimama.com/index.htm');
		curl_setopt($zym_13, CURLOPT_HTTPHEADER, array( 'Cookie:{'.$zym_1.'}', ));
		curl_setopt($zym_13, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($zym_13, CURLOPT_FOLLOWLOCATION, 1);
		$zym_73 = curl_exec($zym_13);
		curl_close($zym_13);
		$zym_12 = json_decode($zym_73,true);
		$zym_11 = count($zym_12['data']['campaignList']);
		if($zym_11==1)
		{
			$zym_9 = $zym_12['data']['campaignList'][0]['CampaignID'];
			$zym_10 = $zym_12['data']['campaignList'][0]['ShopKeeperID'];
			$zym_29 = $zym_12['data']['campaignList'][0]['Exist'];
		}
		else
		{
			for($zym_66=0;$zym_66<$zym_11;$zym_66++)
			{
				$zym_30 = $zym_66+1;
				if($zym_30==$zym_11)
				{
					$zym_50 .= $zym_12['data']['campaignList'][$zym_66]['AvgCommission']*100;
				}
				else
				{
					$zym_50 .= ($zym_12['data']['campaignList'][$zym_66]['AvgCommission']*100).',';
				}
			}
			$zym_50 = str_replace($zym_17, $zym_22, $zym_50);
			$zym_49 = explode(',', $zym_50);
			$zym_48 = array_search(max($zym_49), $zym_49);
			$zym_9 = $zym_12['data']['campaignList'][$zym_48]['CampaignID'];
			$zym_10 = $zym_12['data']['campaignList'][$zym_48]['ShopKeeperID'];
			$zym_29 = $zym_12['data']['campaignList'][$zym_48]['Exist'];
		}
		if(!$zym_29)
		{
			$zym_46 = 'http://pub.alimama.com/pubauc/applyForCommonCampaign.json';
			$zym_47 = array( '_tb_token_'=>$zym_23, 'applyreason'=>$zym_7, 'campId'=>$zym_9, 'keeperid'=>$zym_10, 't'=>$zym_2[0], );
			$zym_47 = http_build_query($zym_47);
			$zym_51 = curl_init();
			$zym_52 = 500;
			curl_setopt($zym_51, CURLOPT_URL, $zym_46);
			curl_setopt($zym_51, CURLOPT_REFERER, 'http://www.alimama.com/index.htm');
			curl_setopt($zym_51, CURLOPT_POST, true);
			curl_setopt($zym_51, CURLOPT_HTTPHEADER, array( 'Cookie:{'.$zym_1.'}', ));
			curl_setopt($zym_51, CURLOPT_POSTFIELDS, $zym_47);
			curl_setopt($zym_51, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($zym_51, CURLOPT_CONNECTTIMEOUT, $zym_52);
			$zym_57 = curl_exec($zym_51);
			curl_close($zym_51);
		}
		return $zym_57;
	}
	public function getqueqiao($zym_103)
	{
		$zym_16 = C('ftx_cookie');
		$zym_17 = array(' ','　','' . "\xa" . '','' . "\xd" . '','' . "\x9" . '');
		$zym_22 = array("","","","","");
		$zym_16 = str_replace($zym_17, $zym_22, $zym_16);
		$zym_23 =get_word($zym_16,'_tb_token_=',';');
		$zym_28 = get_word($zym_16,'t=',';');
		$zym_27 = get_word($zym_16,'cna=',';');
		$zym_26 = get_word($zym_16,'l=',';');
		$zym_24 = get_word($zym_16,'mm-guidance3',';');
		$zym_25 = get_word($zym_16,'_umdata=',';');
		$zym_15 = get_word($zym_16,'cookie2=',';');
		$zym_14 = get_word($zym_16,'cookie32=',';');
		$zym_5 = get_word($zym_16,'cookie31=',';');
		$zym_6 = get_word($zym_16,'alimamapwag=',';');
		$zym_4 = get_word($zym_16,'login=',';');
		$zym_3 = get_word($zym_16,'alimamapw=',';');
		$zym_1 = 't='.$zym_28.';cna='.$zym_27.';l='.$zym_26.';mm-guidance3='.$zym_24.';_umdata='.$zym_25.';cookie2='.$zym_15.';_tb_token_='.$zym_23.';v=0;cookie32='.$zym_14.';cookie31='.$zym_5.';alimamapwag='.$zym_6.';login='.$zym_4.';alimamapw='.$zym_3;
		$zym_2 =microtime(true)*1000;
		$zym_2 = explode('.', $zym_2);
		$zym_56 = get_client_ip();
		$zym_55 = '19_'.$zym_56.'_366_1468693605455';
		$zym_53 = C('ftx_youpid');
		$zym_54 = explode('_',$zym_53);
		$zym_45 = $zym_54[2];
		$zym_44 = $zym_54[3];
		$zym_35 = 'http://pub.alimama.com/common/code/getAuctionCode.json?auctionid='.$zym_103.'&adzoneid='.$zym_44.'&siteid='.$zym_45.'&scenes=3&channel=tk_qqhd&t='.$zym_2[0].'&_tb_token_='.$zym_23.'&pvid='.$zym_55;
		$zym_36 = curl_init();
		curl_setopt($zym_36, CURLOPT_URL, $zym_35);
		curl_setopt($zym_36, CURLOPT_REFERER, 'http://pub.alimama.com/promo/item/channel/index.htm?channel=qqhd');
		curl_setopt($zym_36, CURLOPT_HTTPHEADER, array( 'Cookie:{'.$zym_1.'}', ));
		curl_setopt($zym_36, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($zym_36, CURLOPT_FOLLOWLOCATION, 1);
		$zym_34 = curl_exec($zym_36);
		curl_close($zym_36);
		$zym_33 = json_decode($zym_34,true);
		if($zym_33)
		{
			$zym_31 = $zym_33['data']['shortLinkUrl'];
		}
		return $zym_31;
	}
	public function getclick($zym_103)
	{
		$zym_16 = C('ftx_cookie');
		$zym_17 = array(' ','　','' . "\xa" . '','' . "\xd" . '','' . "\x9" . '');
		$zym_22 = array("","","","","");
		$zym_16 = str_replace($zym_17, $zym_22, $zym_16);
		$zym_23 =get_word($zym_16,'_tb_token_=',';');
		$zym_28 = get_word($zym_16,'t=',';');
		$zym_27 = get_word($zym_16,'cna=',';');
		$zym_26 = get_word($zym_16,'l=',';');
		$zym_24 = get_word($zym_16,'mm-guidance3',';');
		$zym_25 = get_word($zym_16,'_umdata=',';');
		$zym_15 = get_word($zym_16,'cookie2=',';');
		$zym_14 = get_word($zym_16,'cookie32=',';');
		$zym_5 = get_word($zym_16,'cookie31=',';');
		$zym_6 = get_word($zym_16,'alimamapwag=',';');
		$zym_4 = get_word($zym_16,'login=',';');
		$zym_3 = get_word($zym_16,'alimamapw=',';');
		$zym_1 = 't='.$zym_28.';cna='.$zym_27.';l='.$zym_26.';mm-guidance3='.$zym_24.';_umdata='.$zym_25.';cookie2='.$zym_15.';_tb_token_='.$zym_23.';v=0;cookie32='.$zym_14.';cookie31='.$zym_5.';alimamapwag='.$zym_6.';login='.$zym_4.';alimamapw='.$zym_3;
		$zym_2 =microtime(true)*1000;
		$zym_2 = explode('.', $zym_2);
		$zym_53 = C('ftx_youpid');
		$zym_54 = explode('_',$zym_53);
		$zym_45 = $zym_54[2];
		$zym_44 = $zym_54[3];
		$zym_56 = get_client_ip();
		$zym_55 = '50_'.$zym_56.'_15881_1468693605455';
		$zym_35 = 'http://pub.alimama.com/common/code/getAuctionCode.json?auctionid='.$zym_103.'&adzoneid='.$zym_44.'&siteid='.$zym_45.'&t='.$zym_2[0].'&pvid='.$zym_55.'&_tb_token_='.$zym_23.'&_input_charset=utf-8';
		$zym_36 = curl_init();
		curl_setopt($zym_36, CURLOPT_URL, $zym_35);
		curl_setopt($zym_36, CURLOPT_REFERER, 'http://www.alimama.com/index.htm');
		curl_setopt($zym_36, CURLOPT_HTTPHEADER, array( 'Cookie:{'.$zym_1.'}', ));
		curl_setopt($zym_36, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($zym_36, CURLOPT_FOLLOWLOCATION, 1);
		$zym_34 = curl_exec($zym_36);
		curl_close($zym_36);
		$zym_33 = json_decode($zym_34,true);
		$zym_101 = $zym_33['data']['shortLinkUrl'];
		return $zym_101;
	}
	public function check_ip()
	{
		$zym_32 = I('ip','','trim');
		$zym_18 = $this->check_proxy_ip($zym_32);
		$zym_37 = '共尝试连接'.$zym_18['times'].'次，成功['.$zym_18['succeed_times'].']次，失败['.$zym_18['defeat_times'].']次，总用时'.$zym_18['total_spen'].'秒。';
		$this->ajaxReturn(1,l('operation_success'),$zym_37);
	}
	public function check_proxy_ip($zym_38=false, $zym_99=5) 
	{
		$zym_43 = array( 'accept: application/json', 'accept-encoding: gzip, deflate', 'accept-language: en-US,en;q=0.8', 'content-type: application/json', 'user-agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.106 Safari/537.36', );
		$zym_8 = 'http://www.baidu.com/';
		$zym_59['times'] = $zym_99;
		$zym_59['succeed_times'] = 0;
		$zym_59['defeat_times'] = 0;
		$zym_59['total_spen'] = 0;
		for ($zym_66=0; $zym_66 < $zym_99; $zym_66++) 
		{
			$zym_42 = microtime();
			$zym_41 = curl_init();
			curl_setopt($zym_41, CURLOPT_URL, $zym_8);
			curl_setopt($zym_41, CURLOPT_HTTPHEADER, $zym_43);
			curl_setopt($zym_41, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($zym_41, CURLOPT_ENCODING, 'gzip,deflate');
			curl_setopt($zym_41, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($zym_41, CURLOPT_SSL_VERIFYHOST, false);
			if (@$zym_38 != false) 
			{
				curl_setopt($zym_41, CURLOPT_HTTPHEADER, array ( 'Client_Ip: '.mt_rand(0, 255).'.'.mt_rand(0, 255).'.'.mt_rand(0, 255).'.'.mt_rand(0, 255), ));
				curl_setopt($zym_41, CURLOPT_HTTPHEADER, array ( 'X-Forwarded-For: '.mt_rand(0, 255).'.'.mt_rand(0, 255).'.'.mt_rand(0, 255).'.'.mt_rand(0, 255), ));
				curl_setopt($zym_41, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
				curl_setopt($zym_41, CURLOPT_PROXY, $zym_38);
			}
			curl_setopt($zym_41, CURLOPT_COOKIEFILE, dirname(__FILE__).'/cookie.txt');
			curl_setopt($zym_41, CURLOPT_COOKIEJAR, dirname(__FILE__).'/cookie.txt');
			curl_setopt($zym_41, CURLOPT_TIMEOUT, 30);
			$zym_39 = curl_exec($zym_41);
			if (strstr($zym_39, '百度一下，你就知道')) 
			{
				$zym_59['list'][$zym_66]['status'] = 1;
				$zym_59['succeed_times'] += 1;
			}
			else 
			{
				$zym_59['list'][$zym_66]['status'] = 0;
				$zym_59['defeat_times'] += 1;
			}
			$zym_40 = microtime();
			$zym_59['total_spen'] += abs($zym_40-$zym_42);
			$zym_59['list'][$zym_66]['spen'] = abs($zym_40-$zym_42);
			$zym_59['list'][$zym_66]['content'] = json_encode($zym_39, true);
		}
		$zym_59['precent'] = (number_format($zym_59['succeed_times']/$zym_99, 4)*100).'%';
		$zym_59['average_spen'] = number_format($zym_59['total_spen']/$zym_99, 4);
		return $zym_59;
	}
}
?>