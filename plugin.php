/**
 * addWebUserFields
 *
 * Доп. поля в админке у юзера
 *
 * @author      1px
 * @category    plugin
 * @version     0.1
 * @internal    @events OnWUsrFormRender,OnWUsrFormSave
 * @internal    @properties
 * @internal    @installset base, sample
 * @internal    @modx_category Admin
 */
defined('IN_MANAGER_MODE') or die();
$e = $modx->event;
$usertable = $modx->getFullTableName('web_user_attributes');
$output = '';
if($e->name == 'OnWUsrFormRender'){
	//Добавление полей
	$userid = $modx->db->escape($_GET['id']);
	$result = $modx->db->query("SELECT `ads_count` FROM " . $usertable . " WHERE internalKey = '".$userid."'");
	if($modx->db->getRecordCount($result) > 0){
		$output.= '
			<div class="sectionBody">
				<div class="tab-pane">
					<div class="sectionHeader">Дополнительные поля</div>
					<div class="tab-page">
						<table border="0" cellspacing="0" cellpadding="3" class="table table--edit table--editUser">
			';
		while($row = $modx->db->getRow($result)){
			$output .= "<tr><th>Лимит объявлений</th>";
			$output .= "<td>&nbsp;</td>";
			$output .= '<td><input class="inputBox" type="text" name="customfield__ads_count" value="'.$row['ads_count'].'" /></td></tr>';         
		}
		$output .= "</table></div></div></div>";
	}
	$e->output($output);
}

if($e->name == 'OnWUsrFormSave'){
	//сохранение полей
	$userid = $modx->db->escape($_POST['id']);
	foreach($_POST as $key => $value){
		if(strpos($key,'customfield__')!==false){
			$field_name = str_replace('customfield__','',$key);
			$customFields[$field_name]=$modx->db->escape($value);
		}               
	}

	if($_POST['mode']=='88' && isset($customFields)){
		$result=$modx->db->update($customFields,$usertable,'internalKey='.$userid);
	}
}