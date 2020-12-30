<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Controller;


use App\Model\BaseList;
use App\Model\Code;
use App\Model\CodeList;
use App\Model\CodeList1;
use App\Model\CodeList10;
use App\Model\CodeList2;
use App\Model\CodeList3;
use App\Model\CodeList4;
use App\Model\CodeList5;
use App\Model\CodeList6;
use App\Model\CodeList7;
use App\Model\CodeList8;
use App\Model\CodeList9;
use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Request;
use Hyperf\Utils\ApplicationContext;
use Swlib\Saber;
use Swlib\SaberGM;

class IndexController extends AbstractController
{

    private $redisClient;
    public function __construct()
    {
        $container = ApplicationContext::getContainer();
        $this->redisClient = $container->get(\Redis::class);
    }
//    static $time = '09:30:00';
    static $time0 = '34200';//开盘测试时间
    static $time1 = '34200';//开盘测试时间
    static $time2 = '34200';//开盘测试时间
    static $time3 = '34200';//开盘测试时间
    static $time4 = '34200';//开盘测试时间
    static $time5 = '34200';//开盘测试时间
    static $time6 = '34200';//开盘测试时间
    static $time7 = '34200';//开盘测试时间
    static $time8 = '34200';//开盘测试时间
    static $time9 = '34200';//开盘测试时间
    static $time10 = '34200';//开盘测试时间
//    static $time = date("h:i:s");
    public function index(Request $request)
    {
        $search =$request->all()['search'];
        $searchs = explode('，',$search);
        if(is_numeric($search)){
            $codes =BaseList::where('code','like','%'.$search.'%')->get()->toArray();
        }else{
            $codes =BaseList::get()->toArray();
        }
        foreach ($codes as $key => $value) {
            if(substr( $value['code'], 0, 1 )=='6'){
                $code = '0'.$value['code'];
            }elseif(substr( $value['code'], 0, 1 )=='0'||substr( $value['code'], 0, 1 )=='3'){
                $code = '1'.$value['code'];
            }
            $i = 0;
            if(!is_numeric($search)&&!empty($search)){

                foreach($searchs as $k=>$v){
                    if(strpos($value['notions'],$v)!==false){
                        $i++;
                    }
                }
                if(count($searchs)==$i){
                    $array[] =[
                        'code'=>$value['code'],
                        'name'=>$value['name'],
                        'notions'=>$value['notions'],
                        'url90'=> 'http://img1.money.126.net/chart/hs/kline/day/90/'.$code.'.png',
                        'href'=>'quotes.money.163.com/'.$code.'.html',
                        'detail'=>'quotes.money.163.com/trade/cjmx_'.$value['code'].'.html'
                    ];
                }
            }else{
                $array[] =[
                    'code'=>$value['code'],
                    'name'=>$value['name'],
                    'notions'=>$value['notions'],
                    'url90'=> 'http://img1.money.126.net/chart/hs/kline/day/90/'.$code.'.png',
                    'href'=>'quotes.money.163.com/'.$code.'.html',
                    'detail'=>'quotes.money.163.com/trade/cjmx_'.$value['code'].'.html'
                ];
            }

        };
        $data['data'] = $array;
        $data['code'] = 0;
        $data['a'] = $search;
        return json_encode($data);
    }

//  此方法为测试方法 可以测试全天的数据
    public function getCodeList(){
        $t = $this->getTime(self::$time0,'0');
        $endTime =$t['endTime'];
        if(($endTime<=41400&&$endTime>=34200)||($endTime>=46800&&$endTime<=54000)){
            $chan = new \Swoole\Coroutine\Channel();
            $result = [];
            $codes =Code::get()->toArray();
            $pageSize =10;
            $pageNum = ceil(count($codes)/$pageSize);
            $ip = \Swoole\Coroutine\System::gethostbyname("quotes.money.163.com", AF_INET, 0.5);
            for($j = 1; $j<$pageNum+1;$j++){
                $start=($j-1)*$pageSize;//偏移量，当前页-1乘以每页显示条数
                $code = array_slice($codes,$start,$pageSize);
                go(function () use ($chan,$code,$endTime) {
                    $cli = new \Swoole\Coroutine\Http\Client('quotes.money.163.com', 80);
                    $cli->set(['timeout' => 10]);
                    $cli->setHeaders([
                        'Host' => 'quotes.money.163.com',
                    ]);
                    foreach($code as $key=>$value){
                        $url = "/service/zhubi_ajax.html?".http_build_query(['symbol'=>$value['code'],'end'=>gmdate('H:i:s', $endTime)]);
                        $ret = $cli->get($url);
                        $chan->push([$value['name']=>$cli->body]);
                    }

                });
            }
            for($i = 0; $i<count($codes);$i++){
                $result += $chan->pop();
            }
            $list=$result;
            $res = $this->getResult($list,$codes);
            $data['data'] = $res;
            $data['code'] = 0;
        }else{
            $data['data'] = [];
            $data['message'] = '非交易时间';
            $data['code'] = 0;
        }
        return json_encode($data);
	}

//  此方法为测试方法 可以测试全天的数据
    public function getCodeList1(){
        $t = $this->getTime(self::$time1,'1');
        $endTime =$t['endTime'];
        if(($endTime<=41400&&$endTime>=34200)||($endTime>=46800&&$endTime<=54000)){
            $chan = new \Swoole\Coroutine\Channel();
            $result = [];
            $codes =CodeList1::get()->toArray();
            $pageSize =10;
            $pageNum = ceil(count($codes)/$pageSize);
            $ip = \Swoole\Coroutine\System::gethostbyname("quotes.money.163.com", AF_INET, 0.5);
            for($j = 1; $j<$pageNum+1;$j++){
                $start=($j-1)*$pageSize;//偏移量，当前页-1乘以每页显示条数
                $code = array_slice($codes,$start,$pageSize);
                go(function () use ($chan,$code,$endTime) {
                    $cli = new \Swoole\Coroutine\Http\Client('quotes.money.163.com', 80);
                    $cli->set(['timeout' => 10]);
                    $cli->setHeaders([
                        'Host' => 'quotes.money.163.com',
                    ]);
                    foreach($code as $key=>$value){
                        $url = "/service/zhubi_ajax.html?".http_build_query(['symbol'=>$value['code'],'end'=>gmdate('H:i:s', $endTime)]);
                        $ret = $cli->get($url);
                        $chan->push([$value['name']=>$cli->body]);
                    }

                });
            }
            for($i = 0; $i<count($codes);$i++){
                $result += $chan->pop();
            }
            $list=$result;
            $res = $this->getResult($list,$codes);
            $data['data'] = $res;
            $data['code'] = 0;
        }else{
            $data['data'] = [];
            $data['message'] = '非交易时间';
            $data['code'] = 0;
        }
        return json_encode($data);
    }
    //  此方法为测试方法 可以测试全天的数据
    public function getCodeList2(){
        $t = $this->getTime(self::$time2,'2');
        $endTime =$t['endTime'];
        if(($endTime<=41400&&$endTime>=34200)||($endTime>=46800&&$endTime<=54000)){
            $chan = new \Swoole\Coroutine\Channel();
            $result = [];
            $codes =CodeList2::get()->toArray();
            $pageSize =10;
            $pageNum = ceil(count($codes)/$pageSize);
            $ip = \Swoole\Coroutine\System::gethostbyname("quotes.money.163.com", AF_INET, 0.5);
            for($j = 1; $j<$pageNum+1;$j++){
                $start=($j-1)*$pageSize;//偏移量，当前页-1乘以每页显示条数
                $code = array_slice($codes,$start,$pageSize);
                go(function () use ($chan,$code,$endTime) {
                    $cli = new \Swoole\Coroutine\Http\Client('quotes.money.163.com', 80);
                    $cli->set(['timeout' => 10]);
                    $cli->setHeaders([
                        'Host' => 'quotes.money.163.com',
                    ]);
                    foreach($code as $key=>$value){
                        $url = "/service/zhubi_ajax.html?".http_build_query(['symbol'=>$value['code'],'end'=>gmdate('H:i:s', $endTime)]);
                        $ret = $cli->get($url);
                        $chan->push([$value['name']=>$cli->body]);
                    }

                });
            }
            for($i = 0; $i<count($codes);$i++){
                $result += $chan->pop();
            }
            $list=$result;
            $res = $this->getResult($list,$codes);
            $data['data'] = $res;
            $data['code'] = 0;
        }else{
            $data['data'] = [];
            $data['message'] = '非交易时间';
            $data['code'] = 0;
        }
        return json_encode($data);
    }
    //  此方法为测试方法 可以测试全天的数据
    public function getCodeList3(){
        $t = $this->getTime(self::$time3,'3');
        $endTime =$t['endTime'];
        if(($endTime<=41400&&$endTime>=34200)||($endTime>=46800&&$endTime<=54000)){
            $chan = new \Swoole\Coroutine\Channel();
            $result = [];
            $codes =CodeList3::get()->toArray();
            $pageSize =10;
            $pageNum = ceil(count($codes)/$pageSize);
            $ip = \Swoole\Coroutine\System::gethostbyname("quotes.money.163.com", AF_INET, 0.5);
            for($j = 1; $j<$pageNum+1;$j++){
                $start=($j-1)*$pageSize;//偏移量，当前页-1乘以每页显示条数
                $code = array_slice($codes,$start,$pageSize);
                go(function () use ($chan,$code,$endTime) {
                    $cli = new \Swoole\Coroutine\Http\Client('quotes.money.163.com', 80);
                    $cli->set(['timeout' => 10]);
                    $cli->setHeaders([
                        'Host' => 'quotes.money.163.com',
                    ]);
                    foreach($code as $key=>$value){
                        $url = "/service/zhubi_ajax.html?".http_build_query(['symbol'=>$value['code'],'end'=>gmdate('H:i:s', $endTime)]);
                        $ret = $cli->get($url);
                        $chan->push([$value['name']=>$cli->body]);
                    }

                });
            }
            for($i = 0; $i<count($codes);$i++){
                $result += $chan->pop();
            }
            $list=$result;
            $res = $this->getResult($list,$codes);
            $data['data'] = $res;
            $data['code'] = 0;
        }else{
            $data['data'] = [];
            $data['message'] = '非交易时间';
            $data['code'] = 0;
        }
        return json_encode($data);
    }
    //  此方法为测试方法 可以测试全天的数据
    public function getCodeList4(){
        $t = $this->getTime(self::$time4,'4');
        $endTime =$t['endTime'];
        if(($endTime<=41400&&$endTime>=34200)||($endTime>=46800&&$endTime<=54000)){
            $chan = new \Swoole\Coroutine\Channel();
            $result = [];
            $codes =CodeList4::get()->toArray();
            $pageSize =10;
            $pageNum = ceil(count($codes)/$pageSize);
            $ip = \Swoole\Coroutine\System::gethostbyname("quotes.money.163.com", AF_INET, 0.5);
            for($j = 1; $j<$pageNum+1;$j++){
                $start=($j-1)*$pageSize;//偏移量，当前页-1乘以每页显示条数
                $code = array_slice($codes,$start,$pageSize);
                go(function () use ($chan,$code,$endTime) {
                    $cli = new \Swoole\Coroutine\Http\Client('quotes.money.163.com', 80);
                    $cli->set(['timeout' => 10]);
                    $cli->setHeaders([
                        'Host' => 'quotes.money.163.com',
                    ]);
                    foreach($code as $key=>$value){
                        $url = "/service/zhubi_ajax.html?".http_build_query(['symbol'=>$value['code'],'end'=>gmdate('H:i:s', $endTime)]);
                        $ret = $cli->get($url);
                        $chan->push([$value['name']=>$cli->body]);
                    }

                });
            }
            for($i = 0; $i<count($codes);$i++){
                $result += $chan->pop();
            }
            $list=$result;
            $res = $this->getResult($list,$codes);
            $data['data'] = $res;
            $data['code'] = 0;
        }else{
            $data['data'] = [];
            $data['message'] = '非交易时间';
            $data['code'] = 0;
        }
        return json_encode($data);
    }
    //  此方法为测试方法 可以测试全天的数据
    public function getCodeList5(){
        $t = $this->getTime(self::$time5,'5');
        $endTime =$t['endTime'];
        if(($endTime<=41400&&$endTime>=34200)||($endTime>=46800&&$endTime<=54000)){
            $chan = new \Swoole\Coroutine\Channel();
            $result = [];
            $codes =CodeList5::get()->toArray();
            $pageSize =10;
            $pageNum = ceil(count($codes)/$pageSize);
            $ip = \Swoole\Coroutine\System::gethostbyname("quotes.money.163.com", AF_INET, 0.5);
            for($j = 1; $j<$pageNum+1;$j++){
                $start=($j-1)*$pageSize;//偏移量，当前页-1乘以每页显示条数
                $code = array_slice($codes,$start,$pageSize);
                go(function () use ($chan,$code,$endTime) {
                    $cli = new \Swoole\Coroutine\Http\Client('quotes.money.163.com', 80);
                    $cli->set(['timeout' => 10]);
                    $cli->setHeaders([
                        'Host' => 'quotes.money.163.com',
                    ]);
                    foreach($code as $key=>$value){
                        $url = "/service/zhubi_ajax.html?".http_build_query(['symbol'=>$value['code'],'end'=>gmdate('H:i:s', $endTime)]);
                        $ret = $cli->get($url);
                        $chan->push([$value['name']=>$cli->body]);
                    }

                });
            }
            for($i = 0; $i<count($codes);$i++){
                $result += $chan->pop();
            }
            $list=$result;
            $res = $this->getResult($list,$codes);
            $data['data'] = $res;
            $data['code'] = 0;
        }else{
            $data['data'] = [];
            $data['message'] = '非交易时间';
            $data['code'] = 0;
        }
        return json_encode($data);
    }//  此方法为测试方法 可以测试全天的数据
    public function getCodeList6(){
        $t = $this->getTime(self::$time6,'6');
        $endTime =$t['endTime'];
        if(($endTime<=41400&&$endTime>=34200)||($endTime>=46800&&$endTime<=54000)){
            $chan = new \Swoole\Coroutine\Channel();
            $result = [];
            $codes =CodeList6::get()->toArray();
            $pageSize =10;
            $pageNum = ceil(count($codes)/$pageSize);
            $ip = \Swoole\Coroutine\System::gethostbyname("quotes.money.163.com", AF_INET, 0.5);
            for($j = 1; $j<$pageNum+1;$j++){
                $start=($j-1)*$pageSize;//偏移量，当前页-1乘以每页显示条数
                $code = array_slice($codes,$start,$pageSize);
                go(function () use ($chan,$code,$endTime) {
                    $cli = new \Swoole\Coroutine\Http\Client('quotes.money.163.com', 80);
                    $cli->set(['timeout' => 10]);
                    $cli->setHeaders([
                        'Host' => 'quotes.money.163.com',
                    ]);
                    foreach($code as $key=>$value){
                        $url = "/service/zhubi_ajax.html?".http_build_query(['symbol'=>$value['code'],'end'=>gmdate('H:i:s', $endTime)]);
                        $ret = $cli->get($url);
                        $chan->push([$value['name']=>$cli->body]);
                    }

                });
            }
            for($i = 0; $i<count($codes);$i++){
                $result += $chan->pop();
            }
            $list=$result;
            $res = $this->getResult($list,$codes);
            $data['data'] = $res;
            $data['code'] = 0;
        }else{
            $data['data'] = [];
            $data['message'] = '非交易时间';
            $data['code'] = 0;
        }
        return json_encode($data);
    }
    //  此方法为测试方法 可以测试全天的数据
    public function getCodeList7(){
        $t = $this->getTime(self::$time7,'7');
        $endTime =$t['endTime'];
        if(($endTime<=41400&&$endTime>=34200)||($endTime>=46800&&$endTime<=54000)){
            $chan = new \Swoole\Coroutine\Channel();
            $result = [];
            $codes =CodeList7::get()->toArray();
            $pageSize =10;
            $pageNum = ceil(count($codes)/$pageSize);
            $ip = \Swoole\Coroutine\System::gethostbyname("quotes.money.163.com", AF_INET, 0.5);
            for($j = 1; $j<$pageNum+1;$j++){
                $start=($j-1)*$pageSize;//偏移量，当前页-1乘以每页显示条数
                $code = array_slice($codes,$start,$pageSize);
                go(function () use ($chan,$code,$endTime) {
                    $cli = new \Swoole\Coroutine\Http\Client('quotes.money.163.com', 80);
                    $cli->set(['timeout' => 10]);
                    $cli->setHeaders([
                        'Host' => 'quotes.money.163.com',
                    ]);
                    foreach($code as $key=>$value){
                        $url = "/service/zhubi_ajax.html?".http_build_query(['symbol'=>$value['code'],'end'=>gmdate('H:i:s', $endTime)]);
                        $ret = $cli->get($url);
                        $chan->push([$value['name']=>$cli->body]);
                    }

                });
            }
            for($i = 0; $i<count($codes);$i++){
                $result += $chan->pop();
            }
            $list=$result;
            $res = $this->getResult($list,$codes);
            $data['data'] = $res;
            $data['code'] = 0;
        }else{
            $data['data'] = [];
            $data['message'] = '非交易时间';
            $data['code'] = 0;
        }
        return json_encode($data);
    }
    //  此方法为测试方法 可以测试全天的数据
    public function getCodeList8(){
        $t = $this->getTime(self::$time8,'8');
        $endTime =$t['endTime'];
        if(($endTime<=41400&&$endTime>=34200)||($endTime>=46800&&$endTime<=54000)){
            $chan = new \Swoole\Coroutine\Channel();
            $result = [];
            $codes =CodeList8::get()->toArray();
            $pageSize =10;
            $pageNum = ceil(count($codes)/$pageSize);
            $ip = \Swoole\Coroutine\System::gethostbyname("quotes.money.163.com", AF_INET, 0.5);
            for($j = 1; $j<$pageNum+1;$j++){
                $start=($j-1)*$pageSize;//偏移量，当前页-1乘以每页显示条数
                $code = array_slice($codes,$start,$pageSize);
                go(function () use ($chan,$code,$endTime) {
                    $cli = new \Swoole\Coroutine\Http\Client('quotes.money.163.com', 80);
                    $cli->set(['timeout' => 10]);
                    $cli->setHeaders([
                        'Host' => 'quotes.money.163.com',
                    ]);
                    foreach($code as $key=>$value){
                        $url = "/service/zhubi_ajax.html?".http_build_query(['symbol'=>$value['code'],'end'=>gmdate('H:i:s', $endTime)]);
                        $ret = $cli->get($url);
                        $chan->push([$value['name']=>$cli->body]);
                    }

                });
            }
            for($i = 0; $i<count($codes);$i++){
                $result += $chan->pop();
            }
            $list=$result;
            $res = $this->getResult($list,$codes);
            $data['data'] = $res;
            $data['code'] = 0;
        }else{
            $data['data'] = [];
            $data['message'] = '非交易时间';
            $data['code'] = 0;
        }
        return json_encode($data);
    }
    //  此方法为测试方法 可以测试全天的数据
    public function getCodeList9(){
        $t = $this->getTime(self::$time9,'9');
        $endTime =$t['endTime'];
        if(($endTime<=41400&&$endTime>=34200)||($endTime>=46800&&$endTime<=54000)){
            $chan = new \Swoole\Coroutine\Channel();
            $result = [];
            $codes =CodeList9::get()->toArray();
            $pageSize =10;
            $pageNum = ceil(count($codes)/$pageSize);
            $ip = \Swoole\Coroutine\System::gethostbyname("quotes.money.163.com", AF_INET, 0.5);
            for($j = 1; $j<$pageNum+1;$j++){
                $start=($j-1)*$pageSize;//偏移量，当前页-1乘以每页显示条数
                $code = array_slice($codes,$start,$pageSize);
                go(function () use ($chan,$code,$endTime) {
                    $cli = new \Swoole\Coroutine\Http\Client('quotes.money.163.com', 80);
                    $cli->set(['timeout' => 10]);
                    $cli->setHeaders([
                        'Host' => 'quotes.money.163.com',
                    ]);
                    foreach($code as $key=>$value){
                        $url = "/service/zhubi_ajax.html?".http_build_query(['symbol'=>$value['code'],'end'=>gmdate('H:i:s', $endTime)]);
                        $ret = $cli->get($url);
                        $chan->push([$value['name']=>$cli->body]);
                    }

                });
            }
            for($i = 0; $i<count($codes);$i++){
                $result += $chan->pop();
            }
            $list=$result;
            $res = $this->getResult($list,$codes);
            $data['data'] = $res;
            $data['code'] = 0;
        }else{
            $data['data'] = [];
            $data['message'] = '非交易时间';
            $data['code'] = 0;
        }
        return json_encode($data);
    }
    //  此方法为测试方法 可以测试全天的数据
    public function getCodeList10(){
        $t = $this->getTime(self::$time10,'10');
        $endTime =$t['endTime'];
        if(($endTime<=41400&&$endTime>=34200)||($endTime>=46800&&$endTime<=54000)){
            $chan = new \Swoole\Coroutine\Channel();
            $result = [];
            $codes =CodeList10::get()->toArray();
            $pageSize =10;
            $pageNum = ceil(count($codes)/$pageSize);
            $ip = \Swoole\Coroutine\System::gethostbyname("quotes.money.163.com", AF_INET, 0.5);
            for($j = 1; $j<$pageNum+1;$j++){
                $start=($j-1)*$pageSize;//偏移量，当前页-1乘以每页显示条数
                $code = array_slice($codes,$start,$pageSize);
                go(function () use ($chan,$code,$endTime) {
                    $cli = new \Swoole\Coroutine\Http\Client('quotes.money.163.com', 80);
                    $cli->set(['timeout' => 10]);
                    $cli->setHeaders([
                        'Host' => 'quotes.money.163.com',
                    ]);
                    foreach($code as $key=>$value){
                        $url = "/service/zhubi_ajax.html?".http_build_query(['symbol'=>$value['code'],'end'=>gmdate('H:i:s', $endTime)]);
                        $ret = $cli->get($url);
                        $chan->push([$value['name']=>$cli->body]);
                    }

                });
            }
            for($i = 0; $i<count($codes);$i++){
                $result += $chan->pop();
            }
            $list=$result;
            $res = $this->getResult($list,$codes);
            $data['data'] = $res;
            $data['code'] = 0;
        }else{
            $data['data'] = [];
            $data['message'] = '非交易时间';
            $data['code'] = 0;
        }
        return json_encode($data);
    }
    public function getResult($list,$codes){
        $data =[];
        foreach ($codes as $key => $value) {
            if(isset($list[$value['name']])){
                $array['content'] =[];
                $arr = json_decode($list[$codes[$key]['name']],true);
                if(!empty($arr['zhubi_list'])){
                    $end = $arr['zhubi_list'][0]['DATE_STR'];
                    $lastPrice = $arr['zhubi_list'][0]['PRICE'];
                    $startPrice = $arr['zhubi_list'][count($arr['zhubi_list'])-1]['PRICE'];
                    unset($arr['zhubi_list'][0]);
                    unset($arr['zhubi_list'][count($arr['zhubi_list'])-1]);
                    $price= array_unique(array_column($arr['zhubi_list'],'PRICE'));
                    asort($price);
                    $minPrice = array_values($price)[0];
                    if(count($arr['zhubi_list'])<200){
                        $result = $this->sec60s($startPrice,$minPrice,$lastPrice);
                    }else{
                        $result = $this->sec5Mins($startPrice,$minPrice,$lastPrice);
                    }
                    $a =array_count_values(array_column($arr['zhubi_list'],'TRADE_TYPE_STR'));
                    if($a['买盘']>$a['卖盘']){
                        $result = true;
                    }
                    if(substr( $value['code'], 0, 1 )=='6'){
                        $code = '0'.$value['code'];
                    }
                    if(substr( $value['code'], 0, 1 )=='0'||substr( $value['code'], 0, 1 )=='3'){
                        $code = '1'.$value['code'];
                    }
                    if($result){
                        $now =time();
                        if($this->redisClient->Hexists($value['code'],'time')){
                            $r = $this->redisClient->hmget($value['code'],['change','time']);
                            $change =$r['change'];
                            $time =$r['time'];
                            if($now-$time>=30000){//有效期
                                $change =0;
                                $time = $now;
                            }
                        }else{
                            $change =0;
                            $time =time();
                        }
                        $change++;
                        $arr=['change'=>$change,'href'=>'quotes.money.163.com/'.$code.'.html','time'=>$time];
                        $this->redisClient->hmset($value['code'],$arr);

                        $data[] = [
                            'time'=>$end,
                            'code'=>$value['code'],
                            'name'=>$value['name'],
                            'notion'=>$value['notions'],
                            'price'=>$lastPrice,
                            'level'=> $change,
                            'count'=> $change,//累计次数
                            'change'=>$change,
                            'href'=>'quotes.money.163.com/'.$code.'.html',
                            'detail'=>'quotes.money.163.com/trade/cjmx_'.$value['code'].'.html'
                        ];
                        DB::table('code')->where('code', $value['code'])->update(['time'=>date('Y-m-d H:i:s',time())]);
                    }
                }
            }
        }
        $last_names = array_column($data,'change');
        array_multisort($last_names,SORT_DESC,$data);
        return $data;
    }
    //走势1 √
    public function sec60s($startPrice,$minPrice,$lastPrice){
        if($lastPrice>$minPrice&&$lastPrice>$startPrice&&(($lastPrice-$startPrice)/$startPrice)>0.015){
            return true;
        }
        return false;

    }
    //走势2 ∠
    public function sec5Mins($startPrice,$minPrice,$lastPrice){
        //如果符合走势，返回true，否则false
        if($lastPrice>$minPrice&&$lastPrice>$startPrice&&(($lastPrice-$startPrice)/$startPrice)>0.015&&(($startPrice-$minPrice)/$minPrice)<0.025){
            return true;
        }
        return false;
    }

