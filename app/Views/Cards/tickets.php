<?php

/**
 * Helper class for this view.
 * TODO: move this helper to own structure
 */
class CardsHelper {

	protected $knownEpics = array();

	public function getEpicNumber($epicname) {
		if(empty($epicname)) return "";
		$array_pos = array_search( $epicname, $this->knownEpics );
		if( $array_pos === false ) {
			$this->knownEpics[] = $epicname;
			$array_pos = array_search( $epicname, $this->knownEpics );
		}
		return $array_pos;
	}

}

$helper = new CardsHelper();
?>

<?php foreach( $tickets as $ticket ) { ?>
<div class="ticket">
	<div class="number">
		<div class="issuetype color <?php echo str_replace('(', '', str_replace(')', '', str_replace('-', '', str_replace(' ', '', strtolower($ticket['issuetype']))))) ?>"></div>
		<?php echo $ticket["key"] ?>
	</div>
	<div>
		<div>
			<div class="issuetype">
				<div class="issuetype image <?php echo str_replace('(', '', str_replace(')', '', str_replace('-', '', str_replace(' ', '', strtolower($ticket['issuetype']))))) ?>"></div>
				<?php echo $ticket['issuetype'] ?>
			</div>
			<div class="priority">
				<div class="priority <?php echo strtolower($ticket['priority']) ?>"></div>
				<?php echo $ticket['priority'] ?>
			</div>
		</div>
		<div>
			<?php if( isset($ticket['epic']) ) { ?>
			<div class="epic epicgroup_<?php echo $helper->getEpicNumber($ticket['epic']); ?>"><?php echo $ticket["epic"] ?></div>
			<?php } ?>
			<?php if( isset($ticket['story_points']) ) { ?>
			<div class="story_points"><?php echo $ticket["story_points"] ?> Points</div>
			<?php } ?>
		</div>
	</div>
	<div class="summary"><?php echo ucfirst($ticket["summary"]) ?></div>
	<!--
	<?php if( isset($ticket['rank']) ) { ?>
	<div class="rank"><?php echo $ticket["rank"] ?></div>
	<?php } ?>
	<?php if(strlen($ticket["parent"]) > 0) { ?>
	<div class="parent"><?php echo $ticket["parent"] ?></div>
	<?php } ?>
	<?php if( isset($ticket['reporter']) ) { ?>
	<div class="reporter"><?php echo $ticket["reporter"] ?></div>
	<?php } ?>
	<?php if( isset($ticket['assignee']) ) { ?>
	<div class="assignee">
		<span class="name"><?php echo $ticket["assignee"] ?></span>
		<img class="avatar" src="<?php echo $ticket['avatar'] ?>" />
	</div>
	<?php } ?>
	<?php if( isset($ticket['remaining_time']) ) { ?>
	<div class="remaining_time"><?php echo $ticket["remaining_time"] ?></div>
	<?php } ?>
	-->
	<?php if( isset($_POST['personalization']) ) { ?>
	<div class="personalization"><?php echo $_POST['personalization'] ?></div>
	<?php } ?>
</div>
<?php } ?>
