<ul>
  @foreach($listChats as $chat)
    <li><a href="http://local.blog.com/adminChat/{{$chat->name}}">{{$chat->name}}</a> {{$chat->username}} </li>
  @endforeach
</ul>
