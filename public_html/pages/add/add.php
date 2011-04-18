<?php


function leadingZero($number) {
	return ((int)$number < 10 ? '0':'').$number;
}

try {
	if($_POST) {
		
		if(!isset($_POST['title']) || empty($_POST['title'])) throw new Exception('Vous devez entrer un titre');
		if(!isset($_POST['datestart']) || empty($_POST['datestart']) || $_POST['datestart'] == '0000-00-00') {
			throw new Exception('Vous devez sélectionner une date de début');
		}
		if(!isset($_POST['dateend']) || empty($_POST['dateend']) || $_POST['dateend'] == '0000-00-00') {
			throw new Exception('Vous devez sélectionner une date de fin');
		}
		
		$config = include dirname(__FILE__).'/config.php';
		
		$service = Zend_Gdata_Calendar::AUTH_SERVICE_NAME;
		$client = Zend_Gdata_ClientLogin::getHttpClient($config['user'], $config['pwd'], $service);
		$service = new Zend_Gdata_Calendar($client);
		
		$event= $service->newEventEntry();
		 
		// Populate the event with the desired information
		// Note that each attribute is crated as an instance of a matching class
		$event->title = $service->newTitle($_POST['title']);
		if(isset($_POST['where']) && !empty($_POST['where'])) $event->where = array($service->newWhere($_POST['where']));
		if(isset($_POST['content']) && !empty($_POST['content'])) $event->content = $service->newContent($_POST['content']);
		 
		// Set the date using RFC 3339 format.
		$startDate = $_POST['datestart'];
		$startTime = leadingZero($_POST['datestart_hour']).':'.leadingZero($_POST['datestart_minute']);
		$endDate = $_POST['dateend'];
		$endTime = leadingZero($_POST['dateend_hour']).':'.leadingZero($_POST['dateend_minute']);
		$tzOffset = "-04";
		 
		$when = $service->newWhen();
		$when->startTime = "{$startDate}T{$startTime}:00.000{$tzOffset}:00";
		$when->endTime = "{$endDate}T{$endTime}:00.000{$tzOffset}:00";
		$event->when = array($when);
		
		$newEvent = $service->insertEvent($event);
		
		header('Location: /?added=true',true);
		exit();
		
	}
} catch(Exception $e) {
	$error = $e->getMessage();
}


$this->addScript('/statics/js/add.js');


?>
<div class="left">
    <h1>Ajouter un événement</h1>
    
    <div class="spacer-small"></div>
    
    <?php if(isset($error) && !empty($error)) { ?>
    <div class="error" style="width:485px;"><?=$error?></div>
    <?php } ?>
    
    <form action="?" method="post">
    
        <div class="field">
            <label>Titre :</label>
            <div class="input"><input type="text" name="title" class="text title" value="<?=(!isset($_POST['title']) ? '':$_POST['title'])?>" /></div>
        </div>
        
        <div class="field">
            <label>Lieu :</label>
            <div class="input"><input type="text" name="where" class="text" value="<?=(!isset($_POST['where']) ? '':$_POST['where'])?>" /></div>
        </div>
        
        <?php
        	
			$hourOptions = array();
			$minuteOptions = array();
        	for($i = 0; $i < 24; $i++) {
				$hour = ($i < 10) ? '0'.$i:$i;
				$sel = isset($_POST['datestart_hour']) && (int)$_POST['datestart_hour'] == (int)$i ? 'selected="selected"':'';
				$hourOptions[] = '<option value="'.$i.'" '.$sel.'>'.$hour.'</option>';
			}
        	for($i = 0; $i < 4; $i++) {
				$minute = ($i*15);
				$sel = isset($_POST['datestart_minute']) && (int)$_POST['datestart_minute'] == (int)$i ? 'selected="selected"':'';
				$minuteOptions[] = '<option value="'.$minute.'" '.$sel.'>'.($minute < 10 ? '0'.$minute:$minute).'</option>';
			}
        
        ?>
        <div class="field">
            <label>Date de début :</label>
            <div class="input">
                <input type="text" name="datestart" class="text date start" maxlength="10" value="<?=(!isset($_POST['datestart']) ? date('Y-m-d'):$_POST['datestart'])?>" /> | 
                <select name="datestart_hour" class="hour"><?=implode("\n",$hourOptions)?></select> : 
                <select name="datestart_minute" class="minute"><?=implode("\n",$minuteOptions)?></select>
            </div>
        </div>
        <?php
        	
			$hourOptions = array();
			$minuteOptions = array();
			if(!isset($_POST['dateend_hour'])) $_POST['dateend_hour'] = '02';
        	for($i = 0; $i < 24; $i++) {
				$hour = ($i < 10) ? '0'.$i:$i;
				$sel = isset($_POST['dateend_hour']) && (int)$_POST['dateend_hour'] == (int)$i ? 'selected="selected"':'';
				$hourOptions[] = '<option value="'.$i.'" '.$sel.'>'.$hour.'</option>';
			}
        	for($i = 0; $i < 4; $i++) {
				$minute = ($i*15);
				$sel = isset($_POST['dateend_minute']) && (int)$_POST['dateend_minute'] == (int)$i ? 'selected="selected"':'';
				$minuteOptions[] = '<option value="'.$minute.'" '.$sel.'>'.($minute < 10 ? '0'.$minute:$minute).'</option>';
			}
        
        ?>
        <div class="field">
            <label>Date de fin :</label>
            <div class="input">
                <input type="text" name="dateend" class="text date end" maxlength="10" value="<?=(!isset($_POST['dateend']) ? date('Y-m-d'):$_POST['dateend'])?>" /> | 
                <select name="dateend_hour" class="hour"><?=implode("\n",$hourOptions)?></select> : 
                <select name="dateend_minute" class="minute"><?=implode("\n",$minuteOptions)?></select>
            </div>
        </div>
        
        <div class="field">
            <label>Description :</label>
            <div class="input"><textarea name="content"><?=(!isset($_POST['content']) ? '':$_POST['content'])?></textarea></div>
        </div>
        
        <p><button type="submit">Ajouter à l'agenda</button></p>
    
    </form>
</div>

<div class="right">
	<h2>Collaborer</h2>
    
    <p>Avant de faire votre première contribution à l'agenda de Gregory Charles, n'oubliez pas les faits suivants:</p>
    <div class="spacer-small"></div>
    <ul class="point">
    	<li>Il peut faire plusieurs chose en même temps</li>
        <li>Il est capable de se téléporter</li>
    </ul>
</div>