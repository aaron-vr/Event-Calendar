<html>
<head>
  <title></title>
</head>
<body>

  <?php
/*this is the document which ensures that the data entered by the user is valid and adequate
to be sent off to the database; all the user will see is a positive or negative message
at the top of the screen including some details in case the INSERT was unsuccessful*/
    if(isset($_POST['submit'])) {
      $data_missing = array();
/*check for null imput for event date*/
      if(empty($_POST['event_date'])) {
        $data_missing[] = 'Event Date';d<
      }
      else {
        $e_date = trim($_POST['event_date']);
      }
/*check for null imput for beginning at*/
      if(empty($_POST['beginning_at'])) {
        $data_missing[] = 'Event Begin';
      }
      else {
        $beg_at = trim($_POST['beginning_at']);
      }
/*check for null imput for ending at*/
      if(empty($_POST['ending_at'])) {
        $data_missing[] = 'Event End';
      }
      else {
        $end_at = trim($_POST['ending_at']);
      }
/*check for null imput for home team*/
      if(empty($_POST['home_team'])) {
        $data_missing[] = 'Home Team';
      }
      else {
        $h_team = trim($_POST['home_team']);
      }
/*check for null imput for visiting team*/
      if(empty($_POST['visiting_team'])) {
        $data_missing[] = 'Visiting Team';
      }
      else {
        $v_team = trim($_POST['visiting_team']);
      }
/*once it is verified that no null values have been entered, dbc is initiated and
the values are inserted (with regards to datatypes of each attribute) using prepared statements and
value placeholders to prevent SQL injections */
      if(empty($data_missing)){

        require_once('mysqli_connect.php');

        $query = "INSERT INTO sport_events
          (event_date, start_time, end_time, home_team, visiting_team) values
          (?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($dbc, $query);

        mysqli_stmt_bind_param($stmt, "sssii", $e_date, $beg_at,
        $end_at, $h_team, $v_team);

        mysqli_stmt_execute($stmt);

        $affected_rows = mysqli_stmt_affected_rows($stmt);
/*checking db integrity constraints - see Sportcalendar_db.sql for details*/
        if ($affected_rows == 1) {
          echo 'Event added to calendar successfully!';
/*close sql statement and db connection as usual*/
          mysqli_stmt_close($stmt);
          mysqli_close($dbc);
          echo '<a href="add_event.php">Add Another Event</a>';
        }
/*notify the user of the potential DB integrity constraints their input violated*/
        else {
          echo 'Error Occurred upon Input<br/>';
          echo mysqli_error($dbc);
/*close the statement and dbc as always*/
          mysqli_stmt_close($stmt);
          mysqli_close($dbc);
          echo '<a href="add_event.php">Try Again</a>';
        }
      }
/*in case some data is still missing i.e. was not inserted by user, list out the null fields to the user to make correction easier*/
      else {
        echo 'Some event information is missing. You forgot to add the following:<br/>';
        foreach($data_missing as $missing) {
          echo "$missing<br/>";
        }
        /*connected with the rest of the files for easier navigation*/
        echo '<a href="add_event.php">Add Another Event</a>';
      }
    }
  ?>

</body>
</html>
