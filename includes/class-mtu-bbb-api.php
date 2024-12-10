<?php
/*
* @package meetingium
*
* Handle requests to BigBlueButton using "https://github.com/bigbluebutton/bigbluebutton-api-php" 
*/

namespace Meetingium\BBB_Api;

use Meetingium\Utils\Utils;
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\EndMeetingParameters;
use BigBlueButton\Parameters\GetRecordingsParameters;
use BigBlueButton\Parameters\IsMeetingRunningParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;

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
    $createMeetingParams->setRecord(true);
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
  * get meeting url to join
  *
  * @param String $meetingId
  * @param String $userName User name inside meeting
  * @param String $password Password to join (moderatorPw for admin and attendeePw for normal clients)
  * @return String $url Meeting url to join
  */
  public static function getMeetingUrl(string $meetingId, string $userName, string $password) {
    $joinMeetingParams = new JoinMeetingParameters($meetingId, $userName, $password);
    $joinMeetingParams->setRedirect(true);

    $bbb = self::bbbInstance();
    if(!$bbb["success"]) return $bbb;
    $url = $bbb["data"]->getJoinMeetingURL($joinMeetingParams);
    return Utils::returnData($url);
  }

  /*
  * End meeting and delete that
  *
  * @param String $meetingId
  * @param String $moderatorPw Meeting admin password
  * @return Object $res
  */
  public static function endMeeting(string $meetingId, string $moderatorPw) {
    $endMeetingParams = new EndMeetingParameters($meetingId, $moderatorPw);
    
    $bbb = self::bbbInstance();
    if(!$bbb["success"]) return $bbb;
    $res = $bbb["data"]->endMeeting($endMeetingParams);
    return Utils::returnData($res);
  }

  /*
  * Get meeting recordings list
  *
  * @param String $meetingId
  * @return Array $res
  */
  public static function getRecordings(string $meetingId) {
    $recordingParams = new GetRecordingsParameters();
    $recordingParams->setMeetingId($meetingId);

    $bbb = self::bbbInstance();
    if(!$bbb["success"]) return $bbb;
    $res = $bbb["data"]->getRecordings($recordingParams);
    if($res->getReturnCode() != "SUCCESS") return Utils::returnErr("Can't get recording videos");    
    return Utils::returnData($res->getRawXml()->recordings->recording);
  }

  /*
  * Check is meeting running
  *
  * @param String $meetingId
  * @return Bool
  */
  public static function isMeetingRunning(string $meetingId) {
    $isMeetingRunningParams = new IsMeetingRunningParameters($meetingId);
    
    $bbb = self::bbbInstance();
    if(!$bbb["success"]) return $bbb;
    $res = $bbb["data"]->isMeetingRunning($isMeetingRunningParams);
    return $res->isRunning();
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
