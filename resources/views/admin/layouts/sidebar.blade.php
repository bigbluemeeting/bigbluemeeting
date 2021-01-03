<aside class="sidebar">
    <div class="sidebar-container">
        <div class="sidebar-header">
            <div class="brand">
                <div class="logo">
                    <span class="l l1"></span>
                    <span class="l l2"></span>
                    <span class="l l3"></span>
                    <span class="l l4"></span>
                    <span class="l l5"></span>
                </div> {{config('global.app_name') }} </div>
        </div>
        <nav class="menu">
            <ul class="sidebar-menu metismenu" id="sidebar-menu">

                <li class="{{ (Request::is('meetings*') || Request::is('meetings')) ? 'active open' : '' }}">
                    <a href="">
                        <i class="fa fa-clock-o"></i> Meetings
                        <i class="fa arrow"></i>
                    </a>
                    <ul class="sidebar-nav">
                        <li>
                            @if(Gate::check('moderate') || Gate::check('users_manage') || Gate::check('master_manage'))
                                <a href="{{ route('meetings.index') }}"> Create Meetings </a>
                            @endif
                            <a href="{{route('invitedMeetings')}}"><?= __('Meeting Invitations'); ?></a>
                        </li>
                    </ul>
                </li>
                <li class="{{ (Request::is('rooms*') || Request::is('room')) ? 'active open' : '' }}">
                    <a href="">

                        <i class="fa fa-handshake-o"></i> Rooms
                        <i class="fa arrow"></i>
                    </a>
                    <ul class="sidebar-nav">
                        <li>
                            <a href="{{ route('rooms.index') }}"> Rooms List </a>
                        </li>

                    </ul>
                </li>

                <li class="{{ (Request::is('files*') || Request::is('files')) ? 'active open' : '' }}">
                    <a href="{{ route('files.index') }}">
                        <i class="fa fa-files-o"></i> Files
                    </a>
                </li>
                <li class="{{ (Request::is('admin/recordings*') || Request::is('admin/recordings')) ? 'active open' : '' }}">
                    <a href="">
                        <i class="fa fa-file-audio-o"></i> Recording
                        <i class="fa arrow"></i>
                    </a>
                    <ul class="sidebar-nav">
                        <li>
                            <a href="{{ route('admin::recordings.index') }}"> Recordings List </a>
                        </li>
                        <li>
                            <a href="{{ route('admin::invitedRoomsRecordings') }}"> Invited Meeting Recordings </a>
                        </li>

                    </ul>
                </li>

                @can('users_manage')
                <li class="{{ (Request::is('mail*') || Request::is('mail')) ? 'active open' : '' }}">
                    <a href="{{ route('mail.index') }}">

                        <i class="fa fa-envelope"></i> E-mail Preferences
                    </a>

                </li>
                @endcan
                @can('users_manage')
                    <li class="{{ (Request::is('admin/users*') || Request::is('admin/roles*')) ? 'active open' : '' }}">
                        <a href="">
                            <i class="fa fa-users"></i> User Management
                            <i class="fa arrow"></i>
                        </a>
                        <ul class="sidebar-nav">
                            <li class="{{ (Request::is('admin/users*')) ? 'active' : '' }}">
                                <a href="{{ route('admin::users.index') }}"> Users </a>
                            </li>
                            <li class="{{ (Request::is('admin/roles*')) ? 'active' : '' }}">
                                <a href="{{ route('admin::roles.index') }}"> Roles </a>
                            </li>
                        </ul>
                    </li>


                @endcan






                <li>
                    <a href="{{ route('admin::change_password') }}">
                        <i class="fa fa-key"></i> Change Password </a>
                </li>
            </ul>
        </nav>
    </div>
    <footer class="sidebar-footer">
        <ul class="sidebar-menu metismenu" id="customize-menu">
            <li>
                <ul>
                    <li class="customize">
                        <div class="customize-item">
                            <div class="row customize-header">
                                <div class="col-4"> </div>
                                <div class="col-4">
                                    <label class="title">fixed</label>
                                </div>
                                <div class="col-4">
                                    <label class="title">static</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <label class="title">Sidebar:</label>
                                </div>
                                <div class="col-4">
                                    <label>
                                        <input class="radio" type="radio" name="sidebarPosition" value="sidebar-fixed">
                                        <span></span>
                                    </label>
                                </div>
                                <div class="col-4">
                                    <label>
                                        <input class="radio" type="radio" name="sidebarPosition" value="">
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <label class="title">Header:</label>
                                </div>
                                <div class="col-4">
                                    <label>
                                        <input class="radio" type="radio" name="headerPosition" value="header-fixed">
                                        <span></span>
                                    </label>
                                </div>
                                <div class="col-4">
                                    <label>
                                        <input class="radio" type="radio" name="headerPosition" value="">
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <label class="title">Footer:</label>
                                </div>
                                <div class="col-4">
                                    <label>
                                        <input class="radio" type="radio" name="footerPosition" value="footer-fixed">
                                        <span></span>
                                    </label>
                                </div>
                                <div class="col-4">
                                    <label>
                                        <input class="radio" type="radio" name="footerPosition" value="">
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="customize-item">
                            <ul class="customize-colors">
                                <li>
                                    <span class="color-item color-red" data-theme="red"></span>
                                </li>
                                <li>
                                    <span class="color-item color-orange" data-theme="orange"></span>
                                </li>
                                <li>
                                    <span class="color-item color-green active" data-theme=""></span>
                                </li>
                                <li>
                                    <span class="color-item color-seagreen" data-theme="seagreen"></span>
                                </li>
                                <li>
                                    <span class="color-item color-blue" data-theme="blue"></span>
                                </li>
                                <li>
                                    <span class="color-item color-purple" data-theme="purple"></span>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
                <a href="">
                    <i class="fa fa-cog"></i> Customize </a>
            </li>
        </ul>
    </footer>
</aside>
<div class="sidebar-overlay" id="sidebar-overlay"></div>
<div class="sidebar-mobile-menu-handle" id="sidebar-mobile-menu-handle"></div>
<div class="mobile-menu-handle"></div>