    //统计双方买卖大小
    public function buyAndSell(){
        //如果买大于卖 返回true;否则返回false
        return true;
    }

    //设置开盘，最高，最低价

    public function setPrice($content,$code){
        if(!empty($content)){
            $lastPrice = array_column($content,'price')[0];
            $price =array_unique(array_column($content,'price'));
            asort($price);
            $minPrice = array_values($price)[0];
            if($this->redisClient->Hexists($code,'minPrice')){
                $p = $this->redisClient->hmget($code,['minPrice']);
                if($p['minPrice']!=0&&$p['minPrice']<$minPrice){
                    $minPrice = $p['minPrice'];
                }
            }
            $this->redisClient->hmset($code,['minPrice'=>$minPrice,'lastPrice'=>$lastPrice]);
        }

    }

    public function compute($array){

        $data =[];
        foreach($array as $key=>$value){
            if(!empty($value['content'])&&count($value['content'])>=5){

                $end = array_values($value['content'])[0]['time'];
                if(substr( $value['code'], 0, 1 )=='6'){
                    $code = '0'.$value['code'];
                }
                if(substr( $value['code'], 0, 1 )=='0'||substr( $value['code'], 0, 1 )=='3'){
                    $code = '1'.$value['code'];
                }
                $now =time();
                $ses = false;
                if($this->redisClient->Hexists($value['code'],'time')){
                    $r = $this->redisClient->hmget($value['code'],['change','time']);
                    $change =$r['change'];
                    $time =$r['time'];
                    if($now-$time>=300){//有效期两分钟分钟内
                        $change =0;
                        $time = $now;
                        $ses = true;
                    }
                }else{
                    $change =0;
                    $time =time();
                }
//                if(count($value['content'])>=5){
//                    $change++;
//                }
                $arr=['change'=>$change,'href'=>'quotes.money.163.com/'.$code.'.html','time'=>$time];
                $this->redisClient->hmset($value['code'],$arr);
                if($ses){//有效期一分钟半内
                    $pass = false;
                    $p = $this->redisClient->hmget($value['code'],['startPrice','minPrice','lastPrice']);
                    if($p['lastPrice']>$p['startPrice']&&$p['startPrice']>$p['minPrice']&&$p['lastPrice']>$p['minPrice']){//如果走势呈现倒三角则符合
                        $pass = true;

                    }
                    if($p['lastPrice']>$p['startPrice']&&$p['minPrice']>$p['startPrice']&&$p['lastPrice']>$p['minPrice']){//如果走势呈现斜向上则符合
                        $pass = true;
                    }
                    $per = (($p['lastPrice']-$p['startPrice'])/$p['startPrice'])*100;
                    if($pass&&$per>1.5){
                        $data[] = [
                            'time'=>$end,
                            'code'=>$value['code'],
                            'name'=>$value['name'],
                            'notion'=>$value['notion'],
                            'price'=>array_values($value['content'])[0]['price'],
                            'level'=> count($value['content']),
                            'count'=> $change,//累计次数
                            'change'=> count(array_unique(array_column($value['content'], 'price'))),
                            'href'=>'quotes.money.163.com/'.$code.'.html',
                            'detail'=>'quotes.money.163.com/trade/cjmx_'.$value['code'].'.html'
                        ];
                    }
                }

            }
        }
        if(!empty($data)){
            array_multisort(array_column($data, 'level'), SORT_DESC, $data);
        }
        if(count($data)>10){//只返回前10只个股
            $data = array_slice($data,0,10);
        }
        return $data;
    }

