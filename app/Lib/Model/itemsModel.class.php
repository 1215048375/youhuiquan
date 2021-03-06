<?php
class itemsModel extends Model
{
    protected $_auto = array(
        array('add_time', 'time', 1, 'function'),
		array('pass',1),
    );
    
    public function ajax_tb_publish($item) {
	    $result['q_sur']			    = $item['q_sur'];
		$result['q_has']			    = $item['q_has'];
		$result['q_price']				= $item['q_price'];
		$result['q_time']		        = $item['q_time'];
		$result['pc_url']			    = $item['pc_url'];		
		$result['wap_url']				= $item['wap_url'];
		$result['q_info']			    = $item['q_info'];		
		$result['volume']	            = $item['volume'];
		$result['price']                = $item['price'];
		$result['intro']                = $item['intro'];
		$result['coupon_rate']          = $item['coupon_rate'];
		$result['click_url']            = $item['click_url'];
		$result['coupon_price']         = $item['coupon_price'];
		$result['coupon_start_time']    = $item['coupon_start_time'];
		$result['coupon_end_time']      = $item['coupon_end_time'];
		$result['add_time']             = time();		
        if($this->where(array('num_iid'=>$item['num_iid']))->count()) {
        $item_id = $this->where(array('num_iid'=>$item['num_iid']))->save($result);
        }else{    
		$this->create($item);
        $item_id = $this->add();
		}
        if ($item_id) {
            return 1;
        } else {
            return 0;
        }
    }

    public function ajax_ftx_publish($item) {
        //是否存在
        if(!$item['num_iid']){return 0;}
        if(!$item['title']){return 0;}
        if ($this->where(array('num_iid'=>$item['num_iid']))->count()) {
            if($item['recid']==1){
                unset($item['recid']);
            }else{
                unset($item['recid']);
                unset($item['cate_id']);
            }
            unset($item['astime']);
            $item['status'] ='underway';
            $item_id = $this->where(array('num_iid'=>(string)$item['num_iid']))->save($item);
            if ($item_id) {
                return 0;
            } else {
                return 0;
            }
        }
        unset($item['recid']);
        $item['pass'] = 1;
        $item['status'] ='underway';
        $this->create($item);
        $item_id = $this->add();
        if ($item_id) {

            vendor('pscws4.pscws4', '', '.class.php');
            $pscws = new PSCWS4();
            $pscws->set_dict(FTX_DATA_PATH . 'scws/dict.utf8.xdb');
            $pscws->set_rule(FTX_DATA_PATH . 'scws/rules.utf8.ini');
            $pscws->set_ignore(true);
            $pscws->send_text($item['title']);
            $wordss = $pscws->get_tops('10');
            $pscws->close();
            $ws = array();
            foreach ($wordss as $val) {
                $ws['name'] = $val['word'];
                $is  = M('tags')->where($ws)->find();
                if($is){
                    $score_data = array('tcount'=>array('exp','tcount+1'));
                    M('tags')->where($ws)->setInc('tcount',1);
                }else{
                    M('tags')->create($ws);
                    M('tags')->add();
                }
            }
            return 1;
        } else {
            return 0;
        }
    }
	
	public function ajax_que_publish($item) {
		if ($this->where(array('num_iid'=>$item['num_iid']))->count()) {
		 return 0;
        }else{
		$this->create($item);
        $item_id = $this->add();
		}
        if ($item_id) {
            return 1;
        } else {
            return 0;
        }
    }


	public function get_tags_by_title($title, $num=10){
        vendor('pscws4.pscws4', '', '.class.php');
        $pscws = new PSCWS4();
        $pscws->set_dict(FTX_DATA_PATH . 'scws/dict.utf8.xdb');
        $pscws->set_rule(FTX_DATA_PATH . 'scws/rules.utf8.ini');
        $pscws->set_ignore(true);
        $pscws->send_text($title);
        $words = $pscws->get_tops($num);
        $pscws->close();
        $tags = array();
        foreach ($words as $val) {
            $tags[] = $val['word'];
        }
        return $tags;
    }

    /**
     *返回出售状态
     */
    public function status($status, $stime ,$etime) {
		
		if('underway'!=$status){ 
			return 'sellout';
		}elseif($stime>time()){
			return 'start';
		}elseif($etime<time()){
			return 'gone';
		}else{
			return 'buy';
		}
	}
	

	public function click_url($url,$num_iid) {
        if ($url && $num_iid) {
			 $this->where(array('num_iid'=>$num_iid))->save(array('click_url'=>$url));
            return true;
        }
    }

}