  <?php

    /*establish a connection with the database*/
    require_once('mysqli_connect.php');

    /*main view used to search past or future events, date formatting adjusted
    with the date_format function, table designed using relational algebra
    the table is ordered by date for easier reading*/
    $query = "SELECT
    date_format(event_date, '%a') AS 'Day of week',
    date_format(event_date, '%d %M %Y') AS 'Date',
    start_time AS 'Beginning at',
    end_time AS 'Ending at',
    sport_name AS 'Sport',
    teams.team_name AS 'Home Team',
    teams1.team_name AS 'Visiting Team' from
    sports
    INNER JOIN teams ON sports.id = teams.sport_id
    INNER JOIN teams AS teams1 ON teams.sport_id = teams1.sport_id
    INNER JOIN sport_events AS events ON
      (teams.id = events.home_team AND teams1.id = events.visiting_team)
    ORDER BY event_date";

/*the result of the query is stored*/
    $response = @mysqli_query ($dbc, $query);

/*the rest of the file is dependent upon a successful connection and a successful querying of the database*/
    if ($response) {

      echo '<table align ="left" cellspacing = "5" cellpadding = "8">
      <td align = "left"><b>Day of Week</b></td>
      <td align = "left"><b>Date</b></td>
      <td align = "left"><b>Beginning at</b></td>
      <td align = "left"><b>Ending at</b></td>
      <td align = "left"><b>Sport</b></td>
      <td align = "left"><b>Home Team</b></td>
      <td align = "left"><b>Visiting Team</b></td>';

  /*unload the result of the database query into the html elements - column by column, row by row i.e. left to right, top to bottom */
      while ($row = mysqli_fetch_array($response)) {
        echo '<tr><td align = "left">' .
          $row['Day of week'] . '</td><td align = "left">' .
          $row['Date'] . '</td><td align = "left">' .
          $row['Beginning at'] . '</td><td align = "left">' .
          $row['Ending at'] . '</td><td align = "left">' .
          $row['Sport'] . '</td><td align = "left">' .
          $row['Home Team'] . '</td><td align = "left">' .
          $row['Visiting Team'] . '</td><td align = "left">';
        echo '</tr>';
      }
      echo '</table>';
    }


    else {
        echo "The database was not queried successfully: ";
        echo mysqli_error($dbc);
    }

    mysqli_close($dbc);

/*added option to categorize i.e. sort the events table by sport, would normally be done on the client-side with JS
  but for the sake of simplicity created another page with a modified sql query*/
    echo '<br/><br/><a href="fetch_events_cat.php">Group the Calendar by Sport</a>';

/*linking to the page where the user can add events to the calendar*/
    echo '<br/><br/><a href="add_event.php">Add an Event to the Calendar</a>';
  ?>
