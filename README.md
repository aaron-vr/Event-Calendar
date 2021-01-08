# Sportradar-Calendar
Coding exercise to implement a calendar for sport events

TECH-STACK: MySQL 10.4.14 connected to the XAMPP 3.2.4 using Apache 2.4 and PHP 7.2.34

The event calendar is made up of three relations (managed using MySQL) which communicate with the user via PHP and Apache. The front end interface - with nested html elements - although covering all the required functionalities, has no visual aspects apart from input forms and hyperlinks. 

Events are added by the user who fills out an html form made up of five pieces of data (the two opposing teams are fetched from the database and selected in a dropdown by the user, rather than manually inserted). 

Once the user submits the form, the data is first checked in PHP for null-values and illegal values and then subsequently checked by the database going through a series of predetermined integrity constraints. If the data is in acceptable form it is inserted and the user is given a positive response. The page allows for further navigation to two other pages, one contains a view of all the added events and the other contains the same view only categorized by sport. All the pages are cross-linked.

Things to note: 
Multiple events can be added to a single day, although an integrity constraint prevents the same team from playing more than one match on a given date. 

Additionally, another constraint is added which prevents users from adding events where both teams are the same. 

To maintain simplicity, the calendar does not include any form of user identification and the added events are therefore not stamped with user details. Finally, update timestamps are also omitted, for the sake of simplicity, nevertheless, each insert into the database is timestamped.

Things to be potentially implemented: 

1. As the calendar supports a multitude of different sports, potential sport mismatches are possible (a football team playing against a basketball team). To be implemented either in the database using an after-insert-trigger that rolls back the transaction (as individual transactions are always made up of only one insert) - could become a bottleneck for larger databases. Alternatively, as the tech-stack is limited to PHP, a server-side condition querying the database and verifying that the data is valid before it is inserted

2. A restriction to prevent past events to be added i.e. those where the event_date has a lower value than the current date; most likely a database trigger, although could be just as easily done in PHP. 

3. Remove looped SQL queries from PHP 

