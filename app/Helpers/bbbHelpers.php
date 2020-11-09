<?php


namespace App\Helpers;


use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\GetRecordingsParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
use Illuminate\Support\Facades\URL;

class bbbHelpers
{

    public  static $createMeetingParams;

    public static function setMeetingParams($params)
    {

        try{
            self::$createMeetingParams = new CreateMeetingParameters($params['meetingUrl'] , $params['meetingName']);
            self::$createMeetingParams->setAttendeePassword($params['attendeePassword']);
            self::$createMeetingParams->setModeratorPassword($params['moderatorPassword']);
            self::$createMeetingParams->setLogoutUrl($params['logoutUrl']);


            if (isset($params['files']))
            {
                foreach ($params['files'] as $file)
                {
                    self::$createMeetingParams->addPresentation($file);

                }
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
        }catch (\Exception $exception)
        {
            return redirect()->back()->with(['danger'=>$exception->getMessage()]);
        }


    }
    public static function createMeeting()
    {
        $credentials = self::setCredentials();
        $bbb = new BigBlueButton($credentials['base_url'],$credentials['secret']);
        $response = $bbb->createMeeting(self::$createMeetingParams);
        return $response;
    }


    public static function joinMeeting($params)
    {


        $credentials = self::setCredentials();
        $bbb = new BigBlueButton($credentials['base_url'],$credentials['secret']);
        $joinMeetingParams = new JoinMeetingParameters($params['meetingId'], $params['username'], $params['password']);
        $joinMeetingParams->setRedirect(true);
        $apiUrl = $bbb->getJoinMeetingURL($joinMeetingParams);
        return $apiUrl;
    }

    public static function recordingLists($url)
    {
        $recordingList = [];
        $recordingParams = new GetRecordingsParameters();
        $recordingParams->setMeetingId($url);


        $credentials = self::setCredentials();
        $bbb = new BigBlueButton($credentials['base_url'],$credentials['secret']);
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

    public static function setCredentials()
    {

        if (!empty(settings()->get('bbb_url')) && !empty(settings()->get('bbb_secret')))
        {


            $base_url = \config('global.bbb_url');
            $secret = \config('global.bbb_secret');
            return [
                'base_url' =>$base_url,
                'secret' =>$secret,];
        }
        else{

            return false;

        }


    }
}