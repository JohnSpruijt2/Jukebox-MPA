<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yotify - {{$list->name}}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet"> 
    <link href="{{ asset('css/default.css') }}" rel="stylesheet">
    <link href="{{ asset('css/songs.css') }}" rel="stylesheet">
</head>
<body>
    <main>
        <nav>
    <form method="POST" action="{{ route('logout') }}">
        @csrf

        <x-dropdown-link :href="route('logout')"
            onclick="event.preventDefault();
            this.closest('form').submit();">
            {{ __('Log Out') }}
        </x-dropdown-link>
        <a href="/dashboard">dashboard</a> 
        
        <a href="/removeList?id={{$list->id}}">delete list</a>
    </form>
    </nav>
    <a class="listName" href="/editList?id={{$list->id}}">{{$list->name}}</a> 
    <h2>total: {{$totalDuration}}</h2>

    @foreach ($songs as $song)
     
     <div class="songDiv">
         <a href="/details?id={{$song->id}}" class="songName">{{$song->name}}</a>
            @foreach ($genres as $genre)
                @if ($genre->id == $song->genre_id)
                    <a href="/genre?id={{$genre->id}}" class="songGenre">
                        {{$genre->name}}
                    </a>
                @endif
            @endforeach
         <a class="songArtist">{{$song->artist}}</a>
         <a href="/removeSong?sid={{$song->songId}}&lid={{$list->id}}" class="removeBtn">-</a>
         <a class="songDuration">{{$song->duration}}</a>
     </div>

 @endforeach
    <a href="/songs">add new song</a>
</body>
</html>