<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yotify - Songs</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet"> 
    <link href="{{ asset('css/default.css') }}" rel="stylesheet">
    <link href="{{ asset('css/songs.css') }}" rel="stylesheet">

</head>
<body>
    <main>
   

    {{ Auth::user()->name }}

    <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                        <a href="/dashboard">dashboard</a> <br>
<div>
     @foreach ($songs as $song)
     
        <div class="songDiv">
            <a class="songName">{{$song->name}}</a>
            @foreach ($genres as $genre)
                @if ($genre->id == $song->genre_id)
                    <a href="/genre?id={{$genre->id}}" class="songGenre">
                        {{$genre->name}}
                    </a>
                @endif
            @endforeach
            <a class="songArtist">{{$song->artist}}</a>

            <div class="dropdown">
                <span class="dropdownBtn">+</span>
                <div class="dropdown-content">
                    @if ($playLists != null)
                        @foreach ($playLists as $playList)
                            <div class="dropdown-item">
                                <a href='/addPlayList?sid={{$song->id}}&lid={{$playList["id"]}}'>
                                {{$playList['name']}}
                                </a>
                            </div>
                        @endforeach
                    @endif
                    @if ($lists != null)
                        @foreach ($lists as $list)
                            <div class="dropdown-item">
                                <a href='/addList?sid={{$song->id}}&lid={{$list->id}}'>
                                {{$list->name}}
                                </a>
                            </div>
                        @endforeach
                    @endif
                    @if ($playLists == null && $lists == null) 
                    <div class="dropdown-item">
                            <a href='/createList'>
                                create new
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <a class="songDuration">{{$song->duration}}</a>
        </div>
  
    @endforeach
</div>
    </main>
</body>
</html>