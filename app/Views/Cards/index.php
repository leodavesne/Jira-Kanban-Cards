<div class="search">
	<form action="?a=tickets" method="POST">
		<h1>Jira Kanban Cards</h1>
		<p>Put your JQL query below and press the button to get the pretty print-version of your tickets:</p>

		<div class="jirainfo">
			<label for="name">Jira-Path: </label>
			<input type="text" name="path" />
			<br />

			<label for="name">Username: </label>
			<input type="text" name="username" />
			<br />

			<label for="password">Password: </label>
			<input type="password" name="password" />
			<br />

			<label for="epic">Epic: </label>
			<select name="epic">
				<option value="1" selected="selected">Include information</option>
				<option value="0">Exclude information</option>
			</select>
			<br />

			<label for="reporter">Reporter: </label>
			<select name="reporter">
				<option value="1">Include information</option>
				<option value="0" selected="selected">Exclude information</option>
			</select>
			<br />

			<label for="assignee">Assignee: </label>
			<select name="assignee">
				<option value="1">Include information</option>
				<option value="0" selected="selected">Exclude information</option>
			</select>
			<br />

			<label for="remaining_time">Remaining Time: </label>
			<select name="remaining_time">
				<option value="1">Include information</option>
				<option value="0" selected="selected">Exclude information</option>
			</select>
			<br />

			<label for="jql">JQL: </label>
			<input type="text" name="jql" />
			<br />

			<label for="personalization">Personalization: </label>
			<input type="text" name="personalization" />
			<br />

			<input type="submit" value="Get tickets!" />
		</div>
	</form>
</div>