    public function getList(){
        $codes =Db::table('code')->orderBy('time', 'desc')->get();
        $array = [];
        foreach($codes as $key=>$value){
            if(substr( $value->code, 0, 1 )=='6'){
                $code = '0'.$value->code;
            }
            if(substr( $value->code, 0, 1 )=='0'||substr( $value->code, 0, 1 )=='3'){
                $code = '1'.$value->code;
            }
            $array[$key]['id'] = $value->id;
            $array[$key]['name'] = $value->name;
            $array[$key]['code'] = $value->code;
            $array[$key]['notions']= $value->notions;
            $array[$key]['time']= $value->time;
            $array[$key]['url'] = 'http://192.168.126.128:9501/getFile?code='.$code.'_'.time();
            $array[$key]['url90'] = 'http://img1.money.126.net/chart/hs/kline/day/90/'.$code.'.png';
            $array[$key]['href'] = 'http://quotes.money.163.com/'.$code.'.html';
            $array[$key]['detail'] = 'http://quotes.money.163.com/trade/cjmx_'.$value->code.'.html';

        }
        $data['data'] = $array;
        $data['code'] = 0;
        return json_encode($data);

    }

    //获取远程图片保存本地
    public function updateImage($file_url){

        $img_file = file_get_contents($file_url);
        $img_content = base64_encode($img_file);
        $type = 'png';//得到图片类型 png?jpg?gif?
        $new_file = BASE_PATH . '/runtime/'.time().'.'.$type;
        if (file_put_contents($new_file, base64_decode($img_content))) {
            return $new_file;
        }else{
            return false;
        }

    }

