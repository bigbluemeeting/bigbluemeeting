<?php


namespace App\Helpers;





use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\GetRecordingsParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use phpDocumentor\Reflection\Types\Self_;

class Helper
{

    public  static $createMeetingParams;
     public static function paginate($items, $perPage = null, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
    public static function get_local_time(){

        $ip = file_get_contents("http://ipecho.net/plain");

        $url = 'http://ip-api.com/json/'.$ip;

        $tz = file_get_contents($url);

        $tz = json_decode($tz,true)['timezone'];

        return $tz;

    }

    public static function setMeetingParams($params)
    {


        self::$createMeetingParams = new CreateMeetingParameters($params['meetingUrl'] , $params['meetingName']);
        self::$createMeetingParams->setAttendeePassword($params['attendeePassword']);
        self::$createMeetingParams->setModeratorPassword($params['moderatorPassword']);
        self::$createMeetingParams->setLogoutUrl($params['logoutUrl']);


        $files = ['https://img.youtube.com/vi/FAFlmkh0tEM/0.jpg','http://www.africau.edu/images/default/sample.pdf','https://file-examples.com/wp-content/uploads/2017/08/file_example_PPT_250kB.ppt'];

        foreach ($files as $file)
        {
            self::$createMeetingParams->addPresentation($file);

        }

        if (isset($params['muteAllUser']))
        {


            self::$createMeetingParams->setMuteOnStart($params['muteAllUser']);
            self::$createMeetingParams->setLockSettingsDisableMic($params['muteAllUser']);

        }
        if (isset($params['moderator_approval']))
        {


            $params['moderator_approval'] ? self::$createMeetingParams->setGuestPolicyAskModerator() : self::$createMeetingParams->setGuestPolicyAlwaysAccept();

        }
        if (isset($params['setRecord']))
        {
            self::$createMeetingParams->setRecord($params['setRecord']);
            self::$createMeetingParams->setAllowStartStopRecording($params['setRecord']);
        }
        if (isset($params['welcome_message']))
        {
            self::$createMeetingParams->setWelcomeMessage($params['welcome_message']);
        }


    }

    public static function createMeeting()
    {
        $bbb = new BigBlueButton();
        $response = $bbb->createMeeting(self::$createMeetingParams);
        return $response;
    }
    public static function joinMeeting($params)
    {
        $bbb = new BigBlueButton();
        $joinMeetingParams = new JoinMeetingParameters($params['meetingId'], $params['username'], $params['password']);
        $joinMeetingParams->setRedirect(true);
        $apiUrl = $bbb->getJoinMeetingURL($joinMeetingParams);
        return $apiUrl;
    }

    public static function formatBytes($size, $precision = 2)
    {
        if ($size > 0) {
            $size = (int) $size;
            $base = log($size) / log(1000);
            $suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');

            return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
        } else {
            return $size;
        }
    }
    public static function recordingLists($url)
    {
        $recordingList = [];
        $recordingParams = new GetRecordingsParameters();
        $recordingParams->setMeetingId($url);
        $bbb = new BigBlueButton();
        $response = $bbb->getRecordings($recordingParams);
        if ($response->getMessageKey() == null) {
            foreach ($response->getRawXml()->recordings->recording as $recording) {
                $recordingList[] = $recording ;
            }
        }

        $roomsRecordingList = [];

        foreach ($recordingList as $recording)
        {
            if ($recording->published == 'true')
            {
                $roomsRecordingList [] = $recording;
            }
        }
        return $roomsRecordingList;
    }

}