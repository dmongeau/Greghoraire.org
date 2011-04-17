<?php


try {
	if($_POST) {
		
		$config = include dirname(__FILE__).'/config.php';
		
		$service = Zend_Gdata_Calendar::AUTH_SERVICE_NAME;
		$client = Zend_Gdata_ClientLogin::getHttpClient($config['user'], $config['pwd'], $service);
		$service = new Zend_Gdata_Calendar($client);
		
		$event= $service->newEventEntry();
		 
		// Populate the event with the desired information
		// Note that each attribute is crated as an instance of a matching class
		$event->title = $service->newTitle($_POST['title']);
		$event->where = array($service->newWhere("Mountain View, California"));
		$event->content = $service->newContent($_POST['content']);
		 
		// Set the date using RFC 3339 format.
		$startDate = "2011-04-20";
		$startTime = "14:00";
		$endDate = "2011-04-20";
		$endTime = "16:00";
		$tzOffset = "-04";
		 
		$when = $service->newWhen();
		$when->startTime = "{$startDate}T{$startTime}:00.000{$tzOffset}:00";
		$when->endTime = "{$endDate}T{$endTime}:00.000{$tzOffset}:00";
		$event->when = array($when);
		 
		// Upload the event to the calendar server
		// A copy of the event as it is recorded on the server is returned
		$newEvent = $service->insertEvent($event);
		
	}
} catch(Exception $e) {
	var_dump($e);
	exit();
}

?><h1>Ajouter un événement</h1>

<form action="?" method="post">

    <div class="field">
        <label>Titre :</label>
        <div class="input"><input type="text" name="title" class="text title" /></div>
    </div>
    
    <div class="field">
        <label>Lieu :</label>
        <div class="input"><input type="text" name="where" class="text" /></div>
    </div>
    
    <div class="field">
        <label>Description :</label>
        <div class="input"><textarea name="content"></textarea></div>
    </div>
    
    <p><button type="submit">Ajouter</button></p>

</form>