    //获取本地文件
    public function getFile(Request $request){

        $code =$request->all()['code'];
        $code = explode('_',$code)[0];
        $url = 'http://img1.money.126.net/chart/hs/time/540x360/'.$code.'.png';
        return file_get_contents($url);
    }

    public function getTime($time,$number,$test=false){//默认测试版，改为true 转为正式
        if($test){
            $time =date('H:i:s',time());
            $endTime =substr($time, 0, 2 )*60*60+substr($time, 3, 2 )*60+substr($time, 6, 2 );
            return ['endTime'=>$endTime];
        }else{
            if($time>41400&&$time<46800){
                $time=46800;
            }
            if($time>54000){
                $time=34200;
            }
            $endTime = 0;
            switch($number){
                case '0':
                    $endTime = self::$time0= $time+60;
                    break;
                case '1':
                    $endTime = self::$time1= $time+60;
                    break;
                case '2':
                    $endTime = self::$time2= $time+60;
                    break;
                case '3':
                    $endTime = self::$time3= $time+60;
                    break;
                case '4':
                    $endTime = self::$time4= $time+60;
                    break;
                case '5':
                    $endTime = self::$time5= $time+60;
                    break;
                case '6':
                    $endTime = self::$time6= $time+60;
                    break;
                case '7':
                    $endTime = self::$time7= $time+60;
                    break;
                case '8':
                    $endTime = self::$time8= $time+60;
                    break;
                case '9':
                    $endTime = self::$time9= $time+60;
                    break;
                case '10':
                    $endTime = self::$time10= $time+60;
                    break;

            }
            return ['endTime'=>$endTime];
        }

    }

