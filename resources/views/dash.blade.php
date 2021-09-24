<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yotify - Dashboard</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet"> 
    <link href="{{ asset('css/default.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dash.css') }}" rel="stylesheet">
</head>
<body>
    <main>
        <nav>
        {{ Auth::user()->name }}
        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <x-dropdown-link :href="route('logout')"
                onclick="event.preventDefault();
                this.closest('form').submit();">
                {{ __('Log Out') }}
            </x-dropdown-link>
            <a href="/songs">songs</a> 
       
        <a href="/genres">genres</a>
        </form>

        
        
        
    </nav>
        @if ($tempPlaylists != null)
                @foreach ($tempPlaylists as $tempPlaylist)
                    @if ($tempPlaylist != null) 
                        <div onclick="location.href = 'showPlayList?id={{$tempPlaylist['id']}}';" class="playlistDiv">
                            <a>{{$tempPlaylist['name']}}</a>
                        </div>
                    @endif
                @endforeach
        @endif
        @if ($playlists != null)
            <h3>saved playlists</h3>
            @foreach ($playlists as $playlist)
                <div onclick="location.href = 'showList?id={{$playlist->id}}';" class="playlistDiv">
                    <a>{{$playlist->name}}</a>
                </div>
            @endforeach
        @endif
        <a href="createList">create new list</a>
    </main>
</body>
</html>