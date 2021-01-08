<!DOCTYPE html>
<?php

  /*establish a connection with the database*/
  require_once('mysqli_connect.php');

  /*sql query used to retreive the set of predefined teams and populate the html dropdowns*/
  $query = "SELECT id, team_name from teams";

  $response = @mysqli_query ($dbc, $query);

?>
<html>
<head>

  <title></title>

</head>
<body>
  <!-- the form to enter all the event details i.e. the date of the event, the beginning and ending times, as well as the two teams competing-->
  <form action="event_added.php" method = "POST">
    <b>New sports event</b>
    <p>Date:
      <input type="date" name="event_date" size="30" value="" />
    </p>

    <p>Beginning at:
      <input type="time" name="beginning_at" size="30" value="" />
    </p>

    <p>Ending at:
      <input type="time" name="ending_at" size="30" value="" />
    </p>

  <!-- two dropdowns for teams below, normally would use cascading dropdowns
    to prevent duplicate inputs but tech stack restricted for this exercise -->
    <?php
      /*standard conditional to ensure no queries are executed if the connection fails*/
      if ($response) {
        /*populate an associative array with the teams from the database*/
        $teams = array();
        while ($row = mysqli_fetch_assoc($response)) {
          array_push($teams, $row);
        }
        echo '<p>Home Team: <select name="home_team">';
        /*unload the teams into the html element - first dropdown; very important: the user sees and selects the team_name
        but the server receives the corresponding option value for that element which represents the corresponding team_id - see SQL database*/
        foreach ($teams as $team) {
          echo '<option value="' . $team['id'] . '">' . $team['team_name'] . '</option>';
        }
        echo '</select></p>';
        /*unload the teams into the html element - second dropdown*/
        echo '<p>Visiting Team: <select name="visiting_team">';
        foreach ($teams as $team) {
          echo '<option value="' . $team['id'] . '">' . $team['team_name'] . '</option>';
        }
        echo '</select></p>';
    }
        /*just in case*/
        else {
          echo '<option>Check DBC</option>';
        }
        /*ensure that the connection is closed at the end*/
        mysqli_close($dbc);
      ?>
      <p>
        <input type="submit" name="submit" value="Add Event to Calendar"/>
      </p>
    </form>
<!-- the option to return to see the events calendar, taken to another page-->
    <a href="fetch_events.php">See the Event Calendar</a>
</body>
</html>
