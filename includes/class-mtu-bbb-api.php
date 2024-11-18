<?php
/*
* @package meetingium
*
* Handle requests to BigBlueButton using "https://github.com/bigbluebutton/bigbluebutton-api-php" 
*/

namespace Meetingium\BBB_Api;

use Meetingium\Utils\Utils as Utils;
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\CreateMeetingParameters;

defined( 'ABSPATH' ) || exit;

class MTU_BBB_Api {
  /*
  * Create meetings on bigbluebutton server using bbb api
  *
  * @param String $meetingName Name of meeting
  * @param String $meetingId (optional)
  * @param String $moderatorPw Admin password to join meeting (optional)
  * @param Strubg $attendeePw Normal client password to join meeting (optional)
  * @return Object $res 
  */
  public static function createMeeting(string $meetingName, string $meetingId = "", string $moderatorPw = "", string $attendeePw = "") {
    $meetingId = $meetingId ?: "meeting-".Utils::genRandomStr(4)."-".time();
    $moderatorPw = $moderatorPw ?: Utils::genRandomStr(10);
    $attendeePw = $attendeePw ?: Utils::genRandomStr(8);

    $createMeetingParams = new CreateMeetingParameters($meetingId, $meetingName);
    $createMeetingParams->setModeratorPassword($moderatorPw);
    $createMeetingParams->setAttendeePassword($attendeePw);
    $createMeetingParams->setLogoutUrl(home_url());
    $createMeetingParams->setAllowStartStopRecording(true);
	  $createMeetingParams->setAutoStartRecording(false);
    $createMeetingParams->setMuteOnStart(true);
    $createMeetingParams->setLockSettingsDisableNote(true);
    $createMeetingParams->setLockSettingsDisablePrivateChat(true);
    $createMeetingParams->setLockSettingsDisablePublicChat(true);
    $createMeetingParams->setWebcamsOnlyForModerator(true);
    $createMeetingParams->setLockSettingsDisableMic(true);
    $createMeetingParams->setLockSettingsLockOnJoin(true);
    $createMeetingParams->setLockSettingsLockOnJoinConfigurable(true);

    $bbb = self::bbbInstance();
    if(!$bbb["success"]) return $bbb;
    $res = $bbb["data"]->createMeeting($createMeetingParams);
    return Utils::returnData($res);
  }

  /*
  * Create BigBlueButton instance
  *
  * @return BigBlueButton
  */
  private static function bbbInstance() {
    $bbbUrl = get_option("meetingium_bbb_url"); 
    $bbbSalt = get_option("meetingium_bbb_salt");
    if(!$bbbUrl || !$bbbSalt) return Utils::returnErr("Can't get BigBlueButton informations");

    $bbb = new BigBlueButton($bbbUrl, $bbbSalt);
    return Utils::returnData($bbb);
  }
}
