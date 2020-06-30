
@extends('admin.layouts.app')

@section('pagename', $pageName)

@section('content')
    <div class="container-fluid">
        <h5><i class="fa fa-envelope"></i>&nbsp;&nbsp;{{$pageName}}</h5>
    </div>

    <div >

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 ml-4">
                        <div class="alert alert-info text-dark">
                            You can override e-mail messages send out by Big Blue Meeting by defining your values below. A token looks like <strong class="text-danger">[token:name]</strong>. You must include all tokens in the default version of the e-mail messages in your version.
                        </div>
                    </div>

                    <div class="col-md-12">
                        <form action="{{route('emailStore')}}" method="Post">
                            @csrf
                            <div class="row ml-2">

                                <div class="col-md-6">

                                    <label for="defaultInviteParticipants">Mail Sent To Invite Participants</label><br>
                                    Default version
                                    <textarea name="defaultInviteParticipants" id="" cols="50" rows="10" class="form-control" disabled></textarea>

                                    <div class="mt-2">
                                    <span class="help-block">
                                        Required tokens: <br>
                                        Meeting Title {meeting_title}<br>
                                        User email {user_email}<br>
                                        Start time for the meeting in UTC {meeting_start}<br>
                                        End time for the meeting in UTC {meeting_end}<br>
                                        The URL to the page {meeting_user_page_link}
                                    </span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="inviteParticipants">Mail Sent To Invite Participants</label><br>
                                    Your version
                                    <textarea name="invite_participants" id="" cols="50" rows="10" class="form-control" >

                            </textarea>

                                    <div class="mt-2">
                                <span class="help-block">
                                    <label>
                                       <input type="checkbox" name="mail_invite_participant_reset" value="true">
                                       Reset to default

                                   </label>
                                </span>
                                    </div>
                                </div>


                            </div>
                            <hr class="ml-4">
                            <div class="row ml-2">
                                <div class="col-md-6">
                                    <label for="defaultMailFooter">Mail Footer Message</label><br>
                                    Default version
                                    <textarea name="default_mail_footer" id="" cols="50" rows="10" class="form-control" disabled></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="mail_footer">Mail Footer Message</label><br>
                                    Your version
                                    <textarea name="mail_footer" id="" cols="50" rows="10" class="form-control" ></textarea>
                                    <div class="mt-2">
                                <span class="help-block">
                                    <label>
                                       <input type="checkbox" name="mail_footer_reset" value="true">
                                       Reset to default

                                   </label>
                                </span>
                                    </div>
                                </div>
                            </div>

                            <hr class="ml-4">
                            <div class="row ml-2">
                                <div class="col-md-6">
                                    <label for="defaultMailFromName">Mail From Name</label><br>
                                    Default version
                                    <input name="default_mail_from_name" id=""  value="Big Blue Meeting" class="form-control" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label for="MailFromName">Mail From Name</label><br>
                                    Your version
                                    <input name="mail_from_name"  class="form-control">
                                    <div class="mt-2">
                                <span class="help-block">
                                    <label>
                                       <input type="checkbox" name="mail_from_name_reset" value="true">
                                       Reset to default

                                   </label>
                                </span>
                                    </div>

                                </div>
                            </div>

                            <hr class="ml-4">
                            <div class="row ml-2">
                                <div class="col-md-6">
                                    <label for="defaultMailSubject">Mail Subject</label><br>
                                    Default version
                                    <input name="default_mail_subject" id=""  value="Online meeting invite titled {meeting_name} from {user_email}" class="form-control" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label for="mailSubject">Mail From Name</label><br>
                                    Your version
                                    <input name="mail_subject"  class="form-control">
                                    <div class="mt-2">
                                <span class="help-block">
                                    <label>
                                       <input type="checkbox" name="mail_subject_reset" value="true">
                                       Reset to default

                                   </label>
                                </span>
                                    </div>

                                </div>
                            </div>

                            <hr class="ml-4">
                            <div class="row ml-2">
                                <div class="col-md-6">
                                    <label for="defaultMailTimezone">Timezone</label><br>
                                    Default version
                                    <select name="default_mail_timezone" id="" disabled class="form-control">
                                        @foreach (timezone_identifiers_list() as $timezone)
                                            <option value="{{ $timezone }}"{{ $timezone == old('timezone') ? ' selected' : '' }} {{$timezone == 'UTC' ? ' selected' : '' }}>{{ $timezone }}</option>
                                        @endforeach
                                    </select>
                                    {{--                                <input name="default_mail_timezone" id=""  value="Online meeting invite titled {meeting_name} from {user_email}" class="form-control" disabled>--}}
                                </div>
                                <div class="col-md-6">
                                    <label for="mailTimezone">Timezone</label><br>
                                    Your version
                                    <select name="default_mail_timezone" id=""  class="form-control">
                                        @foreach (timezone_identifiers_list() as $timezone)
                                            <option value="{{ $timezone }}"{{ $timezone == old('timezone') ? ' selected' : '' }} {{$timezone == 'UTC' ? ' selected' : '' }}>{{ $timezone }}</option>
                                        @endforeach
                                    </select>
                                    <div class="mt-2">
                                <span class="help-block">
                                    <label>
                                       <input type="checkbox" name="mail_timezone_reset" value="true">
                                       Reset to default

                                   </label>
                                </span>
                                    </div>

                                </div>
                            </div>

                            <hr class="ml-4">
                            <div class="row ml-2">
                                <div class="col-md-6">
                                    <label for="defaultMailModMail">Mail Sent To Moderator After A Meeting Is Created</label><br>
                                    Default version
                                    <textarea name="default_mod_mail" id="" cols="50" rows="10" class="form-control" disabled></textarea>
                                    <div class="mt-2">
                                    <span class="help-block">
                                        Required tokens: <br>
                                        The URL to the site {site_url}
                                    </span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="mailModMail">Mail Sent To Moderator After A Meeting Is Created</label><br>
                                    Your version
                                    <textarea name="mod_mail" id="" cols="50" rows="10" class="form-control" ></textarea>
                                    <div class="mt-2">
                                <span class="help-block">
                                    <label>
                                       <input type="checkbox" name="mail_mod_reset" value="true">
                                       Reset to default

                                   </label>
                                </span>
                                    </div>
                                </div>
                            </div>

                            <hr class="ml-4">
                            <div class="row ml-2">
                                <div class="col-md-6">
                                    <label for="defaultMailModMailFooter">Mail Footer Message</label><br>
                                    Default version
                                    <textarea name="default_mod_mail_footer" id="" cols="50" rows="10" class="form-control" disabled></textarea>

                                </div>

                                <div class="col-md-6">
                                    <label for="mailModMailFooter">Mail Footer Message</label><br>
                                    Your version
                                    <textarea name="mod_mail_footer" id="" cols="50" rows="10" class="form-control" ></textarea>
                                    <div class="mt-2">
                                <span class="help-block">
                                    <label>
                                       <input type="checkbox" name="mod_mail_footre_reset" value="true">
                                       Reset to default

                                   </label>
                                </span>
                                    </div>
                                </div>
                            </div>

                            <hr class="ml-4">
                            <div class="row ml-2">
                                <div class="col-md-6">
                                    <input type="submit" value="Update" class="btn btn-info">
                                </div>

                            </div>

                        </form>
                    </div>


                </div>

            </div>
        </div>



@stop