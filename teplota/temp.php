<?php
function print_temp($text,$cur,$color,$cerp){
	$image = ($cerp==1)?'<img src="img/cerpadlo2.gif" align="left"/>':'';
	return '	
	<table class="tab01">
			<tbody>
				<tr>
					<td class="akt_nadp">'.trim($text).': </td>					
					<td class="akt"	style="color:#'.$color.';">'.$cur.'°C</td>					
					<td class="image">'.$image.'</td>
				</tr>
			</tbody>
	</table>
';
}
