<?php

$tech_agent_name=$_GET['tech_agent_name'];
$tech_agent_mail=$_GET['tech_agent_mail'];


$date = $_GET['date'];
$startTime = $_GET['startTime'];
$endTime = $_GET['endTime'];
$subject_ics  = $_GET['subject_ics'];
$body_ics = $_GET['body_ics'];
$location_ics = $_GET['location_ics'];
$call_id=$_GET['call_id'];
$notes=$_GET['notes'];


$ical = "BEGIN:VCALENDAR
PRODID:-//Microsoft Corporation//Outlook 16.0 MIMEDIR//EN
VERSION:2.0
METHOD:PUBLISH
X-MS-OLK-FORCEINSPECTOROPEN:TRUE
BEGIN:VTIMEZONE
TZID:Israel Standard Time
BEGIN:STANDARD
DTSTART:16011028T020000
RRULE:FREQ=YEARLY;BYDAY=-1SU;BYMONTH=10
TZOFFSETFROM:+0300
TZOFFSETTO:+0200
END:STANDARD
BEGIN:DAYLIGHT
DTSTART:16010330T020000
RRULE:FREQ=YEARLY;BYDAY=-1FR;BYMONTH=3
TZOFFSETFROM:+0200
TZOFFSETTO:+0300
END:DAYLIGHT
END:VTIMEZONE
BEGIN:VEVENT
CLASS:PUBLIC
ATTENDEE;CN=\"" . $tech_agent_name . "\";RSVP=TRUE:" . $tech_agent_mail . "
CREATED:20220502T090030Z
DESCRIPTION:" . $body_ics . "&call_id=" . $call_id . " \\n הערות: $notes
DTEND;TZID=\"Israel Standard Time\":" . $date . "T" . $endTime . "
DTSTAMP:20220502T090030Z
DTSTART;TZID=\"Israel Standard Time\":" . $date . "T" . $startTime . "
LAST-MODIFIED:20220502T090030Z
LOCATION:" . $location_ics . "
PRIORITY:5
SEQUENCE:0
SUMMARY;LANGUAGE=he:" . $subject_ics . "
TRANSP:OPAQUE
X-MICROSOFT-CDO-BUSYSTATUS:BUSY
X-MICROSOFT-CDO-IMPORTANCE:1
X-MICROSOFT-DISALLOW-COUNTER:FALSE
X-MS-OLK-AUTOFILLLOCATION:FALSE
X-MS-OLK-CONFTYPE:0
BEGIN:VALARM
TRIGGER:-PT15M
ACTION:DISPLAY
DESCRIPTION:Reminder
END:VALARM
END:VEVENT
END:VCALENDAR";



header('Content-type: text/calendar; charset=utf-8');
header('Content-Disposition: inline; filename=' .$subject_ics .'.ics');

echo $ical;
?>
