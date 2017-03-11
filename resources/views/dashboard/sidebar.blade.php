<div class="side-menu sidebar-inverse">
    <nav class="navbar navbar-default" role="navigation">
        <div class="side-menu-container">
            <div class="navbar-header">
                <a class="navbar-brand" href="{{ route('omega.dashboard') }}">
                    <div class="logo-icon-container">
                        <?php $admin_logo_img = Omega::setting('admin_icon_image', ''); ?>
                        @if($admin_logo_img == '')
                            <img src="{{ config('omega.assets_path') }}/images/logo-icon-light.png" alt="Logo Icon">
                        @else
                            <img src="{{ Omega::image($admin_logo_img) }}" alt="Logo Icon">
                        @endif
                    </div>
                    <div class="title">{{Omega::setting('admin_title', 'Omega Ragnarok Admin Panel')}}</div>
                </a>
            </div><!-- .navbar-header -->

            <div class="panel widget center bgimage"
                 style="background-image:url({{ Omega::image( Omega::setting('admin_bg_image'), config('omega.assets_path') . '/images/bg.jpg' ) }});">
                <div class="dimmer"></div>
                <div class="panel-content">
                    <img src="{{ $user_avatar }}" class="avatar" alt="{{ Auth::user()->name }} avatar">
                    <h4>{{ ucwords(Auth::user()->name) }}</h4>
                    <p>{{ Auth::user()->email }}</p>

                    <a href="{{ route('omega.profile') }}" class="btn btn-primary">Profile</a>
                    <div style="clear:both"></div>
                </div>
            </div>

        </div>

        <div class="navbar-expand-toggle">
        {!! menu('admin', 'admin_menu') !!}
        </div>
    </nav>
</div>
