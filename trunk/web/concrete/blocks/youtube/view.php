<?
defined('C5_EXECUTE') or die(_("Access Denied."));
$url = parse_url($videoURL);
parse_str($url['query'], $query);
global $c;
 
$vWidth=425;
$vHeight=344;
if ($c->isEditMode()) { ?>
	<div style="width:<?=$vWidth?>px; height:<?=$vHeight?>px; background: #ddd; color:#aaa; border:1px solid #888; text-align:center; font-size:14px; font-family: Helvetica Neue, Arial, Helvetica; font-weight: bold; padding:0px;">
		<div style="padding:8px; padding-top: <?=round($vHeight/2)-10?>px;"><?=t('Content disabled in edit mode.')?></div>
	</div>
<? }else{ ?>
<object width="<?=$vWidth?>" height="<?=$vHeight?>">
	<param name="movie" value="http://www.youtube.com/v/<?=$query['v']?>&hl=en"></param>
	<param name="wmode" value="transparent"></params>
	<embed src="http://www.youtube.com/v/<?=$query['v']?>&hl=en" type="application/x-shockwave-flash" wmode="transparent" width="425" height="344"></embed>
</object>
<? } ?>