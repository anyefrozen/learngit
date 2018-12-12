<?php 
    /**
     *  @功能  :   收益率 = （收益 / 成本 - 1） x 100%（收益：指投资周期内回笼的全部资金）
     *  @date  : 2017-10-24
     *  @author: 01194954
     */
    header("Content-type: text/html; charset=utf-8"); 
    $time = time();
    $day  = date('Y-m-d H:i:s',$time);
    echo "当前日期：".$day."；时间戳：".$time."<br>";
    echo "***************<br>";
    echo "***************<br>";


    $rates      = 0.00025;          // 佣金 万2.5，起点5元
    $stamp_duty = 0.001;            // 印花税

    $buy_price  = number_format(20.62,2);   // 买入价
    $sell_price = number_format(21.06,2);   // 卖出价
    $buy_num    = 1;                        // 买入数量
    $sell_num   = 1;                        // 卖出数量
    $buy_time   = '2017-10-30';             // 开始日期
    $sell_time  = '2017-11-2';              // 结束日期

    $buy_cost  = $buy_price * $buy_num * 100 ;      // 卖入总价（初始）
    $sell_cost = $sell_price * $sell_num * 100 ;    // 买出总价（初始）

    $buy_rate = $buy_cost * $rates;
    $buy_rate = $buy_rate < 5 ? 5 : $buy_rate ;         // 买入佣金（卖入手续费）

    $sell_rate1 = $sell_cost * $rates;
    $sell_rate1 = $sell_rate1 < 5 ? 5 : $sell_rate1 ;   // 卖出佣金
    $sell_rate2 = $sell_cost * $stamp_duty;             // 卖出印花税
    $sell_rate  = floatval($sell_rate1)  + $sell_rate2; // 卖出总手续费

    $buy_average_price = number_format($buy_price + $buy_rate / 100,2)  ;
    $sell_average_price = number_format($sell_price - $sell_rate / 100,2)  ;

    $sxf = floatval($buy_rate) + $sell_rate ;

    echo "买入价:".$buy_price."元； 卖出价".$sell_price."元<br> ";
    echo "买入均价:".$buy_average_price."元； 卖出均价".$sell_average_price."元<br> ";
    echo "***************<br>";
    echo "***************<br>";
    echo "买入佣金:".$buy_rate."元； 卖出佣金".$sell_rate1."元； 卖出印花税".$sell_rate2."元； <br>总手续费：". number_format($sxf,2)."元<br>";
    echo "***************<br>";
    echo "***************<br>";


    $cost       = $buy_cost + $buy_rate ;             // 成本
    $earnings   = $sell_cost - $cost - $sell_rate;    // 净收益
    $total_rate = ( $earnings / $cost ) * 100 ;       // 总净收益率
    $days       = getDayCounts($buy_time,$sell_time);
    showRateString($total_rate,$days,$cost);
   
    function getDayCounts($date1,$date2){
        $d1   = strtotime($date1);
        $d2   = strtotime($date2);
        $days = round(($d2-$d1)/3600/24);
        return $days;
    }

    function showRateString($rate,$days,$cost){
        $dayRate   = $rate/$days;
        $monthRate = $dayRate *30;
        $yearRate  = $dayRate * 365;
        $money     = $rate * $cost / 100 ;
        $showStr   = $monthRate > 15 ? "高收益" : '参考行情' ;

        $showRate      = round($rate,2).'%';
        $showDayRate   = round($dayRate,2).'%';
        $showMonthRate = round($monthRate,2).'%';
        $showYearRate  = round($yearRate,2).'%';
        $money         = round($money,2);

        $html = '<table border="6" style="margin: 0 auto; width: 560px; text-align: center;">';
        $html .=    '<tr><td colspan="6">投资收益表（'.$showStr.'）</td></tr>';
        $html .=    '<tr>';
        $html .=         '<td>周期（日）</td>';
        $html .=         '<td>本金（元）</td>';
        $html .=         '<td>盈利（元）</td>';
        $html .=         '<td>日收益率</td>';
        $html .=         '<td>月收益率</td>';
        $html .=         '<td>年收益率</td>';
        $html .=     '</tr>';
        $html .=     '<tr>';
        $html .=         '<td>'.$days.'</td>';
        $html .=         '<td>'.$cost.'</td>';
        $html .=         '<td>'.$money.'</td>';
        $html .=         '<td>'.$showDayRate.'</td>';
        $html .=         '<td>'.$showMonthRate.'</td>';
        $html .=         '<td>'.$showYearRate.'</td>';
        $html .=     '</tr>';
        $html .= '</table>';

        echo $html;
    }


?>