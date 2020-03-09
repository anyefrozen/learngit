<?php
/**
 * @Desc: 股票交易手续费 
 *       1、印花税： 成交金额的1‰（卖出时收取，买入时不收）    =====   S
 *       2、证管费： 约为成交金额的0.002%（双向收取）    =====   B\S
 *       3、证券交易经手费： A股，按成交金额的0.00696%收取（卖出时收取，买入时不收） =》 S  (忽略不计)
 *       4、过户费： 成交金额的0.002%（卖出时收取，买入时不收）    =====   S
 *       5、券商交易佣金： 0.025%（双向收取， 起点5元） =》 万2.5    =====   B\S
 */

	$stockName = '嘉禾集团';
	$stockCode = '000732';

	$buyPrice  = 6.28;	// B-单价
	$buyNum    = 500;	// B-数量
	$sellPrice = 6.48;	// S-单价
	$sellNum   = 500;	// S-数量


	$stampRate   = 1 / 1000;  // 印花税率
	$tubeRate    = 0.002 / 100; // 证管费率
	$changeRate  = 0.002 / 100; // 过户费率
	$commissRate = 0.025 / 100; // 佣金率


	$buyMoney  = $buyPrice * $buyNum; 	// B-成交金额
	$sellMoney = $sellPrice * $sellNum; // S-成交金额
	$stockType = getStockType($stockCode);

	$tubeBuyFee  = $buyMoney * $tubeRate; // 证管费
	$commiBuyFee = $buyMoney * $commissRate > 5 ? $buyMoney * $commissRate : 5; // 佣金

	$stampFee     = $sellMoney * $stampRate; // 印花税
	$tubeSellFee  = $sellMoney * $tubeRate; // 证管费
	$changeFee    = getChangeFee($stockType, $sellNum, $changeRate); // 过户费
	$commiSellFee = $sellPrice * $commissRate > 5 ? $sellPrice * $commissRate : 5; // 佣金

	$tardBuyPrice  = $tubeBuyFee + $commiBuyFee;
	$tardSellPrice = $stampFee + $tubeSellFee + $changeFee + $commiSellFee;
	$costBuyfee    = ($buyMoney + $tardBuyPrice) / $buyNum;
	$costSellfee   = ($sellMoney - $tardBuyPrice) / $sellNum;


	showText($stockName, $stockCode, $stockType, $buyMoney, $tardBuyPrice, $sellMoney, $tardSellPrice);
	showTableHtml($buyPrice, $buyNum, $buyMoney, $tardBuyPrice, $costBuyfee, 'B');
	showTableHtml($sellPrice, $sellNum, $sellMoney, $tardSellPrice, $costSellfee, 'S');


	// 获取stock类型 => 沪A 代码是以600、601或603打头;  深A 代码是以000打头;
	function getStockType($stockCode)
	{
		$subCode = substr($stockCode, 0 , 3);

		switch ($subCode) {
			case '000':
				$stockType = '深A';
				break;
			default:
				$stockType = '沪A';
				break;
		}

		return $stockType;
	}

	// 获取stock过户费 =》 沪A  过户费每一千股收取1元;  深A  过户费0元
	function getChangeFee($stockType, $sellNum, $changeRate)
	{
		$changeFee = 0;
		if ($stockType == '沪A') {
			$changeFee = $sellNum * $changeRate;
		}
		
		return $changeFee;
	}

	// 格式化几位小数
	function formatNumber($number, $num)
	{
		return number_format($number, $num); 
	}

	// 输出表格
	function showTableHtml($price, $num, $totalPrice, $tardfee, $costfee, $type)
	{
		$price      = number_format($price, 2);
		$totalPrice = number_format($totalPrice, 2);
		$tardfee    = number_format($tardfee, 2);
		$costfee    = number_format($costfee, 2);

		echo '<br>';
		echo '<table border="1" style="text-align:center;" >';
			echo '<tr>';
				echo '<th width="80px">'.$type.'-单价</th>';
				echo '<th width="80px">'.$type.'-数量</th>';
				echo '<th width="100px">'.$type.'-成交金额</th>';
				echo '<th width="100px">'.$type.'-手续费</th>';
				echo '<th width="100px">'.$type.'-成本</th>';
			echo '</tr>';
			echo '<tr >';
				echo '<td>￥'.$price.'</td>';
				echo '<td>'.$num.'</td>';
				echo '<td>￥'.$totalPrice.'</td>';
				echo '<td>￥'.$tardfee.'</td>';
				echo '<td>￥'.$costfee.'</td>';
			echo '</tr>';
		echo '</table>';
	}

	// 输出文本
	function showText($stockName, $stockCode, $stockType, $buyMoney, $tardBuyPrice, $sellMoney, $tardSellPrice)
	{
		$earnMoney = $sellMoney - $tardSellPrice - $buyMoney - $tardBuyPrice;
		$earnMoney = number_format($earnMoney, 2);

		echo $stockName.'('.$stockCode.'): - '.$stockType.'<br>';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;收益：'.$earnMoney.'元<br>';
	}

