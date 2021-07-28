<?php
//print_r($_REQUEST);

// echo "Hello from Json";
echo "
<p>A new approach is being introduced to try and increase the number of hot line calls recorded in the Call Tracking System (CTS).  This is mainly accomplished by greatly simplifying the data entry process by requiring entry of just the bare minimum of call details.</p>
<p>There is currently no facility that allows the reporting of the total number of calls made to the hot line during a single month other than the counts of calls that have been actually been entered into CTS.  The main objective of this new methodology is to simplify and speed up the capture of most, if not all, of the calls recorded onn the hot line voice message system.  Secondarily, the redesign is done specifically to allow use of phones or tablets to be used to make the process more universal.</p>
<p>The date of the call is assumed to be the &apos;TODAY&apos;'s date with the ability to post-date the call for up to a week.  The facility also provides the <b>required</b> entry of the additional minimal, basic information about a call: the reason for the call and the caller location.  Additionally, if the call is to be immediately closed, a resolution of the call is required.  Obviously the caller's name and contact information is also desirable but, ultimately, final business reporting does not use these fields.</p>
<p>Entry of the call data centers around using the caller phone number which is the main data point needed for a hot line volunteer (HLV) to provide assistance.  Entry of a valid 7 or 10 digit phone will automatically trigger a database look up of all previous calls using this number.</p>
<p>The special phone number facility built into the form does an automatic look up of the phone number entered to determine if that number has previously been EVER been used to originate a call to the hot line.  Any information entered including the caller name, email address and mailing address entered on any previous call is carried forward and recorded in the new call being entered.  A blue <span class='glyphicon glyphicon-question-sign ' style='color: blue; font-size: 15px'></span> will then be displayed if any previous calls exist and, if clicked, will display that information to be recorded for the new call.</p>
<p>All of the required fields are selected via drop down selection lists (except the phone number) are easily accessed on phones and tablets.  This makes the entry and recording of calls easily performed on these devices by reducing the amount of keyboard entry needed.</p>
<p>Once the required fields have been entered the call can be added into with CTS in one of two ways:</p>
<ol>
	<li><b>Save and Update New Call</b> - enters the call information provided as a new call in the CTS database and then displays the call for additional details, further updates and eventual closure.</li>
	<li><b>Add and Close Call</b> - clicking this button will cause the display and required entry of the &apos;Call Resolution&apos; field and the &apos;Resolve, Close and Add Another&apos; button.  Clicking this button will add the call information entered into the CTS database, marks the call as &apos;CLOSED&apos; and display the Add New Call form again in anticipation of the entry of another new call.  Use of any main menu item will cancel the entry form.</li>
</ol>
<p>If the first option is used, the call is &quot;OPEN&quot;ed and is displayed in the update call form.  The new call can then be updated with further details.  Eventually, when appropriate, the call can be closed.</p>
<p>In the second case, the call is added and marked as &quot;CLOSED&quot; immediately with a blank open new call form displayed.  This would probably be the case for a large number of calls that are being entered just to merely record their occurrence.</p>
<p>All calls entered will be listed in the menu selections for listing open and closed calls.  Please remember that calls can not be updated (nor re-opened) once they are closed as has always been the case in the past.</p>
<p>The main menu is always available for selection of other options but will prompt for confirmation of any newly selected action if any unsaved changes have been entered in the displayed form.  This behavior is unchanged from previous versions.</p>
<p>It is hoped that these simplified entry steps will dramatically speed up the entry process resulting in the number of calls recorded in CTS will be close(r) to the actual number left on the hot line service.</p>
";

?>