<?php
/**
* Build script adapted from the Login package by Jason Coward and Shaun McCormick
*
* Licensed under the GNU General Public License V2 or later
*/
 
$chunks = array();
$chunks[1]= $modx->newObject('modChunk');
$chunks[1]->fromArray(array(
    'id' => 1,
    'name' => 'emReservationForm',
    'description' => 'Form (including FormIt call) to make a Reservation.',
    'snippet' => '[[!FormIt?
   &hooks=`emNewReservationHook,email,redirect`
   &emailTpl=`emReservationEmail`
   &emailTo=`[[+email]]`
   &emailSubject=`Uw online reservering`
   &emailFrom=`<snip>`
   &emailFromName=`<snip>`
   &emailReplyTo=`<snip>`
   &emailReplyToName=`<snip>`
   &emailBCC=`<snip>`
   &redirectTo=`15`
   &validate=`name:required,
      email:email:required,
      eventid:required:isNumber:minValue=`1`,
      tickets:required:isNumber:minValue=`1`,
      lastname:required,
      phone:required`
]]
[[!+fi.error.debug]]
<form action="[[~[[*id]]]]" method="post" class="form">
  <fieldset>
    <legend>Activiteit gegevens</legend>
    <p><label for="eventid">Activiteit</label>
      <select name="eventid" id="eventid">
        [[!emListEvents? &rowTpl=`emRowSelectBoxTpl` &limit=`0` &default=`[[!+fi.eventid:default=`@GET eid`]]`]]
      </select>
      <span class="error">[[!+fi.error.eventid]]</span>
    </p>
    <p><label for="tickets">Aantal personen</label>
      <input type="text" name="tickets" id="tickets" value="[[!+fi.tickets]]" />
      <span class="error">[[!+fi.error.tickets]]</span>
    </p>
  </fieldset>
  <fieldset>
    <legend>Persoonsgegevens</legend>
    <p>Wij respecteren uw gegevens en gaan hier zorgvuldig mee om. Deze zullen niet gebruikt worden voor commerciele doeleinden.</p>
    <p><label for="firstname">Voornaam</label>
      <input type="text" name="firstname" id="firstname" value="[[!+fi.firstname]]" />
      <span class="error">[[!+fi.error.firstname]]</span>
    </p>
    <p><label for="lastname">Achternaam</label>
      <input type="text" name="lastname" id="lastname" value="[[!+fi.lastname]]" />
      <span class="error">[[!+fi.error.lastname]]</span>
    </p>
    <p><label for="address">Adres</label>
      <textarea name="address" id="address">[[!+fi.address]]</textarea>
      <span class="error">[[!+fi.error.address]]</span>
    </p>
    <p><label for="phone">Telefoon</label>
      <input type="text" name="phone" id="phone" value="[[!+fi.phone]]" />
      <span class="error">[[!+fi.error.phone]]</span>
    </p>
    <p><label for="email">Email</label>
      <input type="text" name="email" id="email" value="[[!+fi.email]]" />
      <span class="error">[[!+fi.error.email]]</span>
    </p>
  </fieldset>
  <fieldset>
    <legend>Bevestiging</legend>
    <p>Door hieronder op "Reservering voltooien" te klikken wordt uw reservering in ons geautomiseerd systeem vast gelegd. U ontvangt vervolgens een email op het adres welke eerder is ingevuld met de gegevens.</p>
    <p>Mocht u ondanks de reservering niet in de gelegenheid te zijn aanwezig te zijn willen wij u vragen om zo spoedig mogelijk per email of telefoon contact met ons op te nemen en uw reserveringsnummer te melden.</p>
    <p>Indien u nog opmerkingen heeft kunt u deze hieronder kwijt. Eventuele wensen en/of vragen worden mogelijk niet voor de activiteit gezien dus neem daarvoor contact op met het beheer.</p>
    <p><label for="remarks">Opmerkingen</label>
      <textarea name="remarks" id="remarks">[[!+fi.remarks]]</textarea>
      <span class="error">[[!+fi.error.remarks]]</span>
    </p>
    <p><input type="submit" name="submit" value="Reservering voltooien" /></p>
  </fieldset>
</form>',
    'properties' => '',
),'',true,true);

$chunks[2]= $modx->newObject('modChunk');
$chunks[2]->fromArray(array(
    'id' => 2,
    'name' => 'emRowSelectBoxTpl',
    'description' => 'Tpl chunk used to display a dropdown list of events on the reservations form',
    'snippet' => '<option value="[[+eventid]]"[[+default:eq=`[[+eventid]]`:then=` selected="selected"`]]>[[+title]] ([[+date]])</option>',
    'properties' => '',
),'',true,true);


$chunks[3]= $modx->newObject('modChunk');
$chunks[3]->fromArray(array(
    'id' => 3,
    'name' => 'emRowTpl',
    'description' => 'Simple template to loop through events',
    'snippet' => '<p class="event-date">[[+date]]</p>
<p class="event-title">[[+title]]</p>
<p class="event-desc">[[+description]]<br />[[+reservationlink]]</p>',
    'properties' => '',
),'',true,true);


$chunks[4]= $modx->newObject('modChunk');
$chunks[4]->fromArray(array(
    'id' => 4,
    'name' => 'emReservationEmail',
    'description' => 'Reservation mail to the customer',
    'snippet' => '<p><em>Dit bericht is automatisch gegenereerd. Neem bij vragen contact op met het beheer van <snip>.</em></p>
<p>Beste [[+firstname:notempty=`[[+firstname]] [[+lastname]]`]],
  <br />
  <br />Bedankt voor uw reservering voor "[[+event.title]]" op [[+event.date:date=`%e %B %Y`]] in <snip>. Door middel van deze e-mail bevestigen wij uw reservering in ons geautomatiseerd reserverings systeem.
  <br />
  <br />De volgende gegevens zijn bij ons bekend:
  <br />- Activiteit: [[+event.title]]
  <br />- Activiteit tijdstip: [[+event.date:date=`%e %B %Y om %H:%M`]]u
  <br />- Aantal kaarten: [[+tickets]]
  <br />
  <br />Uw reservering is aangemaakt met de volgende gegevens:
  <br />- Reserveringsnummer: [[+reservationid]]
  <br />- Naam: [[+firstname:notempty=`[[+firstname]] [[+lastname]]`]]
  <br />- Email: [[+email]]
  <br />- Telefoon: [[+phone]]
  <br />- Adres: [[+address:nl2br]]
  <br />- Opmerkingen: [[+remarks:htmlentities:nl2br]]
  <br />
  <br />Nogmaals bedankt voor uw reservering, en graag tot [[+event.date:date=`%e %B %Y`]]!
  <br />
  <br /><em><snip></em></p>',
    'properties' => '',
),'',true,true);


return $chunks;