    public function addOpt(Request $request){
        $rq = $request->all();
        $code =  $rq['code'];
        $count = Code::where('code',$code)->count();
        if($count==0){
            Db::table('code')->insert([
                ['code' =>$rq['code'], 'name' =>$rq['name'],'notions'=>$rq['notions'],'time'=>date('Y-m-d H:i:s',time())]
            ]);
            $data['data'] = '添加成功';
            $data['code'] = 0;
        }else{
            $data['data'] = '已经存在';
            $data['code'] = 600;
        }
        return json_encode($data);
    }

    public function updateOpt(Request $request){
        $rq = $request->all();
        $code =  $rq['code'];
        if($rq['type']==1){
            DB::table('code')->where('code', $code)->update(['time'=>date('Y-m-d H:i:s',time())]);
        }else{
            DB::table('code')->where('code', $code)->update(['time'=>'2000-01-01 23:59:59']);
        }
//        $data['data'] = '更新成功';
        $data['data'] = $rq;
        $data['code'] = 0;
        return json_encode($data);
    }

    public function deleteOpt(Request $request){
        $rq = $request->all();
        $code =  $rq['code'];
        Code::where('code',$code)->delete();
        $data['data'] = '移除成功';
        $data['code'] = 0;
        return json_encode($data);
    }

    public function addLots(Request $request){
        $rq = $request->all();
        $code =  $rq['search'];
        $datas = explode(' ',$code);
        $array = [];
        foreach($datas as $key=>$value){
            $array[$key] = substr($value, 2);
        }
        $data = [];
        $list = BaseList::whereIn('code',$array)->get()->toArray();
        foreach($list as $key=>$value){
            $count = Code::where('code',$value)->count();
            if($count==0){
                Db::table('code')->insert([
                    ['code' =>$value['code'], 'name' =>$value['name'],'notions'=>$value['notions'],'time'=>date('Y-m-d H:i:s',time())]
                ]);
                $data['data'] = '添加成功';
                $data['code'] = 0;
            }
        }
        return json_encode($data);
    }
}
