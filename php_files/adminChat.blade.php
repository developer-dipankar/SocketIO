<!doctype html>
<html>
  <head>
    <title>Socket.IO chat</title>
    <style>
      * { margin: 0; padding: 0; box-sizing: border-box; }
      body { font: 13px Helvetica, Arial; }
      form { background: #000; padding: 3px; position: fixed; bottom: 0; width: 100%; }
      form input { border: 0; padding: 10px; width: 90%; margin-right: .5%; }
      form button { width: 9%; background: rgb(130, 224, 255); border: none; padding: 10px; }
      #messages { list-style-type: none; margin: 0; padding: 0; }
      #messages li { padding: 5px 10px; }
      #messages li:nth-child(odd) { background: #eee; }
      #messages { margin-bottom: 40px }
    </style>
  </head>
  <body>
    <ul id="messages"></ul>
    <form action="">
      <input type="text" id="name" value="Admin">
      <input type="text" id="email" value="admin@gmail.com">
      <input type="text" id="mobile" value="9804026898">
      <input type="hidden" id="room" value="{{$room}}">
      <input id="image" type='file' accept="image/x-png,image/gif,image/jpeg"><input type="hidden" id="image64" value="">
      <input id="m" autocomplete="off" /><button>Send</button>
    </form>

  </body>
  <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
  <script src="https://cdn.socket.io/socket.io-1.4.5.js"></script>
  <script>
    $(function () {
      var name = $('#name').val();
      var email = $('#email').val();
      var mobile = $('#mobile').val();
      var socket = io('http://localhost:3000');
      var room = $('#room').val();
      var userType = 'Admin';
      socket.emit('subscribe', room);
      socket.emit('chatInfo', { name: name, email: email, mobile: mobile, userType: userType });
      $('form').submit(function(){
        var message = $('#m').val();
        var image64 = $('#image64').val();
        socket.emit('send', { room: room, message: message, name: name, email: email, mobile: mobile, userType: userType, image64: image64 });
        $('#m').val('');
        $('#image64').val('');
        return false;
      });
      socket.on('message', function(msg){
        if (msg.message != '') {
          $('#messages').append($('<li>').text(msg.name+': '+msg.message));
          window.scrollTo(0, document.body.scrollHeight);
        }
        if (msg.image64 != '') {
          $('#messages').append($('<li>').append(msg.name+': <img src="'+msg.image64+'" width="200px" height="200px" >'));
          window.scrollTo(0, document.body.scrollHeight);
        }
      });


    // Image base64
    function readFile() {
      if (this.files && this.files[0]) {
        var FR= new FileReader();
        FR.addEventListener("load", function(e) {
          document.getElementById("image64").value = e.target.result;
        });
        FR.readAsDataURL( this.files[0] );
      }
    }
    document.getElementById("image").addEventListener("change", readFile);
    });
  </script>
</html>
